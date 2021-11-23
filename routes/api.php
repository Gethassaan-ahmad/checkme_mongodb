<?php

use App\Http\Controllers\commentController;
use App\Http\Controllers\mongoController;
use App\Http\Controllers\userController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Posts Routes
Route::post("/insert",[mongoController::class,'insert']);
Route::post("/update/{user_id}",[mongoController::class,'update']);
Route::get("/read",[mongoController::class,'read']);
Route::post("/delete/{user_id}",[mongoController::class,'delete']);

// Comment Routes
Route::post("/commentinsert",[commentController::class,'insert']);
Route::post("/commentupdate/{post_id}",[commentController::class,'update']);
Route::get("/commentread",[commentController::class,'read']);
Route::post("/commentdelete",[commentController::class,'delete']);

// Register Routes
Route::post("/register",[userController::class,'register']);
Route::post("/login",[userController::class,'login']);
Route::post("/logout",[userController::class,'logout']);

