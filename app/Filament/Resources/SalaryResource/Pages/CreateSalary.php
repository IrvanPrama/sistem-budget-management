<?php

namespace App\Filament\Resources\SalaryResource\Pages;

use App\Filament\Resources\SalaryResource;
use App\Models\Budget;
use App\Models\Profitloss;
use Filament\Resources\Pages\CreateRecord;

class CreateSalary extends CreateRecord
{
    protected static string $resource = SalaryResource::class;

    protected function afterCreate(): void
    {
        $salary = $this->record;

        Budget::create([
            'transaction_date' => $salary->transaction_date,
            'project_name' => $salary->project_name,
            'client' => $salary->project->client ?? '-',
            'expense_name' => 'Gaji - '.$salary->employee_name,
            'estimate' => $salary->salary ?? 0,
            'expenses' => ($salary->salary ?? 0) + ($salary->addon ?? 0), // salary + addon,
        ]);

        Profitloss::create([
            'date' => $salary->transaction_date,
            'transaction_name' => $salary->project_name.'-'.$salary->employee_name,
            'transaction_type' => 'Gaji Karyawan',
            'income' => 0,
            'expense' => ($salary->salary ?? 0) + ($salary->addon ?? 0), // salary + addon,
        ]);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
