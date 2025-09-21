<?php

namespace App\Services\Implementations;

use App\Services\IContractService;
use App\Models\Contract;
use Filament\Facades\Filament;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Date;

class ContractService implements IContractService
{
    public function generateContractRegistrationKey(int $number, string $date): string
    {
        $formattedDate = $this->formatDate($date);
        return 'nr. ' . $number . '/' . $formattedDate;
    }

    private function formatDate(string|\DateTimeInterface $date): string
    {
        if ($date instanceof \DateTimeInterface) {
            return $date->format('d.m.Y');
        }

        try {
            return \Illuminate\Support\Carbon::parse($date)->format('d.m.Y');
        } catch (\Throwable $e) {
            return Date::now()->format('d.m.Y');
        }
    }

    public function createContract(array $data): Contract
    {
        $contractNumber = (int) Arr::get($data, 'number');
        $signDate = (string) Arr::get($data, 'sign_date');
        $startDate = (string) Arr::get($data, 'start_date');
        $endDate = (string) Arr::get($data, 'end_date');
        $beneficiaryId = (int) Arr::get($data, 'beneficiary_id');
        $executorId = (int) Arr::get($data, 'executor_id');

        $registrationKey = $this->generateContractRegistrationKey($contractNumber, $signDate);

        return Contract::create([
            'registration_key' => $registrationKey,
            'contract_number' => $contractNumber,
            'beneficiary_id' => $beneficiaryId,
            'executor_id' => $executorId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'sign_date' => $signDate,
        ]);
    }
}
