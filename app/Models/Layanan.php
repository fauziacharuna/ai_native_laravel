<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Layanan extends Model
{
    use HasFactory;

    protected $table = 'layanans';

    protected $fillable = [
        'dinas_id',
        'nama_layanan',
        'persyaratan',
        'prosedur',
        'estimasi_waktu',
        'tarif',
        'produk',
        'pengaduan',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'tarif' => 'decimal:2',
        'estimasi_waktu' => 'integer',
    ];

    public function dinas(): BelongsTo
    {
        return $this->belongsTo(Dinas::class, 'dinas_id');
    }

    public function antrians(): HasMany
    {
        return $this->hasMany(Antrian::class, 'layanan_id');
    }

    public function trackings(): HasMany
    {
        return $this->hasMany(Tracking::class, 'layanan_id');
    }

    public function surveis(): HasMany
    {
        return $this->hasMany(Survei::class, 'layanan_id');
    }
}
