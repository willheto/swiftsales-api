<?php

namespace App\Managers\DailyCoManager;

use Illuminate\Support\Facades\Http;

class DailyCoManager
{

    public static function createMeetingUrl()
    {
        $token = env('DAILY_CO_API_KEY');
        $expiryInSeconds = time() + 60 * 60; // 1 hour
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
        ])
            ->post('https://api.daily.co/v1/rooms', [
                'properties' => [
                    'exp' => $expiryInSeconds,
                ],
            ]);

        $meeting = $response->json();
        return $meeting;
    }
}
