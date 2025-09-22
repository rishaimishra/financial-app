<?php

namespace App\Filament\Resources\Businesses\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BusinessForm
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
                TextInput::make('industry'),
                DatePicker::make('created_date'),
            ]);
    }
}