<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FloorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('floors')->insert([
            [
                'name' => 'parter',
                'building_id' => 1
            ],[
                'name' => 'etaj 1',
                'building_id' => 1
            ],[
                'name' => 'etaj 2',
                'building_id' => 1
            ],[
                'name' => 'etaj 3',
                'building_id' => 1
            ],[
                'name' => 'etaj 4',
                'building_id' => 1
            ],[
                'name' => 'etaj 5',
                'building_id' => 1
            ]
        ]);
    }
}
