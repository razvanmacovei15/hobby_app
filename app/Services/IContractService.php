<?php

namespace App\Services;

use App\Models\Contract;
use Illuminate\Support\Facades\Date;

interface IContractService
{
    public function generateContractRegistrationKey(int $number, string $date): string;
    public function createContract(array $data): Contract;
}
