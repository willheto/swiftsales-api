<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

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

        $henri = [
            'firstName' => 'Henri',
            'lastName' => 'Willman',
            'email' => 'henri.willman@swiftsales.fi',
            'password' => password_hash('test', PASSWORD_BCRYPT)
        ];

        $otto = [
            'firstName' => 'Otto',
            'lastName' => 'Örn',
            'email' => 'otto.orn@swiftsales.fi',
        ];

        $santeri = [
            'firstName' => 'Santeri',
            'lastName' => 'Pohjakallio',
            'email' => 'santeri.pohjakallio@swiftsales.fi'
        ];

        $miska = [
            'firstName' => 'Miska',
            'lastName' => 'Lampinen',
            'email' => 'miska.lampinen@swiftsales.fi'
        ];

        User::create($henri);
        User::create($otto);
        User::create($santeri);
        User::create($miska);
    }
}
