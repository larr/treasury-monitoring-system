<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Check2 extends Model
{
    //
    protected $table = 'tr_checks';

    protected $fillable = [
        'check_id',
        'check_amount',
        'sm_id',
        'deposit_id',
        'trans_code',
        'type',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
