<?php

namespace App\Filament\Admin\Widgets;

use App\Models\PaymentTransaction;
use App\Models\PaymentGateway;
use App\Models\PaymentMethod;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class PaymentOverviewWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';
    
    protected function getStats(): array
    {
        // Get transactions from last 30 days
        $startDate = now()->subDays(30)->startOfDay();
        
        // Get total transactions and amount
        $totalTransactions = PaymentTransaction::where('created_at', '>=', $startDate)->count();
        $totalAmount = PaymentTransaction::where('created_at', '>=', $startDate)->sum('amount');
        
        // Calculate success rate
        $successCount = PaymentTransaction::where('status', 'success')
            ->where('created_at', '>=', $startDate)
            ->count();
            
        $successRate = $totalTransactions > 0 
            ? round(($successCount / $totalTransactions) * 100, 2) 
            : 0;
            
        // Get today's transaction count
        $todayCount = PaymentTransaction::whereDate('created_at', today())->count();
        $yesterdayCount = PaymentTransaction::whereDate('created_at', today()->subDay())->count();
        
        $trend = $yesterdayCount > 0 
            ? round((($todayCount - $yesterdayCount) / $yesterdayCount) * 100, 2) 
            : ($todayCount > 0 ? 100 : 0);

        return [
            Stat::make('Total Transactions', number_format($totalTransactions))
                ->description('Last 30 days')
                ->descriptionIcon('heroicon-m-calendar')
                ->chart([7, 2, 10, 3, 15, 4, 17, $todayCount])
                ->color('info'),
                
            Stat::make('Total Amount', 'Rp ' . number_format($totalAmount, 0, ',', '.'))
                ->description('Last 30 days')
                ->descriptionIcon('heroicon-m-banknotes')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
                
            Stat::make('Success Rate', $successRate . '%')
                ->description($successRate > 90 ? 'Excellent' : ($successRate > 80 ? 'Good' : 'Needs improvement'))
                ->descriptionIcon($successRate > 90 ? 'heroicon-m-check-circle' : 'heroicon-m-exclamation-circle')
                ->chart([$successRate, 100 - $successRate])
                ->color($successRate > 90 ? 'success' : ($successRate > 80 ? 'warning' : 'danger')),
                
            Stat::make("Today's Transactions", number_format($todayCount))
                ->description($trend > 0 ? '+' . $trend . '% from yesterday' : $trend . '% from yesterday')
                ->descriptionIcon($trend >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($trend >= 0 ? 'success' : 'danger'),
        ];
    }
}
