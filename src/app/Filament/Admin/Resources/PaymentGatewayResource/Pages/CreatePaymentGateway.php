<?php

namespace App\Filament\Admin\Resources\PaymentGatewayResource\Pages;

use App\Filament\Admin\Resources\PaymentGatewayResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePaymentGateway extends CreateRecord
{
    protected static string $resource = PaymentGatewayResource::class;
}
