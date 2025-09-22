<?php

namespace App\Filament\Resources\InvestmentRecords;

use App\Filament\Resources\InvestmentRecords\Pages\CreateInvestmentRecord;
use App\Filament\Resources\InvestmentRecords\Pages\EditInvestmentRecord;
use App\Filament\Resources\InvestmentRecords\Pages\ListInvestmentRecords;
use App\Filament\Resources\InvestmentRecords\Schemas\InvestmentRecordForm;
use App\Filament\Resources\InvestmentRecords\Tables\InvestmentRecordsTable;
use App\Models\InvestmentRecord;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class InvestmentRecordResource extends Resource
{
    protected static ?string $model = InvestmentRecord::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'InvestmentRecord';

    public static function form(Schema $schema): Schema
    {
        return InvestmentRecordForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InvestmentRecordsTable::configure($table);
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
            'index' => ListInvestmentRecords::route('/'),
            'create' => CreateInvestmentRecord::route('/create'),
            'edit' => EditInvestmentRecord::route('/{record}/edit'),
        ];
    }
}
