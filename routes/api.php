<?php

use App\Http\Controllers\AudienceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\UserController;
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
Route::post('/aktivasi', [AuthController::class, 'activate']);
Route::post('/lupa', [AuthController::class, 'forgot']);
Route::post('/aktiflupa', [AuthController::class, 'activeforgot']);
Route::post('/doreset', [AuthController::class, 'doreset']);
Route::get('/cekuser/{email}', [AuthController::class, 'cekuser']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logingoogle', [AuthController::class, 'logingoogle'])->name('logingoogle');
Route::get('/userse', [UserController::class, 'index'])->name('userse');

Route::group(['middleware' => 'auth:sanctum'], function(){
    Route::resource('events', EventController::class);
    // Route::resource('audiences', AudienceController::class);
    Route::get('/events_token/{token}', [EventController::class, 'token']);
    Route::get('/homeevents/{userid}', [EventController::class, 'main']);
    Route::get('/audiences_token/{token}', [AudienceController::class, 'token']);
    Route::get('/homeaudiences/{userid}', [AudienceController::class, 'main']);

    Route::get('/logout', [AuthController::class, 'logout']);
});
// Route::get('/events', [EventController::class, 'index']);
// Route::post('/events', [EventController::class, 'store']);
Route::post('/audiences', [AudienceController::class, 'store']);
// Route::get('/events/{id}', [EventController::class, 'show']);
