<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MsChatRoom;
use App\Models\TrMessages;
use App\Events\MessageSent;
use Carbon\Carbon;




class ChatController extends Controller
{
    // Menampilkan daftar room chat user
    public function index(Request $request)
    {
        $user_id = session('id');
        $role = session('role');

        if ($role == 2) { // Jika Pelajar
            $chatRooms = MsChatRoom::where('student_id', $user_id)->get();
        } else { // Jika Tutor
            $chatRooms = MsChatRoom::where('tutor_id', $user_id)->get();
        }

        foreach ($chatRooms as $room) {
            $room->lastMessage = TrMessages::where('room_id', $room->id)
                ->latest('created_at')
                ->first();
        
            if ($room->lastMessage) {
                $room->lastMessage->created_at = Carbon::parse($room->lastMessage->created_at);
            }
        
            // Hitung jumlah pesan yang belum dibaca
            $room->newMessagesCount = TrMessages::where('room_id', $room->id)
                ->where('sender_id', '!=', $user_id)
                ->where('isRead', 0)
                ->count();
        }
        

        return view('mainpage.chat.index', compact('chatRooms'));
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
        return redirect()->route('chat.room', ['room_id' => $chatRoom->id]);
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
    
        TrMessages::where('room_id', $room_id)
        ->where('sender_id', '!=', $user_id)
        ->where('isRead', 0)
        ->update(['isRead' => 1]);

        $messages = TrMessages::where('room_id', $room_id)->orderBy('created_at')->get();

    
        return view('mainpage.chat.room', compact('chatRoom', 'messages'));
    }
    
    // Mengirim pesan
    public function sendMessage(Request $request, $room_id)
{
    $user_id = session('id');
    $chatRoom = MsChatRoom::findOrFail($room_id);

    if ($user_id != $chatRoom->student_id && $user_id != $chatRoom->tutor_id) {
        abort(403, 'Unauthorized');
    }

    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('chat_images', 'public');
    }

    $filePath = null;
    if ($request->hasFile('attachment')) { // Menggunakan key yang benar
        $filePath = $request->file('attachment')->store('chat_files', 'public');
    }

    if (!$request->filled('message') && !$imagePath && !$filePath) {
        return redirect()->back()->with('error', 'Pesan tidak boleh kosong!');
    }

    // Membuat pesan baru
    $message = TrMessages::create([
        'room_id' => $room_id,
        'sender_id' => $user_id,
        'message' => $request->message ?? '',
        'image' => $imagePath,
        'file' => $filePath
    ]);

    $message->save();
    $message->refresh(); // pastikan created_at dan relasi 'sender' terisi
    $this->sendMessageToPusher($message);


    $chatRoom->update(['last_activity' => now()]);

    // Mendispatch event setelah pesan terkirim
    event(new MessageSent($message->id));

    return redirect()->back();
}

    

    
    
}
