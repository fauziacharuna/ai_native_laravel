<?php

namespace App\Filament\Widgets;

use App\Models\Antrian;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class AntrianStatusChartWidget extends ChartWidget
{
    protected static ?int $sort = 5;
    protected ?string $heading = 'Distribusi Status Antrian';
    public ?string $filter = '30_days';

    protected function getType(): string
    {
        return 'doughnut';
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
        $statuses = [
            'waiting' => 'Menunggu',
            'calling' => 'Dipanggil',
            'completed' => 'Selesai',
        ];

        $counts = [];

        foreach (array_keys($statuses) as $status) {
            $query = Antrian::query()->where('status', '=', $status, 'and');

            if ($startDate !== null) {
                $query->whereDate('tanggal', '>=', $startDate, 'and');
            }

            $counts[$status] = $query->count();
        }

        return [
            'datasets' => [
                [
                    'data' => array_values($counts),
                    'backgroundColor' => [
                        'rgba(147, 197, 253, 0.95)',
                        'rgba(59, 130, 246, 0.9)',
                        'rgba(30, 64, 175, 0.85)',
                    ],
                    'borderColor' => [
                        'rgba(147, 197, 253, 1)',
                        'rgba(59, 130, 246, 1)',
                        'rgba(30, 64, 175, 1)',
                    ],
                ],
            ],
            'labels' => array_values($statuses),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                    'labels' => [
                        'boxWidth' => 12,
                    ],
                ],
            ],
        ];
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
}