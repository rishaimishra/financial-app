<?php

namespace App\Filament\Resources\InvestmentRecords\Pages;

use App\Filament\Resources\InvestmentRecords\InvestmentRecordResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInvestmentRecords extends ListRecords
{
    protected static string $resource = InvestmentRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
