<?php

namespace App\Http\Controllers;

use App\Models\Category;
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
                    // <a type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modal-block-normal"><i
                    // class="fa fa-fw fa-plus me-1"></i>Add Product</a>
                    $action .= '<a href="javascript:void(0)" class="btn btn-primary btn-circle btn-sm editProduct" onClick="editCatFunc({{ $category->id }})" data-toggle="tooltip" title="Show">Edit</a>';
                    $action .= '<a class="btn btn-danger btn-circle btn-sm m-l-10 ml-1 mr-1" data-toggle="tooltip" onClick="deleteCatFunc({{ $category->id }})" title="Delete">Delete</a>';
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
}