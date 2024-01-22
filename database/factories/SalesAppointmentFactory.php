<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\SalesAppointment;
use App\Models\User;
use App\Models\Lead;


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
        $userID = User::all()->random()->userID;
        $leadID = Lead::where('userID', $userID)->get()->random()->leadID;
        $notes = $this->faker->paragraph;

        return [
            'userID' => $userID,
            'leadID' => $leadID,
            'notes' => $notes,
        ];
    }
}
