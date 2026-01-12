<?php

namespace App\Filament\Widgets;

use App\Models\Budget;
use App\Models\Employee;
use App\Models\Project;
use App\Models\Salary;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $monthlyTotal = Budget::query()
          ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
          ->sum('budget');

        if (auth()->user()->role == 0) {
            return [
                Stat::make('project',
                    Project::all()->where('status', '1')
                    ->count()
                )
                ->label('Proyek'),

                Stat::make('employee',
                    Employee::all()->where('status', '1')
                    ->count()
                )
                ->label('Pegawai'),
                Stat::make('income', $monthlyTotal
                )
                ->label('Pendapatan'),
            ];
        } else {
            return [
                Stat::make('project',
                    Salary::all()->where('employee_id', auth()->user()->id)->where('status', '1')
                    ->count()
                )
                ->label('Proyek'),
                Stat::make('project',
                    Salary::all()->where('employee_id', auth()->user()->id)->where('status', '1')
                    ->sum('salary')
                )
                ->label('Gajih'),
            ];
        }
    }
}
