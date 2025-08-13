<?php

namespace App\Services\Implementations;

use App\Models\Company;
use App\Models\Workspace;
use App\Models\WorkspaceExecutor;
use App\Services\IExecutorService;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RectorPrefix202507\React\Dns\Query\ExecutorInterface;
use RuntimeException;

class ExecutorService implements IExecutorService
{

    public function queryForCurrentWorkspace(?bool $onlyActive = null): Builder
    {
        $tenant = Filament::getTenant();

        // Defensive: if no tenant selected, return an empty query to avoid leakage.
        $q = WorkspaceExecutor::query()
            ->with(['executor', 'workspace'])
            ->when($tenant, fn($q) => $q->where('workspace_id', $tenant->id))
            ->when(!$tenant, fn($q) => $q->whereRaw('1 = 0'));

        if (!is_null($onlyActive)) {
            $q->where('is_active', $onlyActive);
        }


        return $q;
    }

    public function findForView(int $workspaceExecutorId): WorkspaceExecutor
    {
        $tenant = Filament::getTenant();
        if (! $tenant) {
            throw new RuntimeException('No active workspace selected.');
        }

        return WorkspaceExecutor::query()
            ->with([
                'executor',                 // Company
                'workspace',                // Workspace
            ])
            ->where('workspace_id', $tenant->id)
            ->findOrFail($workspaceExecutorId);
    }

}
