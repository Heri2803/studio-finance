<?php

namespace Tests\Unit;

use App\Models\Employee;
use App\Services\PayrollCalculatorService;
use PHPUnit\Framework\TestCase;

class PayrollCalculatorServiceTest extends TestCase
{
    private PayrollCalculatorService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new PayrollCalculatorService();
    }

    public function test_calculate_fulltime_take_home_pay(): void
    {
        // Gaji 6.000.000 + Tunjangan 500.000 + Bonus 500.000 - Potongan 0 = 7.000.000
        $netPay = $this->service->calculateFulltime(6000000, 500000, 500000, 0);
        $this->assertEquals(7000000, $netPay);

        // Dengan potongan kasbon 1.000.000 = 6.000.000
        $netPayWithKasbon = $this->service->calculateFulltime(6000000, 500000, 500000, 1000000);
        $this->assertEquals(6000000, $netPayWithKasbon);
    }

    public function test_calculate_freelance_per_session(): void
    {
        // 4 sesi x 500.000 = 2.000.000 + bonus 400.000 = 2.400.000
        $result = $this->service->calculateFreelance(500000, 4, 0, 400000, 0);

        $this->assertEquals(2000000, $result['basic_amount']);
        $this->assertEquals(2400000, $result['net_pay']);
    }

    public function test_calculate_retainer(): void
    {
        // Retainer 3.500.000 + Bonus 500.000 = 4.000.000
        $netPay = $this->service->calculateRetainer(3500000, 500000, 0);
        $this->assertEquals(4000000, $netPay);
    }

    public function test_net_pay_never_negative(): void
    {
        // Potongan melebihi gaji (10.000.000 > 6.000.000) harus return 0
        $netPay = $this->service->calculateFulltime(6000000, 0, 0, 10000000);
        $this->assertEquals(0, $netPay);
    }
}
