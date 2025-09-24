<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
protected $fillable = [
    'project_name',
    'client',
    'transaction_date',
    'expense_name',
    'expenses',
    'estimate',
    ];

    public function setExpensesAttribute($value)
{
    $this->attributes['expenses'] = (int) str_replace(['.', ','], '', $value);
}

    public function setEstimateAttribute($value)
{
    $this->attributes['estimate'] = (int) str_replace(['.', ','], '', $value);

}


}