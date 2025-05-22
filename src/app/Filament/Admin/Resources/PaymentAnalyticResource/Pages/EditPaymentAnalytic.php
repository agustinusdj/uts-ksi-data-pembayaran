<?php

namespace App\Filament\Admin\Resources\PaymentAnalyticResource\Pages;

use App\Filament\Admin\Resources\PaymentAnalyticResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPaymentAnalytic extends EditRecord
{
    protected static string $resource = PaymentAnalyticResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
