<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'category',
        'amount',
        'transaction_date',
        'description',
        'location',
        'payroll_slip_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date',
    ];

    public function payrollSlip(): BelongsTo
    {
        return $this->belongsTo(PayrollSlip::class);
    }

    public function isIncome(): bool
    {
        return $this->type === 'INCOME';
    }

    public function isExpense(): bool
    {
        return $this->type === 'EXPENSE';
    }

    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    public function getCategoryLabelAttribute(): string
    {
        return match($this->category) {
            'PROJECT_DP' => 'DP Proyek Jasa',
            'PROJECT_SETTLEMENT' => 'Pelunasan Proyek',
            'CLIENT_RETAINER' => 'Retainer Klien',
            'PAYROLL' => 'Gaji & Honor Tim',
            'AD_SPEND' => 'Saldo Iklan (Meta/TikTok)',
            'STUDIO_KEMANG' => 'Operasional Studio Kemang',
            'PROPS_GEAR' => 'Properti & Sewa Alat',
            'SUBSCRIPTION' => 'Langganan Tools / Software',
            default => $this->category,
        };
    }
}
