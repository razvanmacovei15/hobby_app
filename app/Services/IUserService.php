<?php

namespace App\Services;

interface IUserService
{
    public function getUserByEmail($email);
    public function createOrUpdateCompanyRepresentative(array $representativeData): \App\Models\User;
}
