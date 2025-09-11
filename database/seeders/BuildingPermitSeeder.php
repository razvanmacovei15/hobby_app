<?php

namespace Database\Seeders;

use App\Enums\PermitType;
use App\Enums\PermitStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BuildingPermitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First create addresses
        $addressIds = [];
        
        $addresses = [
            [
                'street' => 'Strada Republicii',
                'street_number' => '15',
                'city' => 'Cluj-Napoca',
                'building' => null,
                'apartment_number' => null,
                'state' => 'Cluj',
                'country' => 'Romania',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'street' => 'Calea Victoriei',
                'street_number' => '100',
                'city' => 'Bucuresti',
                'building' => 'A1',
                'apartment_number' => '25',
                'state' => 'București',
                'country' => 'Romania',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'street' => 'Strada Mihai Viteazu',
                'street_number' => '42',
                'city' => 'Timisoara',
                'building' => null,
                'apartment_number' => null,
                'state' => 'Timiș',
                'country' => 'Romania',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($addresses as $address) {
            $addressIds[] = DB::table('addresses')->insertGetId($address);
        }

        // Now create building permits
        DB::table('building_permits')->insert([
            [
                'permit_number' => '2024001',
                'permit_type' => PermitType::CONSTRUCTION->value,
                'status' => PermitStatus::APPROVED->value,
                'workspace_id' => 1,
                'name' => 'Residential Complex Phase 1',
                'height_regime' => 'P+4',
                'land_book_number' => '123456',
                'cadastral_number' => '2024/1/15',
                'architect' => 'Arch. Ion Popescu',
                'execution_duration_days' => 180,
                'image_url' => null,
                'validity_term' => '2026-12-31',
                'work_start_date' => '2024-03-01',
                'work_end_date' => '2024-08-28',
                'address_id' => $addressIds[0],
                'issuance_year' => 2024,
                'created_at' => now()->subDays(30),
                'updated_at' => now()->subDays(30),
            ],
            [
                'permit_number' => '2024002',
                'permit_type' => PermitType::RENOVATION->value,
                'status' => PermitStatus::PENDING->value,
                'workspace_id' => 2,
                'name' => 'Office Building Renovation',
                'height_regime' => 'P+8',
                'land_book_number' => '789012',
                'cadastral_number' => '2024/2/20',
                'architect' => 'Arch. Maria Ionescu',
                'execution_duration_days' => 120,
                'image_url' => null,
                'validity_term' => '2025-06-30',
                'work_start_date' => '2024-10-01',
                'work_end_date' => '2025-01-29',
                'address_id' => $addressIds[1],
                'issuance_year' => 2024,
                'created_at' => now()->subDays(15),
                'updated_at' => now()->subDays(15),
            ],
            [
                'permit_number' => '2024003',
                'permit_type' => PermitType::EXTENSION->value,
                'status' => PermitStatus::REJECTED->value,
                'workspace_id' => 1,
                'name' => 'Warehouse Extension',
                'height_regime' => 'P',
                'land_book_number' => '345678',
                'cadastral_number' => '2024/1/30',
                'architect' => 'Arch. Andrei Georgescu',
                'execution_duration_days' => 90,
                'image_url' => null,
                'validity_term' => '2025-03-31',
                'work_start_date' => null,
                'work_end_date' => null,
                'address_id' => $addressIds[2],
                'issuance_year' => 2024,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'permit_number' => '2025001',
                'permit_type' => PermitType::CONSTRUCTION->value,
                'status' => PermitStatus::APPROVED->value,
                'workspace_id' => 2,
                'name' => 'Shopping Mall Development',
                'height_regime' => 'P+2',
                'land_book_number' => '901234',
                'cadastral_number' => '2025/2/01',
                'architect' => 'Arch. Elena Vasilescu',
                'execution_duration_days' => 365,
                'image_url' => null,
                'validity_term' => '2027-12-31',
                'work_start_date' => '2025-02-01',
                'work_end_date' => '2026-02-01',
                'address_id' => $addressIds[0],
                'issuance_year' => 2025,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
