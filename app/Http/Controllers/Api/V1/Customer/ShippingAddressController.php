<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Models\ShippingAddress;
use Illuminate\Http\Request;

class ShippingAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shipping_adresses = ShippingAddress::where('user_id', auth()->id)->get();

        return response(['success' => true, 'data' => $shipping_adresses]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated_data = $request->validate([
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'postal_number' => ['required', 'string', 'max:10'],
            'place_name' => ['required', 'string', 'max:255'],
            'main' => ['boolean'],
        ]);

        ShippingAddress::create($validated_data + ['user_id' => auth()->id]);

        return response(['success' => true, 'message' => 'Address added Successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $shipping_adress = ShippingAddress::findOrFail($id);

        return response(['success' => true, 'data' => $shipping_adress]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated_data = $request->validate([
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'postal_number' => ['required', 'string', 'max:10'],
            'place_name' => ['required', 'string', 'max:255'],
            'main' => ['boolean'],
        ]);

        $shipping_adress = ShippingAddress::findOrFail($id);
        $shipping_adress->update($validated_data + ['user_id' => auth()->id]);

        return response(['success' => true, 'message' => 'Address Updated Successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $shipping_adress = ShippingAddress::findOrFail($id);
        $shipping_adress->delete();

        return response(['success' => true, 'message' => 'Address Deleted Successfully']);
    }
}
