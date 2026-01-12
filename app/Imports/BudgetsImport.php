<?php

namespace App\Imports;

use App\Models\Budget;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class BudgetsImport implements ToModel, WithHeadingRow, WithValidation, WithChunkReading, WithBatchInserts, ShouldQueue, SkipsOnFailure, SkipsOnError
{
    use SkipsFailures;
    use SkipsErrors;

    public function model(array $row)
    {
        $expenses = (float) $row['expenses'];
        $budget = (float) $row['budget'];

        return new Budget([
            'project_name' => $row['project_name'],
            'client' => $row['client'],
            'transaction_date' => Carbon::parse($row['transaction_date']),
            'expense_name' => $row['expense_name'],
            'expenses' => $expenses,
            'estimate' => $row['estimate'],
            'budget' => $budget,
            'profit_loss' => $budget - $expenses,
            'bukti_transfer' => null,
        ]);
    }

    public function rules(): array
    {
        return [
            'project_name' => 'required|string',
            'client' => 'required|string',
            'transaction_date' => 'required|date',
            'expense_name' => 'required|string',
            'expenses' => 'required|numeric|min:0',
            'estimate' => 'nullable|numeric|min:0',
            'budget' => 'required|numeric|min:0',
        ];
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function batchSize(): int
    {
        return 500;
    }
}
