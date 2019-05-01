<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employeepis extends Model
{
    //
    protected $connection = 'pissql';
    protected $primaryKey = 'record_no';
    protected $table = 'employee3';

}