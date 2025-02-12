<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsUser extends Model
{
    use HasFactory;

    protected $table = 'msuser'; 
    protected $fillable = ['username', 'password', 'email', 'role','TeacherId']; 

    protected $primaryKey = 'id'; 

    // Konfigurasi timestamp
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $timestamps = true; 
}
