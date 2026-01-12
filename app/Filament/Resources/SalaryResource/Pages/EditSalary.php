<?php

namespace App\Filament\Resources\SalaryResource\Pages;

use App\Filament\Resources\SalaryResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditSalary extends EditRecord
{
    protected static string $resource = SalaryResource::class;

    public function mount($record): void
    {
        // Jika bukan role 0, langsung redirect ke /admin
        if (auth()->user()->role === 2) {
            Notification::make()
            ->title('Anda tidak memiliki akses ke halaman Hasil.')
            ->danger()
            ->send();

            $this->redirect('/admin/salaries');
        }

        parent::mount($record);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
