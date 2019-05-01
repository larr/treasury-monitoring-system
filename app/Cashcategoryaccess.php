<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cashcategoryaccess extends Model
{
    use SoftDeletes;
    //
    protected $table = 'tr_cash_category_access';
    protected $fillable = ['bank_id', 'cash_category_id'];

    public function cash_category() {
        return $this->belongsTo('App\Cashcategory', 'cash_category_id','id');
    }

//    public function cash_category() {
//        return $this->belongsTo('App\Cashcategory', 'id', 'cash_category_id');
//    }
//    public function bank() {
//        $this->hasMany('App\Bankaccount', 'bank_id', 'id');
//    }
}
