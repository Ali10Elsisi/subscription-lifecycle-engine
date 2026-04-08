<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;



class UserController extends Controller
{
    //



     public function login(Request $request)
     {
         $credentials = $request->validate([
             'email' => ['required', 'email'],
             'password' => ['required'],
         ]);

         if (auth()->attempt($credentials)) {
             $user = auth()->user();
             $token = $user->createToken('api-token')->plainTextToken;

             return response()->json([
                 'access_token' => $token,
                 'token_type' => 'Bearer',
             ]);
         }

         return response()->json(['message' => 'Invalid credentials'], 401);
     }
    public function update(Request $request,$id)
    {
        $user = User::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|min:8',
        ]);

        if (isset($validatedData['password'])) {
            $validatedData['password'] = bcrypt($validatedData['password']);
        }

        $user->update($validatedData);

        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }


}
