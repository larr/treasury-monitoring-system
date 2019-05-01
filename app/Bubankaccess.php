<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bubankaccess extends Model
{
    //
    protected $table = 'tr_bu_bank';
    protected $fillable = ['buid', 'bank_id'];
    public $timestamps = false;

    public function bank() {
        return $this->belongsTo('App\Bankaccount', 'bank_id', 'id');
    }
}
