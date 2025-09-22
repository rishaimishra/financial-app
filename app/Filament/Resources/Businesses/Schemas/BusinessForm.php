<?php

namespace App\Filament\Resources\Businesses\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BusinessForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('industry'),
                DatePicker::make('created_date'),
            ]);
    }
}
