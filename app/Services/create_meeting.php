<?php

require __DIR__ . '/../../vendor/autoload.php';

use GuzzleHttp\Client;
use Carbon\Carbon;

// Ambil Client ID, Client Secret, dan Account ID dari .env
$clientId = getenv('ZOOM_CLIENT_ID');
$clientSecret = getenv('ZOOM_CLIENT_SECRET');
$accountId = getenv('ZOOM_ACCOUNT_ID');

$client = new Client();

try {
    // Dapatkan access token
    $tokenResponse = $client->post('https://zoom.us/oauth/token', [
        'headers' => [
            'Authorization' => 'Basic ' . base64_encode("$clientId:$clientSecret"),
        ],
        'form_params' => [
            'grant_type' => 'account_credentials',
            'account_id' => $accountId,
        ],
    ]);

    $tokenData = json_decode($tokenResponse->getBody(), true);
    $accessToken = $tokenData['access_token'];

    // Buat data meeting
    $meetingData = [
        'topic' => 'Tutor Session',
        'type' => 2, // Scheduled meeting
        'start_time' => Carbon::now()->addMinutes(5)->toIso8601String(),
        'duration' => 65, // Durasi 1 jam 5 menit
        'timezone' => 'Asia/Jakarta',
        'settings' => [
            'join_before_host' => true,
            'host_video' => true,
            'participant_video' => true,
            'mute_upon_entry' => false,
            'waiting_room' => false,
            'auto_recording' => 'cloud', 
        ],
    ];

    $meetingResponse = $client->post('https://api.zoom.us/v2/users/me/meetings', [
        'headers' => [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ],
        'json' => $meetingData,
    ]);

    $meetingInfo = json_decode($meetingResponse->getBody(), true);

    // Tampilkan informasi meeting
    echo "Meeting URL: " . $meetingInfo['join_url'] . "\n";
    echo "Meeting ID: " . $meetingInfo['id'] . "\n";
} catch (\GuzzleHttp\Exception\ClientException $e) {
    $response = $e->getResponse();
    $errorBody = json_decode($response->getBody(), true);
    echo "Error Details:\n";
    print_r($errorBody);
}