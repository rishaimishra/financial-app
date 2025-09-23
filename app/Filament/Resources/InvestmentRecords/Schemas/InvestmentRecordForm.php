<?php

namespace App\Filament\Resources\InvestmentRecords\Schemas;

use App\Models\User; // Don't forget to import the User model!
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
                Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->searchable()
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