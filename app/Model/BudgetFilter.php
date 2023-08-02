<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetFilter extends Model
{
    use HasFactory;

    protected $table = "budget_filters";

    protected $fillable = ['f_num','s_num','t_num','fo_num','bg'];
}
