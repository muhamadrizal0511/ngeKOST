<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'kost_id',
        'tanggal_masuk',
        'durasi_bulan',
        'total_harga',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kost()
    {
        return $this->belongsTo(Kost::class);
    }
    public function payment()
{
    return $this->hasOne(Payment::class);
}
}