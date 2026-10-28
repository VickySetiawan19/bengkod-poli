<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DaftarPoli extends Model
{
    protected $table = 'daftar_poli';

    protected $fillable = [
        'id_pasien',
        'id_jadwal',
        'keluhan',
        'no_antrian',
        'status_periksa',
    ];

    public function pasien()
    {
        return $this->belongsTo(User::class, 'id_pasien');
    }

    public function jadwalPeriksa()
    {
        return $this->belongsTo(JadwalPeriksa::class, 'id_jadwal');
    }

    public function periksa()
    {
        return $this->hasOne(Periksa::class, 'id_daftar_poli');
    }

    public function periksas()
    {
        return $this->hasMany(Periksa::class, 'id_daftar_poli');
    }

    /**
     * Scope query to only include active or uncompleted registrations.
     */
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->where('status_periksa', '!=', 'selesai')
                ->orWhereDoesntHave('periksa')
                ->orWhereHas('periksa', function ($sub) {
                    $sub->where('status_pembayaran', '!=', 'lunas');
                });
        });
    }
}