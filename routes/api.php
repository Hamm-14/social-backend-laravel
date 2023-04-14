<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\FriendshipController;
use App\Http\Controllers\API\PostController;

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
    Route::get('user',[UserController::class,'userDetails']);
    Route::get('users',[UserController::class,'fetchAllUsers']);
    Route::post('follow', [FriendshipController::class, 'followUser']);
    Route::get('/following',[FriendshipController::class, 'fetchUserFollowing']);
    Route::get('/followers',[FriendshipController::class, 'fetchUserFollowers']);
    Route::post('post',[PostController::class,'create']);
    Route::patch('post',[PostController::class,'update']);
    Route::delete('post',[PostController::class,'delete']);
    Route::get('post/like/toggle',[PostController::class,'toggleLike']);
    Route::get('/post/all',[PostController::class, 'allPosts']);
    Route::get('/post/user',[PostController::class, 'userPosts']);
    Route::get('logout',[UserController::class,'logout']);
});