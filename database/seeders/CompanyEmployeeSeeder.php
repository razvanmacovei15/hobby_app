<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanyEmployeeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('company_employees')->upsert([
            [
                'company_id' => 1,
                'user_id' => 1,
                'job_title' => 'Manager',
                'salary' => 8500.00,
                'hired_at' => '2024-01-15',
            ],
            [
                'company_id' => 1,
                'user_id' => 2,
                'job_title' => 'Site Supervisor',
                'salary' => 6500.00,
                'hired_at' => '2024-02-01',
            ],
            [
                'company_id' => 1,
                'user_id' => 3,
                'job_title' => 'Project Coordinator',
                'salary' => 5500.00,
                'hired_at' => '2024-02-15',
            ],
            [
                'company_id' => 1,
                'user_id' => 4,
                'job_title' => 'Construction Worker',
                'salary' => 3500.00,
                'hired_at' => '2024-03-01',
            ],
            [
                'company_id' => 1,
                'user_id' => 5,
                'job_title' => 'Safety Officer',
                'salary' => 4500.00,
                'hired_at' => '2024-03-10',
            ],
            [
                'company_id' => 1,
                'user_id' => 6,
                'job_title' => 'Electrician',
                'salary' => 4200.00,
                'hired_at' => '2024-03-20',
            ],
            [
                'company_id' => 1,
                'user_id' => 7,
                'job_title' => 'Plumber',
                'salary' => 4000.00,
                'hired_at' => '2024-04-01',
            ],
            [
                'company_id' => 1,
                'user_id' => 8,
                'job_title' => 'Carpenter',
                'salary' => 3800.00,
                'hired_at' => '2024-04-15',
            ],
            [
                'company_id' => 1,
                'user_id' => 9,
                'job_title' => 'Mason',
                'salary' => 3600.00,
                'hired_at' => '2024-05-01',
            ],
            [
                'company_id' => 1,
                'user_id' => 10,
                'job_title' => 'Equipment Operator',
                'salary' => 4100.00,
                'hired_at' => '2024-05-15',
            ],
        ], ['company_id', 'user_id']);
    }
}
