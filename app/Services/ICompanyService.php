<?php

namespace App\Services;

interface ICompanyService
{
    public function createOrUpdateCompany(array $companyData, array $addressData, array $representativeData): \App\Models\Company;
}
