<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //
    public function index(Request $request)
    {
        if (request()->ajax()) {
            return datatables()->of(Category::select('id', 'category_name', 'category_details'))

                ->addColumn('action', function (Category $category) {
                    $action  = '';
                    $action .= '<a href="javascript:void(0)" id="' . $category->id . '" class="btn btn-primary mr-2 editCategory" >Edit</a>';
                    $action .= '<a href="javascript:void(0)" id="' . $category->id . '" data-id="' . $category->id . '" class="btn btn-danger mr-2 deleteCategory" >Delete</a>';
                    return $action;
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('category.category');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $categoryID = $request->id;
        $request->validate([
            'category_name' => 'required',
            'category_details' => 'required'
        ]);

        $category = Category::updateOrCreate(
            [
                'id' => $categoryID
            ],
            [
                'category_name' => $request->category_name,
                'category_details' => $request->category_details
            ]
        );

        return Response()->json($category);
    }

    public function edit(Request $request)
    {
        $category_id = Category::where('id', $request->id)->first();
        return Response()->json($category_id);
    }

    public function delete(Request $request)
    {
        $category_dltId = Category::where('id', $request->id)->first();
        $category_dltId->delete();
        return Response()->json($category_dltId);
    }
}
