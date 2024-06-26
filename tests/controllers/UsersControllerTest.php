<?php

use Tests\TestCase;

class UsersControllerTest extends TestCase
{

    public function testSuccessfulUpdate(): void
    {
        $user = $this->getTestUser();

        $postData = [
            'firstName' => 'Bob changed',
            'lastName' => 'Swift changed',
            'email' => 'bob.swift.changed@swiftsales.fi',
            'timeZone' => 'Europe/Stocholm',
            'password' => 'test'
        ];

        $headers = $this->createAuthorizationHeaders($user);

        $this->json('PATCH', 'users', $postData, $headers)
            ->seeStatusCode(200)
            ->seeJson([
                'firstName' => $postData['firstName'],
                'lastName' => $postData['lastName'],
                'email' => $postData['email'],
                'timeZone' => $postData['timeZone'],
            ]);
    }

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
