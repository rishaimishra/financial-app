<?php

namespace App\Filament\Resources\FinancialGoals\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class FinancialGoalForm
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
                TextInput::make('name')
                    ->required(),
                Select::make('type')
                    ->options([
                        'savings' => 'Savings',
                        'education' => 'Education',
                        'wedding' => 'Wedding',
                        'elder_care' => 'Elder care',
                        'emergency_fund' => 'Emergency fund',
                        'car' => 'Car',
                        'home' => 'Home',
                    ])
                    ->required(),
                TextInput::make('target_amount')
                    ->required()
                    ->numeric(),
                TextInput::make('current_amount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('currency')
                    ->required()
                    ->default('INR'),
                DatePicker::make('target_date'),
                Toggle::make('is_completed')
                    ->required(),
            ]);
    }
}