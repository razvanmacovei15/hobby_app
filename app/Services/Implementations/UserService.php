<?php

namespace App\Services\Implementations;

use App\Models\User;
use App\Services\IUserService;

class UserService implements IUserService
{

    public function getDefaultWorkReportCreator()
    {
        return User::where('email', 'dan@email.com')->first();
    }
}
