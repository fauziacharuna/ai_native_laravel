<?php

namespace App\Filament\Widgets;

use App\Models\Antrian;
use App\Models\Survei;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MppStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // 1. Total Antrian Menunggu (Hari ini)
        $waitingQueues = Antrian::whereDate('tanggal', Carbon::today())
            ->whereIn('status', ['waiting', 'calling'])
            ->count();

        // 2. Rata-rata Waktu Tunggu (Menit) - Hari ini / Keseluruhan
        // Selisih antara waktu_panggil dan waktu_ambil
        $avgWaitTime = Antrian::whereNotNull('waktu_panggil')
            ->select(DB::raw('AVG(EXTRACT(EPOCH FROM (waktu_panggil - waktu_ambil)) / 60) as avg_wait'))
            ->first()
            ->avg_wait ?? 0;
            
        $avgWaitTimeText = number_format($avgWaitTime, 1) . ' Menit';

        // 3. Rasio Kepatuhan SLA (%)
        // Selisih waktu_selesai - waktu_mulai_layanan <= layanans.estimasi_waktu
        $completedAntrian = Antrian::where('status', 'completed')
            ->whereNotNull('waktu_mulai_layanan')
            ->whereNotNull('waktu_selesai')
            ->join('layanans', 'antrians.layanan_id', '=', 'layanans.id')
            ->select(
                DB::raw('COUNT(antrians.id) as total'),
                DB::raw('SUM(CASE WHEN (EXTRACT(EPOCH FROM (antrians.waktu_selesai - antrians.waktu_mulai_layanan)) / 60) <= layanans.estimasi_waktu THEN 1 ELSE 0 END) as compliant')
            )
            ->first();

        $slaRatio = 100;
        if ($completedAntrian && $completedAntrian->total > 0) {
            $slaRatio = ($completedAntrian->compliant / $completedAntrian->total) * 100;
        }
        $slaText = number_format($slaRatio, 1) . '%';

        // 4. Rata-rata Nilai Kepuasan SKM
        $avgRating = Survei::avg('skor_kepuasan') ?? 0;
        $avgRatingText = number_format($avgRating, 2) . ' / 5.00';

        return [
            Stat::make('Antrian Menunggu (Hari Ini)', $waitingQueues)
                ->description('Jumlah antrian yang belum dilayani')
                ->descriptionIcon('heroicon-m-clock')
                ->color($waitingQueues > 10 ? 'danger' : 'success'),
            Stat::make('Rata-rata Waktu Tunggu', $avgWaitTimeText)
                ->description('Durasi tiket diambil s/d dipanggil')
                ->descriptionIcon('heroicon-m-bolt')
                ->color($avgWaitTime > 30 ? 'warning' : 'success'),
            Stat::make('Kepatuhan SLA Layanan', $slaText)
                ->description('Layanan selesai sesuai target waktu')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color($slaRatio >= 85 ? 'success' : 'danger'),
            Stat::make('Indeks Kepuasan (SKM)', $avgRatingText)
                ->description('Rata-rata skor penilaian pemohon')
                ->descriptionIcon('heroicon-m-face-smile')
                ->color($avgRating >= 4 ? 'success' : 'warning'),
        ];
    }
}
