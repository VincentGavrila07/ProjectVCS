<?php

namespace App\Models;

use App\Models\MsSubject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MsUser extends Model
{
    use HasFactory;

    protected $table = 'msuser'; 
    protected $fillable = ['username', 'password', 'email', 'role','TeacherId','subjectClass']; 

    protected $primaryKey = 'id'; 

    // Konfigurasi timestamp
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $timestamps = true; 

    // MsUser Model
    public function subjectClass()
    {
        return $this->belongsTo(MsSubject::class, 'subjectClass', 'id');
    }
    
    public function chatRoomsAsStudent()
    {
        return $this->hasMany(MsChatRoom::class, 'student_id');
    }

    public function chatRoomsAsTutor()
    {
        return $this->hasMany(MsChatRoom::class, 'tutor_id');
    }

    public function messages()
    {
        return $this->hasMany(TrMessages::class, 'sender_id');
    }

    
}
