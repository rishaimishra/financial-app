<?php

namespace App\Filament\Resources\InvestmentRecords\Pages;

use App\Filament\Resources\InvestmentRecords\InvestmentRecordResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditInvestmentRecord extends EditRecord
{
    protected static string $resource = InvestmentRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
