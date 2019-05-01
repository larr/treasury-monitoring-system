<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subsectionpis extends Model
{
    //
    protected $connection = 'pissql';
    protected $table = 'locate_sub_section';
    protected $primaryKey = 'sscode';
}
