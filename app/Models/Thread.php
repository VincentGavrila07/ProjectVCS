<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use HasFactory;

    protected $table = 'threads';

    protected $fillable = ['title','content','user_id','subject_id'];

    public function user()
    {
        return $this->belongsTo(MsUser::class,'user_id');
    }
    public function subject()
    {
        return $this->belongsTo(MsSubject::class,'subject_id');
    }
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

}
