<?php

namespace App\Policies;

use App\Enums\WorkReportStatus;
use App\Models\User;
use App\Models\WorkReport;
use Illuminate\Support\Facades\Log;

class WorkReportPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        $result = $user->canInWorkspace('work-reports.view');
        Log::info('WorkReportPolicy::viewAny', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'permission' => 'work-reports.view',
            'result' => $result
        ]);
        return $result;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, WorkReport $workReport): bool
    {
        $result = $user->canInWorkspace('work-reports.view');
        Log::info('WorkReportPolicy::view', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'work_report_id' => $workReport->id,
            'permission' => 'work-reports.view',
            'result' => $result
        ]);
        return $result;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        $result = $user->canInWorkspace('work-reports.create');
        Log::info('WorkReportPolicy::create', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'permission' => 'work-reports.create',
            'result' => $result
        ]);
        return $result;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, WorkReport $workReport): bool
    {
        // Prevent editing approved work reports
        if ($workReport->status === WorkReportStatus::APPROVED) {
            Log::info('WorkReportPolicy::update - BLOCKED (approved status)', [
                'user_id' => $user->id,
                'work_report_id' => $workReport->id,
                'work_report_status' => $workReport->status->value,
                'result' => false
            ]);
            return false;
        }

        $result = $user->canInWorkspace('work-reports.edit');
        Log::info('WorkReportPolicy::update', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'work_report_id' => $workReport->id,
            'permission' => 'work-reports.edit',
            'result' => $result
        ]);
        return $result;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, WorkReport $workReport): bool
    {
        $result = $user->canInWorkspace('work-reports.delete');
        Log::info('WorkReportPolicy::delete', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'work_report_id' => $workReport->id,
            'permission' => 'work-reports.delete',
            'result' => $result
        ]);
        return $result;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, WorkReport $workReport): bool
    {
        return $user->canInWorkspace('work-reports.edit');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, WorkReport $workReport): bool
    {
        return $user->canInWorkspace('work-reports.delete');
    }

    /**
     * Determine whether the user can approve the work report.
     */
    public function approve(User $user, WorkReport $workReport): bool
    {
        $result = $user->canInWorkspace('work-reports.approve');
        Log::info('WorkReportPolicy::approve', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'work_report_id' => $workReport->id,
            'permission' => 'work-reports.approve',
            'result' => $result
        ]);
        return $result;
    }
}
