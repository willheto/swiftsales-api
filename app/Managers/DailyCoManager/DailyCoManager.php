<?php

namespace App\Managers\DailyCoManager;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class DailyCoManager
{

    public function createMeetingUrl(string $timeStart, string $timeEnd): array
    {
        $token = env('DAILY_CO_API_KEY');
        $nbf = strtotime($timeStart);
        $exp = strtotime($timeEnd);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
        ])
            ->post('https://api.daily.co/v1/rooms', [
                'properties' => [
                    'nbf' => $nbf,
                    'exp' => $exp,
                ],
            ]);

        $meeting = $response->json();

        if (isset($meeting['error'])) {
            throw new Exception($meeting['info']);
        }

        return $meeting;
    }

    public function updateMeetingTime(string $meetingUrl, string $timeStart, string $timeEnd): array
    {

        $token = env('DAILY_CO_API_KEY');
        $nbf = strtotime($timeStart);
        $exp = strtotime($timeEnd);

        //parse meeting id from the meeting url
        $meetingID = explode('/', $meetingUrl)[3];

        Log::error('https://api.daily.co/v1/rooms/' . $meetingID);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
        ])
            ->post('https://api.daily.co/v1/rooms/' . $meetingID, [
                'properties' => [
                    'nbf' => $nbf,
                    'exp' => $exp,
                ],
            ]);

        $meeting = $response->json();

        if (isset($meeting['error'])) {
            throw new Exception($meeting['info']);
        }

        return $meeting;
    }
}
