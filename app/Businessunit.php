<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Businessunit extends Model
{
    //
    use SoftDeletes;

    protected $primaryKey = "unitid";
    protected $table = "businessunit";

    public function company()
    {
        return $this->belongsTo('App\Company','company_code','company_code');
    }

    public function bankaccess() {
        return $this->belongsToMany('App\Bankaccount', 'tr_bu_bank','buid', 'bank_id');
    }

}
