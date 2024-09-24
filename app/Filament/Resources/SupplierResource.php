<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierResource\Pages;
use App\Filament\Resources\SupplierResource\RelationManagers;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255)
                ->prefixIcon('heroicon-o-user'),
            Forms\Components\TextInput::make('email')
                ->required()
                ->email()
                ->maxLength(255)
                ->prefixIcon('heroicon-o-envelope'),
            Forms\Components\TextInput::make('phone')
                ->required()
                ->maxLength(20)
                ->tel()
                ->prefixIcon('heroicon-o-phone'),
                Forms\Components\Select::make('country_id')
                ->label('Country')
                ->relationship('country', 'name') // Create relation with Country
                ->searchable()
                ->required(),
            Forms\Components\TextInput::make('company_name')
                ->maxLength(255)
                ->prefixIcon('heroicon-o-building-office'),
            Forms\Components\TextInput::make('address')
                ->maxLength(255)
                ->prefixIcon('heroicon-o-map-pin'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('email')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('phone')->sortable(),
            Tables\Columns\TextColumn::make('company_name')->sortable(),
            Tables\Columns\TextColumn::make('address')->sortable(),
            Tables\Columns\TextColumn::make('created_at')->dateTime(),
        ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }
}
