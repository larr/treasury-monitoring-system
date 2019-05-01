<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    //
    protected $connection = 'ccmsql';
    protected $table = 'currency';
    protected $primaryKey = 'currency_id';
    protected $hidden = ['created_at', 'updated_at'];
}
