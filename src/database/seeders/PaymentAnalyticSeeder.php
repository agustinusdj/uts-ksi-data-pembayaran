<?php

namespace Database\Seeders;

use App\Models\PaymentAnalytic;
use App\Models\PaymentGateway;
use App\Models\PaymentTransaction;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PaymentAnalyticSeeder extends Seeder
{
    public function run(): void
    {
        $gateways = PaymentGateway::all();
        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();

        foreach ($gateways as $gateway) {
            $currentDate = $startDate->copy();

            while ($currentDate->lte($endDate)) {
                $transactions = PaymentTransaction::where('payment_gateway_id', $gateway->id)
                    ->whereDate('created_at', $currentDate)
                    ->get();

                if ($transactions->count() > 0) {
                    $successCount = $transactions->where('status', 'success')->count();
                    $failedCount = $transactions->where('status', 'failed')->count();
                    $pendingCount = $transactions->where('status', 'pending')->count();
                    $totalTransactions = $transactions->count();
                    $successRate = $totalTransactions > 0 ? ($successCount / $totalTransactions) * 100 : 0;

                    PaymentAnalytic::create([
                        'payment_gateway_id' => $gateway->id,
                        'date' => $currentDate->format('Y-m-d'),
                        'total_transactions' => $totalTransactions,
                        'total_amount' => $transactions->sum('amount'),
                        'total_fee' => $transactions->sum('fee_amount'),
                        'success_count' => $successCount,
                        'failed_count' => $failedCount,
                        'pending_count' => $pendingCount,
                        'success_rate' => $successRate,
                        'avg_processing_time' => rand(30, 300), // seconds
                    ]);
                }

                $currentDate->addDay();
            }
        }
    }
}
