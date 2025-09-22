<?php

namespace App\Filament\Resources\Transactions\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required(),
                TextInput::make('category_id')
                    ->required()
                    ->numeric(),
                TextInput::make('type_id')
                    ->numeric(),
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
