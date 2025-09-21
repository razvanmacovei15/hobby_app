<?php

namespace Database\Seeders;

use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            UserSeeder::class,
            AddressSeeder::class,
//            LocationSeeder::class,
            CompanySeeder::class,
            WorkspaceSeeder::class,
            WorkspaceUserSeeder::class,
            BuildingPermitSeeder::class,
//            ConstructionSiteSeeder::class,
//            BuildingSeeder::class,
            ContractSeeder::class,
            ContractAnnexSeeder::class,
            ContractedServiceSeeder::class,
            WorkspaceExecutorSeeder::class,
            WorkReportSeeder::class,
//            ContractExtraServiceSeeder::class,
            WorkReportEntrySeeder::class,
//            StaircaseSeeder::class,
//            FloorSeeder::class,
//            ApartmentSeeder::class,
            AuthorizationSeeder::class
        ]);
    }
}
