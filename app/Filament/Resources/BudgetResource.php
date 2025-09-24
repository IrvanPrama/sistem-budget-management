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

    protected static ?string $navigationGroup = 'Keuangan';


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
                ->label('Expenses')
                ->numeric()
                ->reactive()
                ->required()
                ->afterStateUpdated(fn ($state, callable $set, $get) =>
                    $set('profit_loss', ($get('estimate') ?? 0) - ($state ?? 0))
                ),

            Forms\Components\TextInput::make('profit_loss')
                ->label('Profit / Loss')
                ->numeric()
                ->disabled() // supaya user tidak bisa edit manual
                ->dehydrated(true), // tetap tersimpan ke database
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
                    ->numeric()
                    ->money('IDR')
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make()
                            ->label('Total Estimasi')
                            ->money('IDR'),
                    ]),

                Tables\Columns\TextColumn::make('expenses')
                    ->label('Realisasi Pengeluaran')
                    ->numeric()
                    ->money('IDR')
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make()
                            ->label('Total Realisasi')
                            ->money('IDR'),
                    ]),

                Tables\Columns\TextColumn::make('profit_loss')
                    ->label('Profit / Loss')
                    ->money('IDR')
                    ->color(fn ($state) => $state >= 0 ? 'success' : 'danger')
                    ->summarize([
                        Tables\Columns\Summarizers\Summarizer::make()
                            ->label('Total Profit / Loss')
                            ->using(fn ($query) => $query->get()->sum('profit_loss'))
                            ->money('IDR'),
                    ]),

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
                // ... header actions kamu tetap sama
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
