<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsWithdraw extends Model
{
    use HasFactory;

    protected $table = 'mswithdraw'; // Nama tabel di database
    protected $primaryKey = 'id';
    public $timestamps = true; // Jika ada created_at & updated_at

    protected $fillable = [
        'user_id',
        'amount',
        'bank_name',
        'account_number',
        'account_name',
        'status' // Pending, Approved, Rejected
    ];

    // Relasi dengan User
    public function user()
    {
        return $this->belongsTo(MsUser::class);
    }
}
