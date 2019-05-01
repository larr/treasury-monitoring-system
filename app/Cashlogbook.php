<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cashlogbook extends Model
{
    use SoftDeletes;
    //
    protected $table = "tr_cash_logbook";
    protected $guarded = [];
    protected $dates = ['sales_date', 'deposit_date', 'dateT'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by'];
    protected $fillable = [
        'sales_date',
        'deposit_date',
        'ds_no',
        'logbook_desc',
        'amount',
        'tre_amount',
        'ar_from',
        'ar_to',
        'bank_code',
        'status_clerk',
        'status_treasury',
        'status_is_cleared',
        'status_adj',
        'user_input_status',
        'bu_status',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'cash_id',
        'bank_id',
        'currency_id',
        'company',
        'bu_unit',
        'hrms_code',
        'cs_amount',
        'trans_code'
    ];

    /**
     * My code
     */
    public function cur() {
        return $this->belongsTo('App\Currency', 'currency_id', 'currency_id');
    }

    public function del_by() {
        return $this->belongsTo('App\User', 'deleted_by', 'user_id');
    }

    public function cre_by() {
        return $this->belongsTo('App\User', 'created_by', 'user_id');
    }

    public function upd_by() {
        return $this->belongsTo('App\User', 'updated_by', 'user_id');
    }

    public function bank() {
        return $this->belongsTo('App\Bankaccount', 'bank_id', 'id');
    }

    public function bu() {
        return $this->belongsTo('App\Businessunit', 'bu_unit', 'unitid');
    }
    
    public function cashLog()
    {
    	return $this->belongsTo('App\Cashlog','cash_id','id');
    }

    public function ds()
    {
        return $this->hasMany('App\Depositslip','cash_logbook_id','id');
    }
    public function sm_br() {
        return $this->hasOne('App\Smbreakdown', 'cash_logbook_id', 'id');
    }
}
