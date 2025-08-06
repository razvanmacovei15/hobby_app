<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConstructionSiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('construction_sites')->insert([
            [
                'name' => 'Elite City',
                'location_id' => 1,
                'site_director_id' => 1,
                'created_at' => now()->subDays(4),
                'updated_at' => now()->subDays(4),
            ],
        ]);
    }
}
