<?php

namespace Tests\Unit;

use App\Services\CatMoodService;
use PHPUnit\Framework\TestCase;

class CatMoodServiceTest extends TestCase
{
    private CatMoodService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new CatMoodService();
    }

    public function test_angy_mood_when_net_profit_is_negative(): void
    {
        $result = $this->service->determineMood(netProfit: -5000000, targetProfit: 20000000, pendingPayrollCount: 0);
        $this->assertEquals('angy', $result['mood']);
    }

    public function test_hapy_mood_when_target_reached(): void
    {
        $result = $this->service->determineMood(netProfit: 25000000, targetProfit: 20000000, pendingPayrollCount: 0);
        $this->assertEquals('hapy', $result['mood']);
    }

    public function test_zen_mood_when_profit_is_positive_but_below_target(): void
    {
        $result = $this->service->determineMood(netProfit: 3000000, targetProfit: 20000000, pendingPayrollCount: 0);
        $this->assertEquals('zen', $result['mood']);
    }
}
