<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Departmentpis extends Model
{
    //
    protected $connection = 'pissql';
    protected $primaryKey = 'dcode';
    protected $table = 'locate_department';
}
