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
    'profit_loss',
    'bukti_transfer',
    ];

    public function setExpensesAttribute($value)
{
    $this->attributes['expenses'] = (int) str_replace(['.', ','], '', $value);
}

    public function setEstimateAttribute($value)
{
    $this->attributes['estimate'] = (int) str_replace(['.', ','], '', $value);

}

public function getProfitLossAttribute(): int
{
    return ($this->estimate ?? 0) - ($this->expenses ?? 0);
}



}