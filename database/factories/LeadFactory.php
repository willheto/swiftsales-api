<?php

namespace Database\Factories;

use App\Models\Lead;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;

class LeadFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Lead::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $faker = Faker::create('fi_FI');
        $firstName = $faker->firstName;
        $lastName = $faker->lastName;

        $businessID = $faker->uuid;
        $companyName = $faker->company;
        $contactPerson = $firstName . ' ' . $lastName;
        $contactPhone = $faker->phoneNumber;
        $contactEmail = iconv('UTF-8', 'ASCII//TRANSLIT', $firstName . '.' . $lastName . rand(0, 99) . '@' . 'swiftsales' . '.fi');
        $header = $faker->sentence;
        $description = $faker->paragraph;
        $userID = rand(1, 14);

        return [
            'userID' => $userID,
            'businessID' => $businessID,
            'companyName' => $companyName,
            'contactPerson' => $contactPerson,
            'contactPhone' => $contactPhone,
            'contactEmail' => $contactEmail,
            'header' => $header,
            'description' => $description,
        ];
    }
}
