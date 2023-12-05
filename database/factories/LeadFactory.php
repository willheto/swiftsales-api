<?php

namespace Database\Factories;

use App\Models\Lead;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastName;

        $businessID = $this->faker->uuid;
        $companyName = $this->faker->company;
        $contactPerson = $firstName . ' ' . $lastName;
        $contactPhone = $this->faker->phoneNumber;
        $contactEmail = iconv('UTF-8', 'ASCII//TRANSLIT', $firstName . '.' . $lastName . rand(0, 99) . '@' . 'swiftsales' . '.fi');
        $header = $this->faker->sentence;
        $description = $this->faker->paragraph;
        $userID = rand(1, 11);

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
