<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
protected $fillable = [
    'project_name',
    'client',
    'project_deskription',
    'expenses',
    'estimate',
    ];
}
