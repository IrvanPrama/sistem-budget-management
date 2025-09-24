<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BudgetResource\Pages;
use App\Filament\Resources\BudgetResource\RelationManagers;
use App\Models\Budget;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Barryvdh\DomPDF\Facade\Pdf;
//1. Install package barryvdh/laravel-dompdf: composer require barryvdh/laravel-dompdf

class BudgetResource extends Resource
{
    protected static ?string $model = Budget::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('project_name')
                    ->label('Project')
                    ->options(\App\Models\Project::pluck('project_name', 'project_name'))
                    ->searchable()
                    ->required()
                    ->reactive() // penting biar bisa trigger update
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $project = \App\Models\Project::where('project_name', $state)->first();
                            if ($project) {
                                $set('client', $project->client); // otomatis isi field client
                            }
                        }
                    }),

                Forms\Components\TextInput::make('client')
                    ->required()
                    ->readonly()
                    ->maxLength(255),
                 Forms\Components\DatePicker::make('transaction_date')
                    ->label('Tgl Transaksi')
                    ->required(),
                Forms\Components\TextInput::make('expense_name')
                    ->label('Nama Pengeluaran')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('estimate')
                    ->label('Estimasi Pengeluaran')
                    ->required()
                    ->numeric()
                    ->prefix('Rp ')
                    ->maxLength(12),
                Forms\Components\TextInput::make('expenses')
                    ->label('Realisasi Pengeluaran')
                    ->numeric()
                    ->prefix('Rp ')
                    ->maxLength(12),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('transaction_date')
                    ->label('Tgl Transaksi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('project_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('client')
                    ->searchable(),
                Tables\Columns\TextColumn::make('expense_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('estimate')
                    ->label('Estimasi Pengeluaran')
                    ->searchable()
                    ->numeric()
                    ->money('IDR'),
                Tables\Columns\TextColumn::make('expenses')
                    ->searchable()
                    ->numeric()
                    ->money('IDR'), //menambah awalan mata uang
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                    Tables\Filters\SelectFilter::make('project_name')
                        ->label('Project')
                        ->options(\App\Models\Project::pluck('project_name', 'project_name'))
                        ->searchable(),

                    Tables\Filters\SelectFilter::make('client')
                        ->label('Client')
                        ->options(\App\Models\Project::pluck('client', 'client'))
                        ->searchable(),
                ])

            
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('addSalary')
                    ->label('Add Salary')
                    ->icon('heroicon-o-plus')
                    ->url(fn () => \App\Filament\Resources\SalaryResource::getUrl('create')), // langsung ke halaman create Salary
                Tables\Actions\Action::make('exportPdf')
                    ->label('Export PDF')
                    ->icon('heroicon-o-document')
                    ->action(function ($livewire) {
                        // Ambil query tabel sesuai filter
                        $budgets = $livewire->getFilteredTableQuery()->get();

                        $pdf = Pdf::loadView('exports.budgets', [
                            'budgets' => $budgets,
                        ]);

                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            'budgets.pdf'
                        );
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBudgets::route('/'),
            'create' => Pages\CreateBudget::route('/create'),
            'edit' => Pages\EditBudget::route('/{record}/edit'),
        ];
    }
}
