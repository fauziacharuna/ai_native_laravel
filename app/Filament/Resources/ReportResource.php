<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportResource\Pages;
use App\Models\Antrian;
use BackedEnum;
use Filament\Resources\Resource;
use UnitEnum;

class ReportResource extends Resource
{
    protected static ?string $model = Antrian::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static ?string $navigationLabel = 'Laporan';

    protected static ?string $modelLabel = 'Laporan';

    protected static ?string $pluralModelLabel = 'Laporan';

    protected static string | UnitEnum | null $navigationGroup = 'Layanan & Antrian';

    protected static ?int $navigationSort = 20;

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageReport::route('/'),
        ];
    }
}
