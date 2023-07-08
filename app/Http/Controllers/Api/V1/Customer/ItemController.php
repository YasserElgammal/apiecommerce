<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Item;
use App\Models\Product;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function addItemCart(Request $request)
    {
        $add_item_cart = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'qty' => ['required', 'numeric', 'gt:0']
        ]);

        $user_id = auth()->user()->id;
        $product_id = $add_item_cart['product_id'];
        $product_qty = $add_item_cart['qty'];


        // get product details
        $product = Product::with('market')->find($product_id);

        // Check Product status
        if (!$product->status) {
            return response(['success' => false, 'message' => 'Not Allowed to Add this item']);
        }

        $product_price = $product->price;
        $product_name = $product->name;

        // check Vat Option
        if ($product->market->vat_included) {
            $price_vat = ($product_price * $product->market->vat) / 100 + $product_price;
        } else {
            $price_vat = 0;
        }

        if (Cart::where([['user_id', $user_id], ['status', 'Pending']])->exists()) {

            $current_user_card = Cart::where([
                ['user_id', $user_id],
                ['status', 'Pending']
            ])->first();

            $current_user_card_id = $current_user_card['id'];
            $current_user_card_total = $current_user_card['total'];
            $current_user_card_total_vat = $current_user_card['total_vat'];


            // create Item
            $this->addItemDetails($product_name, $product_price, $price_vat, $product_id, $product_qty, $current_user_card_id);

            Cart::where('id', $current_user_card_id)->update([
                'total' => ($current_user_card_total + $product_price),
                'total_vat' => ($price_vat + $current_user_card_total_vat)
            ]);

            return response(['success' => true, 'message' => 'Item has been added to Existing Card']);
        } else {

            $new_cart = Cart::create(['user_id' => $user_id]);
            $cart_id = $new_cart['id'];

            // create Item
            $this->addItemDetails($product_name, $product_price, $price_vat, $product_id, $product_qty, $cart_id);

            Cart::where('id', $cart_id)->update(['total' => $product_price, 'total_vat' => $price_vat]);

            return response(['success' => true, 'message' => 'Item has been added to cart']);
        }
    }

    protected function addItemDetails($name, $price, $price_vat, $product_id, $product_qty, $cart_id)
    {
        Item::create([
            'name' => $name,
            'price' => $price,
            'price_vat' => $price_vat,
            'product_id' => $product_id,
            'qty' => $product_qty,
            'cart_id' => $cart_id
        ]);
    }

    protected function removeItemFromCart(Request $request)
    {
        $remove_item_cart = $request->validate([
            'product_id' => ['required', 'exists:products,id']
        ]);

        $current_user_card = Cart::with('items')->where([
            ['user_id', auth()->id()],
            ['status', 'Pending']
        ])->first();

        $current_user_card_id = $current_user_card['id'];
        $current_user_card_total = $current_user_card['total'];
        $current_user_card_total_vat = $current_user_card['total_vat'];

        $item = Item::where([
            ['cart_id', $current_user_card_id],
            [
                'product_id', $remove_item_cart['product_id']
            ]
        ])->first();

        $current_user_card->update([
            'total' => ($current_user_card_total - $item['price']),
            'total_vat' => ($current_user_card_total_vat - $item['price_vat'])
        ]);

        $item->delete();

        if ($current_user_card->fresh()->items->isEmpty()) {
            $current_user_card->delete();
        }

        return response(['success' => false, 'message' => 'Item Removed']);
    }
}
