<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MainController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [MainController::class, 'index']);
Route::get('/auth/signin', [AuthController::class, 'index']);
Route::get('/auth/signup', [AuthController::class, 'signup']);
Route::post('/auth/signup', [AuthController::class, 'signup_post']);
Route::post('/auth/signin', [AuthController::class, 'signin']);


Route::middleware('auth')->group( function() {
    Route::get('/auth/signout', [AuthController::class, 'signout']);
});
