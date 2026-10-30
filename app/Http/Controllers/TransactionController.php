<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\CatMoodService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    protected CatMoodService $catMoodService;

    public function __construct(CatMoodService $catMoodService)
    {
        $this->catMoodService = $catMoodService;
    }

    public function index(Request $request)
    {
        $type = $request->get('type');
        $location = $request->get('location');
        $month = $request->get('month', Carbon::now()->format('Y-m'));

        $query = Transaction::query()->with('payrollSlip.employee');

        if ($month) {
            $query->whereYear('transaction_date', substr($month, 0, 4))
                  ->whereMonth('transaction_date', substr($month, 5, 2));
        }

        if ($type && in_array($type, ['INCOME', 'EXPENSE'])) {
            $query->where('type', $type);
        }

        if ($location) {
            $query->where('location', $location);
        }

        $transactions = $query->orderBy('transaction_date', 'desc')
                              ->orderBy('id', 'desc')
                              ->get();

        // Summary calculations (Aggregated single-query untuk kecepatan TTFB)
        $monthSums = Transaction::whereYear('transaction_date', substr($month, 0, 4))
            ->whereMonth('transaction_date', substr($month, 5, 2))
            ->selectRaw("
                COALESCE(SUM(CASE WHEN type = 'INCOME' THEN amount ELSE 0 END), 0) as total_inflow,
                COALESCE(SUM(CASE WHEN type = 'EXPENSE' THEN amount ELSE 0 END), 0) as total_outflow
            ")
            ->first();

        $totalInflow = (float) ($monthSums->total_inflow ?? 0);
        $totalOutflow = (float) ($monthSums->total_outflow ?? 0);
        $netCashflow = $totalInflow - $totalOutflow;

        $stats = [
            'total_inflow' => $totalInflow,
            'total_outflow' => $totalOutflow,
            'net_cashflow' => $netCashflow,
            'month' => $month,
        ];

        $catMood = $this->catMoodService->determineTransactionMood($stats, $transactions->count());

        return view('transactions.index', compact('transactions', 'stats', 'type', 'location', 'month', 'catMood'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:INCOME,EXPENSE',
            'category' => 'required|string|max:50',
            'amount' => 'required|numeric|min:1',
            'transaction_date' => 'required|date',
            'description' => 'required|string|max:255',
            'location' => 'required|string|max:50',
        ]);

        Transaction::create($validated);

        $badge = $validated['type'] === 'INCOME' ? 'Kas Masuk (+)' : 'Pengeluaran (-)';
        return redirect()->back()
            ->with('success', "Catatan {$badge} berhasil disimpan! 🐾")
            ->with('action', 'create');
    }

    public function destroy(Transaction $transaction)
    {
        // Jika transaksi ini terhubung ke slip gaji, informasikan
        if ($transaction->payroll_slip_id) {
            return redirect()->back()->with('error', 'Transaksi ini terhubung ke pembayaran gaji. Silakan batalkan via menu Penggajian Tim.');
        }

        $desc = $transaction->description;
        $transaction->delete();

        return redirect()->back()
            ->with('success', "Catatan transaksi '{$desc}' berhasil dihapus.")
            ->with('action', 'delete');
    }
}
