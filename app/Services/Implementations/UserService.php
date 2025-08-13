<?php

namespace App\Services\Implementations;

use App\Models\User;
use App\Services\IUserService;

class UserService implements IUserService
{

    public function getUserByEmail($email)
    {
        return User::query()->firstWhere('email', $email);
    }
}
