<?php

namespace App\Models;

use App\Helpers\EncryptionHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_gateway_id',
        'payment_method_id',
        'transaction_code',
        'amount',
        'fee_amount',
        'status',
        'customer_name',
        'customer_email',
        'description',
        'processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'fee_amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    /**
     * Encrypt the customer email when setting it
     */
    public function setCustomerEmailAttribute($value)
    {
        $this->attributes['customer_email'] = EncryptionHelper::encrypt($value);
    }

    /**
     * Decrypt the customer email when getting it
     */
    public function getCustomerEmailAttribute($value)
    {
        return EncryptionHelper::decrypt($value);
    }

    /**
     * Encrypt the customer name when setting it
     */
    public function setCustomerNameAttribute($value)
    {
        $this->attributes['customer_name'] = EncryptionHelper::encrypt($value);
    }

    /**
     * Decrypt the customer name when getting it
     */
    public function getCustomerNameAttribute($value)
    {
        return EncryptionHelper::decrypt($value);
    }

    public function paymentGateway(): BelongsTo
    {
        return $this->belongsTo(PaymentGateway::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function getStatusBadgeAttribute(): array
    {
        return match($this->status) {
            'pending' => ['label' => 'Pending', 'color' => 'warning'],
            'success' => ['label' => 'Success', 'color' => 'success'],
            'failed' => ['label' => 'Failed', 'color' => 'danger'],
            'cancelled' => ['label' => 'Cancelled', 'color' => 'gray'],
            'expired' => ['label' => 'Expired', 'color' => 'danger'],
            default => ['label' => ucfirst($this->status), 'color' => 'gray']
        };
    }

    public function getNetAmountAttribute(): float
    {
        return $this->amount - $this->fee_amount;
    }
}
