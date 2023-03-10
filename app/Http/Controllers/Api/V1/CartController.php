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
        $auth_user_cart_items = Cart::with('items')->where('user_id', $auth_user)->get();

        return $auth_user_cart_items;
    }
}
