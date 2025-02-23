<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsChatRoom extends Model
{
    use HasFactory;

    protected $table = 'mschatroom'; // Sesuai nama tabel di database
    protected $fillable = ['student_id', 'tutor_id', 'last_activity'];
    public $timestamps = false;
    // Relasi ke MsUser (Student)
    public function student()
    {
        return $this->belongsTo(MsUser::class, 'student_id');
    }

    // Relasi ke MsUser (Tutor)
    public function tutor()
    {
        return $this->belongsTo(MsUser::class, 'tutor_id');
    }

    // Relasi ke TrMessages
    public function messages()
    {
        return $this->hasMany(TrMessages::class, 'room_id');
    }
}
