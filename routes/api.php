<?php

use App\Http\Controllers\AudienceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function(){
    Route::resource('events', EventController::class);
    Route::resource('audiences', AudienceController::class);
    Route::get('/events/token/{token}', [EventController::class, 'token']);
    Route::get('/audiences/token/{token}', [AudienceController::class, 'token']);

    Route::get('/logout', [AuthController::class, 'logout']);
});
// Route::get('/events', [EventController::class, 'index']);
// Route::post('/events', [EventController::class, 'store']);
// Route::get('/events/{id}', [EventController::class, 'show']);
