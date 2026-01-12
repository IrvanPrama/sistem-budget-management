<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'employee_name',
        'employee_id',
        'nomor_tlp',
        'adress',
        'salary',
        'position',
        'status',
    ];
}
