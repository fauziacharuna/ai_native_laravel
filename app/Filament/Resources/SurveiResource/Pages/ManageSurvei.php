<?php

namespace App\Filament\Resources\SurveiResource\Pages;

use App\Filament\Resources\SurveiResource;
use Filament\Resources\Pages\ManageRecords;

class ManageSurvei extends ManageRecords
{
    protected static string $resource = SurveiResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
