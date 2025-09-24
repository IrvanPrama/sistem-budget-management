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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
