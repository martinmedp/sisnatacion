<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\WebsiteController;

use App\Http\Controllers\BannerController;

// Rutas para el sitio web

//Rutas para link del sitio web
Route::get('/', [WebsiteController::class, 'inicio'])->name('inicio');
Route::get('/nosotros', [WebsiteController::class, 'nosotros'])->name('nosotros');
Route::get('/contacto', [WebsiteController::class, 'contacto'])->name('contacto');
Route::get('/admisiones', [WebsiteController::class, 'admisiones'])->name('admisiones');
Route::get('/noticias', [WebsiteController::class, 'noticias'])->name('noticias');
Route::get('/galeria', [WebsiteController::class, 'galeria'])->name('galeria');


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


// Rutas para la configuración del sistema
Route::get('/admin/configuracion', [App\Http\Controllers\ConfiguracionController::class, 'index'])->name('admin.configuracion.index')->middleware('auth');
Route::post('/admin/configuracion/create', [App\Http\Controllers\ConfiguracionController::class, 'create'])->name('admin.configuracion.create')->middleware('auth');


//Rutas para la gestión de banners
Route::get('/admin/banners', [BannerController::class, 'index'])->name('admin.banners.index');
//->middleware('auth'); //quitamos comentario colocar autenticación
Route::get('/admin/banners/create', [BannerController::class, 'create'])->name('admin.banners.create');
Route::post('/admin/banners/store', [BannerController::class, 'store'])->name('admin.banners.store');
Route::get('/admin/banners/{id}/edit', [BannerController::class, 'edit'])->name('admin.banners.edit');
Route::put('/admin/banners/{id}', [BannerController::class, 'update'])->name('admin.banners.update');
Route::delete('/admin/banners/{id}', [BannerController::class, 'destroy'])->name('admin.banners.destroy');
