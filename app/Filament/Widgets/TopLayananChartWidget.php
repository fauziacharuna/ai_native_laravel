<?php

namespace App\Filament\Widgets;

use App\Models\Antrian;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;

class TopLayananChartWidget extends ChartWidget
{
    protected static ?int $sort = 4;
    protected ?string $heading = 'Layanan Paling Banyak Dikunjungi';
    public ?string $filter = '30_days';

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getFilters(): ?array
    {
        return [
            '7_days' => '7 Hari',
            '30_days' => '30 Hari',
            '90_days' => '90 Hari',
        ];
    }

    protected function getData(): array
    {
        $startDate = $this->getStartDate();

        $query = Antrian::query()
            ->select('layanan_id')
            ->selectRaw('COUNT(*) as total', [])
            ->with('layanan:id,nama_layanan')
            ->whereNotNull('layanan_id')
            ->groupBy('layanan_id');

        if ($startDate !== null) {
            $query->whereDate('tanggal', '>=', $startDate, 'and');
        }

        $topLayanan = $query
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $fullLabels = $topLayanan
            ->map(fn (Antrian $antrian) => $antrian->layanan?->nama_layanan ?? 'Tidak Diketahui')
            ->all();

        return [
            'datasets' => [
                [
                    'label' => 'Kunjungan',
                    'data' => $topLayanan->pluck('total')->all(),
                    'fullLabels' => $fullLabels,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.6)',
                    'borderColor' => 'rgba(30, 64, 175, 1)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => array_map(fn (string $label): string => $this->shortLabel($label), $fullLabels),
        ];
    }

    protected function getOptions(): RawJs
    {
        return RawJs::make(<<<'JS'
            {
                scales: {
                    x: {
                        ticks: {
                            callback: function (value) {
                                const label = this.getLabelForValue(value) || '';
                                const words = String(label).split(' ');
                                const lines = [];
                                let currentLine = '';

                                for (const word of words) {
                                    const testLine = currentLine ? currentLine + ' ' + word : word;

                                    if (testLine.length <= 13) {
                                        currentLine = testLine;
                                        continue;
                                    }

                                    if (currentLine) {
                                        lines.push(currentLine);
                                    }

                                    currentLine = word;
                                }

                                if (currentLine) {
                                    lines.push(currentLine);
                                }

                                return lines.length ? lines : [label];
                            },
                        },
                    },
                },
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        callbacks: {
                            title: function (items) {
                                if (!items.length) {
                                    return '';
                                }

                                const item = items[0];
                                const fullLabels = item.dataset.fullLabels || [];

                                return fullLabels[item.dataIndex] || item.label;
                            },
                        },
                    },
                },
            }
        JS);
    }

    private function getStartDate(): ?Carbon
    {
        $days = match ($this->filter) {
            '7_days' => 7,
            '30_days' => 30,
            '90_days' => 90,
            default => null,
        };

        if ($days === null) {
            return null;
        }

        return Carbon::today()->subDays($days - 1);
    }

    private function shortLabel(string $value): string
    {
        return Str::limit($value, 24);
    }
}