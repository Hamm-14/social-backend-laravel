<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\FriendshipController;

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
    Route::get('logout',[UserController::class,'logout']);
});