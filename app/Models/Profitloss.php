<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profitloss extends Model
{
    protected $fillable = [
        'date',
        'income',
        'operation_fee',
        'employee_salary',
        'other_fee',
        'total_cost',
        'profit',
    ];
}
