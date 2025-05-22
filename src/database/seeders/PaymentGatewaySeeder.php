<?php

namespace Database\Seeders;

use App\Models\PaymentGateway;
use Illuminate\Database\Seeder;

class PaymentGatewaySeeder extends Seeder
{
    public function run(): void
    {
        $gateways = [
            [
                'name' => 'Midtrans Payment',
                'provider' => 'midtrans',
                'fee_percentage' => 2.90,
                'api_endpoint' => 'https://api.midtrans.com',
                'is_active' => true,
            ],
            [
                'name' => 'Xendit Gateway',
                'provider' => 'xendit',
                'fee_percentage' => 2.50,
                'api_endpoint' => 'https://api.xendit.co',
                'is_active' => true,
            ],
            [
                'name' => 'DOKU Payment',
                'provider' => 'doku',
                'fee_percentage' => 3.00,
                'api_endpoint' => 'https://api.doku.com',
                'is_active' => true,
            ],
            [
                'name' => 'OVO Gateway',
                'provider' => 'ovo',
                'fee_percentage' => 1.50,
                'api_endpoint' => 'https://api.ovo.id',
                'is_active' => false,
            ],
        ];

        foreach ($gateways as $gateway) {
            PaymentGateway::create($gateway);
        }
    }
}
