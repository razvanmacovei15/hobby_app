<?php

namespace App\Enums;

enum ServiceType: string
{
    case CONTRACT_SERVICE = 'contract_service';
    case WORK_REPORT_EXTRA_SERVICE = 'work_report_extra_service';

    public function label(): string
    {
        return match($this) {
            self::CONTRACT_SERVICE => 'Contract Service',
            self::WORK_REPORT_EXTRA_SERVICE => 'Work Report Extra Service',
        };
    }
}
