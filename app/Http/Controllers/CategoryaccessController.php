<?php

namespace App\Http\Controllers;

use App\Bankaccount;
use App\Cashcategory;
use App\Cashcategoryaccess;
use Illuminate\Http\Request;

class CategoryaccessController extends Controller
{
    //
    public function index(Request $request) {

        $bankid = $request->bankid;
        $bank = Bankaccount::select('bank','accountno', 'accountname')
            ->where('id', $request->bankid)
            ->first();

        if ($request->ajax()) {
            $access = Cashcategoryaccess::select('id', 'cash_category_id', 'deleted_at')
                ->with(['cash_category'=>function($query){
                    $query->select('id','description','deleted_at');
                }])
                ->where('bank_id', $request->bankid)
                ->withTrashed()
                ->get();

            $data = ['data'=>[]];

            foreach ($access as $a) {

                $action = '<button class="btn btn-danger btn-sm delete" href="'.route('category-access.destroy', $a->id).'"><i class="fa fa-times"></i></button>';
                if ($a->trashed()) {
                    $action = '<button class="btn btn-success btn-sm restore" href="'.route('category-access.update', [$a->id]).'"><i class="fa fa-recycle"></i></button>';
                }

                $data['data'][] = [
                    $a->cash_category->description,
                    $action
                ];
            }
            return response()->json($data);
        }

        return view('admin.categoryaccess.index', compact('bankid', 'bank'));
    }

    public function store(Request $request) {
        $data = $request->validate([
            'bankid' => 'required',
            'category_name' => 'required|not_in:0',
        ]);
        $create = Cashcategoryaccess::updateOrCreate([
            'bank_id' => $request->bankid,
            'cash_category_id' => $request->category_name,
        ],[
            'bank_id' => $request->bankid,
            'cash_category_id' => $request->category_name,
        ]);
        return $request;
    }

    public function destroy(Request $request, $id) {
        $delete = Cashcategoryaccess::where('id',$id)
            ->delete();
        return $id;
    }

    public function update(Request $request, $id) {
        Cashcategoryaccess::withTrashed()
            ->where('id', $id)
            ->restore();
        return 'success';
    }

    public function categoryList() {
        $categories = Cashcategory::all();

        return view('admin.categoryaccess.categories', compact('categories'));
    }
}
