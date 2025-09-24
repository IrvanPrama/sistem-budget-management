<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SalaryResource\Pages;
use App\Filament\Resources\SalaryResource\RelationManagers;
use App\Models\Salary;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SalaryResource extends Resource
{
    protected static ?string $model = Salary::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Keuangan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('transaction_date')
                    ->required(),
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
                    ->readonly()
                    ->required(),
                Forms\Components\Select::make('employee_name')
                    ->options(\App\Models\Employee::pluck('employee_name', 'employee_name'))
                    ->searchable()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $employee = \App\Models\Employee::where('employee_name', $state)->first();
                            if ($employee) {
                                $set('position', $employee->position);
                                $set('salary', $employee->salary);
                            }
                        }
                    }),
                Forms\Components\TextInput::make('position')
                    ->required(),
                Forms\Components\TextInput::make('salary')
                    ->label('Gaji Pokok')
                    ->required()
                    ->numeric()
                    ->readonly()
                    ->prefix('Rp ')
                    ->formatStateUsing(fn ($state) => $state !== null ? number_format($state, 0, ',', '.'): 0 
                ),
                 Forms\Components\TextInput::make('addon')
                    ->label('Bonus / Tunjangan')
                    ->required()
                    ->numeric()
                    ->prefix('Rp ')
                    ->formatStateUsing(fn ($state) => $state !== null ? number_format($state, 0, ',', '.'): 0 
                    )// menambah pemisah ribuan
                    ->maxLength(255),
                Forms\Components\Select::make('status')
                    ->required()
                    ->options([
                        0 => 'belum dibayar',
                        1 => 'dibayar',
                    ])
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('transaction_date')
                    ->label('Tanggal')
                    ->searchable(),
                Tables\Columns\TextColumn::make('project_name')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('client')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('employee_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('position')
                    ->searchable(),
                Tables\Columns\TextColumn::make('salary')
                    ->label('Gaji Pokok')
                    ->money('IDR')
                    ->searchable(),
                Tables\Columns\TextColumn::make('addon')
                    ->label('Bonus / Tunjangan')
                    ->money('IDR')
                    ->searchable(),
                Tables\Columns\IconColumn::make('status')
                    ->label('Status Dibayar')
                    ->boolean(),
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
                //
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
            'index' => Pages\ListSalarys::route('/'),
            'create' => Pages\CreateSalary::route('/create'),
            'edit' => Pages\EditSalary::route('/{record}/edit'),
        ];
    }
}
