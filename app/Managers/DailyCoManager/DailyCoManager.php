<?php

namespace App\Managers\DailyCoManager;

use Illuminate\Support\Facades\Http;

class DailyCoManager
{

    public static function createMeetingUrl()
    {
        $token = env('DAILY_CO_API_KEY');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
        ])
            ->post('https://api.daily.co/v1/rooms');

        $data = $response->json();

        return $data;
    }
}
