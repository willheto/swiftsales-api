<?php

namespace App\Managers\DailyCoManager;

use Illuminate\Database\DBAL\TimestampType;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DailyCoManager
{

    public function createMeetingUrl(int $expiryInHours)
    {
        $token = env('DAILY_CO_API_KEY');
        $expiryInSeconds = time() + 60 * 60 * $expiryInHours;
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
        ])
            ->post('https://api.daily.co/v1/rooms', [
                'properties' => [
                    'exp' => $expiryInSeconds,
                ],
            ]);

        $expiryTime = $this->secondsToMariaDBDate($response->json()['config']['exp']);
        $meeting = $response->json();
        $meeting['expiryTime'] = $expiryTime;
        return $meeting;
    }

    protected function secondsToMariaDBDate($seconds)
    {


        // Create a mariadb timestamp from the seconds
        $mysqlDateFormat = date('Y-m-d H:i:s', $seconds);

        // Construct the SQL query to convert it to a MariaDB date object
        $mariaDBDateObject = $mysqlDateFormat;


        return $mariaDBDateObject;
    }
}
