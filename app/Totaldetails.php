<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Totaldetails extends Model
{
    //
    use SoftDeletes;
    protected $table='tr_total_details';
    protected $fillable=['pdc_total', 'dated_check_total', 'cash_pullout_total', 'sales_date', 'deposit_date', 'bu_id', 'created_by', 'updated_by'];
}
