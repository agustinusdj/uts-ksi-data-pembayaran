<?php

namespace App\Filament\Admin\Widgets;

use App\Models\PaymentTransaction;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TransactionTrendsWidget extends ChartWidget
{
    protected static ?string $heading = 'Transaction Trends';
    protected static ?string $pollingInterval = '60s';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $days = 14; // Last 14 days

        $transactions = PaymentTransaction::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(amount) as amount'),
                DB::raw('SUM(CASE WHEN status = "success" THEN 1 ELSE 0 END) as success_count')
            )
            ->whereDate('created_at', '>=', now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $dates = collect();
        for ($i = $days; $i >= 0; $i--) {
            $dates->push(now()->subDays($i)->format('Y-m-d'));
        }

        $counts = $dates->map(function ($date) use ($transactions) {
            $transaction = $transactions->firstWhere('date', $date);
            return $transaction ? $transaction->count : 0;
        });

        $amounts = $dates->map(function ($date) use ($transactions) {
            $transaction = $transactions->firstWhere('date', $date);
            return $transaction ? round($transaction->amount / 1000000, 2) : 0; // In millions
        });

        $successCounts = $dates->map(function ($date) use ($transactions) {
            $transaction = $transactions->firstWhere('date', $date);
            return $transaction ? $transaction->success_count : 0;
        });

        $formattedDates = $dates->map(function ($date) {
            return Carbon::parse($date)->format('d M');
        });

        return [
            'datasets' => [
                [
                    'label' => 'Total Transactions',
                    'data' => $counts->toArray(),
                    'borderColor' => 'rgb(75, 192, 192)',
                    'backgroundColor' => 'rgba(75, 192, 192, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Successful Transactions',
                    'data' => $successCounts->toArray(),
                    'borderColor' => 'rgb(54, 162, 235)',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Amount (In Millions Rp)',
                    'data' => $amounts->toArray(),
                    'borderColor' => 'rgb(255, 99, 132)',
                    'backgroundColor' => 'rgba(255, 99, 132, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                    'yAxisID' => 'y1',
                ],
            ],
            'labels' => $formattedDates->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
                'tooltip' => [
                    'enabled' => true,
                    'mode' => 'index',
                    'intersect' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Transactions Count',
                    ],
                    'beginAtZero' => true,
                ],
                'y1' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'right',
                    'title' => [
                        'display' => true,
                        'text' => 'Amount (In Millions Rp)',
                    ],
                    'beginAtZero' => true,
                    'grid' => [
                        'drawOnChartArea' => false,
                    ],
                ],
                'x' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Date',
                    ],
                ],
            ],
            'maintainAspectRatio' => false,
            'responsive' => true,
        ];
    }
}
