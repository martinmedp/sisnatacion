<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\PublicBannerController;
use App\Http\Controllers\Api\PublicNoticiaController;
use App\Http\Controllers\Api\PublicDocenteController;
use App\Http\Controllers\Api\PublicGaleriaController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('public')
    ->group(function () {

        Route::get('/banners', [PublicBannerController::class, 'index']);
        Route::get('/noticias', [PublicNoticiaController::class, 'index']);
        Route::get('/docentes', [PublicDocenteController::class, 'index']);
        Route::get('/galerias', [PublicGaleriaController::class, 'index']);
    });
