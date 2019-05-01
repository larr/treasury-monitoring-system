<?php

namespace App\Http\Controllers;

use App\Cashcategory;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    //
    public function index(Request $request) {

        if ($request->ajax()) {
            $categories = Cashcategory::orderBy('id', 'DESC')->get();
            $args = [];
            foreach ($categories as $category) {
                $args['data'][] = [
                    '<input type="checkbox">',
                    $category->description,
                    '<button class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></button> <button href="'.route('categories.destroy',$category->id).'" class="btn btn-danger btn-sm delete"><i class="fa fa-times"></i></button>'
                ];
            }
            return response()->json($args);
        }

        return view('admin.categories.index', compact('categories'));
    }
    public function store(Request $request) {
        $data = $request->validate([
            'category_name' => 'required'
        ]);

        $create = Cashcategory::insert([
            'description' => strtoupper($request->category_name)
        ]);
        return 'success';
    }
    public function destroy(Request $request, $id) {
        $delete = Cashcategory::findOrFail($id)
            ->delete();
        return $id;
    }
}
