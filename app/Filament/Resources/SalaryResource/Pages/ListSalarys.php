<?php

namespace App\Filament\Resources\SalaryResource\Pages;

use App\Filament\Resources\SalaryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSalarys extends ListRecords
{
    protected static string $resource = SalaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                         ->visible(fn () => auth()->check() && auth()->user()->role == 0),
        ];
    }
}
