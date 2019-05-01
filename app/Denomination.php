<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Denomination extends Model
{
    //
    protected $connection = 'cssql';
    protected $table = 'cs_denomination';
    protected $dates = ['sales_date', 'date_shrt'];
    public $timestamps = false;
}
