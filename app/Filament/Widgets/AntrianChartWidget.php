<?php

namespace App\Filament\Widgets;

use App\Models\Antrian;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Widgets\ChartWidget;

class AntrianChartWidget extends ChartWidget
{
    protected static ?int $sort = 2;
    protected ?string $heading = 'Tren Penggunaan MPP';
    public ?string $filter = '7_days';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export-daily')
                ->label('Export Harian')
                ->icon('heroicon-m-arrow-down-tray')
                ->color('primary')
                ->url(route('reports.download', ['type' => 'daily']))
                ->openUrlInNewTab(),
            Action::make('export-monthly')
                ->label('Export Bulanan')
                ->icon('heroicon-m-document-arrow-down')
                ->color('gray')
                ->url(route('reports.download', ['type' => 'monthly']))
                ->openUrlInNewTab(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
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
        $days = $this->getSelectedDays();
        $labels = [];
        $data = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labels[] = $date->translatedFormat('d M');
            $data[] = Antrian::query()->whereDate('tanggal', '=', $date, 'and')->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total',
                    'data' => $data,
                    'borderColor' => 'rgba(30, 64, 175, 1)',
                    'backgroundColor' => 'rgba(30, 64, 175, 0.18)',
                    'tension' => 0.4,
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
        ];
    }

    private function getSelectedDays(): int
    {
        return match ($this->filter) {
            '30_days' => 30,
            '90_days' => 90,
            default => 7,
        };
    }
}