<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cashcategory extends Model
{
    //
    use SoftDeletes;
    protected $table = 'tr_cash_category';

}