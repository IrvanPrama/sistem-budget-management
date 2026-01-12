<?php

namespace App\Filament\Pages;

use App\Models\Akun;
use Filament\Pages\Page;

class AkunSummary extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.akun-summary';

    // Properti ini otomatis tersedia di blade
    public $akunSummary = [];

    public function mount()
    {
        $this->akunSummary = Akun::select('jenis')
            ->selectRaw('SUM(saldo) as total_saldo')
            ->groupBy('jenis')
            ->orderBy('jenis')
            ->get();
    }
}
