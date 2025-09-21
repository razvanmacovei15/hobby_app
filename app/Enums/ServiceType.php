<?php

namespace App\Enums;

enum ServiceType: string
{
    case CONTRACTED_SERVICE = 'contract_service';
    case WORK_REPORT_EXTRA_SERVICE = 'work_report_extra_service';

    public function label(): string
    {
        return match($this) {
            self::CONTRACTED_SERVICE => 'Contracted Service',
            self::WORK_REPORT_EXTRA_SERVICE => 'Work Report Extra Service',
        };
    }
}
