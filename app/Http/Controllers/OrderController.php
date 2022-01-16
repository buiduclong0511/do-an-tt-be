<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index(Request $request) {
        $q = $request->q;

        return response([
            'message' => 'get data success.',
            'data' => Order::with(['user', 'product'])
                ->where('receiver', 'like', '%'.$q.'%')
                ->orWhereHas('product', function ($query) use($q) {
                    $query->where('name', 'like', '%'.$q.'%');
                })
                ->get()
        ]);
    }

    public function getOrderUser() {
        return response([
            'message' => 'Get data success.',
            'data' => Order::with('product')->where('user_id', auth()->user()->id)->get()
        ]);
    }

    public function createOrder(Request $request) {
        $fields = $request->validate([
            'receiver' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'product_id' => 'required|exists:products,id',
            'number' => 'required',
            'price' => 'required'
        ]);


        Order::create(array_merge($fields, ['status' => 0, 'user_id' => auth()->user()->id]));


        return response([
            'message' => 'Create order success.',
            'data' => Order::with('product')->where('user_id', auth()->user()->id)->get()
        ]);

    }

    public function changeStatus(Request $request, $id) {
        $fields = $request->validate([
            'status' => 'required|in:0,1,2'
        ]);

        $order = Order::findOrFail($id);

        $order->update(['status' => $fields['status']]);

        return response([
            'message' => 'Order status was changed.',
            'data' => $order
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
