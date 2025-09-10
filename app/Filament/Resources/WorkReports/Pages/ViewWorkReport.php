<?php

namespace App\Filament\Resources\WorkReports\Pages;

use App\Enums\WorkReportStatus;
use App\Filament\Resources\WorkReports\WorkReportResource;
use App\Services\IWorkReportService;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewWorkReport extends ViewRecord
{
    protected static string $resource = WorkReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()->color('edit')->icon('heroicon-o-pencil')->label('Edit Work Report'),
            
            Action::make('markAsApproved')
                ->label('Mark as Approved')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn() => $this->record->status !== WorkReportStatus::APPROVED && auth()->user()->can('approve', $this->record))
                ->requiresConfirmation()
                ->modalHeading('Mark Work Report as Approved')
                ->modalDescription('Are you sure you want to mark this work report as approved? This action cannot be undone.')
                ->modalSubmitActionLabel('Yes, Approve')
                ->action(function (IWorkReportService $workReportService) {
                    try {
                        $workReportService->markAsApproved($this->record, auth()->id());
                        
                        Notification::make()
                            ->title('Work Report Approved')
                            ->success()
                            ->send();
                            
                        $this->refreshFormData([$this->record->getKeyName()]);
                        
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Error')
                            ->body('Failed to approve work report: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }

    public function getTitle(): string
    {
        return "{$this->record->getFilamentName()}";
    }
}
