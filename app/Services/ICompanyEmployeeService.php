<?php

namespace App\Services;

interface ICompanyEmployeeService
{
    public function creteCompanyEmployee(array $data);
    public function getEmployeesTheAreNotInWorkspace(int $workspaceId);
    public function restoreCompanyEmployee(int $id);
    public function forceDeleteCompanyEmployee(int $id);
}
