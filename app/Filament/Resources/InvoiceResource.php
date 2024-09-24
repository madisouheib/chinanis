<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Filament\Resources\InvoiceResource\RelationManagers;
use App\Models\Invoice;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Modal;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;
    public $invoiceId;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Select::make('client_id')
                ->relationship('client', 'name')
                ->createOptionForm([
                    Forms\Components\TextInput::make('name')->required(),
                    Forms\Components\TextInput::make('email')->email()->required(),
                    Forms\Components\TextInput::make('phone'),
                ])->required(),
            Forms\Components\Select::make('supplier_id')
                ->relationship('supplier', 'name')
                ->createOptionForm([
                    Forms\Components\TextInput::make('name')->required(),
                    Forms\Components\TextInput::make('email')->email(),
                    Forms\Components\TextInput::make('phone'),
                ])->required(),
            Forms\Components\DatePicker::make('from')->required(),
            Forms\Components\DatePicker::make('to')->required(),
            Forms\Components\Repeater::make('items')
                ->relationship('items')
                ->schema([
                    Forms\Components\Select::make('product_id')
                        ->relationship('product', 'name')
                        ->createOptionForm([
                            Forms\Components\TextInput::make('name')->required(),
                            Forms\Components\TextInput::make('price')->numeric()->required(),
                            Forms\Components\Select::make('supplier_id')
                                        ->relationship('supplier', 'name')
                                        ->required()
                                        ->label('Supplier'), // Add the supplier select here
                        ])->required(),
                    Forms\Components\TextInput::make('quantity')->numeric()->required(),
                    Forms\Components\TextInput::make('unit_price')->numeric()->required(),
                    Forms\Components\TextInput::make('subtotal')->numeric()->required(),
                ])
                ->required(),
            Forms\Components\TextInput::make('total_amount')->numeric()->required(),
            Forms\Components\Select::make('status')
                ->options([
                    'draft' => 'Draft',
                    'approved' => 'Approved',
                    'in_process' => 'In Process',
                    'completed' => 'Completed',
                ])->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('client.name')->label('Client'),
            Tables\Columns\TextColumn::make('supplier.name')->label('Supplier'),
            Tables\Columns\TextColumn::make('total_amount')->label('Total Amount'),
            Tables\Columns\TextColumn::make('paid_amount')->label('Paid Amount'),
            Tables\Columns\TextColumn::make('status')->label('Status'),
            Tables\Columns\TextColumn::make('created_at')->dateTime(),
        ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('generatePDF')
                ->label('Download PDF')
                ->icon('heroicon-o-inbox-arrow-down')
                ->url(fn (Invoice $record) => route('invoices.pdf', $record))
                ->openUrlInNewTab(), // Optional: to open in a new tab
                Tables\Actions\Action::make('addPayment')
                ->label('Add Payment')
                ->icon('heroicon-o-banknotes')
                ->action(function (Invoice $record, array $data) {
                    // Add payment to the invoice
                    $record->payments()->create([
                        'amount' => $data['amount'],
                        'payment_date' => $data['payment_date'], // Payment date field

                    ]);

                    // Check if invoice is fully paid
                    if ($record->paid_amount >= $record->total_amount) {
                        $record->update(['status' => 'completed']);
                    }
                })
                ->form([
                    Forms\Components\TextInput::make('amount')
                        ->label('Payment Amount')
                        ->numeric()
                        ->required(),
                              
                    Forms\Components\DatePicker::make('payment_date')
                    ->label('Payment Date')
                    ->default(now()) // Defaults to the current date
                    ->required(),
                ])
                ->modalHeading('Add Payment')
                ->modalButton('Add Payment'),
                Action::make('viewPayments')
                ->label('View Payments')
                ->icon('heroicon-o-eye')
                ->modalHeading('Payment History')
                ->modalButton('Close')
                ->modalWidth('lg')
                ->form(function (Tables\Actions\Action $action) {
                    return [
                        Repeater::make('payments')
                            ->label('Payment History')
                            ->schema([
                                TextInput::make('payment_date')
                                    ->label('Payment Date')
                                    ->disabled(),
                                TextInput::make('amount')
                                    ->label('Amount')
                                    ->numeric()
                                    ->disabled(),
                            ])
                            ->disableItemMovement()
                            ->disableItemCreation()
                            ->disableItemDeletion(),
                    ];
                })
                ->action(function ($record, $data) {
                    // Pass payment data to modal
                    return $record->payments->map(function($payment) {
                        return [
                            'payment_date' => $payment->payment_date->format('Y-m-d'),
                            'amount' => $payment->amount,
                        ];
                    })->toArray();
                })
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
   
   
   


}
