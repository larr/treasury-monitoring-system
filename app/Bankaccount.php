<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bankaccount extends Model
{
    //
    protected $table="bankaccount";

//    public function cash_category()
//    {
//        return $this->hasMany('App\Cashcategory', 'id', 'bank_id');
//    }
    public function businessunit()
    {
        return $this->belongsTo('App\Businessunit','buid','unitid');
    }
}
