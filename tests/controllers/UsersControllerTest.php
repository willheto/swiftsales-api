<?php

use Tests\TestCase;

class UsersControllerTest extends TestCase
{


    public function testUpdateWithoutAuth(): void
    {
        $postData = [
            'firstName' => 'Bob changed',
            'lastName' => 'Swift changed',
            'email' => 'bob.swift.changed@swiftsales.fi',
            'timeZone' => 'Europe/Stocholm',
            'userType' => 'admin',
            'password' => password_hash('test22', PASSWORD_BCRYPT)
        ];

        $this->json('PATCH', 'users', $postData)
            ->seeStatusCode(401);
    }
}
