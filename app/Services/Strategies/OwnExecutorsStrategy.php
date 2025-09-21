<?php

namespace App\Services\Strategies;

use App\Models\User;
use App\Services\Contracts\IExecutorQueryStrategy;
use Illuminate\Database\Eloquent\Builder;

class OwnExecutorsStrategy implements IExecutorQueryStrategy
{
    public function applyQuery(Builder $query, User $user): Builder
    {
        return $query->whereHas('engineers', function (Builder $engineersQuery) use ($user) {
            $engineersQuery->where('user_id', $user->id);
        });
    }
}
