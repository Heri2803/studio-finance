<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Services\PayrollCalculatorService;
use App\Services\CatMoodService;
use Carbon\Carbon;

echo "--- Testing PayrollCalculatorService ---\n";
$payroll = new PayrollCalculatorService();

$fulltime = $payroll->calculateFulltime(6000000, 500000, 500000, 0);
assert($fulltime === 7000000.0, "Fulltime net pay should be 7.000.000");
echo "✓ Fulltime calculation passed: " . number_format($fulltime, 0, ',', '.') . "\n";

$freelance = $payroll->calculateFreelance(500000, 4, 100000, 400000, 0);
assert($freelance['basic_amount'] === 2000000.0, "Freelance basic amount should be 2.000.000");
assert($freelance['net_pay'] === 2500000.0, "Freelance net pay should be 2.500.000");
echo "✓ Freelance calculation passed: " . number_format($freelance['net_pay'], 0, ',', '.') . "\n";

$retainer = $payroll->calculateRetainer(3500000, 500000, 0);
assert($retainer === 4000000.0, "Retainer net pay should be 4.000.000");
echo "✓ Retainer calculation passed: " . number_format($retainer, 0, ',', '.') . "\n";

echo "\n--- Testing CatMoodService ---\n";
$cat = new CatMoodService();

// Test Angy: rugi / minus
$angy = $cat->determineMood(-2000000, 20000000, 0);
assert($angy['mood'] === 'angy', "Negative profit must yield angy mood");
echo "✓ Angy mood passed: " . $angy['title'] . "\n";

// Test Zen: profit 5jt, target 20jt, tanggal 15
$zen = $cat->determineMood(5000000, 20000000, 1, Carbon::create(2026, 10, 15));
assert($zen['mood'] === 'zen', "Positive profit below target on mid-month must yield zen mood");
echo "✓ Zen mood passed: " . $zen['title'] . "\n";

// Test Alert: tanggal 27 (>= 25) dan masih ada pending payroll
$alert = $cat->determineMood(5000000, 20000000, 2, Carbon::create(2026, 10, 27));
assert($alert['mood'] === 'alert', "End of month with pending payroll must yield alert mood");
echo "✓ Alert mood passed: " . $alert['title'] . "\n";

// Test Hapy: profit 25jt, target 20jt
$hapy = $cat->determineMood(25000000, 20000000, 0);
assert($hapy['mood'] === 'hapy', "Target profit reached must yield hapy mood");
echo "✓ Hapy mood passed: " . $hapy['title'] . "\n";

echo "\n=====================================\n";
echo "All domain service tests PASSED 100%!\n";
echo "=====================================\n";
