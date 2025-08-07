<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ConstructionSite>
 */
class ConstructionSiteFactory extends Factory
{
    protected $siteNames = ['Elite City', 'Grand Park Sud'];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement($this->siteNames),
            'address_id' => Address::factory(),
            'location_id' => Location::factory(),
            'site_director_id' => User::factory(),
        ];
    }
}
