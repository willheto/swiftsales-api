<?php

namespace Database\Factories;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = Faker::create('fi_FI');

        $organizationID = Organization::all()->random()->organizationID;
        $firstName = $faker->firstName;
        $lastName = $faker->lastName;
        $email = iconv('UTF-8', 'ASCII//TRANSLIT', $firstName . '.' . $lastName . rand(0, 99) . '@swiftsales.fi');
        $password = password_hash('test', PASSWORD_BCRYPT);

        return [
            'organizationID' => $organizationID,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
            'password' => $password
        ];
    }
}
