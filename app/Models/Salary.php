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
        'date',
        'employee_name',
        'position',
        'addon',
        'status',
    ];
}
