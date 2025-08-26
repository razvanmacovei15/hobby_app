<?php

namespace App\Filament\Resources\Executors\Pages;

use App\Filament\Resources\Executors\ExecutorResource;
use App\Filament\Resources\Contracts\ContractResource;
use App\Models\Contract;
use App\Services\IContractService;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\App;
use App\Filament\Resources\Executors\Schemas\ExecutorForm;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;

class ViewExecutor extends ViewRecord
{
    protected static string $resource = ExecutorResource::class;

    protected ?IContractService $contractService = null;

    protected function initServices(): void {}

    public function mount($record): void
    {
        parent::mount($record);
        $this->initServices();
    }

    protected function getContractService(): IContractService
    {
        if (!$this->contractService) {
            $this->initServices();
        }
        return $this->contractService;
    }

    public function form(Schema $schema): Schema
    {
        return ExecutorForm::configure($schema)
            ->record($this->record);
    }

    protected function getHeaderActions(): array
    {
        $buttonName = $this->record->has_contract ? 'View Contract' : 'Create Contract';

        return [
            Action::make('viewOrCreateContract')
                ->label($buttonName)
                ->icon('heroicon-o-document')
                ->action(function (): void {
                    $tenant = Filament::getTenant();
                    $beneficiaryId = $tenant?->owner_id;

                    if ($this->record->has_contract && $beneficiaryId) {
                        $contract = Contract::query()
                            ->where('beneficiary_id', $beneficiaryId)
                            ->where('executor_id', $this->record->executor_id)
                            ->latest('id')
                            ->first();

                        if ($contract) {
                            $this->redirect(ContractResource::getUrl('view', ['record' => $contract]));
                            return;
                        }
                    }

                    $this->redirect(ContractResource::getUrl('create', [
                        'executor_id' => $this->record->executor_id,
                    ]));
                }),
            EditAction::make()->icon('heroicon-o-pencil')->color('edit'),
            DeleteAction::make()->icon('heroicon-o-trash')->color('delete'),
        ];
    }
}
