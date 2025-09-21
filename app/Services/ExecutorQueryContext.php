<?php

namespace App\Services;

use App\Enums\Permissions\WorkspaceExecutorPermission;
use App\Models\User;
use App\Services\Contracts\IExecutorQueryStrategy;
use App\Services\Strategies\AllExecutorsStrategy;
use App\Services\Strategies\OwnExecutorsStrategy;
use Illuminate\Database\Eloquent\Builder;

class ExecutorQueryContext
{
    public function __construct(
        private AllExecutorsStrategy $allExecutorsStrategy,
        private OwnExecutorsStrategy $ownExecutorsStrategy
    ) {}

    public function getQuery(Builder $query, User $user): Builder
    {
        $strategy = $this->determineStrategy($user);

        return $strategy->applyQuery($query, $user);
    }

    private function determineStrategy(User $user): IExecutorQueryStrategy
    {
        if ($user->canInWorkspace(WorkspaceExecutorPermission::VIEW->value)) {
            return $this->allExecutorsStrategy;
        }

        if ($user->canInWorkspace(WorkspaceExecutorPermission::OWN_VIEW->value)) {
            return $this->ownExecutorsStrategy;
        }

        return $this->ownExecutorsStrategy;
    }
}
