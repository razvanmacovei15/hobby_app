<?php

namespace Database\Seeders;

use App\Enums\BuildingType;
use App\Models\ConstructionSite;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BuildingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('buildings')->insert([
            [
                'name' => 'GP5',
                'address_id' => 1,
                'building_type' => BuildingType::APARTMENT_BUILDING->value,
                'construction_site_id' => 1,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ]
        ]);
    }
}
