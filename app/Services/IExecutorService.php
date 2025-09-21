<?php

namespace App\Services;

use App\Models\Company;
use App\Models\WorkspaceExecutor;
use Illuminate\Database\Eloquent\Builder;

interface IExecutorService
{
    /**
     * Query builder scoped to the CURRENT Filament tenant (workspace).
     * Return a Builder so Filament can paginate/sort/filter.
     */
    public function queryForCurrentWorkspace(?bool $onlyActive = null): Builder;

    /**
     * Fetch one WorkspaceExecutor for viewing the row you clicked,
     * scoped to the current tenant and eager-loaded.
     */
    public function findForView(int $workspaceExecutorId): WorkspaceExecutor;

    public function mutateFormDataBeforeSave(array $data, ?Company $currentExecutor = null): array;

    /**
     * Get executors by responsible engineer in current workspace (legacy single relationship)
     */
    public function queryByResponsibleEngineer(int $userId): Builder;

    /**
     * Get executors by assigned engineers in current workspace (many-to-many relationship)
     */
    public function queryByAssignedEngineers(array $userIds): Builder;

    /**
     * Assign multiple engineers to an executor
     */
    public function assignEngineers(WorkspaceExecutor $executor, array $engineerData): void;

    /**
     * Sync engineers for an executor (replace all existing assignments)
     */
    public function syncEngineers(WorkspaceExecutor $executor, array $engineerData): void;
}
