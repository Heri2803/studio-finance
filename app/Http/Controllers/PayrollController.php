<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\PayrollSlip;
use App\Models\Transaction;
use App\Services\CatMoodService;
use App\Services\PayrollCalculatorService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayrollController extends Controller
{
    protected PayrollCalculatorService $calculator;
    protected CatMoodService $catMoodService;

    public function __construct(PayrollCalculatorService $calculator, CatMoodService $catMoodService)
    {
        $this->calculator = $calculator;
        $this->catMoodService = $catMoodService;
    }

    public function index(Request $request)
    {
        $period = $request->get('period', Carbon::now()->translatedFormat('F Y')); // e.g. "Oktober 2026"

        $slips = PayrollSlip::with('employee')
            ->where('period', $period)
            ->get();

        $stats = [
            'total_burden' => $slips->sum('net_pay'),
            'total_paid' => $slips->where('status', 'PAID')->sum('net_pay'),
            'total_pending' => $slips->where('status', 'PENDING')->sum('net_pay'),
            'count_paid' => $slips->where('status', 'PAID')->count(),
            'count_pending' => $slips->where('status', 'PENDING')->count(),
            'period' => $period,
        ];

        $activeEmployeesWithoutSlip = Employee::where('is_active', true)
            ->whereNotIn('id', $slips->pluck('employee_id'))
            ->count();

        // Arus kas studio untuk mendeteksi kas tipis vs ada gaji pending
        $inflow = Transaction::where('type', 'INCOME')->sum('amount');
        $outflow = Transaction::where('type', 'EXPENSE')->sum('amount');
        $netCashflow = $inflow - $outflow;

        $catMood = $this->catMoodService->determinePayrollMood($slips, $netCashflow, $activeEmployeesWithoutSlip);

        return view('payroll.index', compact('slips', 'stats', 'period', 'activeEmployeesWithoutSlip', 'catMood'));
    }

    public function generateDrafts(Request $request)
    {
        $period = $request->input('period', Carbon::now()->translatedFormat('F Y'));
        $employees = Employee::where('is_active', true)->get();

        $createdCount = 0;

        foreach ($employees as $employee) {
            $existing = PayrollSlip::where('employee_id', $employee->id)
                ->where('period', $period)
                ->first();

            if (!$existing) {
                $calc = $this->calculator->calculateForEmployee($employee, [
                    'sessions_count' => $employee->employment_type === 'FREELANCE' ? 1 : 0,
                    'allowance' => $employee->employment_type === 'FULLTIME' ? 500000 : 0,
                    'bonus' => 0,
                    'deduction' => 0,
                ]);

                PayrollSlip::create([
                    'employee_id' => $employee->id,
                    'period' => $period,
                    'sessions_count' => $calc['sessions_count'],
                    'basic_amount' => $calc['basic_amount'],
                    'allowance' => $calc['allowance'],
                    'bonus' => $calc['bonus'],
                    'deduction' => $calc['deduction'],
                    'net_pay' => $calc['net_pay'],
                    'status' => 'PENDING',
                ]);

                $createdCount++;
            }
        }

        return redirect()->route('payroll.index', ['period' => $period])
            ->with('success', "Draft payroll berhasil dibuat untuk {$createdCount} pekerja! 🐾")
            ->with('action', 'create');
    }

    public function updateComponent(Request $request, PayrollSlip $payrollSlip)
    {
        $validated = $request->validate([
            'sessions_count' => 'nullable|integer|min:0',
            'allowance' => 'required|numeric|min:0',
            'bonus' => 'required|numeric|min:0',
            'deduction' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        $employee = $payrollSlip->employee;

        $calc = $this->calculator->calculateForEmployee($employee, $validated);

        $payrollSlip->update([
            'sessions_count' => $calc['sessions_count'],
            'basic_amount' => $calc['basic_amount'],
            'allowance' => $calc['allowance'],
            'bonus' => $calc['bonus'],
            'deduction' => $calc['deduction'],
            'net_pay' => $calc['net_pay'],
            'notes' => $validated['notes'] ?? $payrollSlip->notes,
        ]);

        // Jika slip ini sudah PAID dan nilainya diubah, update juga record pengeluaran kasnya
        if ($payrollSlip->status === 'PAID') {
            Transaction::where('payroll_slip_id', $payrollSlip->id)->update([
                'amount' => $calc['net_pay'],
            ]);
        }

        return redirect()->back()
            ->with('success', "Komponen gaji {$employee->name} berhasil diperbarui! ✨")
            ->with('action', 'update');
    }

    /**
     * Eksekusi bayar gaji (IDEMPOTENT & CROSS-MODULE SAFE).
     */
    public function markAsPaid(PayrollSlip $payrollSlip)
    {
        if ($payrollSlip->status === 'PAID') {
            return redirect()->back()->with('success', 'Gaji sudah berstatus lunas sebelumnya.');
        }

        DB::transaction(function () use ($payrollSlip) {
            $payrollSlip->update([
                'status' => 'PAID',
                'paid_at' => Carbon::now(),
            ]);

            // Catat pengeluaran kas keluar secara otomatis
            Transaction::updateOrCreate(
                ['payroll_slip_id' => $payrollSlip->id],
                [
                    'type' => 'EXPENSE',
                    'category' => 'PAYROLL',
                    'amount' => $payrollSlip->net_pay,
                    'transaction_date' => Carbon::now()->toDateString(),
                    'description' => "Gaji {$payrollSlip->period} - {$payrollSlip->employee->name} ({$payrollSlip->employee->role})",
                    'location' => $payrollSlip->employee->location ?? 'KEMANG',
                ]
            );
        });

        return redirect()->back()
            ->with('success', "Gaji {$payrollSlip->employee->name} ditandai LUNAS dan dicatat ke kas keluar! 🐾🎉")
            ->with('action', 'create');
    }

    /**
     * Batalkan status pembayaran (Revert ke PENDING).
     */
    public function markAsPending(PayrollSlip $payrollSlip)
    {
        DB::transaction(function () use ($payrollSlip) {
            $payrollSlip->update([
                'status' => 'PENDING',
                'paid_at' => null,
            ]);

            // Hapus pengeluaran kas terkait agar saldo kas tetap akurat
            Transaction::where('payroll_slip_id', $payrollSlip->id)->delete();
        });

        return redirect()->back()
            ->with('success', "Status pembayaran gaji {$payrollSlip->employee->name} dibatalkan.")
            ->with('action', 'delete');
    }
}
