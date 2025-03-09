<?php

require __DIR__ . '/../../vendor/autoload.php';

use GuzzleHttp\Client;
use Carbon\Carbon;

// Ambil Client ID, Client Secret, dan Account ID dari environment variable atau langsung diisi
$clientId = 'xucQ2olQdicJgCy0gb0qw'; // Ganti dengan Client ID Anda
$clientSecret = 'Ra2ogUQod1LImkGAkTbGX6JnQHbGQ8DQ'; // Ganti dengan Client Secret Anda
$accountId = 'AiYBZ2LXTnKnuiq5n1gB5A'; // Ganti dengan Account ID Anda

// Gunakan GuzzleHttp untuk mendapatkan access token
$client = new Client();

try {
    // Dapatkan access token
    $tokenResponse = $client->post('https://zoom.us/oauth/token', [
        'headers' => [
            'Authorization' => 'Basic ' . base64_encode($clientId . ':' . $clientSecret),
        ],
        'form_params' => [
            'grant_type' => 'account_credentials',
            'account_id' => $accountId,
        ],
    ]);

    $tokenData = json_decode($tokenResponse->getBody(), true);
    $accessToken = $tokenData['access_token'];

    // Gunakan access token untuk membuat meeting
    $meetingData = [
        'topic' => 'Tutor Session',
        'type' => 2, // Scheduled meeting
        'start_time' => now()->addMinutes(5)->format('Y-m-d H:i:s'),
        'duration' => 60, // Durasi 1 jam
        'timezone' => 'Asia/Jakarta',
        'settings' => [
            'join_before_host' => true,
            'host_video' => true,
            'participant_video' => true,
            'mute_upon_entry' => false,
            'waiting_room' => false,
        ],
    ];

    $meetingResponse = $client->post('https://api.zoom.us/v2/users/me/meetings', [
        'headers' => [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ],
        'json' => $meetingData,
    ]);

    // Decode respons dari Zoom
    $meetingInfo = json_decode($meetingResponse->getBody(), true);

    // Tampilkan informasi meeting
    echo "Meeting URL: " . $meetingInfo['join_url'] . "\n";
    echo "Meeting ID: " . $meetingInfo['id'] . "\n";
} catch (\GuzzleHttp\Exception\ClientException $e) {
    // Tangani error
    $response = $e->getResponse();
    $errorBody = json_decode($response->getBody(), true);

    // Tampilkan detail error
    echo "Error Details:\n";
    print_r($errorBody); // Tampilkan seluruh respons error untuk debugging
}