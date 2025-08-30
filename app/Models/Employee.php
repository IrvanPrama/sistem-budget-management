<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
 protected $fillable = [
        'employee_name',
        'nomor_tlp',
        'adress',
        'position',
        'status',
    ];
}
