<?php

namespace App\Filament\Resources\CompanyEmployees\Pages;

use App\Filament\Resources\CompanyEmployees\CompanyEmployeeResource;
use App\Services\ICompanyEmployeeService;
use App\Services\Implementations\CompanyEmployeeService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use Throwable;

class CreateCompanyEmployee extends CreateRecord
{
    protected static string $resource = CompanyEmployeeResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): Model
    {
        $companyEmployeeService = app(ICompanyEmployeeService::class);

        try {
            // Your service returns a WorkReport model (already saved)
            return $companyEmployeeService->creteCompanyEmployee($data);
        } catch (Throwable $e) {
            // Optional: log
            report($e);

            // Show a danger notification
            Notification::make()
                ->title('Could not create employee')
                ->body($e->getMessage())
                ->danger()
                ->send();

            // Keep the user on the form with a validation error
            throw ValidationException::withMessages([
                'report_month' => 'Creation failed: ' . $e->getMessage(),
            ]);
        }
    }
}
