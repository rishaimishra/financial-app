<?php

namespace App\Filament\Resources\InsurancePolicies\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InsurancePolicyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required(),
                TextInput::make('provider_name')
                    ->required(),
                Select::make('policy_type')
                    ->options([
            'health' => 'Health',
            'term' => 'Term',
            'life' => 'Life',
            'motor' => 'Motor',
            'other' => 'Other',
        ])
                    ->required(),
                TextInput::make('policy_number')
                    ->required(),
                TextInput::make('premium_amount')
                    ->numeric(),
                TextInput::make('premium_frequency'),
                TextInput::make('sum_assured')
                    ->numeric(),
                DatePicker::make('maturity_date'),
                TextInput::make('meta_data'),
            ]);
    }
}
