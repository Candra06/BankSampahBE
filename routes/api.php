<?php

use App\Http\Controllers\API\ArtikelController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PengumpulanSampahController;
use App\Http\Controllers\API\WilayahController;
use App\Models\PengumpulanSampah;
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

Route::get('/listWilayah', [WilayahController::class, 'index']);
Route::middleware('auth:sanctum')->group(function ()
{
    Route::post('/updateProfil', [AuthController::class, 'updateProfil']);
    Route::get('/dashboard', [AuthController::class, 'dashboard']);
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

    // pengumpulan sampah
    Route::prefix('pengumpulan')->group(function() {
        Route::get('/history', [PengumpulanSampahController::class, 'index']);
        Route::post('/create', [PengumpulanSampahController::class, 'store']);
        Route::post('/updatePoin', [PengumpulanSampahController::class, 'editPoin']);
    });

     // pengumpulan sampah
     Route::prefix('user')->group(function() {
        Route::get('/list', [AuthController::class, 'index']);
        Route::post('/create', [AuthController::class, 'addUser']);
        Route::post('/update/{id}', [AuthController::class, 'update']);
    });
});
