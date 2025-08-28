<?php

namespace App\Services;

use App\Models\User;

interface IUserRegistrationService
{
    public function registerUserWithDefaultWorkspace(array $userData): User;
}
