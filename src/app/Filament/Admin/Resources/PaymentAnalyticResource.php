<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PaymentAnalyticResource\Pages;
use App\Models\PaymentAnalytic;
use App\Models\PaymentGateway;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Builder;

class PaymentAnalyticResource extends Resource
{
    protected static ?string $model = PaymentAnalytic::class;
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Analytics';
    protected static ?string $modelLabel = 'Payment Analytic';
    protected static ?string $pluralModelLabel = 'Payment Analytics';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationGroup = 'Payment Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Analytics Data')
                    ->schema([
                        Forms\Components\Select::make('payment_gateway_id')
                            ->label('Payment Gateway')
                            ->options(PaymentGateway::pluck('name', 'id'))
                            ->required()
                            ->searchable(),
                        Forms\Components\DatePicker::make('date')
                            ->label('Date')
                            ->required(),
                        Forms\Components\TextInput::make('total_transactions')
                            ->label('Total Transactions')
                            ->numeric()
                            ->default(0),
                        Forms\Components\TextInput::make('total_amount')
                            ->label('Total Amount')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0),
                        Forms\Components\TextInput::make('total_fee')
                            ->label('Total Fee')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0),
                        Forms\Components\TextInput::make('success_count')
                            ->label('Success Count')
                            ->numeric()
                            ->default(0),
                        Forms\Components\TextInput::make('failed_count')
                            ->label('Failed Count')
                            ->numeric()
                            ->default(0),
                        Forms\Components\TextInput::make('pending_count')
                            ->label('Pending Count')
                            ->numeric()
                            ->default(0),
                        Forms\Components\TextInput::make('success_rate')
                            ->label('Success Rate (%)')
                            ->numeric()
                            ->step(0.01)
                            ->suffix('%')
                            ->default(0),
                        Forms\Components\TextInput::make('avg_processing_time')
                            ->label('Avg Processing Time (seconds)')
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('paymentGateway.name')
                    ->label('Gateway')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_transactions')
                    ->label('Total Transactions')
                    ->badge()
                    ->color('info')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total Amount')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('success_count')
                    ->label('Success')
                    ->badge()
                    ->color('success')
                    ->sortable(),
                Tables\Columns\TextColumn::make('failed_count')
                    ->label('Failed')
                    ->badge()
                    ->color('danger')
                    ->sortable(),
                Tables\Columns\TextColumn::make('success_rate')
                    ->label('Success Rate')
                    ->suffix('%')
                    ->sortable(),
                Tables\Columns\TextColumn::make('avg_processing_time')
                    ->label('Avg Time')
                    ->formatStateUsing(fn (int $state): string => gmdate("H:i:s", $state))
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('payment_gateway_id')
                    ->label('Gateway')
                    ->options(PaymentGateway::pluck('name', 'id')),
                Filter::make('date')
                    ->form([
                        Forms\Components\DatePicker::make('date_from'),
                        Forms\Components\DatePicker::make('date_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['date_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['date_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                            );
                    }),
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
            ->defaultSort('date', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Daily Analytics')
                    ->schema([
                        Infolists\Components\TextEntry::make('paymentGateway.name')
                            ->label('Payment Gateway'),
                        Infolists\Components\TextEntry::make('date')
                            ->label('Date')
                            ->date(),
                        Infolists\Components\TextEntry::make('total_transactions')
                            ->label('Total Transactions')
                            ->badge()
                            ->color('info'),
                        Infolists\Components\TextEntry::make('total_amount')
                            ->label('Total Amount')
                            ->money('IDR'),
                        Infolists\Components\TextEntry::make('total_fee')
                            ->label('Total Fees')
                            ->money('IDR'),
                    ])
                    ->columns(2),
                    
                Infolists\Components\Section::make('Success Metrics')
                    ->schema([
                        Infolists\Components\TextEntry::make('success_count')
                            ->label('Success Count')
                            ->badge()
                            ->color('success'),
                        Infolists\Components\TextEntry::make('failed_count')
                            ->label('Failed Count')
                            ->badge()
                            ->color('danger'),
                        Infolists\Components\TextEntry::make('pending_count')
                            ->label('Pending Count')
                            ->badge()
                            ->color('warning'),
                        Infolists\Components\TextEntry::make('success_rate')
                            ->label('Success Rate')
                            ->suffix('%')
                            ->color(fn($state) => 
                                $state > 90 ? 'success' : 
                                ($state > 70 ? 'warning' : 'danger')),
                        Infolists\Components\TextEntry::make('avg_processing_time')
                            ->label('Avg Processing Time')
                            ->formatStateUsing(fn (int $state): string => gmdate("H:i:s", $state)),
                    ])
                    ->columns(2),
                    
                Infolists\Components\Section::make('Visualization')
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\Stats::make('Success/Failed')
                                    ->statsColor('success')
                                    ->chart([
                                        $infolist->getRecord()->success_count, 
                                        $infolist->getRecord()->failed_count,
                                        $infolist->getRecord()->pending_count
                                    ])
                                    ->columnSpan(3),
                            ]),
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
            'index' => Pages\ListPaymentAnalytics::route('/'),
            'create' => Pages\CreatePaymentAnalytic::route('/create'),
            'view' => Pages\ViewPaymentAnalytic::route('/{record}'),
            'edit' => Pages\EditPaymentAnalytic::route('/{record}/edit'),
        ];
    }
}
