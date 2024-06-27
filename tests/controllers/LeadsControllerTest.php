<?php

use Tests\TestCase;

class LeadsControllerTest extends TestCase
{

    public function testGetAllLeadsByUserID(): void
    {
        $user = $this->getTestUser();
        $headers = $this->createAuthorizationHeaders($user);

        $this->json('GET', 'users/' . $user->userID . '/leads', [], $headers)
            ->seeStatusCode(200);
    }


    public function testCreateLead(): void
    {
        $user = $this->getTestUser();
        $headers = $this->createAuthorizationHeaders($user);

        $postData = [
            'userID' => $user->userID,
            'businessID' => '1',
            'companyName' => 'Test company',
            'contactPerson' => 'Test person',
            'contactPhone' => '123456789',
            'contactEmail' => 'test@gmail.com',
            'header' => 'Test header',
            'description' => 'Test description'
        ];

        $this->json('POST', 'leads/', $postData, $headers)
            ->seeStatusCode(201)
            ->seeJsonStructure([
                'lead'
            ]);
    }

    public function testGetSingleLead(): void
    {
        $user = $this->getTestUser();
        $headers = $this->createAuthorizationHeaders($user);

        $this->json('GET', 'users/' . $user->userID . '/leads/1', [], $headers)
            ->seeStatusCode(200)
            ->seeJsonStructure([
                'lead'
            ]);
    }

    public function testCreateBatchLeads(): void
    {
        $user = $this->getTestUser();
        $headers = $this->createAuthorizationHeaders($user);

        $postData = [
            'userID' => $user->userID,
            'leads' => [
                [
                    'userID' => $user->userID,
                    'businessID' => '1',
                    'companyName' => 'Test company',
                    'contactPerson' => 'Test person',
                    'contactPhone' => '123456789',
                    'contactEmail' => 'test@gmail.com',
                    'notes' => "test"
                ],
                [
                    'userID' => $user->userID,
                    'businessID' => '1',
                    'companyName' => 'Test company2',
                    'contactPerson' => 'Test person',
                    'contactPhone' => '123456789',
                    'contactEmail' => 'test@gmail.com',
                    'notes' => 'notes test'
                ]
            ]
        ];

        $this->json('POST', 'leads/batch', $postData, $headers)
            ->seeStatusCode(201)
            ->seeJsonStructure([
                'leads'
            ]);
    }

    public function testUpdateLead(): void
    {
        $user = $this->getTestUser();
        $headers = $this->createAuthorizationHeaders($user);

        $postData = [
            'leadID' => 1,
            'businessID' => 'updated lead',
        ];

        $this->json('PATCH', 'leads/', $postData, $headers)
            ->seeStatusCode(200)
            ->seeJson(
                [
                    'leadID' => 1,
                    'businessID' => 'updated lead'
                ]
            );
    }

    public function testDeleteSingleLead(): void
    {
        $user = $this->getTestUser();
        $headers = $this->createAuthorizationHeaders($user);

        $postData = [
            'leadID' => 1
        ];

        $this->json('DELETE', 'leads/', $postData, $headers)
            ->seeStatusCode(200)
            ->seeJson(
                [
                    'success' => 'Lead deleted'
                ]
            );
    }
}
