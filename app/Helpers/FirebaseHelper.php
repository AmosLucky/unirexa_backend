<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class FirebaseHelper
{
    public static function sendNotification($deviceToken, $title, $body, $data = [])
    {
        $serverKey = env('FIREBASE_SERVER_KEY');

        $payload = [
            'to' => $deviceToken,
            'notification' => [
                'title' => $title,
                'body' => $body,
                'sound' => 'default',
            ],
            'data' => $data,
        ];

        $response = Http::withHeaders([
            'Authorization' => 'key=' . $serverKey,
            'Content-Type' => 'application/json',
        ])->post('https://fcm.googleapis.com/fcm/send', $payload);

        return $response->json();
    }
}
