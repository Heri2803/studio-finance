<?php

namespace App\Http\Controllers;

use App\Models\PayrollSlip;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class SlipGajiController extends Controller
{
    public function downloadPdf(PayrollSlip $payrollSlip)
    {
        $payrollSlip->load('employee');

        $pdf = Pdf::loadView('pdf.slip-gaji', compact('payrollSlip'))
            ->setPaper('a5', 'landscape');

        $filename = 'Slip-Gaji-' . str_replace(' ', '-', $payrollSlip->employee->name) . '-' . str_replace(' ', '-', $payrollSlip->period) . '.pdf';

        return $pdf->stream($filename);
    }

    public function preview(PayrollSlip $payrollSlip)
    {
        $payrollSlip->load('employee');
        return view('pdf.slip-gaji', compact('payrollSlip'));
    }
}
