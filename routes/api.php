<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\FriendshipController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\CommentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('login',[UserController::class,'loginUser']);
Route::post('register',[UserController::class,'registerUser']);


Route::group(['middleware' => 'auth:sanctum'],function(){
    
    //fetch user details
    Route::get('user',[UserController::class,'userDetails']);

    //upload user avatar
    Route::post('user/avatar', [UserController::class,'uploadAvatar']);

    //fetch all users
    Route::get('users',[UserController::class,'fetchAllUsers']);

    //follow a user
    Route::post('follow', [FriendshipController::class, 'followUser']);

    //fetch user followings
    Route::get('/following',[FriendshipController::class, 'fetchUserFollowing']);

    //fetch user followers
    Route::get('/followers',[FriendshipController::class, 'fetchUserFollowers']);
    
    //create post
    Route::post('post',[PostController::class,'create']); 

    //upload post pic
    Route::post('post/pic',[PostController::class,'uploadPic']); 
    
    //update post
    Route::patch('post',[PostController::class,'update']); 
    
    //delete post
    Route::delete('post',[PostController::class,'delete']);  
    
    //toggle like on post
    Route::get('post/like/toggle',[PostController::class,'toggleLike']);
    
    //fetch all posts
    Route::get('/post/all',[PostController::class, 'allPosts']);       
    
     //fetch all posts of a user
    Route::get('/post/user',[PostController::class, 'userPosts']);     
    
      //Create new comment  
    Route::post('/comment',[CommentController::class, 'create']); 
    
     //fetch all comments of a post
    Route::get('/comment',[CommentController::class, 'comments']); 

    //update comment
    Route::patch('/comment',[CommentController::class, 'update']); 

    //delete comment
    Route::delete('/comment',[CommentController::class, 'delete']);

    //toggle comment likes
    Route::get('/comment/like/toggle',[CommentController::class, 'toggleLike']);

    //logout
    Route::get('logout',[UserController::class,'logout']);
});