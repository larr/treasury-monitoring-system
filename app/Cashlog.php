<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cashlog extends Model
{
    //
    protected $table ="tr_cash_log";
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function businessunit() {
        return $this->hasMany('App\Cashlogbu', 'cashlogid');
    }
}
