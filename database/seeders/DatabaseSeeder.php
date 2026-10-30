<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\PayrollSlip;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Guard: Skip if data already seeded
        if (Employee::count() > 0) {
            return;
        }

        // 1. Seed Karyawan & Talent (Profil Tim Studio Kemang)
        $dian = Employee::create([
            'name' => 'Dian Pratama',
            'role' => 'Video Content Creator',
            'employment_type' => 'FULLTIME',
            'base_salary_or_rate' => 6000000,
            'bank_name' => 'BCA',
            'bank_account_number' => '8220192831',
            'bank_account_name' => 'Dian Pratama',
            'location' => 'KEMANG',
            'is_active' => true,
        ]);

        $bella = Employee::create([
            'name' => 'Bella Safira',
            'role' => 'Freelance Model Konten TikTok',
            'employment_type' => 'FREELANCE',
            'base_salary_or_rate' => 500000, // per sesi shooting
            'bank_name' => 'Mandiri',
            'bank_account_number' => '142001928371',
            'bank_account_name' => 'Bella Safira',
            'location' => 'KEMANG',
            'is_active' => true,
        ]);

        $rian = Employee::create([
            'name' => 'Rian Hidayat',
            'role' => 'Meta & TikTok Ads Specialist',
            'employment_type' => 'RETAINER',
            'base_salary_or_rate' => 3500000, // monthly retainer
            'bank_name' => 'BCA',
            'bank_account_number' => '5271829011',
            'bank_account_name' => 'Rian Hidayat',
            'location' => 'REMOTE',
            'is_active' => true,
        ]);

        // 2. Seed Payroll Slips (Oktober 2026)
        $slipDian = PayrollSlip::create([
            'employee_id' => $dian->id,
            'period' => 'Oktober 2026',
            'sessions_count' => 0,
            'basic_amount' => 6000000,
            'allowance' => 500000,
            'bonus' => 500000,
            'deduction' => 0,
            'net_pay' => 7000000,
            'notes' => 'Gaji Pokok + Uang Makan + Bonus Lembur Project Urgent',
            'status' => 'PAID',
            'paid_at' => Carbon::now()->subDays(2),
        ]);

        $slipBella = PayrollSlip::create([
            'employee_id' => $bella->id,
            'period' => 'Oktober 2026',
            'sessions_count' => 4,
            'basic_amount' => 2000000, // 4 sesi x 500rb
            'allowance' => 100000,
            'bonus' => 400000,
            'deduction' => 0,
            'net_pay' => 2500000,
            'notes' => '4 Sesi Shooting Konten TikTok + Bonus FYP 1M Views',
            'status' => 'PAID',
            'paid_at' => Carbon::now()->subDays(1),
        ]);

        $slipRian = PayrollSlip::create([
            'employee_id' => $rian->id,
            'period' => 'Oktober 2026',
            'sessions_count' => 0,
            'basic_amount' => 3500000,
            'allowance' => 0,
            'bonus' => 500000,
            'deduction' => 0,
            'net_pay' => 4000000,
            'notes' => 'Retainer Iklan Bulanan + Bonus Efisiensi Budget ROAS',
            'status' => 'PENDING',
            'paid_at' => null,
        ]);

        // 3. Seed Transaksi Arus Kas (Single-Entry Cashflow)
        // Kas Masuk (Incomes)
        Transaction::create([
            'type' => 'INCOME',
            'category' => 'PROJECT_DP',
            'amount' => 25000000,
            'transaction_date' => Carbon::now()->subDays(15),
            'description' => 'DP 50% Campaign Video Skincare Brand A',
            'location' => 'KEMANG',
        ]);

        Transaction::create([
            'type' => 'INCOME',
            'category' => 'PROJECT_SETTLEMENT',
            'amount' => 15000000,
            'transaction_date' => Carbon::now()->subDays(8),
            'description' => 'Pelunasan Project Video F&B Client B',
            'location' => 'KEMANG',
        ]);

        Transaction::create([
            'type' => 'INCOME',
            'category' => 'CLIENT_RETAINER',
            'amount' => 8000000,
            'transaction_date' => Carbon::now()->subDays(5),
            'description' => 'Retainer Bulanan Social Media Management Client C',
            'location' => 'SURABAYA',
        ]);

        // Kas Keluar (Expenses)
        Transaction::create([
            'type' => 'EXPENSE',
            'category' => 'PAYROLL',
            'amount' => 7000000,
            'transaction_date' => Carbon::now()->subDays(2),
            'description' => 'Gaji Oktober 2026 - Dian Pratama (Video Creator)',
            'location' => 'KEMANG',
            'payroll_slip_id' => $slipDian->id,
        ]);

        Transaction::create([
            'type' => 'EXPENSE',
            'category' => 'PAYROLL',
            'amount' => 2500000,
            'transaction_date' => Carbon::now()->subDays(1),
            'description' => 'Honor Shooting 4 Sesi - Bella Safira (Model TikTok)',
            'location' => 'KEMANG',
            'payroll_slip_id' => $slipBella->id,
        ]);

        Transaction::create([
            'type' => 'EXPENSE',
            'category' => 'AD_SPEND',
            'amount' => 6000000,
            'transaction_date' => Carbon::now()->subDays(10),
            'description' => 'Top-up Saldo Iklan Meta Ads & TikTok Ads',
            'location' => 'ONLINE',
        ]);

        Transaction::create([
            'type' => 'EXPENSE',
            'category' => 'STUDIO_KEMANG',
            'amount' => 2500000,
            'transaction_date' => Carbon::now()->subDays(12),
            'description' => 'Sewa Spot Studio Kemang + Listrik & Wifi',
            'location' => 'KEMANG',
        ]);

        Transaction::create([
            'type' => 'EXPENSE',
            'category' => 'PROPS_GEAR',
            'amount' => 1200000,
            'transaction_date' => Carbon::now()->subDays(7),
            'description' => 'Wardrobe shooting model + aksesoris konten',
            'location' => 'KEMANG',
        ]);

        Transaction::create([
            'type' => 'EXPENSE',
            'category' => 'SUBSCRIPTION',
            'amount' => 850000,
            'transaction_date' => Carbon::now()->subDays(14),
            'description' => 'Langganan Adobe CC, CapCut Pro & Canva',
            'location' => 'ONLINE',
        ]);
    }
}
