<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PaymentGatewayResource\Pages;
use App\Models\PaymentGateway;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class PaymentGatewayResource extends Resource
{
    protected static ?string $model = PaymentGateway::class;
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationLabel = 'Payment Gateways';
    protected static ?string $modelLabel = 'Payment Gateway';
    protected static ?string $pluralModelLabel = 'Payment Gateways';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationGroup = 'Payment Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Gateway Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Gateway Name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('provider')
                            ->label('Provider')
                            ->options([
                                'midtrans' => 'Midtrans',
                                'xendit' => 'Xendit',
                                'doku' => 'DOKU',
                                'ovo' => 'OVO',
                                'gopay' => 'GoPay',
                                'dana' => 'DANA',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('fee_percentage')
                            ->label('Fee Percentage (%)')
                            ->numeric()
                            ->step(0.01)
                            ->minValue(0)
                            ->maxValue(100)
                            ->suffix('%')
                            ->required(),
                        Forms\Components\TextInput::make('api_endpoint')
                            ->label('API Endpoint')
                            ->url()
                            ->maxLength(255),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active Status')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Gateway Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('provider')
                    ->label('Provider')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'midtrans' => 'success',
                        'xendit' => 'info',
                        'doku' => 'warning',
                        'ovo' => 'purple',
                        default => 'gray',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('fee_percentage')
                    ->label('Fee %')
                    ->suffix('%')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_transactions')
                    ->label('Total Transactions')
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('provider')
                    ->options([
                        'midtrans' => 'Midtrans',
                        'xendit' => 'Xendit',
                        'doku' => 'DOKU',
                        'ovo' => 'OVO',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Gateway Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->label('Gateway Name'),
                        Infolists\Components\TextEntry::make('provider')
                            ->label('Provider')
                            ->badge(),
                        Infolists\Components\TextEntry::make('fee_percentage')
                            ->label('Fee Percentage')
                            ->suffix('%'),
                        Infolists\Components\TextEntry::make('api_endpoint')
                            ->label('API Endpoint'),
                        Infolists\Components\IconEntry::make('is_active')
                            ->label('Active Status')
                            ->boolean(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentGateways::route('/'),
            'create' => Pages\CreatePaymentGateway::route('/create'),
            'view' => Pages\ViewPaymentGateway::route('/{record}'),
            'edit' => Pages\EditPaymentGateway::route('/{record}/edit'),
        ];
    }
}
