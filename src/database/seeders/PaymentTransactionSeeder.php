<?php

namespace Database\Seeders;

use App\Models\PaymentTransaction;
use App\Models\PaymentGateway;
use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PaymentTransactionSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $gateways = PaymentGateway::all();
        $methods = PaymentMethod::all();
        $statuses = ['pending', 'success', 'failed', 'cancelled', 'expired'];

        for ($i = 0; $i < 500; $i++) {
            $gateway = $gateways->random();
            $method = $methods->random();
            $amount = $faker->randomFloat(2, 10000, 5000000);
            $feeAmount = $amount * ($gateway->fee_percentage / 100);

            PaymentTransaction::create([
                'payment_gateway_id' => $gateway->id,
                'payment_method_id' => $method->id,
                'transaction_code' => 'TRX-' . strtoupper($faker->bothify('####??####')),
                'amount' => $amount,
                'fee_amount' => $feeAmount,
                'status' => $faker->randomElement($statuses),
                'customer_name' => $faker->name(),
                'customer_email' => $faker->email(),
                'description' => $faker->sentence(6),
                'processed_at' => $faker->dateTimeBetween('-30 days', 'now'),
                'created_at' => $faker->dateTimeBetween('-30 days', 'now'),
            ]);
        }
    }
}
