<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SalesAppointment;

class SalesAppointmentsSeeder extends Seeder
{
    /**
     * Run the leads seeder.
     *
     * @return void
     */
    public function run()
    {
        SalesAppointment::factory()->count(100)->create();
    }
}
