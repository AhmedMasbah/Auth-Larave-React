<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PassowordController;

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

Route::post('register', [AuthController::class , 'register']);
Route::post('login', [AuthController::class , 'login']);
Route::post('forget', [PassowordController::class , 'forget']);
Route::post('reset', [PassowordController::class , 'reset']);


Route::middleware(['auth:sanctum'])->group(function () {
    // Authenticated routes
    Route::get('/user', [AuthController::class, 'user']);
    
    // Add other authenticated routes as needed
    Route::post('/logout', [AuthController::class, 'logout']);
});