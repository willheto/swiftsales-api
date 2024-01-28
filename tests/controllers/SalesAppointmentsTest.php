<?php

use Tests\TestCase;
use App\Models\Lead;

class SalesAppointmentsTest extends TestCase
{

    public function testGetAllSalesAppointmentsByUserID(): void
    {
        $user = $this->getTestUser();
        $headers = $this->createAuthorizationHeaders($user);

        $this->json('GET', 'users/' . $user->userID . '/sales-appointments', [], $headers)
            ->seeStatusCode(200);
    }

    public function testCreateSalesAppointment(): void
    {
        $user = $this->getTestUser();
        $headers = $this->createAuthorizationHeaders($user);

        $lead = Lead::create(
            [
                'userID' => $user->userID,
                'companyName' => 'Test company',
            ]
        );

        $postData = [
            'userID' => $user->userID,
            'leadID' => $lead->leadID,
            'notes' => 'Test notes',
            'isCustomerAllowedToShareFiles' => true,
        ];

        $this->json('POST', 'sales-appointments/', $postData, $headers)
            ->seeStatusCode(201)
            ->seeJsonStructure([
                'salesAppointment'
            ]);
    }

    public function testGetSingleSalesAppointment(): void
    {
        $user = $this->getTestUser();
        $headers = $this->createAuthorizationHeaders($user);

        $this->json('GET', 'users/' . $user->userID . '/sales-appointments/1', [], $headers)
            ->seeStatusCode(200)
            ->seeJsonStructure([
                'salesAppointment'
            ]);
    }

    public function testGetPublicSalesAppointments(): void
    {
        $this->json('GET', 'sales-appointments/1')
            ->seeStatusCode(200);
    }

    public function testGetMissingPublicSalesAppointments(): void
    {
        $this->json('GET', 'sales-appointments/999')
            ->seeStatusCode(404);
    }

    public function testUpdateSalesAppointment(): void
    {
        $user = $this->getTestUser();
        $headers = $this->createAuthorizationHeaders($user);

        $postData = [
            'salesAppointmentID' => 1,
            'notes' => 'Test notes',
            'isCustomerAllowedToShareFiles' => true,
        ];

        $this->json('PATCH', 'sales-appointments/', $postData, $headers)
            ->seeStatusCode(200)
            ->seeJsonStructure([
                'salesAppointment'
            ]);
    }


    public function testRenewMeetingUrl(): void
    {
        $user = $this->getTestUser();
        $headers = $this->createAuthorizationHeaders($user);

        $this->json('POST', 'sales-appointments/' . 1 . '/renew-meeting-url', [], $headers)
            ->seeStatusCode(200)
            ->seeJsonStructure([
                'salesAppointment'
            ]);
    }

    public function testDeleteSalesAppointment(): void
    {
        $user = $this->getTestUser();
        $headers = $this->createAuthorizationHeaders($user);

        $postData = [
            'salesAppointmentID' => 1,
        ];

        $this->json('DELETE', 'sales-appointments/', $postData, $headers)
            ->seeStatusCode(200);
    }
}
