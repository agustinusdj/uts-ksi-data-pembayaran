<?php

namespace App\Filament\Admin\Resources\PaymentGatewayResource\Pages;

use App\Filament\Admin\Resources\PaymentGatewayResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPaymentGateway extends ViewRecord
{
    protected static string $resource = PaymentGatewayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
