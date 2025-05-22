<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentAnalytic extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_gateway_id',
        'date',
        'total_transactions',
        'total_amount',
        'total_fee',
        'success_count',
        'failed_count',
        'pending_count',
        'success_rate',
        'avg_processing_time',
    ];

    protected $casts = [
        'date' => 'date',
        'total_amount' => 'decimal:2',
        'total_fee' => 'decimal:2',
        'success_rate' => 'decimal:2',
    ];

    public function paymentGateway(): BelongsTo
    {
        return $this->belongsTo(PaymentGateway::class);
    }

    public function getFormattedSuccessRateAttribute(): string
    {
        return number_format($this->success_rate, 2) . '%';
    }

    public function getFormattedProcessingTimeAttribute(): string
    {
        return gmdate("H:i:s", $this->avg_processing_time);
    }
}
