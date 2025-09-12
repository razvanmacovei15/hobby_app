<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('companies')->insert([
            [
                'name' => 'Construct Pro SRL',
                'j' => 'J12345678/2023/123',
                'cui' => 'RO12345678',
                'place_of_registration' => 'Cluj-Napoca',
                'iban' => 'RO1234567890123456789012',
                'representative_id' => 2,
                'phone' => '+40 264 123 456',
                'email' => 'contact@constructpro.ro',
                'address_id' => 1,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'name' => 'Building Solutions Ltd',
                'j' => 'J87654321/2023/456',
                'cui' => 'RO87654321',
                'place_of_registration' => 'Bucuresti',
                'iban' => 'RO8765432109876543210987',
                'representative_id' => 1,
                'phone' => '+40 21 987 654',
                'email' => 'info@buildingsolutions.ro',
                'address_id' => 2,
                'created_at' => now()->subDays(4),
                'updated_at' => now()->subDays(4),
            ],
            [
                'name' => 'ElectricTech Solutions SRL',
                'j' => 'J13579246/2023/789',
                'cui' => 'RO13579246',
                'place_of_registration' => 'Timisoara',
                'iban' => 'RO1357924680246813579135',
                'representative_id' => 3,
                'phone' => '+40 256 456 789',
                'email' => 'office@electrictechsolutions.ro',
                'address_id' => 3,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
            [
                'name' => 'Plumbing Masters SRL',
                'j' => 'J24681357/2023/012',
                'cui' => 'RO24681357',
                'place_of_registration' => 'Iasi',
                'iban' => 'RO2468135791357024681357',
                'representative_id' => 4,
                'phone' => '+40 232 789 123',
                'email' => 'contact@plumbingmasters.ro',
                'address_id' => 4,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'name' => 'Interior Design Pro SRL',
                'j' => 'J97531864/2023/345',
                'cui' => 'RO97531864',
                'place_of_registration' => 'Constanta',
                'iban' => 'RO9753186420864197531975',
                'representative_id' => 5,
                'phone' => '+40 241 654 321',
                'email' => 'hello@interiordesignpro.ro',
                'address_id' => 5,
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
            [
                'name' => 'Roofing Specialists SRL',
                'j' => 'J36925814/2023/678',
                'cui' => 'RO36925814',
                'place_of_registration' => 'Brasov',
                'iban' => 'RO3692581470258147036925',
                'representative_id' => 6,
                'phone' => '+40 268 147 258',
                'email' => 'contact@roofingspecialists.ro',
                'address_id' => 1,
                'created_at' => now()->subHours(12),
                'updated_at' => now()->subHours(12),
            ],
            [
                'name' => 'Demolition Experts SRL',
                'j' => 'J85274196/2023/901',
                'cui' => 'RO85274196',
                'place_of_registration' => 'Craiova',
                'iban' => 'RO8527419630741963085274',
                'representative_id' => 7,
                'phone' => '+40 251 852 741',
                'email' => 'office@demolitionexperts.ro',
                'address_id' => 2,
                'created_at' => now()->subHours(8),
                'updated_at' => now()->subHours(8),
            ],
            [
                'name' => 'Concrete Works SRL',
                'j' => 'J74185296/2023/234',
                'cui' => 'RO74185296',
                'place_of_registration' => 'Oradea',
                'iban' => 'RO7418529652963074185296',
                'representative_id' => 8,
                'phone' => '+40 259 741 852',
                'email' => 'info@concreteworks.ro',
                'address_id' => 3,
                'created_at' => now()->subHours(4),
                'updated_at' => now()->subHours(4),
            ],
            [
                'name' => 'Steel Structures SRL',
                'j' => 'J96385274/2023/567',
                'cui' => 'RO96385274',
                'place_of_registration' => 'Arad',
                'iban' => 'RO9638527418529639638527',
                'representative_id' => 9,
                'phone' => '+40 257 963 852',
                'email' => 'contact@steelstructures.ro',
                'address_id' => 4,
                'created_at' => now()->subHours(2),
                'updated_at' => now()->subHours(2),
            ],
            [
                'name' => 'Insulation Masters SRL',
                'j' => 'J15926374/2023/890',
                'cui' => 'RO15926374',
                'place_of_registration' => 'Sibiu',
                'iban' => 'RO1592637485207415926374',
                'representative_id' => 10,
                'phone' => '+40 269 159 263',
                'email' => 'hello@insulationmasters.ro',
                'address_id' => 5,
                'created_at' => now()->subHour(),
                'updated_at' => now()->subHour(),
            ]
        ]);
    }
}
