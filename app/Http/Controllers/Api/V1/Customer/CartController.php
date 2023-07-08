<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function getCartItems()
    {
        if (Cart::where([['user_id', auth()->id()], ['status', 'Pending']])->exists()) {

            $auth_user_cart_items = Cart::with('items')->where([['user_id', auth()->id()], ['status', 'Pending']])->get();

            return response(['success' => true, 'cart' => $auth_user_cart_items]);
        } else {
            return response(['success' => false, 'message' => 'You Don\'t have items in your cart']);
        }
    }

    public function submitOrder(Request $request)
    {
        $submit_order = $request->validate([
            'payment_option' => ['required', 'in:Cash,Visa'],
            'shipping_address_id' => ['required', 'exists:shipping_addresses,id']
        ]);

        $auth_user_cart_items = Cart::with('items')->where([['user_id', auth()->id()], ['status', 'Pending']])->first();

        $auth_user_cart_items->update([
            'shipping_address_id' => $submit_order['shipping_address_id'],
            'payment_option' => $submit_order['payment_option'],
            'status' => 'Ordered',
        ]);

        return response(['success' => true, 'message' => 'Order submitted Successfully !']);
    }

    public function listAllOrders() {
        $auth_user_cart_items = Cart::with('items')->where(['user_id', auth()->id()])->get();

        return response(['success' => true, 'carts' => $auth_user_cart_items]);

    }
}
