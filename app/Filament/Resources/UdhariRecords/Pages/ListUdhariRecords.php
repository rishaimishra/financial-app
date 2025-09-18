<?php

namespace App\Filament\Resources\UdhariRecords\Pages;

use App\Filament\Resources\UdhariRecords\UdhariRecordResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUdhariRecords extends ListRecords
{
    protected static string $resource = UdhariRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
