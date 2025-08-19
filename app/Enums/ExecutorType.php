<?php

namespace App\Enums;

enum ExecutorType: string
{
    case ELECTRICAL = 'electrical';
    case MASONRY = 'masonry';
    case PLUMBING = 'plumbing';
    case FACADES = 'facades';
    case FINISHES = 'finishes';

    public function label(): string
    {
        return match ($this) {
            self::ELECTRICAL => 'Electrical Works',
            self::MASONRY => 'Masonry Works',
            self::PLUMBING => 'Plumbing Works',
            self::FACADES => 'Facades Works',
            self::FINISHES => 'Finishes Works',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($c) => [$c->value => $c->label()])
            ->all();
    }
}
