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
        $userID = rand(1, 11);
        $leadID = rand(1, 100);

        $faker = Faker::create();
        $timeStart = $faker->dateTimeBetween('now', '+1 week')->format('Y-m-d H:i:s');
        $timeEnd = $faker->dateTimeBetween($timeStart, $timeStart . '+1 hour')->format('Y-m-d H:i:s');
        
        $notes = $this->faker->paragraph;

        return [
            'userID' => $userID,
            'leadID' => $leadID,
            'timeStart' => $timeStart,
            'timeEnd' => $timeEnd,
            'notes' => $notes,
        ];
    }
}
