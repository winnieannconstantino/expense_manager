<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expenses extends Model
{
    protected $table = 'expenses';
    
    protected $fillable = ['expenses_id','category_id','amount','entry_date','created_at', 'updated_at'];
}
