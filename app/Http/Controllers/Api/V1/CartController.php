<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function getCartItems()
    {
        $auth_user = auth()->user()->id;

        if (Cart::where([['user_id', $auth_user] , ['status','Pending']])->exists()) {

            $auth_user_cart_items = Cart::with('items')->where([['user_id', $auth_user] , ['status','Pending']])->get();
            return response(['success'=> true,'cart'=> $auth_user_cart_items]);

        }else{
            return response(['success'=> false,'message'=> 'You Don\'t have items in your cart']);
        }
        //return $auth_user_cart_items;
    }
}
