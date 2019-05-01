<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Useraccess extends Model
{
    use SoftDeletes;
    //
    protected $table = 'tr_user_access';
    protected $primaryKey = 'id';
    protected $fillable = ['bank_id', 'company_code', 'bunit_code', 'dept_code', 'section_code', 'sub_section_code', 'cash_bu_id', 'company_code_2', 'bcode', 'dcode', 'scode', 'sscode'];
    public $timestamps = false;

    public function bupis() {
        return $this->belongsTo('App\Businessunitpis', 'bcode', 'bcode');
    }

    public function companypis() {
        return $this->belongsTo('App\Companypis', 'company_code_2', 'ccode');
    }

    public function departmentpis() {
        return $this->belongsTo('App\Departmentpis', 'dcode', 'dcode');
    }

    public function sectionpis() {
        return $this->belongsTo('App\Sectionpis', 'scode', 'scode');
    }

    public function subsectionpis() {
        return $this->belongsTo('App\Subsectionpis', 'sscode', 'sscode');
    }

    public function banks() {
        return $this->belongsTo('App\Bankaccount', 'bank_id', 'id');
    }

    public function cash_bu() {
        return $this->belongsTo('App\Cashbu', 'cash_bu_id', 'id');
    }

    public function getCompanyPIS() {
        $company = Companypis::where('company_code', $this->company_code)
            ->first();

        return ($company)?$company->company:'';
    }

    public function getBUPIS() {
        $bu = Businessunitpis::where('company_code', $this->company_code)
            ->where('bunit_code', $this->bunit_code)
            ->first();

        return ($bu)?$bu->business_unit:'';
    }

    public function getDepartmentPIS() {

//        return Denominationcs::whereIn('company_code', [(int)$this->company_code])
//            ->whereIn('bunit_code', [(int)$this->bunit_code])
//            ->whereIn('dept_code', [(int)$this->dept_code])
//            ->whereIn('section_code', [(int)$this->section_code])
//            ->whereIn('sub_sec_code', [(int)$this->sub_section_code])
//            ->first();

        $dept = Departmentpis::where('company_code', $this->company_code)
            ->where('bunit_code', $this->bunit_code)
            ->where('dept_code', $this->dept_code)
            ->first();

        return ($dept)?$dept->dept_name:'';

//        return ::where('id', $this->cash_bu_id)->first();
    }

    public function getSectionPIS() {
        $section = Sectionpis::where('company_code', $this->company_code)
            ->where('bunit_code', $this->bunit_code)
            ->where('dept_code', $this->dept_code)
            ->where('section_code', $this->section_code)
            ->first();

        return ($section)?$section->section_name:'';
    }

    public function getSubSectionPIS() {
        $sub_section = Subsectionpis::where('company_code', $this->company_code)
            ->where('bunit_code', $this->bunit_code)
            ->where('dept_code', $this->dept_code)
            ->where('section_code', $this->section_code)
            ->where('sub_section_code', $this->sub_section_code)
            ->first();

        return ($sub_section)?$sub_section->sub_section_name:'';
    }


}
