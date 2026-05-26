<?php

namespace App\Http\Controllers\Front;

use App\Helpers\Api;
use App\Helpers\Cart;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function add(Request $request)
    {
        if (Cart::add($request->id, $request->qty ?? 1)) {
            // return Api::setResponse('qty', Cart::qty());
            return response()->json([
            'msg'=>'Product Added to Cart',
            'msg_type'=>'success',
            'qty'=> Cart::qty()
        ]);
        } else {
            return Api::setError('Item out of stock!');
            return response()->json([
            'msg'=>'Product Added to Cart',
            'msg_type'=>'danger',
        ]);
        }
    }

    public function remove(Request $request)
    {
        Cart::remove($request->id);
        return Api::setResponse('cart', Session::get('cart'));
    }

    public function increment(Request $request)
    {
        if (Cart::increase($request->id)) {
            return Api::setResponse('cart', Session::get('cart'));
        } else {
            return Api::setError('Item out of stock!');
        }
    }

    public function decrement(Request $request)
    {
        Cart::decrease($request->id);
        return Api::setResponse('cart', Session::get('cart'));
    }

    public function clear()
    {
        Session::forget('cart');
        Session::forget('coupen');
        Session::forget('check');
        return redirect()->back();
    }
}
