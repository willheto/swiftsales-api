<?php

namespace Database\Factories;

use App\Models\SalesAppointment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;


class SalesAppointmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SalesAppointment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $userID = rand(1, 14);
        $leadID = rand(1, 100);
        $notes = $this->faker->paragraph;

        return [
            'userID' => $userID,
            'leadID' => $leadID,
            'notes' => $notes,
        ];
    }
}
