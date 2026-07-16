<?php

namespace App\Filament\Widgets;

use App\Models\Antrian;
use App\Models\Dinas;
use Filament\Widgets\ChartWidget;

class AntrianChartWidget extends ChartWidget
{
    protected static ?int $sort = 2;

    public function getHeading(): string
    {
        return 'Volume Antrian per Gerai Dinas';
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $dinasList = Dinas::all();

        $labels = [];
        $data = [];

        foreach ($dinasList as $dinas) {
            $labels[] = $dinas->kode;
            $data[] = Antrian::where('dinas_id', $dinas->id)->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Antrian Terdaftar',
                    'data' => $data,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.6)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }
}