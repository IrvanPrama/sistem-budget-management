<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SalaryResource\Pages;
use App\Models\Salary;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SalaryResource extends Resource
{
    protected static ?string $model = Salary::class;

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        // Jika user login role = 0 (admin), tampilkan semua
        if (auth()->check() && auth()->user()->role === 0) {
            return $query;
        }

        // Selain role 0 → filter berdasarkan banjar user
        if (auth()->check()) {
            return $query->where('employee_id', auth()->user()->id);
        }

        // Default kalau tidak login (harusnya nggak terjadi di Filament)
        return $query->whereRaw('1=0');
    }

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
                Forms\Components\Select::make('employee_id')
                    ->options(\App\Models\Employee::pluck('employee_name', 'id'))
                    ->searchable()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $employee = \App\Models\Employee::where('id', $state)->first();
                            if ($employee) {
                                $set('employee_name', $employee->employee_name);
                                $set('position', $employee->position);
                                $set('salary', $employee->salary);
                            }
                        }
                    }),
                Forms\Components\TextInput::make('employee_name')
                    ->required(),
                Forms\Components\TextInput::make('position')
                    ->required(),
                Forms\Components\TextInput::make('salary')
                    ->label('Gaji Pokok')
                    ->required()
                    ->numeric()
                    ->readonly()
                    ->prefix('Rp ')
                    ->formatStateUsing(fn ($state) => $state !== null ? number_format($state, 0, ',', '.') : 0
                    ),
                Forms\Components\TextInput::make('addon')
                   ->label('Project Fee')
                   ->required()
                   ->numeric()
                   ->prefix('Rp ')
                   ->formatStateUsing(fn ($state) => $state !== null ? number_format($state, 0, ',', '.') : 0
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
                    ->searchable()
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make()
                            ->label('Gaji Pokok')
                            ->money('IDR'),
                    ]),
                Tables\Columns\TextColumn::make('addon')
                    ->label('Bonus / Tunjangan')
                    ->money('IDR')
                    ->searchable()
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make()
                            ->label('Bonus / Tunjangan')
                            ->money('IDR'),
                    ]),
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
            ])
            ->actions([
                Actions\EditAction::make()
                   ->visible(fn () => auth()->user()->role == 0), // hanya role 0
                Actions\DeleteAction::make()
                    ->visible(fn () => auth()->user()->role == 0),
            ])
            ->headerActions([
                Action::make('exportPdf')
                    ->label('Export Slip Gaji')
                    ->icon('heroicon-o-document')
                    ->action(function ($livewire) {
                        $salary = $livewire->getFilteredTableQuery()->get();

                        $pdf = Pdf::loadView('exports.salary', [
                            'salary' => $salary,
                        ]);

                        return response()->streamDownload(
                            fn () => print ($pdf->output()),
                            'salary.pdf'
                        );
                    }),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
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
