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
            'password' => password_hash('test', PASSWORD_BCRYPT)
        ];

        $santeri = [
            'firstName' => 'Santeri',
            'lastName' => 'Pohjakallio',
            'email' => 'santeri.pohjakallio@swiftsales.fi',
            'password' => password_hash('test', PASSWORD_BCRYPT)
        ];

        $miska = [
            'firstName' => 'Miska',
            'lastName' => 'Lampinen',
            'email' => 'miska.lampinen@swiftsales.fi',
            'password' => password_hash('test', PASSWORD_BCRYPT)
        ];

        User::create($henri);
        User::create($otto);
        User::create($santeri);
        User::create($miska);
    }
}
