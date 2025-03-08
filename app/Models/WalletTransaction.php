<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $table = 'wallettransactions'; // Karena ga pake migration, kita kasih tahu nama tabelnya

    protected $fillable = ['user_id', 'order_id', 'amount', 'status'];

    public function user()
    {
        return $this->belongsTo(MsUser::class, 'user_id');
    }
}