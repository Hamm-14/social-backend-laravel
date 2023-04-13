<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function loginUser(Request $request): Response
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
   
        if($validator->fails()){

            return Response(['message' => $validator->errors()],401);
        }
   
        if(Auth::attempt($request->all())){

            // $user = Auth::user();

            $user = User::where('email', $request->email)->first();
    
            $success =  $user->createToken('MySocialApp')->plainTextToken; 
        
            return Response(['token' => $success],200);
        }

        return Response(['message' => 'email or password wrong'],401);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function userDetails()
    {
        if (Auth::check()) {

            $user = Auth::user();

            return Response(['data' => $user],200);
        }
    }

    /**
     * Display the specified resource.
     */
    public function logout()
    {
        $authUser = Auth::user();

        $user = User::find($authUser->id)->first();

        $user->tokens()->delete();
        
        return Response(['data' => 'User Logout successfully.'],200);
    }
}
