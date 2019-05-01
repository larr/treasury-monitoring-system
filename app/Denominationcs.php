<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Denominationcs extends Model
{
    //
    protected $connection = 'cssql';
    protected $table = 'cebo_cs_denomination';
    protected $dates = ['sales_date', 'date_shrt'];
    public $timestamps = false;
}