<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrMessages extends Model
{
    use HasFactory;

    protected $table = 'trmessages'; // Sesuai nama tabel di database
    protected $fillable = ['room_id', 'sender_id', 'message','image','file','created_at'];
    public $timestamps = false;
    protected $dates = ['created_at']; 
    protected $casts = [
        'created_at' => 'datetime',
    ];
    
    // Relasi ke MsChatRoom
    public function chatRoom()
    {
        return $this->belongsTo(MsChatRoom::class, 'room_id');
    }
    

    // Relasi ke MsUser (Pengirim pesan)
    public function sender()
    {
        return $this->belongsTo(MsUser::class, 'sender_id');
    }
}

