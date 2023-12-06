<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Lead;
use App\Models\SalesAppointment;

class UsersSeeder extends Seeder
{
    /**
     * Run the users seeder.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(10)->create();

        $testUser = [
            'firstName' => 'Henri',
            'lastName' => 'Willman',
            'email' => 'henri.willman@swiftsales.fi',
            'password' => password_hash('test', PASSWORD_BCRYPT)
        ];

        User::create($testUser);
    }
}
