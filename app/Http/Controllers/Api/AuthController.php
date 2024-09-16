<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //
    public function signup(Request $req){
        //validate the user data
        $validateUser = Validator::make(
            $req->all(),[
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',

            ]);

        //if the validation fails then gives error
        if($validateUser->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validateUser->errors()->all()
            ],401);
        }

        //if the authentication pass then store the data
        $user = User::create([
            'name' => $req->name,
            'email' => $req->email,
            'password' => $req->password,
        ]);

        //if the data is inserted successfully
        return response()->json([
            'status' =>true,
            'message' => 'User Created successfully',
            'user' => $user,
        ],200);

    }
    public function login(Request $req){
        $validateUser = Validator::make(
            $req->all(),
            [
                'email' => 'required|email',
                'password' => 'required',
            ]);

        //if the authentication fails
        if($validateUser->fails()){
            return response()->json([
                'status' => false,
                'message' => 'User Authentication Fails',
                'errors' => $validateUser->errors()->all()
            ],404);
        }

        $authenticatedUser = [
            'email'=>$req->email,
            'password' => $req->password
        ];
        //if the authentication pass 
        if(Auth::attempt($authenticatedUser)){
            $authUser = Auth::user();
                return response()->json([
                    'status' =>true,
                    'message' => 'User Logged successfully',
                    'token' => $authUser->createToken("API Token")->plainTextToken,
                    'token_type' => 'bearer',
                ],200);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'Email, Password wrong',
                ],401);
            }
    }
    public function logout(Request $req){
        $loggedUser = $req->user();
        $loggedUser->token()->delete();

        //loggout successful
        return response()->json([
            'status' => true,
            'user' => $loggedUser,
            'message' => 'Logged out successfully',
        ],200);
    }
}
