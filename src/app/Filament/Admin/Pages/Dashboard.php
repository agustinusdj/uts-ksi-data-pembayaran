<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Widgets\LatestTransactionsWidget;
use App\Filament\Admin\Widgets\PaymentGatewayStatusWidget;
use App\Filament\Admin\Widgets\PaymentMethodsDistributionWidget;
use App\Filament\Admin\Widgets\PaymentOverviewWidget;
use App\Filament\Admin\Widgets\TransactionTrendsWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?int $navigationSort = 0;
    
    public function getHeaderWidgets(): array
    {
        return [
            PaymentOverviewWidget::class,
        ];
    }
    
    public function getFooterWidgets(): array
    {
        return [];
    }
    
    public function getColumns(): int | array
    {
        return 2;
    }
    
    public function getWidgets(): array
    {
        return [
            PaymentOverviewWidget::class,
            PaymentGatewayStatusWidget::class,
            PaymentMethodsDistributionWidget::class,
            TransactionTrendsWidget::class,
            LatestTransactionsWidget::class,
        ];
    }
}
