<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Departmentccm extends Model
{
    //
    public $connection = 'ccmsql';
    protected $table = 'department';
    protected $primaryKey = 'department_id';
    public $timestamps = false;
}
