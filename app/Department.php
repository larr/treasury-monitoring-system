<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    //
    protected $connection = 'pissql';
    protected $table = 'locate_department';
}
