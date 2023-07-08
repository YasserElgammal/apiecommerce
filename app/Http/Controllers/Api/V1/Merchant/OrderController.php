<?php

namespace App\Http\Controllers\Api\V1\Merchant;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Cart;
use App\Models\Item;
use App\Models\Product;

class OrderController extends Controller
{

    public function index()
    {
        $items = Item::whereHas('cart', function ($query) {
            $query->where('status', 'Ordered');
        })
        ->where('product_id', auth()->market()->products()->id)
        ->get();

        return ProductResource::collection($items);
    }

    public function store(StoreProductRequest $request)
    {

    }

    public function update(StoreProductRequest $request, $id)
    {
        $product_data = $request->validated();
        $product = Product::where([['market_id', auth()->user()->market->id], ['product_id', $id]])->findOrFail();
        $product_data['market_id'] = auth()->user()->market->id;
        $product_data['name'] = ['en' => $product_data['name'], 'ar' => $product_data['name_ar']];
        $product_data['desc'] = ['en' => $product_data['desc'], 'ar' => $product_data['desc_ar']];

        $product->update($product_data);

        return response(['success'=> true, 'message'=>'Product added Successfully']);
    }

    public function show($id)
    {
        Product::where([['market_id', auth()->user()->market->id], ['product_id', $id]])->findOrFail($id);

        return response(['success'=> true, 'message'=>'Product Deleted Successfully']);
    }

    public function destroy($id)
    {
        $product = Product::where('market_id', auth()->user()->market->id)->findOrFail($id);
        $product->delete();

        return response(['success'=> true, 'message'=>'Product Deleted Successfully']);
    }

}
