<?php

namespace App\Services\Implementations;

use App\Models\Address;
use App\Models\Company;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceExecutor;
use App\Services\IExecutorService;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Support\Arr;
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

    public function mutateFormDataBeforeSave(array $data, ?Company $currentExecutor = null): array
    {
        // --- pull nested executor payload from form state ---
        $executorData = Arr::pull($data, 'executor', []);
        $addressData  = Arr::pull($executorData, 'address', []);
        $repData      = Arr::pull($executorData, 'representative', []);

        // --- load or create the executor company ---
        $executor = $currentExecutor ?? new Company();

        $executor->fill(Arr::only($executorData, [
            'name', 'cui', 'j', 'place_of_registration', 'iban',
            'phone', 'email', 'representative_id', // representative_id will be overwritten after we resolve the user
        ]));

        $executor->save();

        // --- address upsert + associate (inline section from previous step) ---
        if (! empty($addressData)) {
            $address = $executor->address ?? new Address();
            $address->fill($addressData)->save();

            if (! $executor->address || $executor->address_id !== $address->id) {
                $executor->address()->associate($address);
                $executor->save();
            }
        }

        // --- representative upsert + associate ---
        if (! empty($repData)) {
            // Prefer to resolve by email to avoid duplicates
            $resolved = null;

            if (! empty($repData['email'])) {
                $resolved = User::query()->firstWhere('email', $repData['email']);
            }

            if ($resolved) {
                // Update name fields on the existing user (don’t touch password)
                $resolved->fill(Arr::only($repData, ['first_name', 'last_name']))->save();
                $user = $resolved;
            } else {
                // Create a new user with a random password (hashed by cast)
                $user = new User();
                $user->fill(Arr::only($repData, ['first_name', 'last_name', 'email']));
                $user->save();
            }

            // Link to the executor
            if (! $executor->representative || $executor->representative_id !== $user->id) {
                $executor->representative()->associate($user);
                $executor->save();
            }
        }
        // If your main record needs an executor_id, put it back so Filament can continue its save
        $data['executor_id'] = $executor->id;
        return $data;
    }


}
