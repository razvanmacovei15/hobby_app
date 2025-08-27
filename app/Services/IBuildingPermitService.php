<?php

namespace App\Services;

interface IBuildingPermitService
{
    public function getAllPermitsForCompanyId(int $companyId);
}
