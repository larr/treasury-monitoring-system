<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Depositslip extends Model
{
    //
    use SoftDeletes;
    protected $table = 'tr_deposit_slip';
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected $fillable = ['ds_number', 'amount', 'cash_logbook_id', 'sales_date', 'created_by', 'updated_by', 'deleted_by', 'trans_code'];

    public function cashlogbook()
    {
        return $this->belongsTo('App\Cashlogbook','id','cash_logbook_id');
    }
}
