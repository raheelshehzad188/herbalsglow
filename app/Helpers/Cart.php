<?php


namespace App\Helpers;

use App\Models\Admins\Product;
use App\Models\Cart as CartModel;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use DB;

class Cart
{
    public static function add($product_id, $qty){
        $cart = [];
        $product = Product::find($product_id);
        $set = false;
        $index = 0;

        if (Session::has('cart')){
            $cart =  Session::get('cart');
            foreach ($cart['items'] as $key => $item) {
                if ($item ['id'] == $product_id) {
                    $index = $key;
                    $set = true;
                }
            }
            ///Cart Exists But Item is NEW
            if(!$set){
                $cart['amount'] += $qty * $product->discount_price;
                $cart['qty'] += $qty ;
                array_push($cart['items'],['id' => $product_id, 'qty' => $qty]);
            }
            else{
                if($qty < $cart['items'][$index]['qty']){
                    $cart['amount'] -= ($cart['items'][$index]['qty'] - $qty) * $product->discount_price;
                    $cart['qty'] -= $cart['items'][$index]['qty'] - $qty;
                }else if ($qty > $cart['items'][$index]['qty']){
                    $cart['amount'] += ($qty - $cart['items'][$index]['qty']) * $product->discount_price;
                    $cart['qty'] += $qty - $cart['items'][$index]['qty'];
                }
                $cart['items'][$index]['qty'] = $qty;
            }
        }

        else{
            $setting = DB::table('setting')
    ->where('id', '=', '1')
    ->first();
            $cart = [
                'qty' => $qty,
                'amount' => $product->discount_price * $qty,
                'items' => [
                    ['id' => $product_id,
                        'qty' => $qty,
                    ]
                ]
            ];
        }
        Session::put('cart',$cart);
        
        // Save to database with IP address
        self::saveToDatabase($product_id, $qty, $product->discount_price);
        
        return true;
    }
    
    /**
     * Save cart item to database with IP address
     */
    private static function saveToDatabase($product_id, $qty, $price) {
        $ip_address = request()->ip();
        $session_id = Session::getId();
        $user_id = Auth::id();
        $total_amount = $qty * $price;
        
        // Check if cart item already exists for this IP/product
        $query = CartModel::where('ip_address', $ip_address)
            ->where('product_id', $product_id);
        
        if ($user_id) {
            $query->where('user_id', $user_id);
        } elseif ($session_id) {
            $query->where('session_id', $session_id);
        }
        
        $existingCart = $query->first();
        
        if ($existingCart) {
            // Update existing cart item
            $existingCart->update([
                'quantity' => $qty,
                'price' => $price,
                'total_amount' => $total_amount,
                'session_id' => $session_id,
                'user_id' => $user_id,
            ]);
        } else {
            // Create new cart item
            CartModel::create([
                'ip_address' => $ip_address,
                'session_id' => $session_id,
                'user_id' => $user_id,
                'product_id' => $product_id,
                'quantity' => $qty,
                'price' => $price,
                'total_amount' => $total_amount,
            ]);
        }
    }

    public static function increase($productId){
        if(!Session::has('cart')) return false;

        $cart =  Session::get('cart');
        $product = Product::find($productId);
        $index = 0;
            
        foreach ($cart['items'] as $key => $item) {
            if ($item ['id'] == $productId) {
                $index = $key;
            }
        }

        $cart['amount'] += $product->discount_price;
        $cart['qty'] ++;
        $cart['items'][$index]['qty'] ++;
        if ($cart['items'][$index]['qty'] > $product->product_quantity){
            return false;
        }

        Session::put('cart',$cart);
        
        // Update database
        $product = Product::find($productId);
        if ($product) {
            self::saveToDatabase($productId, $cart['items'][$index]['qty'], $product->discount_price);
        }
        
        return true;
    }
    
