<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrackingLog extends Model
{
    use HasFactory;

    protected $table = 'tracking_logs';

    protected $fillable = [
        'tracking_id',
        'status',
        'petugas_id',
        'catatan',
    ];

    public function tracking(): BelongsTo
    {
        return $this->belongsTo(Tracking::class, 'tracking_id');
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}
