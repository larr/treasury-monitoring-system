<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customerccm extends Model
{
    //
    public $connection = 'ccmsql';
    protected $table = 'customers';
    public $timestamps = false;
    protected $primaryKey = 'customer_id';
}
