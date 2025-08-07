<?php

namespace App\Enums;

enum BuildingType: string
{
    case OFFICE = 'office';
    case APARTMENT_BUILDING = 'apartment_building';
    case HOUSE = 'house';
    case SCHOOL = 'school';
    case SCHOOL_GYM = 'school_gym';

    public function label(): string {
        return match ($this) {
            self::OFFICE => 'Office',
            self::APARTMENT_BUILDING => 'Apartment Building',
            self::HOUSE => 'House',
            self::SCHOOL => 'School',
            self::SCHOOL_GYM => 'School Gym',
        };
    }
}
