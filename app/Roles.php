<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    protected $table = 'roles';
    
    protected $fillable = ['role_id','display_name','description','created_at'];
}
