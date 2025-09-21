<?php

namespace App\Services\Contracts;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

interface IExecutorQueryStrategy
{
    public function applyQuery(Builder $query, User $user): Builder;
}
