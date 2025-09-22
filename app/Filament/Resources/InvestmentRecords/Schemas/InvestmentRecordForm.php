<?php

namespace App\Filament\Resources\InvestmentRecords\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InvestmentRecordForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                Select::make('type')
                    ->options([
            'mutual_fund' => 'Mutual fund',
            'fd' => 'Fd',
            'stock' => 'Stock',
            'gold' => 'Gold',
            'sip' => 'Sip',
            'other' => 'Other',
        ])
                    ->required(),
                TextInput::make('amount_invested')
                    ->numeric(),
                TextInput::make('current_value')
                    ->numeric(),
                TextInput::make('account_number'),
                DatePicker::make('start_date'),
                DatePicker::make('end_date'),
                TextInput::make('meta_data'),
            ]);
    }
}
