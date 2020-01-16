<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expense_Categories extends Model
{
    protected $table = 'expense_categories';
    
    protected $fillable = ['category_id','display_name','description','user_id','created_at', 'updated_at'];
}
