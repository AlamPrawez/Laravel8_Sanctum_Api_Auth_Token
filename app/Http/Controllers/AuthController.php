<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function register(Request $request){

    	$validator = Validator::make($request->all(),[
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                // 'mobile'=>'required|unique:users,mobile',
                // 'address' => 'required',
                'password' => 'required|same:password_confirmation',
    	]);

    	if($validator->fails()){
    		return response()->json(['status_code' => 400, 'message' => 'Bad Request']);
    	}

    	$user = new User();
    	$user->name = $request->name;
    	$user->email = $request->email;
    	// $user->mobile = $request->mobile;
    	// $user->address = $request->address;
    	$user->password = bcrypt($request->password);
    	$user->save();
    	return response()->json([
       'status_code' => 200,
       'message'  =>  'User create Succesfully !'
    	]);
    }

    public function login(Request $request){

    	$validator = Validator::make($request->all(),[
                'email' => 'required',//|email|unique:users,email',
                'password' => 'required',
    	]);

    	if($validator->fails()){
    		return response()->json(['status_code' => 400, 'message' => 'Bad Request']);
    	}
            
            $credentials = request(['email' , 'password']);

            if(!Auth::attempt( $credentials)){
            
             return response()->json([
            'status_code' => 500,
            'message'  => 'Unauthorized'
             ]);
            }

            $user = User::where('email' , $request->email)->first();
            $tokenResult = $user->createToken('authToken')->plainTextToken;

            return response()->json([
              
             'status_code' => 200,
             'user' =>  $user,
             'toke'  =>  $tokenResult

            ]);

    }

    public function logout(Request $request){

    	$request->user()->currentAccessToken()->delete();
    	return response()->json([
              'status_code' => 200,
               'message'  => 'token delete successfully !'
            ]);
    }
}
