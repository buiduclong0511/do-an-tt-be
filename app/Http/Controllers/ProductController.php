<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response([
            'message' => 'Get data success.',
            'data' => Product::all()
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
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|max:191',
            'description' => 'required|max:1000',
            'price' => 'required|numeric',
        ]);

        $product = null;

        if ($request->image) {
            $path = 'images/' . time() . '-' . $request->image->getClientOriginalName();
            $request->image->move(public_path('images'), $path);

            $product = Product::create(array_merge(['image' => $path], $fields));
        } else {
            $product = Product::create($fields);
        }

        return response([
            'message' => 'Product was created.',
            'data' => $product
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::with('category')->where('id', $id)->get()->first();

        if (!$product) {
            return response([
                'message' => 'Product not found.'
            ], 404);
        }

        return response([
            'message' => 'Get data success.',
            'data' => $product
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
        $product = Product::findOrFail($id);

        if ($request->image) {
            $path = 'images/' . time() . '-' . $request->image->getClientOriginalName();
            $product->update(array_merge($request->all(), [
                'image' => $path
            ]));
        } else {
            $product->update($request->all());
        }

        return response([
            'message' => 'Product was updated.',
            'data' => $product
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
        Product::destroy($id);

        return response([
            'message' => 'Product was deleted.'
        ]);
    }
}
