<?php

namespace App\Filament\Resources\UdhariRecords;

use App\Filament\Resources\UdhariRecords\Pages\CreateUdhariRecord;
use App\Filament\Resources\UdhariRecords\Pages\EditUdhariRecord;
use App\Filament\Resources\UdhariRecords\Pages\ListUdhariRecords;
use App\Filament\Resources\UdhariRecords\Schemas\UdhariRecordForm;
use App\Filament\Resources\UdhariRecords\Tables\UdhariRecordsTable;
use App\Models\UdhariRecord;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UdhariRecordResource extends Resource
{
    protected static ?string $model = UdhariRecord::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return UdhariRecordForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UdhariRecordsTable::configure($table);
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
            'index' => ListUdhariRecords::route('/'),
            'create' => CreateUdhariRecord::route('/create'),
            'edit' => EditUdhariRecord::route('/{record}/edit'),
        ];
    }
}
