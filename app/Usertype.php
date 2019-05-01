<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Usertype extends Model
{
    //
    use SoftDeletes;

    protected $primaryKey = "user_type_id";
    protected $table = "user_type";
    protected $dates = ['deleted_at'];
}
