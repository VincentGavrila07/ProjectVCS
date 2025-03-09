<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomZoomCall extends Model
{
    use HasFactory;

    protected $table = 'roomzoomcall'; // Nama tabel
    protected $fillable = [
        'transaction_id',
        'room_name',
        'meeting_url',
        'start_time',
        'duration',
        'status',
        'host_id',
        'participant_id',
        'zoom_meeting_id',
        'zoom_password',
        'recording_url',
        'notes',
    ];

    // Relasi ke tabel transactions
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    // Relasi ke tabel msuser (host)
    public function host()
    {
        return $this->belongsTo(MsUser::class, 'host_id');
    }

    // Relasi ke tabel msuser (participant)
    public function participant()
    {
        return $this->belongsTo(MsUser::class, 'participant_id');
    }
}