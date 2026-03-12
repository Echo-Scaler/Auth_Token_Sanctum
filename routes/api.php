<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NoteController;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);

// Route::post('logout',[AuthController::class,'logout'])->middleware('auth:sanctum');
// Route::get('me',[AuthController::class,'me'])->middleware('auth:sanctum');
// Route::apiResource('notes',NoteController::class)->middleware('auth:sanctum');

//change to groups
Route::middleware('auth:sanctum')->group(function(){
Route::post('logout',[AuthController::class,'logout']);
Route::get('me',[AuthController::class,'me']);

Route::apiResource('notes',NoteController::class);
});