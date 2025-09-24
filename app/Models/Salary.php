<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Salary extends Model
{
    use HasFactory, Notifiable;
    protected $table = 'salarys';
    protected $fillable = [
        'transaction_date',
        'employee_name',
        'project_name',
        'client',
        'position',
        'salary',
        'addon',
        'status',
    ];

    protected static function booted()
    {
        static::created(function ($salary) {
            \App\Models\Budget::create([
                'transaction_date' => $salary->transaction_date,
                'project_name'     => $salary->project_name,
                'client'           => $salary->project->client ?? '-', // kalau ada relasi Project
                'expense_name'     => 'Salary ' . $salary->employee_name,
                'estimate'         => $salary->addon,
                'expenses'         => $salary->addon,
            ]);
        });
    }

    public function project()
    {
        return $this->belongsTo(\App\Models\Project::class, 'project_name', 'project_name');
    }
}
