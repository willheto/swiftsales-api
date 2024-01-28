<?php

namespace Tests\Controllers;

use Tests\TestCase;
use App\Models\User;

class userAuthControllerTest extends TestCase
{

    public function testSuccessfulLogin(): void
    {

        $email = 'bob.swift@swiftsales.fi';
        $password = 'test';

        $postData = ['email' => $email, 'password' => $password];

        $this->json('POST', 'user/login', $postData)
            ->seeStatusCode(200)
            ->seeJson([
                'email' => $email,
            ]);
    }

    public function testWrongCredentialsLogin(): void
    {
        $email = 'wrong@swiftsales.fi';
        $password = 'wrongPassword';

        $postData = [
            'email' => $email,
            'password' => $password
        ];

        $this->json('POST', 'user/login', $postData)
            ->seeStatusCode(401)
            ->seeJson([
                'error' => 'Invalid credentials'
            ]);
    }

    public function testWrongPostDataLogin(): void
    {
        $this->json('POST', 'user/login', [])->seeStatusCode(422);

        $this->json('POST', 'user/login', [
            'email' => 'test'
        ])->seeStatusCode(422);

        $this->json('POST', 'user/login', [
            'password' => 'test'
        ])->seeStatusCode(422);

        $this->json('POST', 'user/login', [
            'wrongKey' => 'test'
        ])->seeStatusCode(422);
    }
}
