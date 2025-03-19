<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\MsUser;
use App\Models\RoomZoomCall;
use GuzzleHttp\Client;
use Carbon\Carbon;

class VideoCallController extends Controller
{
    public function index($transactionId)
    {
        $transaction = Transaction::findOrFail($transactionId);
        $userId = session('id');

        if ($transaction->student_id !== $userId && $transaction->tutor_id !== $userId) {
            abort(403, 'Unauthorized');
        }

        $roomZoomCall = RoomZoomCall::where('transaction_id', $transactionId)->first();

        if (!$roomZoomCall) {
            $roomZoomCall = $this->createZoomMeeting($transaction);
        }

        $user = MsUser::find($userId);
        if (!$user) {
            abort(404, 'User tidak ditemukan.');
        }

        return view('videocall', [
            'roomName' => $roomZoomCall->room_name,
            'username' => $user->username,
            'userId' => $userId,
            'otherUserId' => $transaction->student_id === $userId ? $transaction->tutor_id : $transaction->student_id,
            'meetingUrl' => $roomZoomCall->meeting_url,
            'zoomPassword' => $roomZoomCall->zoom_password, // Kirim password meeting
        ]);
    }

    private function createZoomMeeting($transaction)
    {
        $clientId = env('ZOOM_CLIENT_ID');
        $clientSecret = env('ZOOM_CLIENT_SECRET');
        $accountId = env('ZOOM_ACCOUNT_ID');
    
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
    
            // Data untuk membuat meeting
            $meetingData = [
                'topic' => 'Tutor Session',
                'type' => 2, // Scheduled meeting
                'start_time' => Carbon::now()->addMinutes(5)->toIso8601String(), // Mulai dalam 5 menit
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
    
            // Buat meeting
            $meetingResponse = $client->post('https://api.zoom.us/v2/users/me/meetings', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => $meetingData,
            ]);
    
            $meetingInfo = json_decode($meetingResponse->getBody(), true);
    
            // Konversi format start_time ke format MySQL
            $startTimeFormatted = Carbon::parse($meetingInfo['start_time'])->format('Y-m-d H:i:s');
    
            // Simpan informasi meeting ke tabel roomzoomcall
            return RoomZoomCall::create([
                'transaction_id' => $transaction->id,
                'room_name' => $meetingInfo['id'], // Gunakan meeting ID sebagai room_name
                'meeting_url' => $meetingInfo['join_url'], // Simpan URL meeting
                'start_time' => $startTimeFormatted, // Gunakan format yang sudah dikonversi
                'duration' => $meetingInfo['duration'], // Simpan durasi meeting
                'status' => 'scheduled', // Status meeting
                'host_id' => $transaction->tutor_id, // Tutor sebagai host
                'participant_id' => $transaction->student_id, // Pelajar sebagai peserta
                'zoom_meeting_id' => $meetingInfo['id'], // Simpan meeting ID dari Zoom
                'zoom_password' => $meetingInfo['password'] ?? null, // Simpan password meeting (jika ada)
                'notes' => 'Diskusi tentang materi kelas.', // Catatan
                'created_at' => now(), // Timestamp created_at
                'updated_at' => now(), // Timestamp updated_at
            ]);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            // Tangani error
            $response = $e->getResponse();
            $errorBody = json_decode($response->getBody(), true);
            abort(500, 'Gagal membuat meeting: ' . ($errorBody['message'] ?? 'Unknown error'));
        }
    }

    private function fetchRecordingUrl($meetingId, $attempts = 5, $delay = 60)
    {
        $clientId = env('ZOOM_CLIENT_ID');
        $clientSecret = env('ZOOM_CLIENT_SECRET');
        $accountId = env('ZOOM_ACCOUNT_ID');

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

            for ($i = 0; $i < $attempts; $i++) {
                // Ambil data rekaman
                $response = $client->get("https://api.zoom.us/v2/meetings/$meetingId/recordings", [
                    'headers' => [
                        'Authorization' => "Bearer $accessToken",
                        'Content-Type' => 'application/json',
                    ],
                ]);

                $recordingData = json_decode($response->getBody(), true);

                // Ambil URL rekaman pertama (jika ada)
                if (!empty($recordingData['recording_files'])) {
                    $recordingUrl = $recordingData['recording_files'][0]['play_url'];

                    // Simpan ke database
                    RoomZoomCall::where('zoom_meeting_id', $meetingId)->update([
                        'recording_url' => $recordingUrl,
                    ]);

                    return $recordingUrl;
                }

                // Tunggu sebelum mencoba lagi
                sleep($delay);
            }

            return null;
        } catch (\Exception $e) {
            Log::error("Gagal mengambil recording URL: " . $e->getMessage());
            return null;
        }
    }

    
}
