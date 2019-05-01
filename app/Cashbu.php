<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cashbu extends Model
{
    //
    protected $table='tr_cash_bu';
    protected $primaryKey='id';

    public function bu_access() {
        return $this->hasMany('App\Useraccess', 'cash_bu_id', 'id');
    }
}
