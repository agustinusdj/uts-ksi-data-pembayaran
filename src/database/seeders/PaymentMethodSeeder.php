<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            [
                'name' => 'BCA Virtual Account',
                'type' => 'virtual_account',
                'icon' => 'bca-icon.png',
                'min_amount' => 10000,
                'max_amount' => 50000000,
                'is_active' => true,
            ],
            [
                'name' => 'Mandiri Virtual Account',
                'type' => 'virtual_account',
                'icon' => 'mandiri-icon.png',
                'min_amount' => 10000,
                'max_amount' => 50000000,
                'is_active' => true,
            ],
            [
                'name' => 'OVO E-Wallet',
                'type' => 'e_wallet',
                'icon' => 'ovo-icon.png',
                'min_amount' => 10000,
                'max_amount' => 10000000,
                'is_active' => true,
            ],
            [
                'name' => 'GoPay E-Wallet',
                'type' => 'e_wallet',
                'icon' => 'gopay-icon.png',
                'min_amount' => 1000,
                'max_amount' => 20000000,
                'is_active' => true,
            ],
            [
                'name' => 'DANA E-Wallet',
                'type' => 'e_wallet',
                'icon' => 'dana-icon.png',
                'min_amount' => 1000,
                'max_amount' => 20000000,
                'is_active' => true,
            ],
            [
                'name' => 'QRIS Payment',
                'type' => 'qris',
                'icon' => 'qris-icon.png',
                'min_amount' => 1000,
                'max_amount' => 10000000,
                'is_active' => true,
            ],
            [
                'name' => 'Credit Card Visa',
                'type' => 'credit_card',
                'icon' => 'visa-icon.png',
                'min_amount' => 50000,
                'max_amount' => 100000000,
                'is_active' => true,
            ],
        ];

        foreach ($methods as $method) {
            PaymentMethod::create($method);
        }
    }
}
