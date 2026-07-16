<?php

namespace App\Filament\Resources\TrackingResource\Pages;

use App\Filament\Resources\TrackingResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTracking extends ManageRecords
{
    protected static string $resource = TrackingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
