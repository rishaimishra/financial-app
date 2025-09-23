<?php

namespace App\Filament\Resources\Transactions\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),
                Select::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->required(),
                Select::make('type_id')
                    ->label('Transaction Type')
                    ->relationship('type', 'name')
                    ->searchable()
                    ->required(),
                TextInput::make('amount')
                    ->required()
                    ->numeric(),
                TextInput::make('description'),
                TextInput::make('currency')
                    ->required()
                    ->default('INR'),
                TextInput::make('source'),
                TextInput::make('meta_data'),
                DateTimePicker::make('transaction_date')
                    ->required(),
            ]);
    }
}
