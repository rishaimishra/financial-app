<?php

namespace App\Filament\Resources\InsurancePolicies\Pages;

use App\Filament\Resources\InsurancePolicies\InsurancePolicyResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditInsurancePolicy extends EditRecord
{
    protected static string $resource = InsurancePolicyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
