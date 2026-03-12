<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;                         // Your User model
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserLoginRequest;
use Illuminate\Support\Facades\Hash;        // For Hash::make()
use Illuminate\Support\Facades\Auth;        // Optional, if you want to login after registration
// use Illuminate\Http\JsonResponse;            // Optional, if you want type hinting

class AuthController extends Controller
{
    public function register(UserRegisterRequest $request)
    //user request=>check validation=>create user=>generate token => return token
    {
        try{
             $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            //create token -> personal access token(show)
            $token = $user->createToken('auth_token')->plainTextToken;
            
            return response()->json([
                'message' => 'User Registered Successfully',
                'token' => $token,
            ],201);
        }catch(Exception $e){
            return response()->json([
                'message' => 'Internal Server Error',
                'error' => $e->getMessage(),
            ],500);
        }
    }
    public function login(UserLoginRequest $request)
    {
        try{
            $user = User::where('email',$request->email)->first();
            
            if(!$user) {
                return response()->json([
                    'message' => 'User not found',
                ],404);
            }
            //check password => hashed Input Code === (DataBase Store Code)
            if(!Hash::check($request->password, $user->password)){
                return response()->json([
                    'message' => 'Invalid Password',
                ],401);
            }
            
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'message' => 'User Logged In Successfully',
                'token' => $token,
            ],200);
            
        }catch(Exception $e){
            return response()->json([
                'message' => 'Internal Server Error',
                'error' => $e->getMessage(),
            ],500);
        }
    }

    public function logout()
    {
        try{
            $user = Auth::user();
            //delete_token_(documentation)
            $user->currentAccessToken()->delete(); //logout-profile
            
        }catch(Exception $e){
            return response()->json([
                'message' => 'Internal Server Error',
                'error' => $e->getMessage(),
            ],500);
        }
    }

    public function me()
    {
        try{
           $user = Auth::user();
           return response()->json([
                'user' => $user,
           ],200);
        }catch(Exception $e){
            return response()->json([
                'message' => 'Internal Server Error',
                'error' => $e->getMessage(),
            ],500);
        }
    }
}