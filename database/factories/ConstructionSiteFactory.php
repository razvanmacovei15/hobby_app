<?php

namespace Database\Factories;

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
            'location_id' => 1,
            'site_director_id' => 1,
        ];
    }
}
