<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'icon',
        'min_amount',
        'max_amount',
        'is_active',
    ];

    protected $casts = [
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function paymentTransactions(): HasMany
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    public function getTypeDisplayAttribute(): string
    {
        return match($this->type) {
            'bank_transfer' => 'Bank Transfer',
            'e_wallet' => 'E-Wallet',
            'credit_card' => 'Credit Card',
            'virtual_account' => 'Virtual Account',
            'qris' => 'QRIS',
            default => ucfirst($this->type)
        };
    }
}
