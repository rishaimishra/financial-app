<?php

namespace App\Filament\Resources\LoanEmiDetails\Pages;

use App\Filament\Resources\LoanEmiDetails\LoanEmiDetailResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLoanEmiDetails extends ListRecords
{
    protected static string $resource = LoanEmiDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
