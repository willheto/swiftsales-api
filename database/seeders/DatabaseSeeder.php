<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
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
