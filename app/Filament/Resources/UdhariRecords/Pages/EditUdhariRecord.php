<?php

namespace App\Filament\Resources\UdhariRecords\Pages;

use App\Filament\Resources\UdhariRecords\UdhariRecordResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditUdhariRecord extends EditRecord
{
    protected static string $resource = UdhariRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
