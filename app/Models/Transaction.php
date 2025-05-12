<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'tutor_id',
        'amount',
        'status',
        'rating'
    ];

    public function subject()
    {
        return $this->belongsTo(MsSubject::class, 'subject_id');
    }

    public function roomZoomCall()
    {
        return $this->hasOne(RoomZoomCall::class, 'transaction_id', 'id');
    }


}