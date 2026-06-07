<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\WebsiteController;

Route::get('/', [WebsiteController::class, 'inicio'])->name('inicio');

Route::get('/nosotros', [WebsiteController::class, 'nosotros'])->name('nosotros');

Route::get('/contacto', [WebsiteController::class, 'contacto'])->name('contacto');

Route::get('/admisiones', [WebsiteController::class, 'admisiones'])->name('admisiones');

Route::get('/noticias', [WebsiteController::class, 'noticias'])->name('noticias');

Route::get('/galeria', [WebsiteController::class, 'galeria'])->name('galeria');

//Route::get('/', function () {return view('welcome');
//});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


// Rutas para la configuración del sistema
Route::get('/admin/configuracion', [App\Http\Controllers\ConfiguracionController::class, 'index'])->name('admin.configuracion.index')->middleware('auth');
Route::post('/admin/configuracion/create', [App\Http\Controllers\ConfiguracionController::class, 'create'])->name('admin.configuracion.create')->middleware('auth');
