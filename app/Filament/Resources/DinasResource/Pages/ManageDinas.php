<?php

namespace App\Filament\Resources\DinasResource\Pages;

use App\Filament\Resources\DinasResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageDinas extends ManageRecords
{
    protected static string $resource = DinasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
