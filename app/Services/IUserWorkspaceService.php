<?php

namespace App\Services;

use App\Models\User;

interface IUserWorkspaceService
{
    public function registerUserWithDefaultWorkspace(array $userData): User;
}
