<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\WebsiteController;

use App\Http\Controllers\BannerController;

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Rutas para el sitio web
Route::get('/', [WebsiteController::class, 'inicio'])->name('inicio');
Route::get('/nosotros', [WebsiteController::class, 'nosotros'])->name('nosotros');
Route::get('/contacto', [WebsiteController::class, 'contacto'])->name('contacto');
Route::get('/admisiones', [WebsiteController::class, 'admisiones'])->name('admisiones');
Route::get('/noticias', [WebsiteController::class, 'noticias'])->name('noticias');
Route::get('/galeria', [WebsiteController::class, 'galeria'])->name('galeria');
Route::get('/docentes', [WebsiteController::class, 'docentes'])->name('docentes');

// Rutas para la configuración del sistema
Route::get('/admin/configuracion', [App\Http\Controllers\ConfiguracionController::class, 'index'])->name('admin.configuracion.index')->middleware('auth');
Route::post('/admin/configuracion/create', [App\Http\Controllers\ConfiguracionController::class, 'create'])->name('admin.configuracion.create')->middleware('auth');

//Rutas para la gestión de banners
Route::get('/admin/banners', [BannerController::class, 'index'])->name('admin.banners.index')->middleware('auth');
Route::get('/admin/banners/create', [BannerController::class, 'create'])->name('admin.banners.create')->middleware('auth');
Route::post('/admin/banners/store', [BannerController::class, 'store'])->name('admin.banners.store')->middleware('auth');
Route::get('/admin/banners/{id}/edit', [BannerController::class, 'edit'])->name('admin.banners.edit')->middleware('auth');
Route::put('/admin/banners/{id}', [BannerController::class, 'update'])->name('admin.banners.update')->middleware('auth');
Route::delete('/admin/banners/{id}', [BannerController::class, 'destroy'])->name('admin.banners.destroy')->middleware('auth');

// Rutas para la gestión de noticias página web
Route::get('/admin/noticias', [App\Http\Controllers\NoticiaController::class, 'index'])->name('admin.noticias.index')->middleware('auth');
Route::get('/admin/noticias/create', [App\Http\Controllers\NoticiaController::class, 'create'])->name('admin.noticias.create')->middleware('auth');
Route::post('/admin/noticias', [App\Http\Controllers\NoticiaController::class, 'store'])->name('admin.noticias.store')->middleware('auth');
Route::get('/admin/noticias/{id}/edit', [App\Http\Controllers\NoticiaController::class, 'edit'])->name('admin.noticias.edit')->middleware('auth');
Route::put('/admin/noticias/{id}', [App\Http\Controllers\NoticiaController::class, 'update'])->name('admin.noticias.update')->middleware('auth');
Route::delete('/admin/noticias/{id}', [App\Http\Controllers\NoticiaController::class, 'destroy'])->name('admin.noticias.destroy')->middleware('auth');
Route::get('/noticias/{noticia}', [WebsiteController::class, 'noticiaDetalle'])->name('noticias.show');

// Rutas para la gestión de galerías página web
Route::get('/admin/galerias', [App\Http\Controllers\GaleriaController::class, 'index'])->name('admin.galerias.index')->middleware('auth');
Route::get('/admin/galerias/create', [App\Http\Controllers\GaleriaController::class, 'create'])->name('admin.galerias.create')->middleware('auth');
Route::post('/admin/galerias', [App\Http\Controllers\GaleriaController::class, 'store'])->name('admin.galerias.store')->middleware('auth');
Route::get('/admin/galerias/{id}/edit', [App\Http\Controllers\GaleriaController::class, 'edit'])->name('admin.galerias.edit')->middleware('auth');
Route::put('/admin/galerias/{id}', [App\Http\Controllers\GaleriaController::class, 'update'])->name('admin.galerias.update')->middleware('auth');
Route::delete('/admin/galerias/{id}', [App\Http\Controllers\GaleriaController::class, 'destroy'])->name('admin.galerias.destroy')->middleware('auth');
// Rutas para la gestión de docentes
Route::get('/admin/docentes', [App\Http\Controllers\DocenteController::class, 'index'])->name('admin.docentes.index')->middleware('auth');
Route::get('/admin/docentes/create', [App\Http\Controllers\DocenteController::class, 'create'])->name('admin.docentes.create')->middleware('auth');
Route::post('/admin/docentes', [App\Http\Controllers\DocenteController::class, 'store'])->name('admin.docentes.store')->middleware('auth');
Route::get('/admin/docentes/{id}/edit', [App\Http\Controllers\DocenteController::class, 'edit'])->name('admin.docentes.edit')->middleware('auth');
Route::put('/admin/docentes/{id}', [App\Http\Controllers\DocenteController::class, 'update'])->name('admin.docentes.update')->middleware('auth');
Route::delete('/admin/docentes/{id}', [App\Http\Controllers\DocenteController::class, 'destroy'])->name('admin.docentes.destroy')->middleware('auth');
