<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{
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
            $errormessage = $validateUser->errors()->all();
            return $this->sendError('Validation Error',$errormessage,401);
        }

        //if the authentication pass then store the data
        $user = User::create([
            'name' => $req->name,
            'email' => $req->email,
            'password' => $req->password,
        ]);

        //if the data is inserted successfully
        return $this->sendResponse($user,'User Created successfully');

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
            $errormessage = $validateUser->errors()->all();
            return $this->sendError('User Authentication Fails',$errormessage,404);
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

                $errormessage = '';
                return $this->sendError('Email, Password wrong',$errormessage,401);                
            }
    }
    public function logout(Request $req){
        $loggedUser = $req->user();
        $loggedUser->currentAccessToken()->delete();

        //loggout successful
        return $this->sendResponse($loggedUser,'Logged out successfully');
    }
}
