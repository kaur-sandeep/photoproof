<?php

namespace App\Services;

use Google\Auth\Credentials\ServiceAccountCredentials;

class FcmService
{
    public function sendToToken(
        string $token,
        string $title,
        string $body,
        array $data = []
    ) {
        $serviceAccountPath = storage_path(
            'app/firebase/service-account.json'
        );

        if (!file_exists($serviceAccountPath)) {
            throw new \Exception(
                'Firebase service account JSON file not found.'
            );
        }

        $credentials = new ServiceAccountCredentials(
            'https://www.googleapis.com/auth/firebase.messaging',
            $serviceAccountPath
        );

        $tokenData = $credentials->fetchAuthToken();

        if (empty($tokenData['access_token'])) {
            throw new \Exception(
                'Unable to generate Firebase access token.'
            );
        }

        $accessToken = $tokenData['access_token'];

        $projectId = config('services.firebase.project_id');

        if (empty($projectId)) {
            throw new \Exception(
                'FIREBASE_PROJECT_ID is not configured.'
            );
        }

        $url = "https://fcm.googleapis.com/v1/projects/"
            . $projectId
            . "/messages:send";

        $payload = [
            'message' => [
                'token' => $token,
                  // Android
                    'android' => [
                        'notification' => [
                            'sound' => 'default',
                        ],
                    ],

                    // iPhone / iOS
                    'apns' => [
                        'payload' => [
                            'aps' => [
                                'sound' => 'default',
                            ],
                        ],
                    ],

                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],

                'data' => array_map(
                    'strval',
                    $data
                ),
            ],
        ];

        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_POST => true,

            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: application/json',
            ],

            CURLOPT_RETURNTRANSFER => true,

            CURLOPT_POSTFIELDS => json_encode($payload),

            CURLOPT_TIMEOUT => 30,
        ]);

        $response = curl_exec($ch);

        if ($response === false) {
            $error = curl_error($ch);

            curl_close($ch);

            throw new \Exception(
                'FCM cURL error: ' . $error
            );
        }

        $httpCode = curl_getinfo(
            $ch,
            CURLINFO_HTTP_CODE
        );

        curl_close($ch);

        return [
            'status' => $httpCode >= 200 && $httpCode < 300,
            'http_code' => $httpCode,
            'response' => json_decode($response, true),
        ];
    }
}