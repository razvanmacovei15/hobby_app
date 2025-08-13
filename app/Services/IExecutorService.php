<?php

namespace App\Services;

use App\Models\Company;
use App\Models\WorkspaceExecutor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

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
}
