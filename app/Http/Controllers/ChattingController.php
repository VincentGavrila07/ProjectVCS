<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MsChatRoom;
use App\Models\TrMessages;
use Pusher\Pusher;
use Carbon\Carbon;

class ChattingController extends Controller
{
    // Menampilkan daftar room chat user
    public function index(Request $request)
    {
        $user_id = session('id');
        $role = session('role');

        // Menentukan query untuk chat rooms berdasarkan role user
        if ($role == 2) { // Jika Pelajar
            $chatRooms = MsChatRoom::where('student_id', $user_id)->get();
        } else { // Jika Tutor
            $chatRooms = MsChatRoom::where('tutor_id', $user_id)->get();
        }

        // Menambahkan pesan terakhir dan menghitung pesan yang belum dibaca untuk setiap room
        foreach ($chatRooms as $room) {
            // Ambil pesan terakhir berdasarkan room_id
            $lastMessage = TrMessages::where('room_id', $room->id)
                ->latest('created_at') // Mengurutkan pesan berdasarkan waktu terbaru
                ->first();

            $room->lastMessage = $lastMessage;

            // Hitung jumlah pesan yang belum dibaca
            $room->newMessagesCount = TrMessages::where('room_id', $room->id)
                ->where('sender_id', '!=', $user_id)
                ->where('isRead', 0)
                ->count();
        }

        // Urutkan chatRooms berdasarkan waktu pesan terakhir (terbaru di atas)
        $chatRooms = $chatRooms->sortByDesc(function($room) {
            return $room->lastMessage ? $room->lastMessage->created_at : Carbon::now();
        });

        return view('mainpage.chatting.index', compact('chatRooms'));
    }

    
    // Membuat atau membuka room chat
    public function createRoom(Request $request, $tutor_id)
    {
        $student_id = session('id');
    
        // Periksa apakah room chat sudah ada
        $chatRoom = MsChatRoom::where('student_id', $student_id)
            ->where('tutor_id', $tutor_id)
            ->first();
    
        // Jika belum ada, buat room baru
        if (!$chatRoom) {
            $chatRoom = MsChatRoom::create([
                'student_id' => $student_id,
                'tutor_id' => $tutor_id,
            ]);
        }
    
        // Redirect ke halaman room chat
        return redirect()->route('chatting.room', ['room_id' => $chatRoom->id]);
    }

    // Menampilkan halaman chat dalam satu room
    public function showRoom($room_id)
    {
        $user_id = session('id');
    
        $chatRoom = MsChatRoom::findOrFail($room_id);
    
        // Pastikan user ada di room ini (baik sebagai student atau tutor)
        if ($user_id != $chatRoom->student_id && $user_id != $chatRoom->tutor_id) {
            abort(403, 'Unauthorized');
        }
    
        // Tandai pesan sebagai sudah dibaca jika sender bukan user yang sedang aktif
        TrMessages::where('room_id', $room_id)
            ->where('sender_id', '!=', $user_id)
            ->where('isRead', 0)
            ->update(['isRead' => 1]);

        // Ambil semua pesan dalam room chat
        $messages = TrMessages::where('room_id', $room_id)->orderBy('created_at')->get();

        return view('mainpage.chatting.room', compact('chatRoom', 'messages'));
    }
    
    // Mengirim pesan
    public function sendMessage(Request $request, $room_id)
    {
        $user_id = session('id');
        $chatRoom = MsChatRoom::findOrFail($room_id);

        // Pastikan user ada di room ini (baik sebagai student atau tutor)
        if ($user_id != $chatRoom->student_id && $user_id != $chatRoom->tutor_id) {
            abort(403, 'Unauthorized');
        }

        // Simpan gambar jika ada
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('chat_images', 'public');
        }

        // Simpan file jika ada
        $filePath = null;
        if ($request->hasFile('attachment')) { // Menggunakan key yang benar
            $filePath = $request->file('attachment')->store('chat_files', 'public');
        }

        // Validasi agar pesan tidak kosong
        if (!$request->filled('message') && !$imagePath && !$filePath) {
            return redirect()->back()->with('error', 'Pesan tidak boleh kosong!');
        }

        $message = TrMessages::create([
            'room_id' => $room_id,
            'sender_id' => $user_id,
            'message' => $request->message ?? '',
            'image' => $imagePath,
            'file' => $filePath
        ]);
        
        $message->refresh();           // ambil ulang data dari DB (pastikan created_at terisi)
        $message->load('sender');      // pastikan relasi sender ikut dimuat
        
        $this->sendMessageToPusher($message);        

        // Update last activity pada room chat
        $chatRoom->update(['last_activity' => now()]);

        // Redirect kembali ke halaman chat
        return redirect()->back();
    }

    // Mengirim pesan ke Pusher untuk update real-time
    private function sendMessageToPusher($message)
    {
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'useTLS' => true
            ]
        );

        // Siapkan data untuk dikirim ke Pusher
        $data = [
            'message' => $message->message,
            'sender_id' => $message->sender_id,
            'created_at' => $message->created_at->format('d M Y, H:i'),
            'sender_image' => $message->sender ? asset('storage/' . $message->sender->image) : null,
            'image' => $message->image ? asset('storage/' . $message->image) : null,
            'file' => $message->file ? asset('storage/' . $message->file) : null,
        ];

        // Trigger Pusher event
        $pusher->trigger('chatroom.' . $message->room_id, 'message_sent', $data);
    }

    public function getUnreadMessagesCount()
    {
        $user_id = session('id');
        $role = session('role');
    
        // Ambil ID semua room milik user
        $roomIds = $role == 2
            ? MsChatRoom::where('student_id', $user_id)->pluck('id')
            : MsChatRoom::where('tutor_id', $user_id)->pluck('id');
    
        // Hitung jumlah pesan yang belum dibaca di room milik user, tapi bukan dari dirinya sendiri
        $unreadMessagesCount = TrMessages::whereIn('room_id', $roomIds)
            ->where('sender_id', '!=', $user_id)
            ->where('isRead', 0)
            ->count();
    
        return response()->json(['unreadMessagesCount' => $unreadMessagesCount]);
    }
    
}
