<?php

namespace App\Filament\Resources\LoanEmiDetails\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LoanEmiDetailsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name') // 👈 Displays the user's name
                    ->sortable()
                    ->searchable(),
                TextColumn::make('lender_name')
                    ->searchable(),
                TextColumn::make('loan_amount')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('emi_amount')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('outstanding_balance')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('emi_day')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_auto_detected')
                    ->boolean(),
                TextColumn::make('account_number')
                    ->searchable(),
                TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->date()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->boolean(),
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
