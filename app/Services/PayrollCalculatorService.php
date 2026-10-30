<?php

namespace App\Services;

use App\Models\Employee;

class PayrollCalculatorService
{
    /**
     * Hitung gaji bersih karyawan Full-Time WFO.
     */
    public function calculateFulltime(
        float $baseSalary,
        float $allowance = 0,
        float $bonus = 0,
        float $deduction = 0
    ): float {
        $netPay = ($baseSalary + $allowance + $bonus) - $deduction;
        return max(0, $netPay);
    }

    /**
     * Hitung honor talent Freelance berbasis sesi shooting.
     * @return array{basic_amount: float, net_pay: float}
     */
    public function calculateFreelance(
        float $ratePerSession,
        int $sessionsCount,
        float $allowance = 0,
        float $bonus = 0,
        float $deduction = 0
    ): array {
        $basicAmount = $ratePerSession * max(0, $sessionsCount);
        $netPay = ($basicAmount + $allowance + $bonus) - $deduction;

        return [
            'basic_amount' => $basicAmount,
            'net_pay' => max(0, $netPay),
        ];
    }

    /**
     * Hitung fee partner Retainer (Media Buyer / Ads Specialist).
     */
    public function calculateRetainer(
        float $retainerFee,
        float $bonus = 0,
        float $deduction = 0
    ): float {
        $netPay = ($retainerFee + $bonus) - $deduction;
        return max(0, $netPay);
    }

    /**
     * Helper universal kalkulasi slip gaji berdasarkan tipe pekerja.
     * @return array{basic_amount: float, allowance: float, bonus: float, deduction: float, net_pay: float, sessions_count: int}
     */
    public function calculateForEmployee(Employee $employee, array $inputs = []): array
    {
        $allowance = (float) ($inputs['allowance'] ?? 0);
        $bonus = (float) ($inputs['bonus'] ?? 0);
        $deduction = (float) ($inputs['deduction'] ?? 0);
        $sessionsCount = (int) ($inputs['sessions_count'] ?? 0);

        if ($employee->employment_type === 'FREELANCE') {
            $freelanceCalc = $this->calculateFreelance(
                (float) $employee->base_salary_or_rate,
                $sessionsCount,
                $allowance,
                $bonus,
                $deduction
            );

            return [
                'basic_amount' => $freelanceCalc['basic_amount'],
                'allowance' => $allowance,
                'bonus' => $bonus,
                'deduction' => $deduction,
                'net_pay' => $freelanceCalc['net_pay'],
                'sessions_count' => $sessionsCount,
            ];
        }

        if ($employee->employment_type === 'RETAINER') {
            $basicAmount = (float) $employee->base_salary_or_rate;
            $netPay = $this->calculateRetainer($basicAmount, $bonus, $deduction);

            return [
                'basic_amount' => $basicAmount,
                'allowance' => 0,
                'bonus' => $bonus,
                'deduction' => $deduction,
                'net_pay' => $netPay,
                'sessions_count' => 0,
            ];
        }

        // FULLTIME
        $basicAmount = (float) $employee->base_salary_or_rate;
        $netPay = $this->calculateFulltime($basicAmount, $allowance, $bonus, $deduction);

        return [
            'basic_amount' => $basicAmount,
            'allowance' => $allowance,
            'bonus' => $bonus,
            'deduction' => $deduction,
            'net_pay' => $netPay,
            'sessions_count' => 0,
        ];
    }
}
