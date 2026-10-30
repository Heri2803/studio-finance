<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PayrollSlip extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'period',
        'sessions_count',
        'basic_amount',
        'allowance',
        'bonus',
        'deduction',
        'net_pay',
        'notes',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'sessions_count' => 'integer',
        'basic_amount' => 'decimal:2',
        'allowance' => 'decimal:2',
        'bonus' => 'decimal:2',
        'deduction' => 'decimal:2',
        'net_pay' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function transaction(): HasOne
    {
        return $this->hasOne(Transaction::class);
    }

    public function isPaid(): bool
    {
        return $this->status === 'PAID';
    }

    public function getFormattedNetPayAttribute(): string
    {
        return 'Rp ' . number_format($this->net_pay, 0, ',', '.');
    }
}
