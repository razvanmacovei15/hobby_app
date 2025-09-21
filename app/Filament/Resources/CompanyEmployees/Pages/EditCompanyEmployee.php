<?php

namespace App\Filament\Resources\CompanyEmployees\Pages;

use App\Filament\Resources\CompanyEmployees\CompanyEmployeeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCompanyEmployee extends EditRecord
{
    protected static string $resource = CompanyEmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()->icon('heroicon-o-trash'),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Load the user relationship data into the form
        if ($this->record->user) {
            $data['user'] = [
                'first_name' => $this->record->user->first_name,
                'last_name' => $this->record->user->last_name,
                'email' => $this->record->user->email,
            ];
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Update the user data if provided
        if (isset($data['user']) && $this->record->user) {
            $this->record->user->update([
                'first_name' => $data['user']['first_name'],
                'last_name' => $data['user']['last_name'],
                'email' => $data['user']['email'],
            ]);
        }

        // Remove user data from the main record data since it's handled separately
        unset($data['user']);

        return $data;
    }
}
