<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('apartments')->insert([
            [
                'label' => 1,
                'address_id' => 1,
                'building_id' => 1,
                'staircase_id' => 1,
                'floor_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'label' => 2,
                'address_id' => 1,
                'building_id' => 1,
                'staircase_id' => 1,
                'floor_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'label' => 3,
                'address_id' => 1,
                'building_id' => 1,
                'staircase_id' => 2,
                'floor_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'label' => 4,
                'address_id' => 1,
                'building_id' => 1,
                'staircase_id' => 2,
                'floor_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
