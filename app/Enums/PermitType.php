<?php

namespace App\Enums;

enum PermitType: string
{
    case CONSTRUCTION = 'construction';
    case DEMOLITION = 'demolition';
    case RENOVATION = 'renovation';
    case EXTENSION = 'extension';

    public function label(): string {
        return match ($this) {
            self::CONSTRUCTION => 'Construction',
            self::DEMOLITION => 'Demolition',
            self::RENOVATION => 'Renovation',
            self::EXTENSION => 'Extension',
        };
    }
}