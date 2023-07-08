<?php

namespace App\Http\Controllers\Api\V1\Merchant;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class ProductController extends Controller
{

    public function index()
    {
        $products = Product::where('market_id', auth()->user()->market->id)->get();

        return ProductResource::collection($products);
    }

    public function store(StoreProductRequest $request)
    {
        $product_data = $request->validated();
        $product_data['market_id'] = auth()->user()->market->id;
        $product_data['name'] = ['en' => $product_data['name'], 'ar' => $product_data['name_ar']];
        $product_data['desc'] = ['en' => $product_data['desc'], 'ar' => $product_data['desc_ar']];

        Product::create($product_data);

        return response(['success'=> true, 'message'=>'Product added Successfully']);
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
