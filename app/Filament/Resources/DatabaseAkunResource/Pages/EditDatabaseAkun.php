<?php

namespace App\Filament\Resources\DatabaseAkunResource\Pages;

use App\Filament\Resources\DatabaseAkunResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDatabaseAkun extends EditRecord
{
    protected static string $resource = DatabaseAkunResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
