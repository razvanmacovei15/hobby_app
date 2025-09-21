<?php

namespace App\Filament\Resources\Contracts\Pages;

use App\Filament\Resources\Contracts\ContractResource;
use App\Models\Contract;
use App\Models\WorkspaceExecutor;
use App\Services\IContractService;
use App\Services\Implementations\ContractService;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class CreateContract extends CreateRecord
{
    protected static string $resource = ContractResource::class;
    protected ?IContractService $contractService = null;

    public function mount(): void
    {
        parent::mount();
        $this->initServices();
    }

    protected function handleRecordCreation(array $data): Model
    {
        $tenant = Filament::getTenant();
        $beneficiaryId = $tenant?->owner_id;

        $service = $this->getContractService();

        $contract = $service->createContract([
            'number' => (int) $data['contract_number'],
            'sign_date' => $data['sign_date'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'beneficiary_id' => $beneficiaryId,
            'executor_id' => $data['executor_id'],
        ]);

        if ($tenant && $data['executor_id']) {
            WorkspaceExecutor::query()
                ->where('workspace_id', $tenant->id)
                ->where('executor_id', $data['executor_id'])
                ->update(['has_contract' => true]);
        }

        return $contract;
    }

    private function initServices(): void
    {
        $this->contractService = App::make(ContractService::class);
    }

    protected function getContractService(): ContractService
    {
        if ($this->contractService === null) {
            $this->initServices();
        }
        return $this->contractService;
    }

    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()
            ->label('Save')
            ->icon('heroicon-o-check-circle')
            ->color('success')
            ->extraAttributes([
                'style' => 'color: black;',
            ]); // or any other color like 'primary', 'warning', etc.
    }

    protected function getCreateAnotherFormAction(): Action
    {
        return parent::getCreateAnotherFormAction()
            ->label('Save & Create Another')
            ->icon('heroicon-o-document-duplicate')
            ->extraAttributes([
                'style' => 'color: black;',
            ]); // or any other color like 'primary', 'warning', etc.
    }

    protected function getCancelFormAction(): Action
    {
        return parent::getCancelFormAction()
            ->label('Cancel')
            ->icon('heroicon-o-x-circle')
            ->color('cancel')
            ->extraAttributes([
                'style' => 'color: black;',
            ]); // or any other color like 'primary', 'warning', etc.
    }
}
