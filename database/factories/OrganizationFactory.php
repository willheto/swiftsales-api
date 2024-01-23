<?php

namespace Database\Factories;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;

class OrganizationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Organization::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = Faker::create('fi_FI');

        $organizationName = $faker->company;
        $licenseType = $faker->randomElement(['basic', 'pro', 'premium']);

        return [
            'organizationName' => $organizationName,
            'licenseType' => $licenseType
        ];
    }
}
