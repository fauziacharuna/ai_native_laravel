<?php

namespace App\Filament\Resources\AntrianResource\Pages;

use App\Filament\Resources\AntrianResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageAntrian extends ManageRecords
{
    protected static string $resource = AntrianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
