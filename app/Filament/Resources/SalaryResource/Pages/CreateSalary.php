<?php

namespace App\Filament\Resources\SalaryResource\Pages;

use App\Models\Budget;
use App\Filament\Resources\SalaryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSalary extends CreateRecord
{
    protected static string $resource = SalaryResource::class;

    protected function afterCreate(): void
    {
        $salary = $this->record;

        Budget::create([
            'transaction_date' => $salary->transaction_date,
            'project_name'     => $salary->project_name,
            'client'           => $salary->project->client ?? '-',
            'expense_name'     => 'Gaji - ' . $salary->employee_name,
            'estimate'         => $salary->salary,
            'expenses'         => ($salary->salary ?? 0) + ($salary->addon ?? 0), // salary + addon,
        ]);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
