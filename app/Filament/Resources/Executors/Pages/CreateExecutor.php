<?php

namespace App\Filament\Resources\Executors\Pages;

use App\Filament\Resources\Executors\ExecutorResource;
use App\Filament\Resources\Executors\Schemas\ExecutorForm;
use App\Models\User;
use App\Services\IExecutorService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Schema;

class CreateExecutor extends CreateRecord
{
    protected static string $resource = ExecutorResource::class;

    public function mount(): void
    {
        // Check if user has required permissions before allowing access
        if (! $this->canCreateWorkspaceExecutor()) {
            Notification::make()
                ->title('Access Denied')
                ->body('You need create permissions for companies, addresses, users (all or none), and workspace executors to create a workspace executor.')
                ->danger()
                ->send();

            $this->redirect($this->getResource()::getUrl('index'));

            return;
        }

        parent::mount();
    }

    protected function canCreateWorkspaceExecutor(): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        // Check if user has all three create permissions (all or nothing)
        $hasCompanyCreatePermission = $user->can('companies.create');
        $hasAddressCreatePermission = $user->can('addresses.create');
        $hasUserCreatePermission = $user->can('users.create');

        // All three must be true or all three must be false
        $allPermissions = [$hasCompanyCreatePermission, $hasAddressCreatePermission, $hasUserCreatePermission];
        $hasAllPermissions = count(array_filter($allPermissions)) === 3;
        $hasNoPermissions = count(array_filter($allPermissions)) === 0;

        if (! ($hasAllPermissions || $hasNoPermissions)) {
            // User has partial permissions, which is not allowed
            return false;
        }

        // Finally check workspace executor create permission
        return $user->can('workspace-executors.create');
    }

    public function getTitle(): string
    {
        return 'Register executor';
    }

    /** In your Edit/Create page class */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        /** @var IExecutorService $svc */
        $svc = app(IExecutorService::class);

        return $svc->mutateFormDataBeforeSave($data, null);
    }

    protected function afterCreate(): void
    {
        // Handle the many-to-many engineer assignments after the main record is created
        $data = $this->form->getRawState(); // Use getRawState() to get dehydrated fields

        if (! empty($data['responsible_engineers'])) {
            $engineerData = [];
            foreach ($data['responsible_engineers'] as $userId) {
                $engineerData[$userId] = 'engineer'; // Default role
            }

            /** @var IExecutorService $svc */
            $svc = app(IExecutorService::class);
            $svc->assignEngineers($this->record, $engineerData);
        }
    }

    public function form(Schema $schema): Schema
    {
        return ExecutorForm::configure($schema)->state([
            'executor' => [
                'address' => [],
                'representative' => [],
            ],
        ]);
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
