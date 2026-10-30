<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'role',
        'employment_type',
        'base_salary_or_rate',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'location',
        'is_active',
    ];

    protected $casts = [
        'base_salary_or_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function payrollSlips(): HasMany
    {
        return $this->hasMany(PayrollSlip::class);
    }

    public function getFormattedBaseSalaryAttribute(): string
    {
        return 'Rp ' . number_format($this->base_salary_or_rate, 0, ',', '.');
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->employment_type) {
            'FULLTIME' => 'Full-Time WFO',
            'FREELANCE' => 'Freelance Per-Sesi',
            'RETAINER' => 'Retainer Partner',
            default => $this->employment_type,
        };
    }
}
