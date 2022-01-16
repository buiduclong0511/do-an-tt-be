<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function getOrderUser() {
        return response([
            'message' => 'Get data success.',
            'data' => Order::with('product')->where('user_id', auth()->user()->id)->get()
        ]);
    }

    public function createOrder(Request $request) {
        $fields = $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'number' => 'required',
            'price' => 'required'
        ]);


        Order::create(array_merge($fields, ['status' => 0]));


        return response([
            'message' => 'Create order success.',
            'data' => Order::with('product')->where('user_id', auth()->user()->id)->get()
        ]);

    }

    public function destroy($id) {
        Order::destroy($id);

        return response([
            'message' => 'Order was deleted.',
            'data' => Order::with('product')->where('user_id', auth()->user()->id)->get()
        ]);

    }
}
