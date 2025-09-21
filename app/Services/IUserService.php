<?php

namespace App\Services;

interface IUserService
{
    public function getUserByEmail($email);

    public function createOrUpdateCompanyRepresentative(array $representativeData): \App\Models\User;

    public function checkExistingUserByEmail(string $email): ?\App\Models\User;

    public function hasActivePassword(\App\Models\User $user): bool;
}
