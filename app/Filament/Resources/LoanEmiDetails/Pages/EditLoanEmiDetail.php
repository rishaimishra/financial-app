<?php

namespace App\Filament\Resources\LoanEmiDetails\Pages;

use App\Filament\Resources\LoanEmiDetails\LoanEmiDetailResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLoanEmiDetail extends EditRecord
{
    protected static string $resource = LoanEmiDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
