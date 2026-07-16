<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Tracking extends Model
{
    use HasFactory;

    protected $table = 'trackings';

    protected $fillable = [
        'antrian_id',
        'dinas_id',
        'layanan_id',
        'nomor_lacak',
        'nama_pemohon',
        'nomor_hp',
        'status_sekarang',
        'catatan_terakhir',
        'tanggal_selesai_estimasi',
    ];

    protected $casts = [
        'tanggal_selesai_estimasi' => 'datetime',
    ];

    public function antrian(): BelongsTo
    {
        return $this->belongsTo(Antrian::class, 'antrian_id');
    }

    public function dinas(): BelongsTo
    {
        return $this->belongsTo(Dinas::class, 'dinas_id');
    }

    public function layanan(): BelongsTo
    {
        return $this->belongsTo(Layanan::class, 'layanan_id');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(TrackingLog::class, 'tracking_id')->orderBy('created_at', 'desc');
    }

    public function survei(): HasOne
    {
        return $this->hasOne(Survei::class, 'tracking_id');
    }
}
