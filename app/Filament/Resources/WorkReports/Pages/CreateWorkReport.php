<?php

namespace App\Filament\Resources\WorkReports\Pages;

use App\Filament\Resources\WorkReports\WorkReportResource;
use App\Services\Implementations\WorkReportService;
use App\Services\IWorkReportService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;
use Throwable;

class CreateWorkReport extends CreateRecord
{
    protected static string $resource = WorkReportResource::class;

    /**
     * Let the service create the record and return the Model.
     * Filament will treat the returned model as the created record.
     */
    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $svc = app(IWorkReportService::class);

        try {
            // Your service returns a WorkReport model (already saved)
            return $svc->createReportFromFilamentResource($data);
        } catch (Throwable $e) {
            // Optional: log
            report($e);

            // Show a danger notification
            Notification::make()
                ->title('Could not create work report')
                ->body($e->getMessage())
                ->danger()
                ->send();

            // Keep the user on the form with a validation error
            throw ValidationException::withMessages([
                'report_month' => 'Creation failed: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Nice success message that uses the created record.
     */
    protected function getCreatedNotification(): ?Notification
    {
        $r = $this->record; // the WorkReport returned above

        return Notification::make()
            ->success()
            ->title('Work report created')
            ->body("Report #{$r->report_number}/{$r->report_year} was created successfully.");
    }

    // (Optional) Redirect wherever you want after success:
     protected function getRedirectUrl(): string
     {
         return static::getResource()::getUrl('view', ['record' => $this->record]);
     }

}
