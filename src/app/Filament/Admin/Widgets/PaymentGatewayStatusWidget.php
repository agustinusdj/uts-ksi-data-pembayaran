<?php

namespace App\Filament\Admin\Widgets;

use App\Models\PaymentGateway;
use Filament\Widgets\Widget;
use Illuminate\Contracts\View\View;
use Illuminate\Support\HtmlString;

class PaymentGatewayStatusWidget extends Widget
{
    protected static string $view = 'filament.admin.widgets.payment-gateway-status';
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';

    public function getViewData(): array
    {
        $gateways = PaymentGateway::all()
            ->map(function (PaymentGateway $gateway) {
                // Simulate checking gateway status - in a real app you'd make API calls
                $isActive = $gateway->is_active;
                $latency = $isActive ? rand(50, 500) : 0; // Simulated latency in ms
                
                $statusColor = match (true) {
                    !$isActive => 'danger',
                    $latency < 100 => 'success',
                    $latency < 300 => 'warning',
                    default => 'danger',
                };
                
                $statusText = match (true) {
                    !$isActive => 'Offline',
                    $latency < 100 => 'Excellent',
                    $latency < 300 => 'Good',
                    $latency < 500 => 'Slow',
                    default => 'Critical',
                };
                
                return [
                    'id' => $gateway->id,
                    'name' => $gateway->name,
                    'provider' => $gateway->provider,
                    'is_active' => $isActive,
                    'latency' => $latency,
                    'status_color' => $statusColor,
                    'status_text' => $statusText,
                    'last_checked' => now()->format('H:i:s'),
                ];
            });

        return [
            'gateways' => $gateways,
        ];
    }
}
