<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function getCartByUserId() {
        $userId = auth()->user()->id;
        $cart = Cart::with('products')->where('user_id', $userId)->get()->first();

        return response([
            'message' => 'Get data success.',
            'data' => $cart
        ]);
    }

    public function addProductToCart(Request $request) {
        $fields = $request->validate([
            'product_id' => 'required|numeric|exists:products,id'
        ]);

        $userId = auth()->user()->id;

        $cart = Cart::where('user_id', $userId)->get()->first();

        if (!$cart) {
            $cart = Cart::create([
                'user_id' => $userId
            ]);
        }

        $cart->products()->attach($fields['product_id']);

        return response([
            'message' => 'Product was added to cart.',
            'data' => Cart::with('products')->where('user_id', $userId)->get()->first()
        ]);
    }

    public function deleteProductFromCart(Request $request) {
        $fields = $request->validate([
            'product_id' => 'required|numeric|exists:products,id'
        ]);

        $userId = auth()->user()->id;

        $cart = Cart::where('user_id', $userId)->get()->first();
        $cart->products()->detach($fields['product_id']);

        return response([
            'message' => 'Product was deleted from cart.',
            'data' => Cart::with('products')->where('user_id', $userId)->get()->first()
        ]);
    }
}
