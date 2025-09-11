<?php

namespace App\Services\Implementations;

use App\Enums\PermitStatus;
use App\Models\Address;
use App\Models\BuildingPermit;
use App\Services\IBuildingPermitService;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class BuildingPermitService implements IBuildingPermitService
{
    public function getAllPermitsForCompanyId(int $companyId): Collection
    {
        return BuildingPermit::query()
            ->whereHas('workspace', function ($query) use ($companyId) {
                $query->where('owner_id', $companyId);
            })
            ->with(['workspace', 'address'])
            ->get();
    }

    public function createPermit(array $data): BuildingPermit
    {
        // Calculate work end date if work start date and duration are provided
        if (isset($data['work_start_date']) && isset($data['execution_duration_days'])) {
            $data['work_end_date'] = Carbon::parse($data['work_start_date'])
                ->addDays($data['execution_duration_days'])
                ->toDateString();
        }

        return BuildingPermit::create($data);
    }

    public function updatePermit(BuildingPermit $permit, array $data): BuildingPermit
    {
        // Calculate work end date if work start date and duration are provided
        if (isset($data['work_start_date']) && isset($data['execution_duration_days'])) {
            $data['work_end_date'] = Carbon::parse($data['work_start_date'])
                ->addDays($data['execution_duration_days'])
                ->toDateString();
        }

        $permit->update($data);
        return $permit->refresh();
    }

    public function deletePermit(BuildingPermit $permit): bool
    {
        return $permit->delete();
    }

    public function getPermitsByStatus(string $status): Collection
    {
        return BuildingPermit::query()
            ->where('status', $status)
            ->with(['workspace', 'address'])
            ->get();
    }

    public function getPermitsExpiringWithin(int $days): Collection
    {
        $expirationDate = Carbon::now()->addDays($days);
        
        return BuildingPermit::query()
            ->where('validity_term', '<=', $expirationDate)
            ->where('validity_term', '>=', Carbon::now())
            ->where('status', '!=', PermitStatus::EXPIRED)
            ->with(['workspace', 'address'])
            ->get();
    }

    public function calculateWorkEndDate(BuildingPermit $permit): ?string
    {
        if ($permit->work_start_date && $permit->execution_duration_days) {
            return Carbon::parse($permit->work_start_date)
                ->addDays($permit->execution_duration_days)
                ->toDateString();
        }
        
        return null;
    }

    public function getPermitsByWorkspace(int $workspaceId): Collection
    {
        return BuildingPermit::query()
            ->where('workspace_id', $workspaceId)
            ->with(['workspace', 'address'])
            ->get();
    }

    public function mutateFormDataBeforeCreate(array $data): array
    {
        // Pull nested address payload from form state
        $addressData = Arr::pull($data, 'address', []);

        // Create address if provided
        if (!empty($addressData)) {
            $address = new Address();
            $address->fill(array_filter($addressData))->save();
            
            // Set the address_id for the building permit
            $data['address_id'] = $address->id;
        }

        // Calculate work end date if work start date and duration are provided
        if (isset($data['work_start_date']) && isset($data['execution_duration_days'])) {
            $data['work_end_date'] = Carbon::parse($data['work_start_date'])
                ->addDays($data['execution_duration_days'])
                ->toDateString();
        }

        return $data;
    }

    public function mutateFormDataBeforeUpdate(BuildingPermit $permit, array $data): array
    {
        // Pull nested address payload from form state
        $addressData = Arr::pull($data, 'address', []);

        // Update or create address if provided
        if (!empty($addressData)) {
            if ($permit->address) {
                // Update existing address
                $permit->address->update(array_filter($addressData));
            } else {
                // Create new address and associate it
                $address = new Address();
                $address->fill(array_filter($addressData))->save();
                $permit->address()->associate($address)->save();
                $data['address_id'] = $address->id;
            }
        }

        // Calculate work end date if work start date and duration are provided
        if (isset($data['work_start_date']) && isset($data['execution_duration_days'])) {
            $data['work_end_date'] = Carbon::parse($data['work_start_date'])
                ->addDays($data['execution_duration_days'])
                ->toDateString();
        }

        return $data;
    }
}
