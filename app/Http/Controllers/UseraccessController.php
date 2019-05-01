<?php

namespace App\Http\Controllers;

use App\Bankaccount;
use App\Bubankaccess;
use App\Businessunitpis;
use App\Cashbu;
use App\Company;
use App\Companypis;
use App\Denominationcs;
use App\Departmentpis;
use App\Sectionpis;
use App\Subsectionpis;
use App\User;
use App\Useraccess;
use function foo\func;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UseraccessController extends Controller
{
    //
    public function index(Request $request) {

        $bankid = $request->bankid;

        if ($request->ajax()) {

            $access = Useraccess::select('id', 'bunit_code', 'company_code', 'dept_code', 'section_code', 'sub_section_code', 'cash_bu_id', 'deleted_at', 'bcode', 'dcode', 'scode', 'sscode')
                ->with(['cash_bu', 'bupis', 'departmentpis', 'sectionpis', 'subsectionpis'])
                ->withTrashed()
                ->where('bank_id', $bankid)
                ->orderBy('id', 'DESC')
                ->get();

            $data = ['data'=>[]];

            foreach ($access as $a) {

                $bu = $a->bupis?$a->bupis->business_unit:'';
                $department = $a->departmentpis?$a->departmentpis->dept_name:'';
                $section = $a->sectionpis?$a->sectionpis->section_name:'';
                $sub_section = $a->subsectionpis?$a->subsectionpis->sub_section_name:'';

                $action = '<button data-url="'.route('section-access.destroy',$a->id).'" class="btn btn-danger btn-sm delete"><i class="fa fa-times"></i></button>';

                if ($a->trashed()) {
                    $action = '<button data-url="'.route('section-access.update', [$a->id]).'" class="btn btn-success btn-sm restore"><i class="fa fa-recycle"></i></button>';
                }

                $data['data'][] = [
                    '[ '.$bu.' <span class="badge badge-primary">bu</span>][ '.$department.' <span class="badge badge-primary">de</span>][ '.$section.' <span class="badge badge-primary">se</span>][ '.$sub_section.' <span class="badge badge-primary">su</span>]',
                    '['.$a->company_code.']['.$a->bunit_code.']['.$a->dept_code.']['.$a->section_code.']['.$a->sub_section_code.']',
                    $action,
                    ($a->cash_bu)?$a->cash_bu->description:''
                ];
            }

            return response()->json($data);
        }

        return view('admin.useraccess.index', compact('bankid'));
    }

    private function checkCode($arr) {
        $max = count($arr)-1;
        for ($i=$max; $i >= 0; $i--) {

            if ($arr[$i] > 0) {
                return $i;
            }
        }
    }

    public function store(Request $request) {
        $data = $request->validate([
            'company'=>'required|not_in:0',
            'bu'=>'required|not_in:0',
            'cashbu'=>'required|not_in:0',
        ]);

        $arr = [
            $request->company,
            $request->bu,
            $request->dept,
            $request->section,
            $request->ssection
        ];

        $index = $this->checkCode($arr);

        $ccode = null;
        $bcode = null;
        $dcode = null;
        $scode = null;
        $sscode = null;

        switch ($index) {
            case(4):
                $sscode = '0'.$request->company.'0'.$request->bu.'0'.$request->dept.'0'.$request->section.'0'.$request->ssection;
            case(3):
                $scode = '0'.$request->company.'0'.$request->bu.'0'.$request->dept.'0'.$request->section;
            case(2):
                $dcode = '0'.$request->company.'0'.$request->bu.'0'.$request->dept;
            case(1):
                $bcode = '0'.$request->company.'0'.$request->bu;
            default:
                $ccode = '0'.$request->company;
        }

        $create = Useraccess::updateOrCreate(
            [
                'bank_id' => $request->bankid,
                'company_code' => $request->company,
                'bunit_code' => $request->bu,
                'dept_code' => $request->dept,
                'section_code' => $request->section,
                'sub_section_code' => $request->ssection,
            ]
            ,[
                'cash_bu_id' => $request->cashbu,
                'bank_id' => $request->bankid,
                'company_code' => $request->company,
                'bunit_code' => $request->bu,
                'dept_code' => $request->dept,
                'section_code' => $request->section,
                'sub_section_code' => $request->ssection,
                'company_code_2' => $ccode,
                'bcode' => $bcode,
                'dcode' => $dcode,
                'scode' => $scode,
                'sscode' => $sscode,
        ]);

        return 'success';
    }

    public function destroy(Request $request, $id) {
        $delete = Useraccess::findOrFail($id)
            ->delete();
        return $id;
    }

    public function update(Request $request, $id) {
        Useraccess::withTrashed()
            ->where('id', $id)
            ->restore();
        return 'success';
    }

    public function useraccessbanks(Request $request) {

        if ($request->ajax()) {
            $data = [];
            $banks = Bubankaccess::pluck('bank_id');
            $bankaccounts = Bankaccount::select('id', 'bank', 'accountno', 'accountname', 'buid')
                ->with('businessunit')
                ->whereIn('id', $banks)
                ->get();
            foreach ($bankaccounts as $bankaccount) {
                $data['data'][] = [
                    $bankaccount->bank.'-'.$bankaccount->accountno.'-'.$bankaccount->accountname,
                    $bankaccount->businessunit->bname,
                    '<a href="'.route('section-access.index',['bankid'=>$bankaccount->id]).'" data-id="'.$bankaccount->id.'" class="btn btn-primary btn-sm">Manage sections</a> <a href="'.route('category-access.index',['bankid'=>$bankaccount->id]).'" class="btn btn-primary btn-sm">Manage categories</a>'
                ];
            }

            return response()->json($data);
        }

        return view('admin.useraccess.banks');
    }

    public function useraccesscompany(Request $request) {
        $company = Companypis::all();
        $cash_bu = Cashbu::all();

        $cash_bu_view = view('admin.useraccess.select.cashbu', compact('cash_bu'))->render();

        $company_view = view('admin.useraccess.select.company', compact('company'))->render();
        return response()->json([
            'cash_bu_view' => $cash_bu_view,
            'company_view' => $company_view
        ]);
    }

    public function useraccessbu(Request $request) {
        $company_code = (int) $request->company_code;

        $bu = Businessunitpis::where('company_code', $company_code)
            ->get();
        return view('admin.useraccess.select.bu', compact('bu'));
    }

    public function useraccessdept(Request $request) {
        $company_code = (int) $request->company_code;
        $bunit_code = (int) $request->bunit_code;
//        $dept_code = (int) $request->dept_code;
        $dept = Departmentpis::where('company_code', $company_code)
            ->where('bunit_code', $bunit_code)
//            ->where('section_code', $dept_code)
            ->get();

        return view('admin.useraccess.select.dept', compact('dept'));
    }

    public function useraccesssec(Request $request) {
        $company_code = (int) $request->company_code;
        $bunit_code = (int) $request->bunit_code;
        $dept_code = (int) $request->dept_code;
        $sec = Sectionpis::where('company_code', $company_code)
            ->where('bunit_code', $bunit_code)
            ->where('dept_code', $dept_code)
            ->get();
        return view('admin.useraccess.select.section', compact('sec'));
    }

    public function useraccesssub(Request $request) {
        $company_code = (int) $request->company_code;
        $bunit_code = (int) $request->bunit_code;
        $dept_code = (int) $request->dept_code;
        $section_code = (int) $request->section_code;
        $sub = Subsectionpis::where('company_code', $company_code)
            ->where('bunit_code', $bunit_code)
            ->where('dept_code', $dept_code)
            ->where('section_code', $section_code)
            ->get();
        return view('admin.useraccess.select.subsection', compact('sub'));
    }

}
