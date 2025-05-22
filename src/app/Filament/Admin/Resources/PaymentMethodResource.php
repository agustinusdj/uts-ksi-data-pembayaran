<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PaymentMethodResource\Pages;
use App\Models\PaymentMethod;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class PaymentMethodResource extends Resource
{
    protected static ?string $model = PaymentMethod::class;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Payment Methods';
    protected static ?string $modelLabel = 'Payment Method';
    protected static ?string $pluralModelLabel = 'Payment Methods';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationGroup = 'Payment Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Method Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Method Name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('type')
                            ->label('Payment Type')
                            ->options([
                                'bank_transfer' => 'Bank Transfer',
                                'e_wallet' => 'E-Wallet',
                                'credit_card' => 'Credit Card',
                                'virtual_account' => 'Virtual Account',
                                'qris' => 'QRIS',
                            ])
                            ->required(),
                        Forms\Components\FileUpload::make('icon')
                            ->label('Icon')
                            ->image()
                            ->imageEditor()
                            ->directory('payment-methods'),
                        Forms\Components\TextInput::make('min_amount')
                            ->label('Minimum Amount')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),
                        Forms\Components\TextInput::make('max_amount')
                            ->label('Maximum Amount')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),
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
                Tables\Columns\ImageColumn::make('icon')
                    ->label('Icon')
                    ->size(40)
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Method Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type_display')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Bank Transfer' => 'info',
                        'E-Wallet' => 'success',
                        'Credit Card' => 'warning',
                        'Virtual Account' => 'purple',
                        'QRIS' => 'orange',
                        default => 'gray',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('min_amount')
                    ->label('Min Amount')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('max_amount')
                    ->label('Max Amount')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'bank_transfer' => 'Bank Transfer',
                        'e_wallet' => 'E-Wallet',
                        'credit_card' => 'Credit Card',
                        'virtual_account' => 'Virtual Account',
                        'qris' => 'QRIS',
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
                Infolists\Components\Section::make('Method Details')
                    ->schema([
                        Infolists\Components\ImageEntry::make('icon')
                            ->label('Icon')
                            ->size(80)
                            ->circular(),
                        Infolists\Components\TextEntry::make('name')
                            ->label('Method Name'),
                        Infolists\Components\TextEntry::make('type_display')
                            ->label('Type')
                            ->badge(),
                        Infolists\Components\TextEntry::make('min_amount')
                            ->label('Minimum Amount')
                            ->money('IDR'),
                        Infolists\Components\TextEntry::make('max_amount')
                            ->label('Maximum Amount')
                            ->money('IDR'),
                        Infolists\Components\IconEntry::make('is_active')
                            ->label('Active Status')
                            ->boolean(),
                    ])
                    ->columns(2),
                Infolists\Components\Section::make('Statistics')
                    ->schema([
                        Infolists\Components\TextEntry::make('paymentTransactions.count')
                            ->label('Total Transactions')
                            ->badge()
                            ->color('success'),
                    ])
                    ->collapsed(),
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
            'index' => Pages\ListPaymentMethods::route('/'),
            'create' => Pages\CreatePaymentMethod::route('/create'),
            'view' => Pages\ViewPaymentMethod::route('/{record}'),
            'edit' => Pages\EditPaymentMethod::route('/{record}/edit'),
        ];
    }
}
