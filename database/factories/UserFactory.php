<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastName;
        $email = iconv('UTF-8', 'ASCII//TRANSLIT', $firstName . '.' . $lastName . rand(0, 99) . '@swiftsales.fi');
        $password = password_hash('test', PASSWORD_BCRYPT);

        return [
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
            'password' => $password
        ];
    }
}
