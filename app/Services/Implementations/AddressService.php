<?php

namespace App\Services\Implementations;

use App\Models\Address;
use App\Services\IAddressService;

class AddressService implements IAddressService
{
    public function createOrUpdateAddress(array $addressData): Address
    {
        $model = new Address();
        $primaryKey = $model->getKeyName();

        $address = null;
        if (isset($addressData[$primaryKey])) {
            $address = Address::query()->find($addressData[$primaryKey]);
        }

        if ($address) {
            $address->fill($addressData);
            $address->save();
            return $address;
        }

        return Address::create($addressData);
    }
}
