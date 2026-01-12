<?php

namespace App\Filament\Widgets;

use App\Models\Salary;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class ProyekAktif extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        $admin = (auth()->user()->role == 0);

        if (!$admin) {
            return $table
                ->query(
                    Salary::query()->where('employee_id', auth()->user()->id)
                )
                ->columns([
                    Tables\Columns\TextColumn::make('project_id')
                        ->searchable(),
                    Tables\Columns\TextColumn::make('project_name')
                        ->searchable(),
                    Tables\Columns\TextColumn::make('client')
                        ->searchable(),
                    Tables\Columns\TextColumn::make('start_time')
                        ->date()
                        ->sortable(),
                    Tables\Columns\TextColumn::make('end_time')
                        ->date()
                        ->sortable(),
                    Tables\Columns\TextColumn::make('coordinator')
                        ->searchable(),
                    Tables\Columns\TextColumn::make('status')
                        ->searchable(),
                    Tables\Columns\TextColumn::make('created_at')
                        ->dateTime()
                        ->sortable()
                        ->toggleable(isToggledHiddenByDefault: true),
                    Tables\Columns\TextColumn::make('updated_at')
                        ->dateTime()
                        ->sortable()
                        ->toggleable(isToggledHiddenByDefault: true),
                ]);
        } else {
            return $table
                ->query(
                    Project::query()
                )
                ->columns([
                    Tables\Columns\TextColumn::make('project_id')
                        ->searchable(),
                    Tables\Columns\TextColumn::make('project_name')
                        ->searchable(),
                    Tables\Columns\TextColumn::make('client')
                        ->searchable(),
                    Tables\Columns\TextColumn::make('start_time')
                        ->date()
                        ->sortable(),
                    Tables\Columns\TextColumn::make('end_time')
                        ->date()
                        ->sortable(),
                    Tables\Columns\TextColumn::make('coordinator')
                        ->searchable(),
                    Tables\Columns\TextColumn::make('status')
                        ->searchable(),
                    Tables\Columns\TextColumn::make('created_at')
                        ->dateTime()
                        ->sortable()
                        ->toggleable(isToggledHiddenByDefault: true),
                    Tables\Columns\TextColumn::make('updated_at')
                        ->dateTime()
                        ->sortable()
                        ->toggleable(isToggledHiddenByDefault: true),
                ]);
        }
    }
}
