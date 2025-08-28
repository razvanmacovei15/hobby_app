<?php

namespace App\Enums;

enum PermissionCategory: string
{
    case USERS = 'users';
    case WORK_REPORTS = 'work-reports';
    case CONTRACTS = 'contracts';
    case WORKSPACE = 'workspace';

    public function label(): string
    {
        return match ($this) {
            self::USERS => 'Users',
            self::WORK_REPORTS => 'Work Reports',
            self::CONTRACTS => 'Contracts',
            self::WORKSPACE => 'Workspace',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::USERS => 'User management permissions',
            self::WORK_REPORTS => 'Work report management permissions',
            self::CONTRACTS => 'Contract management permissions',
            self::WORKSPACE => 'Workspace management permissions',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($c) => [$c->value => $c->label()])
            ->all();
    }
}
