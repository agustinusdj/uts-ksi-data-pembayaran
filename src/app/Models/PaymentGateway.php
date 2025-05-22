<?php

namespace App\Models;

use App\Helpers\EncryptionHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentGateway extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'provider',
        'fee_percentage',
        'api_endpoint',
        'is_active',
        'config_json',
        'description',
    ];

    protected $casts = [
        'fee_percentage' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Encrypt the API endpoint when setting it
     */
    public function setApiEndpointAttribute($value)
    {
        $this->attributes['api_endpoint'] = EncryptionHelper::encrypt($value);
    }

    /**
     * Decrypt the API endpoint when getting it
     */
    public function getApiEndpointAttribute($value)
    {
        return EncryptionHelper::decrypt($value);
    }

    /**
     * Encrypt the config JSON when setting it
     */
    public function setConfigJsonAttribute($value)
    {
        $this->attributes['config_json'] = EncryptionHelper::encrypt($value);
    }

    /**
     * Decrypt the config JSON when getting it
     */
    public function getConfigJsonAttribute($value)
    {
        return EncryptionHelper::decrypt($value);
    }

    public function paymentTransactions(): HasMany
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    public function paymentAnalytics(): HasMany
    {
        return $this->hasMany(PaymentAnalytic::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return $this->is_active ? 'Aktif' : 'Non-Aktif';
    }

    public function getTotalTransactionsAttribute(): int
    {
        return $this->paymentTransactions()->count();
    }
}
