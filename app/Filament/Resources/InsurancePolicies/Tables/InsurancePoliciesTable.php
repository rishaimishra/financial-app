<?php

namespace App\Filament\Resources\InsurancePolicies\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InsurancePoliciesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user_id')
                    ->searchable(),
                TextColumn::make('provider_name')
                    ->searchable(),
                TextColumn::make('policy_type')
                    ->badge(),
                TextColumn::make('policy_number')
                    ->searchable(),
                TextColumn::make('premium_amount')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('premium_frequency')
                    ->searchable(),
                TextColumn::make('sum_assured')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('maturity_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
