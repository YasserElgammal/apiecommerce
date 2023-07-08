<?php

namespace App\Http\Controllers\Api\V1\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function index()
    {
        $items = Item::with('cart.user.shippingAdresses', 'product.market')
            ->whereHas('cart', function ($query) {
                $query->where('status', "Ordered");
            })->whereHas('product', function ($query) {
                $query->where('market_id', auth()->user()->market->id);
            })
            ->get();

        return response(['success' => true, 'items' => $items]);
    }

}
