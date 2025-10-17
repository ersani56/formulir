<?php

namespace App\Filament\Resources\ArsipPegResource\Pages;

use App\Filament\Resources\ArsipPegResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditArsipPeg extends EditRecord
{
    protected static string $resource = ArsipPegResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
