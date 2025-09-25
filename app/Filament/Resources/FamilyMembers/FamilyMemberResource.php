<?php

namespace App\Filament\Resources\FamilyMembers;

use App\Filament\Resources\FamilyMembers\Pages\CreateFamilyMember;
use App\Filament\Resources\FamilyMembers\Pages\EditFamilyMember;
use App\Filament\Resources\FamilyMembers\Pages\ListFamilyMembers;
use App\Filament\Resources\FamilyMembers\Schemas\FamilyMemberForm;
use App\Filament\Resources\FamilyMembers\Tables\FamilyMembersTable;
use App\Models\FamilyMember;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FamilyMemberResource extends Resource
{
    protected static ?string $model = FamilyMember::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'FamilyMember';

    public static function form(Schema $schema): Schema
    {
        return FamilyMemberForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FamilyMembersTable::configure($table);
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
            'index' => ListFamilyMembers::route('/'),
            'create' => CreateFamilyMember::route('/create'),
            'edit' => EditFamilyMember::route('/{record}/edit'),
        ];
    }
}
