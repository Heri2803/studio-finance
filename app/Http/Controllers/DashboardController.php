<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\PayrollSlip;
use App\Models\Transaction;
use App\Services\CatMoodService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected CatMoodService $catMoodService;

    public function __construct(CatMoodService $catMoodService)
    {
        $this->catMoodService = $catMoodService;
    }

    public function index(Request $request)
    {
        $currentMonth = Carbon::now()->format('Y-m');
        $month = $request->get('month', $currentMonth);
        $periodLabel = Carbon::parse($month . '-01')->translatedFormat('F Y');

        $targetOmset = 40000000; // Target omset Rp 40jt
        $targetProfit = 20000000; // Target laba bersih Rp 20jt

        // Transaksi bulan berjalan (Aggregated single-query untuk kecepatan TTFB)
        $monthSums = Transaction::whereYear('transaction_date', substr($month, 0, 4))
            ->whereMonth('transaction_date', substr($month, 5, 2))
            ->selectRaw("
                COALESCE(SUM(CASE WHEN type = 'INCOME' THEN amount ELSE 0 END), 0) as total_inflow,
                COALESCE(SUM(CASE WHEN type = 'EXPENSE' THEN amount ELSE 0 END), 0) as total_outflow
            ")
            ->first();

        $totalInflow = (float) ($monthSums->total_inflow ?? 0);
        $totalOutflow = (float) ($monthSums->total_outflow ?? 0);
        $netProfit = $totalInflow - $totalOutflow;

        // Payroll bulan berjalan
        $payrollSlips = PayrollSlip::with('employee')
            ->where('period', $periodLabel)
            ->get();

        $totalPayrollBurden = $payrollSlips->sum('net_pay');
        $pendingPayrollCount = $payrollSlips->where('status', 'PENDING')->count();
        $paidPayrollCount = $payrollSlips->where('status', 'PAID')->count();

        // Panggil Cat Mood Engine
        $catMood = $this->catMoodService->determineMood(
            netProfit: (float) $netProfit,
            targetProfit: (float) $targetProfit,
            pendingPayrollCount: $pendingPayrollCount
        );

        // 5 Transaksi terbaru
        $recentTransactions = Transaction::orderBy('transaction_date', 'desc')
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        // Karyawan aktif
        $employees = Employee::where('is_active', true)->get();

        return view('dashboard.index', compact(
            'periodLabel',
            'month',
            'totalInflow',
            'totalOutflow',
            'netProfit',
            'targetOmset',
            'targetProfit',
            'totalPayrollBurden',
            'pendingPayrollCount',
            'paidPayrollCount',
            'catMood',
            'payrollSlips',
            'recentTransactions',
            'employees'
        ));
    }
}
