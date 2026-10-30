<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\SlipGajiController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — TabyFinance (PawRoll)
|--------------------------------------------------------------------------
*/

// Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index']);

// Employees & Talents CRUD
Route::resource('employees', EmployeeController::class)->except(['create', 'show', 'edit']);

// Payroll Management
Route::prefix('payroll')->name('payroll.')->group(function () {
    Route::get('/', [PayrollController::class, 'index'])->name('index');
    Route::post('/generate', [PayrollController::class, 'generateDrafts'])->name('generate');
    Route::put('/{payrollSlip}/update-component', [PayrollController::class, 'updateComponent'])->name('update-component');
    Route::post('/{payrollSlip}/paid', [PayrollController::class, 'markAsPaid'])->name('paid');
    Route::post('/{payrollSlip}/pending', [PayrollController::class, 'markAsPending'])->name('pending');
});

// Transactions & Single-Entry Cashflow
Route::prefix('transactions')->name('transactions.')->group(function () {
    Route::get('/', [TransactionController::class, 'index'])->name('index');
    Route::post('/', [TransactionController::class, 'store'])->name('store');
    Route::delete('/{transaction}', [TransactionController::class, 'destroy'])->name('destroy');
});

// Slip Gaji PDF Export & Preview
Route::get('/slip-gaji/{payrollSlip}/pdf', [SlipGajiController::class, 'downloadPdf'])->name('slip.pdf');
Route::get('/slip-gaji/{payrollSlip}/preview', [SlipGajiController::class, 'preview'])->name('slip.preview');
