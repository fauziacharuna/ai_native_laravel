<?php

namespace App\Filament\Widgets;

use App\Models\Antrian;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;

class SlaPerDinasChartWidget extends ChartWidget
{
    protected static ?int $sort = 6;
    protected ?string $heading = 'Kepatuhan SLA per Dinas';
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
            ->with([
                'dinas:id,nama',
                'layanan:id,estimasi_waktu',
            ])
            ->where('status', '=', 'completed', 'and')
            ->whereNotNull('dinas_id')
            ->whereNotNull('waktu_mulai_layanan')
            ->whereNotNull('waktu_selesai');

        if ($startDate !== null) {
            $query->whereDate('tanggal', '>=', $startDate, 'and');
        }

        $rows = $query->get();

        $stats = $rows
            ->groupBy('dinas_id')
            ->map(function ($items) {
                $total = $items->count();

                $compliant = $items
                    ->filter(function (Antrian $antrian): bool {
                        if (! $antrian->layanan || $antrian->layanan->estimasi_waktu === null) {
                            return false;
                        }

                        $duration = $antrian->waktu_mulai_layanan->diffInMinutes($antrian->waktu_selesai);

                        return $duration <= (int) $antrian->layanan->estimasi_waktu;
                    })
                    ->count();

                return [
                    'nama' => $items->first()?->dinas?->nama ?? 'Tidak Diketahui',
                    'ratio' => $total > 0 ? round(($compliant / $total) * 100, 1) : 0,
                    'total' => $total,
                ];
            })
            ->sortByDesc('total')
            ->take(5)
            ->values();

        $fullLabels = $stats->pluck('nama')->all();

        return [
            'datasets' => [
                [
                    'label' => 'SLA',
                    'data' => $stats->pluck('ratio')->all(),
                    'fullLabels' => $fullLabels,
                    'backgroundColor' => 'rgba(37, 99, 235, 0.7)',
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
                            label: function (item) {
                                return 'SLA: ' + item.formattedValue + '%';
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