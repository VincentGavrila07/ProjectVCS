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


class TutorController extends Controller
{
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

        // Buat ruangan video call
        $roomName = 'room-' . uniqid(); // Contoh: room-64f1e2b3c4d5e
        $roomVideoCall = RoomVideoCall::create([
            'transaction_id' => $transactionId,
            'room_name' => $roomName,
        ]);

        // Update status transaksi dan tambahkan roomvideocall_id
        $transaction->update([
            'status' => 'confirmed',
            'roomvideocall_id' => $roomVideoCall->id,
        ]);

        // Kurangi saldo pelajar
        $studentWallet->withdraw($transaction->amount);

        DB::commit();

        Log::info('Transaksi berhasil dikonfirmasi:', ['transaction_id' => $transactionId]);

        // Kembalikan URL video call
        $videoCallUrl = route('video.call', ['transaction_id' => $transactionId]);
        return response()->json(['success' => true, 'video_call_url' => $videoCallUrl]);
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
}