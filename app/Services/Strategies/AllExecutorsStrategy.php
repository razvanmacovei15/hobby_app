<?php

namespace App\Services\Strategies;

use App\Models\User;
use App\Services\Contracts\IExecutorQueryStrategy;
use Illuminate\Database\Eloquent\Builder;

class AllExecutorsStrategy implements IExecutorQueryStrategy
{
    public function applyQuery(Builder $query, User $user): Builder
    {
        return $query;
    }
}
