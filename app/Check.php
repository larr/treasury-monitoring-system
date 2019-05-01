<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Check extends Model
{
    use SoftDeletes;
    //
    public $connection = 'ccmsql';
    protected $table = 'checks';
    protected $dates = ['check_date'];
    public $timestamps = false;


    public function departmentccm()
    {
        return $this->belongsTo('App\Departmentccm','department_from','department_id');
    }
    public function customerccm()
    {
        return $this->belongsTo('App\Customerccm','customer_id','customer_id');
    }

    public function scopeAtpDept($query, $department) {
        return $query->whereIn('department_from',$department);
    }
}
