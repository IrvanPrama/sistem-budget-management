<?php

namespace App\Filament\Resources\BudgetResource\Pages;

use App\Filament\Resources\BudgetResource;
use App\Models\Profitloss;
use Filament\Resources\Pages\CreateRecord;

class CreateBudget extends CreateRecord
{
    protected static string $resource = BudgetResource::class;

    protected function afterCreate(): void
    {
        $budget = $this->record;

        Profitloss::create([
            'date' => $budget->transaction_date,
            'transaction_name' => $budget->expense_name.'-'.$budget->project_name,
            'transaction_type' => 'project expenses',
            'income' => 0,
            'expense' => ($budget->expenses ?? 0), // salary + addon,
        ]);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
