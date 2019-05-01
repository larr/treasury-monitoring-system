<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cashpullout extends Model
{
    //
    protected $table = 'tr_cash_pull_out';
//    protected $guarded = [];
    protected $dates = ['release_date', 'pull_out_date', 'date_paid'];
    protected  $fillable = ['department', 'amount', 'amount_words', 'purpose', 'approved_by', 'date_approved', 'release_by', 'release_date', 'pull_out_date', 'date_paid', 'amount_paid', 'created_by', 'updated_by', 'deleted_by', 'company_id', 'bu_id'];
}
