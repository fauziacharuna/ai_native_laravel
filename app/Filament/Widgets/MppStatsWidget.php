<?php

namespace App\Filament\Widgets;

use App\Models\Antrian;
use App\Models\Layanan;
use App\Models\Survei;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MppStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    protected ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $today = Carbon::today();
        $todayAntrian = Antrian::query()->whereDate('tanggal', '=', Carbon::today(), 'and')->get();
        $totalToday = $todayAntrian->count();
        $waitingQueues = $todayAntrian->whereIn('status', ['waiting', 'calling'])->count();
        $completedToday = $todayAntrian->where('status', 'completed')->count();

        $avgWaitTime = $todayAntrian
            ->filter(fn (Antrian $antrian) => $antrian->waktu_ambil && $antrian->waktu_panggil)
            ->map(fn (Antrian $antrian) => max(0, $antrian->waktu_ambil->diffInMinutes($antrian->waktu_panggil)))
            ->avg();

        $completedWithTime = $todayAntrian->filter(fn (Antrian $antrian) => $antrian->status === 'completed' && $antrian->waktu_mulai_layanan && $antrian->waktu_selesai && $antrian->layanan);
        $compliantCount = 0;

        foreach ($completedWithTime as $antrian) {
            $durationInMinutes = $antrian->waktu_mulai_layanan->diffInMinutes($antrian->waktu_selesai);

            if ($durationInMinutes <= $antrian->layanan->estimasi_waktu) {
                $compliantCount++;
            }
        }

        $slaRatio = $completedWithTime->isEmpty()
            ? 0
            : ($compliantCount / $completedWithTime->count()) * 100;

        $avgRating = round(Survei::avg('skor_kepuasan') ?? 0, 2);
        $activeServices = Layanan::query()->where('status', '=', true, 'and')->count();

        $yesterdayAntrian = Antrian::query()->whereDate('tanggal', '=', $today->copy()->subDay(), 'and')->get();
        $yesterdayTotal = $yesterdayAntrian->count();
        $yesterdayWaiting = $yesterdayAntrian->whereIn('status', ['waiting', 'calling'])->count();
        $yesterdayCompleted = $yesterdayAntrian->where('status', 'completed')->count();

        $yesterdayAvgWait = $yesterdayAntrian
            ->filter(fn (Antrian $antrian) => $antrian->waktu_ambil && $antrian->waktu_panggil)
            ->map(fn (Antrian $antrian) => max(0, $antrian->waktu_ambil->diffInMinutes($antrian->waktu_panggil)))
            ->avg() ?? 0;

        $yesterdayCompletedWithTime = $yesterdayAntrian->filter(fn (Antrian $antrian) => $antrian->status === 'completed' && $antrian->waktu_mulai_layanan && $antrian->waktu_selesai && $antrian->layanan);
        $yesterdayCompliantCount = 0;

        foreach ($yesterdayCompletedWithTime as $antrian) {
            $durationInMinutes = $antrian->waktu_mulai_layanan->diffInMinutes($antrian->waktu_selesai);

            if ($durationInMinutes <= $antrian->layanan->estimasi_waktu) {
                $yesterdayCompliantCount++;
            }
        }

        $yesterdaySlaRatio = $yesterdayCompletedWithTime->isEmpty()
            ? 0
            : ($yesterdayCompliantCount / $yesterdayCompletedWithTime->count()) * 100;

        $last7Days = [];
        $registrationTrend = [];
        $waitingTrend = [];
        $completedTrend = [];
        $slaTrend = [];
        $waitTrend = [];
        $ratingTrend = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i);
            $dayRows = Antrian::query()->whereDate('tanggal', '=', $date, 'and')->get();

            $last7Days[] = $date;
            $registrationTrend[] = $dayRows->count();
            $waitingTrend[] = $dayRows->whereIn('status', ['waiting', 'calling'])->count();
            $completedTrend[] = $dayRows->where('status', 'completed')->count();

            $dayWithTime = $dayRows->filter(fn (Antrian $antrian) => $antrian->status === 'completed' && $antrian->waktu_mulai_layanan && $antrian->waktu_selesai && $antrian->layanan);
            $dayCompliant = 0;

            foreach ($dayWithTime as $antrian) {
                $durationInMinutes = $antrian->waktu_mulai_layanan->diffInMinutes($antrian->waktu_selesai);

                if ($durationInMinutes <= $antrian->layanan->estimasi_waktu) {
                    $dayCompliant++;
                }
            }

            $slaTrend[] = $dayWithTime->isEmpty() ? 0 : round(($dayCompliant / $dayWithTime->count()) * 100, 1);

            $dayAvgWait = $dayRows
                ->filter(fn (Antrian $antrian) => $antrian->waktu_ambil && $antrian->waktu_panggil)
                ->map(fn (Antrian $antrian) => max(0, $antrian->waktu_ambil->diffInMinutes($antrian->waktu_panggil)))
                ->avg();

            $waitTrend[] = round($dayAvgWait ?? 0, 1);
            $ratingTrend[] = round(Survei::query()->whereDate('created_at', '=', $date, 'and')->avg('skor_kepuasan') ?? 0, 2);
        }

        [$totalDesc, $totalDescIcon] = $this->describeDelta($totalToday, $yesterdayTotal);
        [$waitingDesc, $waitingDescIcon] = $this->describeDelta($waitingQueues, $yesterdayWaiting, true);
        [$completedDesc, $completedDescIcon] = $this->describeDelta($completedToday, $yesterdayCompleted);
        [$slaDesc, $slaDescIcon] = $this->describeDelta($slaRatio, $yesterdaySlaRatio);
        [$waitDesc, $waitDescIcon] = $this->describeDelta($avgWaitTime ?? 0, $yesterdayAvgWait, true);
        [$ratingDesc, $ratingDescIcon] = $this->describeDelta($avgRating, $ratingTrend[5] ?? 0);

        return [
            Stat::make('Antrian Hari Ini', number_format($totalToday))
                ->description($totalDesc)
                ->descriptionIcon($totalDescIcon)
                ->chart($registrationTrend)
                ->color('primary'),
            Stat::make('Menunggu + Dipanggil', number_format($waitingQueues))
                ->description($waitingDesc)
                ->descriptionIcon($waitingDescIcon)
                ->chart($waitingTrend)
                ->color($waitingQueues > 10 ? 'warning' : 'success'),
            Stat::make('Selesai Hari Ini', number_format($completedToday))
                ->description($completedDesc)
                ->descriptionIcon($completedDescIcon)
                ->chart($completedTrend)
                ->color('success'),
            Stat::make('Kepatuhan SLA', number_format($slaRatio, 1) . '%')
                ->description($slaDesc)
                ->descriptionIcon($slaDescIcon)
                ->chart($slaTrend)
                ->color($slaRatio >= 85 ? 'success' : 'danger'),
            Stat::make('Rata-rata Waktu Tunggu', number_format($avgWaitTime ?? 0, 1) . ' Menit')
                ->description($waitDesc)
                ->descriptionIcon($waitDescIcon)
                ->chart($waitTrend)
                ->color($avgWaitTime > 30 ? 'warning' : 'success'),
            Stat::make('Layanan Aktif', number_format($activeServices))
                ->description('Layanan aktif saat ini')
                ->descriptionIcon('heroicon-m-document-text')
                ->chart(array_fill(0, 7, $activeServices))
                ->color('primary'),
            Stat::make('Kepuasan Pelayanan', number_format($avgRating, 2) . ' / 5.00')
                ->description($ratingDesc)
                ->descriptionIcon($ratingDescIcon)
                ->chart($ratingTrend)
                ->color($avgRating >= 4 ? 'success' : 'warning'),
        ];
    }

    private function describeDelta(float|int $current, float|int $previous, bool $lowerIsBetter = false): array
    {
        if ($current === $previous) {
            return ['Stabil dibanding kemarin', 'heroicon-m-minus'];
        }

        $isUp = $current > $previous;
        $isPositive = $lowerIsBetter ? ! $isUp : $isUp;
        $delta = abs($current - $previous);
        $direction = $isUp ? 'Naik' : 'Turun';

        return [
            $direction . ' ' . number_format($delta, 1) . ' vs kemarin',
            $isPositive ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down',
        ];
    }
}
