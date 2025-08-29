<?php

namespace App\Filament\Pages\Invitations;

use App\Services\ICompanyEmployeeService;
use App\Services\IRoleService;
use App\Services\IWorkspaceInvitationService;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\App;
use Exception;

class InviteEmployee extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $title = 'Invite Employee To Workspace';
    protected static ?string $navigationLabel = 'Invite Employee';
    protected static string|null|\UnitEnum $navigationGroup = 'Workspace';
    protected static string|null|\BackedEnum $navigationIcon = Heroicon::OutlinedUserPlus;
    protected string $view = 'filament.pages.invitations.invite-employee';

    public ?array $data = [];

    private IWorkspaceInvitationService $invitationService;

    public function boot(): void
    {
        $this->invitationService = App::make(IWorkspaceInvitationService::class);
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Grid::make(3)->schema([
                    Grid::make(1)->schema([
                        Select::make('employee')
                            ->label('Select Employee to Invite')
                            ->options($this->getEmployeeOptions())
                            ->required()
                            ->searchable()
                            ->columnSpan(1)
                            ->placeholder('Choose an employee...')
                            ->helperText('Select an employee who is not currently in this workspace.'),

                        Select::make('roles')
                            ->label('Select Roles to Assign')
                            ->options($this->getRoleOptions())
                            ->required()
                            ->multiple()
                            ->searchable()
                            ->columnSpan(1)
                            ->placeholder('Choose roles...')
                            ->helperText('The employee will be assigned these roles when they accept the invitation.'),
                    ])->columnSpan(1)
                ])
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        try {
            $data = $this->form->getState();
            $workspace = Filament::getTenant();

            // Send the invitation
            $invitation = $this->invitationService->inviteEmployee(
                $data['employee'],
                $workspace->id,
                $data['roles']
            );

            // Show success notification
            Notification::make()
                ->title('Invitation Sent Successfully!')
                ->body('The invitation has been sent to the employee and they will receive an email with instructions.')
                ->success()
                ->duration(5000)
                ->actions([
                    Action::make('view_invitations')
                        ->label('View All Invitations')
                        ->url('/admin/workspace-invitations')
                        ->button()
                ])
                ->send();

            // Clear the form
            $this->form->fill();

        } catch (Exception $e) {
            // Show error notification
            Notification::make()
                ->title('Failed to Send Invitation')
                ->body($e->getMessage())
                ->danger()
                ->duration(8000)
                ->send();

            // Log the error
            \Log::error('Failed to send workspace invitation', [
                'employee_id' => $data['employee'] ?? null,
                'workspace_id' => $workspace->id ?? null,
                'roles' => $data['roles'] ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    protected function getEmployeeOptions(): array
    {
        try {
            $employees = $this->getAllEmployeesThatAreNotInThisWorkspace();

            if ($employees->isEmpty()) {
                return ['no_employees' => 'No employees available to invite'];
            }

            return $employees->mapWithKeys(function ($employee) {
                $jobTitle = $employee->job_title ? " ({$employee->job_title})" : '';
                return [
                    $employee->id => "{$employee->user->first_name} {$employee->user->last_name}{$jobTitle}"
                ];
            })->toArray();

        } catch (Exception $e) {
            \Log::error('Failed to load employee options', [
                'error' => $e->getMessage()
            ]);

            return ['error' => 'Failed to load employees'];
        }
    }

    protected function getRoleOptions(): array
    {
        try {
            $roles = $this->getRolesForCurrentWorkspace();

            if ($roles->isEmpty()) {
                return ['no_roles' => 'No roles available'];
            }

            return $roles->mapWithKeys(function ($role) {
                return [
                    $role->id => $role->display_name
                ];
            })->toArray();

        } catch (Exception $e) {
            \Log::error('Failed to load role options', [
                'error' => $e->getMessage()
            ]);

            return ['error' => 'Failed to load roles'];
        }
    }

    protected function getAllEmployeesThatAreNotInThisWorkspace()
    {
        $companyEmployeeService = App::make(ICompanyEmployeeService::class);
        $workspace = Filament::getTenant();
        return $companyEmployeeService->getEmployeesTheAreNotInWorkspace($workspace->id);
    }

    protected function getRolesForCurrentWorkspace()
    {
        $roleService = App::make(IRoleService::class);
        return $roleService->getCurrentWorkspaceRoles();
    }

    // Add method to check if user can send invitations
    public static function canAccess(): bool
    {
        // Add your permission logic here
//        return auth()->user()->can('send_workspace_invitations');
    return true;
    }
}
