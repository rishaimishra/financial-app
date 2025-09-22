<?php

namespace App\Filament\Resources\FinancialGoals;

use App\Filament\Resources\FinancialGoals\Pages\CreateFinancialGoal;
use App\Filament\Resources\FinancialGoals\Pages\EditFinancialGoal;
use App\Filament\Resources\FinancialGoals\Pages\ListFinancialGoals;
use App\Filament\Resources\FinancialGoals\Schemas\FinancialGoalForm;
use App\Filament\Resources\FinancialGoals\Tables\FinancialGoalsTable;
use App\Models\FinancialGoal;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FinancialGoalResource extends Resource
{
    protected static ?string $model = FinancialGoal::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'FinancialGoal';

    public static function form(Schema $schema): Schema
    {
        return FinancialGoalForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FinancialGoalsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFinancialGoals::route('/'),
            'create' => CreateFinancialGoal::route('/create'),
            'edit' => EditFinancialGoal::route('/{record}/edit'),
        ];
    }
}
