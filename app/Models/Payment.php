<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'booking_id',
        'jumlah_bayar',
        'bukti_pembayaran',
        'status'
    ];

    protected $appends = ['bukti_url'];

    public function getBuktiUrlAttribute()
    {
        return asset('storage/'.$this->bukti_pembayaran);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}