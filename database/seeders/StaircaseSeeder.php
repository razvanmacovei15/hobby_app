<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StaircaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('staircases')->insert([
            [
                'label' => 'A2',
                'building_id' => 1,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],[
                'label' => 'A1',
                'building_id' => 1,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],[
                'label' => 'A3',
                'building_id' => 1,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],[
                'label' => 'A4',
                'building_id' => 1,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
        ]);
    }
}
