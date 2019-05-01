<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Companypis extends Model
{
    //
    protected $table = 'locate_company';
    protected $primaryKey = 'company_code';
    protected $connection = 'pissql';
}
