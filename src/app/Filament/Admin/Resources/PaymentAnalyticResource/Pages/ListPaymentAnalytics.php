<?php

namespace App\Filament\Admin\Resources\PaymentAnalyticResource\Pages;

use App\Filament\Admin\Resources\PaymentAnalyticResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPaymentAnalytics extends ListRecords
{
    protected static string $resource = PaymentAnalyticResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
