<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;


class Project extends Model
{
    use HasFactory, Notifiable;
    protected $fillable = [
        'project_id',
        'project_name',
        'client',
        'start_time',
        'end_time',
        'coordinator',
        'status',
    ];
}
