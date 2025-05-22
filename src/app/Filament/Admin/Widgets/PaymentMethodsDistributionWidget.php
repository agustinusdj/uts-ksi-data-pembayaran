<?php

namespace App\Filament\Admin\Widgets;

use App\Models\PaymentTransaction;
use App\Models\PaymentMethod;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PaymentMethodsDistributionWidget extends ChartWidget
{
    protected static ?string $heading = 'Payment Methods Distribution';
    protected static ?string $pollingInterval = '30s';
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $data = PaymentTransaction::select('payment_method_id', DB::raw('count(*) as total'))
            ->whereDate('created_at', '>=', now()->subDays(30))
            ->groupBy('payment_method_id')
            ->get()
            ->map(function ($item) {
                $method = PaymentMethod::find($item->payment_method_id);
                return [
                    'method' => $method ? $method->name : 'Unknown',
                    'total' => $item->total,
                ];
            });

        return [
            'datasets' => [
                [
                    'label' => 'Transactions',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => [
                        'rgb(54, 162, 235)', 'rgb(255, 99, 132)', 
                        'rgb(75, 192, 192)', 'rgb(255, 159, 64)',
                        'rgb(153, 102, 255)', 'rgb(255, 205, 86)',
                        'rgb(201, 203, 207)', 'rgb(255, 99, 71)',
                    ],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $data->pluck('method')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'right',
                ],
                'tooltip' => [
                    'enabled' => true,
                ],
            ],
            'maintainAspectRatio' => false,
        ];
    }
}
