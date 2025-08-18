<?php

namespace App\Services;

interface IAddressService
{
    public function createOrUpdateAddress(array $addressData): \App\Models\Address;
}
