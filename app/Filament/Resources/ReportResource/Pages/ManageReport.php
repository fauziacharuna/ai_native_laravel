<?php

namespace App\Filament\Resources\ReportResource\Pages;

use App\Filament\Resources\ReportResource;
use App\Models\Antrian;
use App\Models\Survei;
use Carbon\Carbon;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Collection;

class ManageReport extends Page
{
    protected static string $resource = ReportResource::class;

    protected static ?string $title = 'Laporan';

    protected string $view = 'filament.resources.report-resource.pages.manage-report';

    public string $reportType = 'monthly';

    public ?string $startDate = null;

    public ?string $endDate = null;

    public function mount(): void
    {
        $today = now()->format('Y-m-d');
        $this->startDate = $today;
        $this->endDate = $today;
    }

    public function updatedReportType(string $value): void
    {
        if ($value === 'custom') {
            return;
        }

        $today = now()->format('Y-m-d');
        $this->startDate = $today;
        $this->endDate = $today;
    }

    public function getCsvDownloadUrlProperty(): string
    {
        [$startDate, $endDate] = $this->resolvePeriod();

        return route('reports.download', [
            'type' => $this->reportType,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
        ]);
    }

    public function getExcelDownloadUrlProperty(): string
    {
        [$startDate, $endDate] = $this->resolvePeriod();

        return route('reports.download.excel', [
            'type' => $this->reportType,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
        ]);
    }

    public function getSummaryProperty(): array
    {
        [$startDate, $endDate] = $this->resolvePeriod();

        $antrian = Antrian::query()
            ->where('tanggal', '>=', $startDate->copy()->startOfDay(), 'and')
            ->where('tanggal', '<=', $endDate->copy()->endOfDay(), 'and')
            ->with(['dinas:id,nama', 'layanan:id,nama_layanan'])
            ->get();

        $surveiAvg = Survei::query()
            ->where('created_at', '>=', $startDate->copy()->startOfDay(), 'and')
            ->where('created_at', '<=', $endDate->copy()->endOfDay(), 'and')
            ->avg('skor_kepuasan') ?? 0;

        return [
            'periodLabel' => $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y'),
            'total' => $antrian->count(),
            'completed' => $antrian->where('status', 'completed')->count(),
            'pending' => $antrian->whereIn('status', ['waiting', 'calling', 'serving'])->count(),
            'skipped' => $antrian->where('status', 'skipped')->count(),
            'avgRating' => round((float) $surveiAvg, 2),
            'topDinas' => $this->buildTopDinas($antrian),
            'topLayanan' => $this->buildTopLayanan($antrian),
        ];
    }

    private function resolvePeriod(): array
    {
        $safeStart = $this->startDate ? Carbon::parse($this->startDate) : Carbon::today();
        $safeEnd = $this->endDate ? Carbon::parse($this->endDate) : Carbon::today();

        if ($this->reportType === 'daily') {
            $startDate = $safeStart->copy()->startOfDay();
            $endDate = $safeStart->copy()->endOfDay();

            return [$startDate, $endDate];
        }

        if ($this->reportType === 'quarterly') {
            $quarter = (int) ceil($safeStart->month / 3);
            $startDate = $safeStart->copy()->month(($quarter - 1) * 3 + 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfQuarter();

            return [$startDate, $endDate];
        }

        if ($this->reportType === 'custom') {
            if ($safeStart->gt($safeEnd)) {
                [$safeStart, $safeEnd] = [$safeEnd, $safeStart];
            }

            return [$safeStart->copy()->startOfDay(), $safeEnd->copy()->endOfDay()];
        }

        $startDate = $safeStart->copy()->startOfMonth();
        $endDate = $safeStart->copy()->endOfMonth();

        return [$startDate, $endDate];
    }

    private function buildTopDinas(Collection $antrian): array
    {
        return $antrian
            ->groupBy('dinas_id')
            ->map(function (Collection $items): array {
                $name = $items->first()?->dinas?->nama ?? 'Tidak Diketahui';

                return [
                    'name' => $name,
                    'count' => $items->count(),
                ];
            })
            ->sortByDesc('count')
            ->take(5)
            ->values()
            ->all();
    }

    private function buildTopLayanan(Collection $antrian): array
    {
        return $antrian
            ->groupBy('layanan_id')
            ->map(function (Collection $items): array {
                $name = $items->first()?->layanan?->nama_layanan ?? 'Tidak Diketahui';

                return [
                    'name' => $name,
                    'count' => $items->count(),
                ];
            })
            ->sortByDesc('count')
            ->take(5)
            ->values()
            ->all();
    }
}
