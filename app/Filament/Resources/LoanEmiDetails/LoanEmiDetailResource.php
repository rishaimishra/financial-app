<?php

namespace App\Filament\Resources\LoanEmiDetails;

use App\Filament\Resources\LoanEmiDetails\Pages\CreateLoanEmiDetail;
use App\Filament\Resources\LoanEmiDetails\Pages\EditLoanEmiDetail;
use App\Filament\Resources\LoanEmiDetails\Pages\ListLoanEmiDetails;
use App\Filament\Resources\LoanEmiDetails\Schemas\LoanEmiDetailForm;
use App\Filament\Resources\LoanEmiDetails\Tables\LoanEmiDetailsTable;
use App\Models\LoanEmiDetail;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LoanEmiDetailResource extends Resource
{
    protected static ?string $model = LoanEmiDetail::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return LoanEmiDetailForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LoanEmiDetailsTable::configure($table);
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
            'index' => ListLoanEmiDetails::route('/'),
            'create' => CreateLoanEmiDetail::route('/create'),
            'edit' => EditLoanEmiDetail::route('/{record}/edit'),
        ];
    }
}
