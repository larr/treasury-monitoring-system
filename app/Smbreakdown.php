<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Smbreakdown extends Model
{
    //
    protected $table = 'tr_sm_breakdown';
    protected $fillable = ['liq_input_amount', 'pdc_total', 'due_checks_total', 'cash_pullout_total', 'cash_logbook_id'];
    public $timestamps = false;
}
