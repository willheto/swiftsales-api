<?php

namespace Database\Seeders;

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
        $this->createAdminUsers();
    }

    protected function createAdminUsers()
    {
        $swiftsalesAdminUsers = [
            [
                'firstName' => 'Henri',
                'lastName' => 'Willman',
                'email' => 'henri.willman@swiftsales.fi',
                'password' => password_hash('test', PASSWORD_BCRYPT)
            ],
            [
                'firstName' => 'Otto',
                'lastName' => 'Örn',
                'email' => 'otto.orn@swiftsales.fi',
                'password' => password_hash('test', PASSWORD_BCRYPT)
            ],
            [
                'firstName' => 'Santeri',
                'lastName' => 'Pohjakallio',
                'email' => 'santeri.pohjakallio@swiftsales.fi',
                'password' => password_hash('test', PASSWORD_BCRYPT)
            ],
            [
                'firstName' => 'Miska',
                'lastName' => 'Lampinen',
                'email' => 'miska.lampinen@swiftsales.fi',
                'password' => password_hash('test', PASSWORD_BCRYPT)

            ]
        ];

        foreach ($swiftsalesAdminUsers as $swiftsalesAdminUser) {
            User::create($swiftsalesAdminUser);
        }
    }
}
