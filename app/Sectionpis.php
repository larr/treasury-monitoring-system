<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sectionpis extends Model
{
    //
    protected $connection = 'pissql';
    protected $table = 'locate_section';
    protected $primaryKey = 'scode';

    public function businessunit()
    {
        return $this->belongsTo('App\Businessunitpis','scode','scode');
    }
}
