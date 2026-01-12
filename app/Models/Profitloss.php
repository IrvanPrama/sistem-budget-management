<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profitloss extends Model
{
    protected $fillable = [
        'date',
        'transaction_name',
        'transaction_type',
        'income',
        'expense',
    ];
}
