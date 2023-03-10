<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function addProduct(StoreProductRequest $request)
    {

        $product_data = $request->validated();
        $product_data['market_id'] = auth()->user()->market->id;

        Product::create($product_data);

        return response(['success'=> true, 'message'=>'Product added Successfully']);
    }
}
