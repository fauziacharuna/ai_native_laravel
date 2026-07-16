<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Antrian extends Model
{
    use HasFactory;

    protected $table = 'antrians';

    protected $fillable = [
        'dinas_id',
        'layanan_id',
        'nomor_antrian',
        'nomor_urut',
        'tanggal',
        'nama_pemohon',
        'nomor_hp',
        'tipe_pendaftaran',
        'status',
        'loket_nomor',
        'waktu_ambil',
        'waktu_panggil',
        'waktu_mulai_layanan',
        'waktu_selesai',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'waktu_ambil' => 'datetime',
        'waktu_panggil' => 'datetime',
        'waktu_mulai_layanan' => 'datetime',
        'waktu_selesai' => 'datetime',
        'nomor_urut' => 'integer',
    ];

    public function dinas(): BelongsTo
    {
        return $this->belongsTo(Dinas::class, 'dinas_id');
    }

    public function layanan(): BelongsTo
    {
        return $this->belongsTo(Layanan::class, 'layanan_id');
    }

    public function tracking(): HasOne
    {
        return $this->hasOne(Tracking::class, 'antrian_id');
    }

    public function survei(): HasOne
    {
        return $this->hasOne(Survei::class, 'antrian_id');
    }
}
