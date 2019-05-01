<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Businessunitpis extends Model
{
    //
    protected $connection = 'pissql';
    protected $table = 'locate_business_unit';
    protected $primaryKey = 'bcode';

    public function company()
    {
        return $this->belongsTo('App\Companypis','company_code','company_code');
    }

    public function section() {
        return $this->hasMany('App\Sectionpis', '', '');
    }
}