    public static function decrease($productId){
        if(!Session::has('cart')) return false;

        $cart =  Session::get('cart');
        $product = Product::find($productId);
        $index = 0;
            
        foreach ($cart['items'] as $key => $item) {
            if ($item ['id'] == $productId) {
                $index = $key;
            }
        }

        $cart['amount'] -= $product->discount_price;
        $cart['qty'] --;
        $cart['items'][$index]['qty'] --;
       
        if($cart['items'][$index]['qty'] < 1) unset($cart['items'][$index]);

        if($cart['qty'] < 1){
            Session::forget('cart');
            // Remove from database
            self::removeFromDatabase($productId);
        } else {
            Session::put('cart',$cart);
            // Update database
            $product = Product::find($productId);
            if ($product) {
                self::saveToDatabase($productId, $cart['items'][$index]['qty'], $product->discount_price);
            }
        }
    }
    
    /**
     * Remove cart item from database
     */
    private static function removeFromDatabase($product_id) {
        $ip_address = request()->ip();
        $session_id = Session::getId();
        $user_id = Auth::id();
        
        $query = CartModel::where('ip_address', $ip_address)
            ->where('product_id', $product_id);
        
        if ($user_id) {
            $query->where('user_id', $user_id);
        } elseif ($session_id) {
            $query->where('session_id', $session_id);
        }
        
        $query->delete();
    }

    public static function remove($product_id){
        $cart = Session::get('cart');
        if (!$cart || !isset($cart['items'])) {
            return;
        }
        
        $product = Product::find($product_id);
        if (!$product) {
            return;
        }
        
        $index = null;
        foreach ($cart['items'] as $key => $item) {
            if ($item['id'] == $product_id) {
                $index = $key;
                break;
            }
        }
        
        if ($index !== null) {
            $cart['amount'] -= $product->discount_price * $cart['items'][$index]['qty'];
            $cart['qty'] -= $cart['items'][$index]['qty'];
            unset($cart['items'][$index]);
            
            // Re-index items array to remove gaps
            $cart['items'] = array_values($cart['items']);

            if($cart['qty'] < 1 || empty($cart['items'])){
                Session::forget('cart');
            } else {
                Session::put('cart', $cart);
            }
            
            // Remove from database
            self::removeFromDatabase($product_id);
        }
    }

    public static function update($products){
        $cart = Session::get('cart');
        $totalamount = 0;
        $totalqty = 0;
        foreach($cart['items'] as $key =>  $item){

            $product = Product::find($item ['id']);

            $cart['items'][$key]['qty'] = $products[$item['id']];
            if ($cart['items'][$key]['qty'] > $product->stock){
                return redirect()->back()->with('msg', 'Item(s) out of stock');
            }
            $totalqty += $products[$item['id']];
            $totalamount += $products[$item['id']] * $product->discount_price;
        }
        $cart['amount'] = $totalamount;
        $cart['qty'] = $totalqty;
        Session::put('cart',$cart);
        
        // Update database for all items
        foreach($cart['items'] as $item) {
            $product = Product::find($item['id']);
            if ($product) {
                self::saveToDatabase($item['id'], $item['qty'], $product->discount_price);
            }
        }

        return;
    }

    public static function products(){
        $cart = Session::get('cart');
        $products = [];
        if($cart){
            foreach ($cart['items'] as $item) {
                $product = Product::find($item['id']);
                $product['qty'] = $item['qty'];
                array_push($products,$product );
            }
        }
        
        return $products;
    }

    public static function has($product_id){
        $cart =  Session::get('cart');
        foreach ($cart['items'] as $key => $item) {
            if ($item ['id'] == $product_id) {
                return true;
            }
        }
        return false;
    }

    public static function qty(){
        $cart = Session::get('cart');
        return $cart ? (count($cart['items']) ?? 0) : 0;
    }
    public static function ship(){
        return Session::get('cart')['ship'];
    }
    
    /**
     * Clear cart from both session and database
     */
    public static function clear() {
        $ip_address = request()->ip();
        $session_id = Session::getId();
        $user_id = Auth::id();
        
        // Clear from session
        Session::forget('cart');
        Session::forget('coupen');
        Session::forget('check');
        
        // Clear from database
        $query = CartModel::where('ip_address', $ip_address);
        
        if ($user_id) {
            $query->where('user_id', $user_id);
        } elseif ($session_id) {
            $query->where('session_id', $session_id);
        }
        
        $query->delete();
        
        return true;
    }
}