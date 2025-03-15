<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Notification;
use App\Models\MsUser;
use App\Models\Wallet;
use App\Models\RoomVideoCall;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\RoomZoomCall;
use GuzzleHttp\Client;


class TutorController extends Controller
{

    public function toggleAvailability(Request $request)
    {
        // Ambil ID tutor dari session
        $tutorId = session('id');

        if (!$tutorId) {
            return response()->json(['success' => false, 'message' => 'User not found'], 403);
        }

        // Temukan user berdasarkan session
        $tutor = MsUser::find($tutorId);

        if (!$tutor) {
            return response()->json(['success' => false, 'message' => 'Tutor not found'], 404);
        }

        // Toggle status isAvailable
        $tutor->isAvailable = !$tutor->isAvailable;
        $tutor->save();

        return response()->json([
            'success' => true,
            'isAvailable' => $tutor->isAvailable
        ]);
    }

    public function setAFK(Request $request)
    {
        // Ambil ID tutor dari session
        $tutorId = session('id');

        if (!$tutorId) {
            return response()->json(['success' => false, 'message' => 'User not found'], 403);
        }

        // Temukan tutor berdasarkan session
        $tutor = MsUser::find($tutorId);

        if (!$tutor) {
            return response()->json(['success' => false, 'message' => 'Tutor not found'], 404);
        }

        // Set status isAvailable ke false
        $tutor->isAvailable = false;
        $tutor->save();

        return response()->json([
            'success' => true,
            'message' => 'Tutor is now AFK'
        ]);
    }


