<?php

namespace Database\Factories;

use App\Enums\BuildingType;
use App\Models\Address;
use App\Models\BuildingPermit;
use App\Models\ConstructionSite;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Building>
 */
class BuildingFactory extends Factory
{
    protected $states = [
        'Cluj', 'Timisoara', 'Bucuresti'
    ];
    protected $names = ['GP5', 'GP6', 'GP7', 'GP8'];
    protected $buildingTypes = [
        BuildingType::APARTMENT_BUILDING,
        BuildingType::HOUSE,
        BuildingType::OFFICE
    ];
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement($this->names),
            'building_type' => $this->faker->randomElement($this->buildingTypes),
            'construction_site_id' => ConstructionSite::factory(),
            'address_id' => Address::factory(),
            'building_permit_id' => BuildingPermit::factory(),
        ];
    }
}
