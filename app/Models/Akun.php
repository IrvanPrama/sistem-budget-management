<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Akun extends Model
{
    use HasFactory;
    use Notifiable;

    protected $table = 'akuns';
    protected $fillable = [
        'akun_name',
        'jenis',
        'sub_jenis',
        'date',
        'saldo',
        'akun_code',
    ];
}
