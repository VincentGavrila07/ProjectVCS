<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomVideoCall extends Model
{
    use HasFactory;

    protected $table = 'roomvideocall';
    protected $fillable = ['transaction_id', 'room_name'];

    // Relasi ke tabel transactions
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}