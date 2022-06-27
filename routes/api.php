<?php

use App\Http\Controllers\API\ArtikelController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\WilayahController;
use Illuminate\Routing\RouteGroup;

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
Route::post('/forgotPassword', [AuthController::class, 'forgotPassword']);
Route::post('/resetPassword', [AuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function ()
{
    Route::post('/updateProfil', [AuthController::class, 'updateProfil']);
    // CRUD Artikel
    Route::prefix('artikel')->group(function() {
        Route::get('/list', [ArtikelController::class, 'index']);
        Route::get('/detail/{id}', [ArtikelController::class, 'show']);
        Route::post('/create', [ArtikelController::class, 'store']);
        Route::post('/update/{id}', [ArtikelController::class, 'update']);
        Route::get('/delete/{id}', [ArtikelController::class, 'destroy']);
    });

     // CRUD Wilayah
     Route::prefix('wilayah')->group(function() {
        Route::get('/list', [WilayahController::class, 'index']);
        Route::get('/detail/{id}', [WilayahController::class, 'show']);
        Route::post('/create', [WilayahController::class, 'store']);
        Route::post('/update/{id}', [WilayahController::class, 'update']);
        Route::get('/delete/{id}', [WilayahController::class, 'destroy']);
    });

    
});
