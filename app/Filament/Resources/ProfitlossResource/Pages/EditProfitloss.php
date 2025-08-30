<?php

namespace App\Filament\Resources\ProfitlossResource\Pages;

use App\Filament\Resources\ProfitlossResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProfitloss extends EditRecord
{
    protected static string $resource = ProfitlossResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
