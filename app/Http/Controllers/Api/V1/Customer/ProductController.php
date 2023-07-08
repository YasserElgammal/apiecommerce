<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class ProductController extends Controller
{
    public function store(StoreProductRequest $request)
    {

        $product_data = $request->validated();
        $product_data['market_id'] = auth()->user()->market->id;
        $product_data['name'] = ['en' => $product_data['name'], 'ar' => $product_data['name_ar']];
        $product_data['desc'] = ['en' => $product_data['desc'], 'ar' => $product_data['desc_ar']];

        Product::create($product_data);

        return response(['success'=> true, 'message'=>'Product added Successfully']);
    }

    public function getAllProducts()
    {
        $products = Product::all();
        return ProductResource::collection($products);
    }
}
