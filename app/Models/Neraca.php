<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Neraca extends Model
{
    use HasFactory, Notifiable;
    protected $fillable = [
        'date',
        'khas_bank',
        'liability',
        'equity',
        'trade_receivable',
        'total_asset',
        'total_liability',
    ];
}
