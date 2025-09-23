<?php

namespace App\Filament\Resources\Businesses\Tables;

use App\Models\Business;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BusinessesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->sortable()
                    ->searchable()
                    ->label('Owner'),
                TextColumn::make('name')
                    ->searchable()
                    ->description(fn (Business $record) => $record->industry)
                    ->weight('bold'),
                TextColumn::make('industry')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('total_income')
                    ->money('INR')
                    ->color('success')
                    ->getStateUsing(function (Business $record) {
                        return $record->incomes()
                            ->with('transaction')
                            ->get()
                            ->sum(function ($income) {
                                return $income->transaction->amount ?? 0;
                            });
                    })
                    ->label('Total Income'),
                TextColumn::make('total_expenses')
                    ->money('INR')
                    ->color('danger')
                    ->getStateUsing(function (Business $record) {
                        return $record->expenses()
                            ->with('transaction')
                            ->get()
                            ->sum(function ($expense) {
                                return $expense->transaction->amount ?? 0;
                            });
                    })
                    ->label('Total Expenses'),
                TextColumn::make('profit_loss')
                    ->money('INR')
                    ->color(function (Business $record) {
                        $income = $record->incomes()
                            ->with('transaction')
                            ->get()
                            ->sum(function ($income) {
                                return $income->transaction->amount ?? 0;
                            });
                        $expenses = $record->expenses()
                            ->with('transaction')
                            ->get()
                            ->sum(function ($expense) {
                                return $expense->transaction->amount ?? 0;
                            });
                        return ($income - $expenses) >= 0 ? 'success' : 'danger';
                    })
                    ->getStateUsing(function (Business $record) {
                        $income = $record->incomes()
                            ->with('transaction')
                            ->get()
                            ->sum(function ($income) {
                                return $income->transaction->amount ?? 0;
                            });
                        $expenses = $record->expenses()
                            ->with('transaction')
                            ->get()
                            ->sum(function ($expense) {
                                return $expense->transaction->amount ?? 0;
                            });
                        return $income - $expenses;
                    })
                    ->label('Profit/Loss'),
                TextColumn::make('transaction_count')
                    ->getStateUsing(function (Business $record) {
                        return $record->incomes()->count() + $record->expenses()->count();
                    })
                    ->label('Transactions'),
                TextColumn::make('created_date')
                    ->date()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Add industry filter
                \Filament\Tables\Filters\SelectFilter::make('industry')
                    ->options(function () {
                        return Business::pluck('industry', 'industry')->unique()->toArray();
                    }),
                // Add date range filter
                \Filament\Tables\Filters\Filter::make('created_date')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('created_from'),
                        \Filament\Forms\Components\DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_date', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_date', '<=', $date),
                            );
                    }),
            ])
            ->recordActions([
                ViewAction::make(), // Add view action
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}