<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $table = 'wallets'; 
    protected $fillable = ['user_id', 'balance'];

    public function user()
    {
        return $this->belongsTo(MsUser::class, 'user_id');
    }

    public function deposit($amount)
    {
        $this->increment('balance', $amount);
    }

    public function withdraw($amount)
    {
        if ($this->balance >= $amount) {
            $this->decrement('balance', $amount);
            return true;
        }
        return false;
    }
}