    public function sewaTutor(Request $request)
{
    $studentId = session('id'); // Ambil ID pelajar dari session
    $tutorId = $request->input('tutor_id');
    $amount = MsUser::find($tutorId)->price; // Ambil harga tutor

    DB::beginTransaction();
    try {
        // Buat transaksi
        $transaction = Transaction::create([
            'student_id' => $studentId,
            'tutor_id' => $tutorId,
            'amount' => $amount,
            'status' => 'pending',
        ]);

        // Kirim notifikasi ke tutor
        Notification::create([
            'user_id' => $tutorId,
            'message' => 'Anda sedang disewa oleh pelajar. Silakan konfirmasi dalam 10 detik.',
            'status' => 'unread',
            'transaction_id' => $transaction->id, // Simpan transaction_id
        ]);

        DB::commit();

        return response()->json(['success' => true, 'transaction_id' => $transaction->id]);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }
}

public function checkNotification()
{
    $tutorId = session('id'); // Ambil ID tutor dari session

    if (!$tutorId) {
        return response()->json(['has_notification' => false]);
    }

    // Cek apakah ada notifikasi baru untuk tutor ini
    $notification = Notification::where('user_id', $tutorId)
                                ->where('status', 'unread')
                                ->first();

    if ($notification) {
        // Tandai notifikasi sebagai "dibaca"
        $notification->update(['status' => 'read']);

        return response()->json([
            'has_notification' => true,
            'transaction_id' => $notification->transaction_id, // Jika ada relasi dengan transaksi
        ]);
    }

    return response()->json(['has_notification' => false]);
}

public function confirmRequest(Request $request)
{
    Log::info('Data yang diterima:', $request->all());

    $transactionId = $request->input('transaction_id');

    DB::beginTransaction();
    try {
        // Cari transaksi
        $transaction = Transaction::find($transactionId);

        if (!$transaction) {
            Log::error('Transaksi tidak ditemukan:', ['transaction_id' => $transactionId]);
            return response()->json(['success' => false, 'message' => 'Transaksi tidak ditemukan.']);
        }

        // Pastikan status transaksi masih 'pending'
        if ($transaction->status !== 'pending') {
            Log::error('Status transaksi tidak valid:', ['status' => $transaction->status]);
            return response()->json(['success' => false, 'message' => 'Transaksi sudah diproses.']);
        }

        // Cari wallet pelajar
        $studentWallet = Wallet::where('user_id', $transaction->student_id)->first();

        if (!$studentWallet) {
            Log::error('Wallet pelajar tidak ditemukan:', ['student_id' => $transaction->student_id]);
            return response()->json(['success' => false, 'message' => 'Wallet pelajar tidak ditemukan.']);
        }

        // Pastikan saldo pelajar mencukupi
        if ($studentWallet->balance < $transaction->amount) {
            Log::error('Saldo pelajar tidak mencukupi:', ['balance' => $studentWallet->balance, 'amount' => $transaction->amount]);
            return response()->json(['success' => false, 'message' => 'Saldo pelajar tidak mencukupi.']);
        }

        // Ambil data pelajar dan tutor dari tabel MsUser
        $student = MsUser::find($transaction->student_id);
        $tutor = MsUser::find($transaction->tutor_id);

        if (!$student || !$tutor) {
            Log::error('Data pelajar atau tutor tidak ditemukan:', [
                'student_id' => $transaction->student_id,
                'tutor_id' => $transaction->tutor_id
            ]);
            return response()->json(['success' => false, 'message' => 'Data pelajar atau tutor tidak ditemukan.']);
        }

        // Buat meeting Zoom
        $client = new Client();
        $clientId = env('ZOOM_CLIENT_ID');
        $clientSecret = env('ZOOM_CLIENT_SECRET');
        $accountId = env('ZOOM_ACCOUNT_ID');

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

        // Data untuk membuat meeting
        $meetingData = [
            'topic' => 'Tutor Session',
            'type' => 2, // Scheduled meeting
            'start_time' => now()->addMinutes(5)->format('Y-m-d\TH:i:s\Z'), // Mulai dalam 5 menit
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

        // Buat ruangan video call
        $roomZoomCall = RoomZoomCall::create([
            'transaction_id' => $transactionId,
            'room_name' => $meetingInfo['id'], // Gunakan meeting ID sebagai room_name
            'meeting_url' => $meetingInfo['join_url'], // Simpan URL meeting
            'start_time' => $meetingInfo['start_time'], // Simpan waktu mulai meeting
            'duration' => $meetingInfo['duration'], // Simpan durasi meeting
            'status' => 'scheduled', // Status meeting
            'host_id' => $transaction->tutor_id, // Tutor sebagai host
            'participant_id' => $transaction->student_id, // Pelajar sebagai peserta
            'zoom_meeting_id' => $meetingInfo['id'], // Simpan meeting ID dari Zoom
            'zoom_password' => $meetingInfo['password'] ?? null, // Simpan password meeting (jika ada)
            'notes' => 'Diskusi tentang materi Matematika kelas 10.', // Catatan
        ]);

        // Update status transaksi dan tambahkan roomzoomcall_id
        $transaction->update([
            'status' => 'confirmed',
            'roomzoomcall_id' => $roomZoomCall->id,
        ]);

        // Kurangi saldo pelajar
        $studentWallet->withdraw($transaction->amount);

        DB::commit();

        Log::info('Transaksi berhasil dikonfirmasi:', ['transaction_id' => $transactionId]);

        // Kembalikan URL video call dan username
        $videoCallUrl = route('video.call', ['transaction_id' => $transactionId]);
        return response()->json([
            'success' => true,
            'video_call_url' => $videoCallUrl,
            'student_username' => $student->username, // Kirim username pelajar
            'tutor_username' => $tutor->username,     // Kirim username tutor
        ]);
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error saat mengonfirmasi transaksi:', ['error' => $e->getMessage()]);
        return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }
}

public function rejectRequest(Request $request)
{
    $transactionId = $request->input('transaction_id');

    DB::beginTransaction();
    try {
        // Cari transaksi
        $transaction = Transaction::find($transactionId);

        if (!$transaction) {
            return response()->json(['success' => false, 'message' => 'Transaksi tidak ditemukan.']);
        }

        // Pastikan status transaksi masih 'pending'
        if ($transaction->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Transaksi sudah diproses.']);
        }

        // Update status transaksi
        $transaction->update(['status' => 'canceled']);

        DB::commit();

        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }
}

public function checkTransactionStatus(Request $request)
{
    $transactionId = $request->input('transaction_id');

    // Cari transaksi
    $transaction = Transaction::find($transactionId);

    if (!$transaction) {
        return response()->json(['success' => false, 'message' => 'Transaksi tidak ditemukan.']);
    }

    // Kembalikan status transaksi dan URL video call jika sudah dikonfirmasi
    return response()->json([
        'success' => true,
        'status' => $transaction->status, // 'confirmed' atau 'rejected'
        'video_call_url' => $transaction->status === 'confirmed' 
            ? route('video.call', ['transaction_id' => $transactionId]) 
            : null,
    ]);
}
}