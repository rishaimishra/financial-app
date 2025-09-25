<?php

namespace App\Filament\Resources\LoanEmiDetails\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class LoanEmiDetailForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id') // 👈 Use Select for user relation
                    ->relationship('user', 'name') // 👈 Link to the 'user' relation and show the 'name' field
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('lender_name')
                    ->required(),
                TextInput::make('loan_amount')
                    ->numeric(),
                TextInput::make('emi_amount')
                    ->required()
                    ->numeric(),
                TextInput::make('outstanding_balance')
                    ->numeric(),
                TextInput::make('emi_day')
                    ->required()
                    ->numeric(),
                Toggle::make('is_auto_detected'),
                TextInput::make('account_number'),
                DatePicker::make('start_date'),
                DatePicker::make('end_date'),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
