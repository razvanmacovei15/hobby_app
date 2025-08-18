<?php

namespace App\Services\Implementations;

use App\Models\User;
use App\Services\IUserService;
use Illuminate\Support\Arr;

class UserService implements IUserService
{

    public function getUserByEmail($email)
    {
        return User::query()->firstWhere('email', $email);
    }

    public function createOrUpdateCompanyRepresentative(array $representativeData): User
    {
        // Resolve existing user either by explicit id or by unique email
        $user = null;

        if (!empty($representativeData['id'])) {
            $user = User::query()->find($representativeData['id']);
        }

        if (!$user && !empty($representativeData['email'])) {
            $user = User::query()->firstWhere('email', $representativeData['email']);
        }

        $fillableFields = ['first_name', 'last_name', 'email'];

        if ($user) {
            $user->fill(Arr::only($representativeData, $fillableFields));
            $user->save();
            return $user;
        }

        $user = new User();
        $user->fill(Arr::only($representativeData, $fillableFields));
        $user->save();
        return $user;
    }
}
