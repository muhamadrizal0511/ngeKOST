<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kost extends Model
{
    protected $table = 'kost';

    protected $fillable = [
        'owner_id',
        'nama_kost',
        'alamat',
        'harga',
        'kategori',
        'deskripsi',
        'foto',
        'latitude',
        'longitude',
    ];

    protected $appends = ['foto_url'];

    public function getFotoUrlAttribute()
    {
        return $this->foto
            ? url('storage/'.$this->foto)
            : null;
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}