<?php

namespace App\Enums;

enum WorkReportStatus: string
{
    case DRAFT = 'draft';
    case PENDING_APPROVAL = 'pending_approval';
    case APPROVED = 'approved';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::PENDING_APPROVAL => 'Pending Approval',
            self::APPROVED => 'Approved',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::DRAFT => 'gray',
            self::PENDING_APPROVAL => 'warning',
            self::APPROVED => 'success',
        };
    }
}
