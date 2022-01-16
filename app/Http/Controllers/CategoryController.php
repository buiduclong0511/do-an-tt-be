<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $q = $request->q;
        if ($q) {
            $categories = Category::where('name', 'like', '%'.$q.'%')->orWhere('id', $q)->get();
            return response([
                'message' => 'Get categories success.',
                'data' => $categories
            ]);
        }

        return response([
            'message' => 'Get categories success.',
            'data' => Category::all()
        ]);
    }

    public function getCategoryProduct($id) {
        return response([
            'message' => 'Get data success.',
            'data' => Product::where('category_id', $id)->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|max:191|unique:categories,name'
        ]);

        $category = null;

        if ($request->image) {
            $path = 'images/' . time() . '-' . $request->image->getClientOriginalName();

            $request->image->move(public_path('images'), $path);

            $category = Category::create([
                'name' => $fields['name'],
                'image' => $path
            ]);
        } else {
            $category = Category::create([
                'name' => $fields['name']
            ]);
        }

        return response([
            'message' => 'Category was created.',
            'data' => $category
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::findOrFail($id);

        return response([
            'message' => 'Get data success.',
            'data' => $category
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        if ($request->image) {
            $path = 'images/' . time() . '-' . $request->image->getClientOriginalName();
            $category->update(array_merge($request->all(), [
                'image' => $path
            ]));
        } else {
            $category->update($request->all());
        }

        return response([
            'message' => 'Category was updated.',
            'data' => $category
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Category::destroy($id);

        return [
            'message' => 'Category was deleted.'
        ];
    }
}
