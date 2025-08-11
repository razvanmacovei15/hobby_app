<?php

namespace App\Enums;

enum ServiceType: string
{
    case CONTRACT_SERVICE = 'contract_service';
    case CONTRACT_EXTRA_SERVICE = 'contract_extra_service';

    public function label(): string
    {
        return match($this) {
            self::CONTRACT_SERVICE => 'Contract Service',
            self::CONTRACT_EXTRA_SERVICE => 'Contract Extra Service',
        };
    }
}
