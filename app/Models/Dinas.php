<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dinas extends Model
{
    use HasFactory;

    protected $table = 'dinas';

    protected $fillable = [
        'nama',
        'kode',
        'deskripsi',
        'jam_operasional',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function layanans(): HasMany
    {
        return $this->hasMany(Layanan::class, 'dinas_id');
    }

    public function antrians(): HasMany
    {
        return $this->hasMany(Antrian::class, 'dinas_id');
    }

    public function trackings(): HasMany
    {
        return $this->hasMany(Tracking::class, 'dinas_id');
    }

    public function surveis(): HasMany
    {
        return $this->hasMany(Survei::class, 'dinas_id');
    }
}
