<?php

namespace App\Filament\Resources\DatabaseAkunResource\Pages;

use App\Filament\Resources\DatabaseAkunResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDatabaseAkuns extends ListRecords
{
    protected static string $resource = DatabaseAkunResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
