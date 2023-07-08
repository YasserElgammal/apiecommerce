<?php

namespace App\Http\Controllers\Api\V1\Merchant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MarketController extends Controller
{
    // echo echo
    public function index()
    {
        if (auth()->user()->tokenCan('merchant')) {
        return response(['success'=> true, 'message'=>'Hello']);
        }else{
            return response(['success'=> false, 'message'=>'Go Away']);
        }

    }
    // Set Store name
    public function setStoreName(Request $request)
    {
        $store_name = $request->validate(['name' => ['required','string', 'max:25']]);

        $user = auth()->user();
        $user->market->update(['name' => $store_name['name']]);

        return response(['success'=> true, 'message'=>'Store Name Updated']);
    }
    // set vat options wether is included vat and vat amount
    public function setVatOptions(Request $request)
    {
        $vat_options = $request->validate(['vat_included' => ['required','boolean'], 'vat' => ['required','numeric', 'max:100']]);

        $user = auth()->user();
        $user->market->update(['vat_included' => $vat_options['vat_included'], 'vat' => $vat_options['vat']]);

        return response(['success'=> true, 'message'=>'Vat Updated']);
    }


}
