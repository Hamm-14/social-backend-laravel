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
     * Register a new user
     */
    public function registerUser(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required',
            'password' => 'required',
        ]);

        $isUserExist = User::where('email',$request->email)->first();

        if($isUserExist){
            return ["message" => "User Already Exist"];
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        return $user;
    }

    /**
     * create user session
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
     * Get User Details
     */
    public function userDetails()
    {
        if (Auth::check()) {

            $user = Auth::user();

            return Response(['data' => $user],200);
        }
    }

     /**
     * Fetch all users
     */
    public function fetchAllUsers()
    {
        if (Auth::check()) {

            $users = User::all();

            return Response(['data' => $users],200);
        }
    }

    /**
     * Destroy user session
     */
    public function logout()
    {
        $authUser = Auth::user();

        $user = User::find($authUser->id)->first();

        $user->tokens()->delete();
        
        return Response(['data' => 'User Logout successfully.'],200);
    }

    /**
     * Upload user avatar
     */
    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,jpg,png,gif,svg|max:1048',
            'userId' => 'required|integer',
        ]);

        $imageName = time().'.'.$request->image->extension();

        $request->image->move(public_path('images/avatar'),$imageName);

        $user = User::find($request->userId);

        $user->avatar = $imageName;

        $user->save();

        return Response(["message" => 'Image uploaded successfully']);
    }
}
