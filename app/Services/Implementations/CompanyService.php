<?php

namespace App\Services\Implementations;

use App\Models\Address;
use App\Models\Company;
use App\Models\User;
use App\Services\IAddressService;
use App\Services\ICompanyService;
use App\Services\IUserService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class CompanyService implements ICompanyService
{
    protected IUserService $userService;
    protected IAddressService $addressService;

    public function __construct(IUserService $userService, IAddressService $addressService)
    {
        $this->userService = $userService;
        $this->addressService = $addressService;
    }

    public function createOrUpdateCompany(array $companyData, array $addressData, array $representativeData): Company
    {
        return DB::transaction(function () use ($companyData, $addressData, $representativeData) {
            // 1) Upsert representative
            /** @var User $representative */
            $representative = $this->userService->createOrUpdateCompanyRepresentative($representativeData);

            // 2) Upsert address
            /** @var Address $address */
            $address = $this->addressService->createOrUpdateAddress($addressData);

            // 3) Upsert company by id, or by unique fields j/cui/iban if provided
            $company = null;
            if (!empty($companyData['id'])) {
                $company = Company::query()->find($companyData['id']);
            }

            if (!$company) {
                $uniqueKeys = ['j', 'cui', 'iban'];
                foreach ($uniqueKeys as $key) {
                    if (!empty($companyData[$key])) {
                        $company = Company::query()->where($key, $companyData[$key])->first();
                        if ($company) {
                            break;
                        }
                    }
                }
            }

            $fillable = [
                'name',
                'j',
                'cui',
                'place_of_registration',
                'iban',
                'phone',
            ];

            $payload = Arr::only($companyData, $fillable);
            $payload['representative_id'] = $representative->id;
            $payload['address_id'] = $address->id;

            if ($company) {
                $company->fill($payload);
                $company->save();
                return $company;
            }

            return Company::create($payload);
        });
    }
}
