<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Survei extends Model
{
    use HasFactory;

    protected $table = 'surveis';

    protected $fillable = [
        'tracking_id',
        'antrian_id',
        'dinas_id',
        'layanan_id',
        'skor_kepuasan',
        'ulasan',
    ];

    protected $casts = [
        'skor_kepuasan' => 'integer',
    ];

    public function tracking(): BelongsTo
    {
        return $this->belongsTo(Tracking::class, 'tracking_id');
    }

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
}
