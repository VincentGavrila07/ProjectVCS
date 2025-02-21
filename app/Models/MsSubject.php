<?php

namespace App\Models;

use App\Models\MsUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MsSubject extends Model
{
    use HasFactory;
    protected $table = 'mssubject';
    protected $fillable = ['subjectName']; 

    protected $primaryKey = 'id'; 
    // Relasi dengan MsUser
    public function users()
    {
        return $this->hasMany(MsUser::class, 'subjectClass', 'id');
    }
}
