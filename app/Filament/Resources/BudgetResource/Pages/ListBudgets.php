<?php

namespace App\Filament\Resources\BudgetResource\Pages;

use App\Filament\Resources\BudgetResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListBudgets extends ListRecords
{
    // Redirect ke dasboard jika bukan role 1 dan 3
    public function mount(): void
    {
        // kalau user bukan role 1 atau 3 → redirect ke /admin + tampil notifikasi
        // if (! in_array(auth()->user()->role, [1, 3])) {
        if (auth()->user()->role == 2) {
            Notification::make()
                ->title('Anda tidak memiliki akses ke halaman itu.')
                ->danger()
                ->send();

            $this->redirect('/admin');
            // atau $this->redirectRoute('filament.admin.pages.dashboard');
        }
    }

    protected static string $resource = BudgetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
