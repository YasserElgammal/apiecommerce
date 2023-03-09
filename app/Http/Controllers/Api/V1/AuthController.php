<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\Market;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request)
    {
        $validated_data = $request->validated();

        $validated_data['password'] = Hash::make($request->password);

        $user = User::create($validated_data);

        if($user['role_id'] == 1) // mean this user is merchant
        {
            // lets create market
            $user->market()->save(new Market());
            $token =  $user->createToken('token-name', ['merchant:roles'])->plainTextToken;
        }else{
            $token =  $user->createToken('token-name', ['user:roles'])->plainTextToken;
        }

        return response(['success'=> true,'user'=> $user, 'access_token'=> $token]);
    }

    public function login(LoginUserRequest $request)
    {
        $login_data = $request->validated();

        if(!auth()->attempt($login_data)) {
            return response(['success'=> false, 'message'=>'Invalid credentials'],401);
        }

        if(auth()->user()->role == "Merchant") // mean this user choose to be merchant
        {
            // create token for merchant
            $token =  auth()->user()->createToken('token-name', ['merchant:roles'])->plainTextToken;
        }else{
            // create token for user
            $token =  auth()->user()->createToken('token-name', ['user:roles'])->plainTextToken;
        }

        return response(['success'=> true,'user'=> auth()->user(), 'access_token'=> $token]);
    }
}
