<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\BannerController;

Auth::routes(['register' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// =====================================================
// SITIO WEB PÚBLICO
// =====================================================
Route::get('/', [WebsiteController::class, 'inicio'])->name('inicio');
Route::get('/nosotros', [WebsiteController::class, 'nosotros'])->name('nosotros');
Route::get('/contacto', [WebsiteController::class, 'contacto'])->name('contacto');
Route::get('/admisiones', [WebsiteController::class, 'admisiones'])->name('admisiones');
Route::get('/noticias', [WebsiteController::class, 'noticias'])->name('noticias');
Route::get('/noticias/{noticia}', [WebsiteController::class, 'noticiaDetalle'])->name('noticias.show');
Route::get('/galeria', [WebsiteController::class, 'galeria'])->name('galeria');
Route::get('/docentes', [WebsiteController::class, 'docentes'])->name('docentes');

// =====================================================
// PANEL ADMIN — solo rol admin
// =====================================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {

  // Dashboard
  Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

  // Configuración
  Route::get('/configuracion', [App\Http\Controllers\ConfiguracionController::class, 'index'])->name('configuracion.index');
  Route::post('/configuracion/create', [App\Http\Controllers\ConfiguracionController::class, 'create'])->name('configuracion.create');

  // Banners
  Route::get('/banners', [BannerController::class, 'index'])->name('banners.index');
  Route::get('/banners/create', [BannerController::class, 'create'])->name('banners.create');
  Route::post('/banners/store', [BannerController::class, 'store'])->name('banners.store');
  Route::get('/banners/{id}/edit', [BannerController::class, 'edit'])->name('banners.edit');
  Route::put('/banners/{id}', [BannerController::class, 'update'])->name('banners.update');
  Route::delete('/banners/{id}', [BannerController::class, 'destroy'])->name('banners.destroy');

  // Noticias
  Route::get('/noticias', [App\Http\Controllers\NoticiaController::class, 'index'])->name('noticias.index');
  Route::get('/noticias/create', [App\Http\Controllers\NoticiaController::class, 'create'])->name('noticias.create');
  Route::post('/noticias', [App\Http\Controllers\NoticiaController::class, 'store'])->name('noticias.store');
  Route::get('/noticias/{id}/edit', [App\Http\Controllers\NoticiaController::class, 'edit'])->name('noticias.edit');
  Route::put('/noticias/{id}', [App\Http\Controllers\NoticiaController::class, 'update'])->name('noticias.update');
  Route::delete('/noticias/{id}', [App\Http\Controllers\NoticiaController::class, 'destroy'])->name('noticias.destroy');

  // Galerías
  Route::get('/galerias', [App\Http\Controllers\GaleriaController::class, 'index'])->name('galerias.index');
  Route::get('/galerias/create', [App\Http\Controllers\GaleriaController::class, 'create'])->name('galerias.create');
  Route::post('/galerias', [App\Http\Controllers\GaleriaController::class, 'store'])->name('galerias.store');
  Route::get('/galerias/{id}/edit', [App\Http\Controllers\GaleriaController::class, 'edit'])->name('galerias.edit');
  Route::put('/galerias/{id}', [App\Http\Controllers\GaleriaController::class, 'update'])->name('galerias.update');
  Route::delete('/galerias/{id}', [App\Http\Controllers\GaleriaController::class, 'destroy'])->name('galerias.destroy');

  // Docentes
  Route::get('/docentes', [App\Http\Controllers\DocenteController::class, 'index'])->name('docentes.index');
  Route::get('/docentes/create', [App\Http\Controllers\DocenteController::class, 'create'])->name('docentes.create');
  Route::post('/docentes', [App\Http\Controllers\DocenteController::class, 'store'])->name('docentes.store');
  Route::get('/docentes/{id}/edit', [App\Http\Controllers\DocenteController::class, 'edit'])->name('docentes.edit');
  Route::put('/docentes/{id}', [App\Http\Controllers\DocenteController::class, 'update'])->name('docentes.update');
  Route::delete('/docentes/{id}', [App\Http\Controllers\DocenteController::class, 'destroy'])->name('docentes.destroy');
});

// =====================================================
// PANEL DOCENTE — solo rol docente
// =====================================================
Route::prefix('docente')->name('docente.')->middleware(['auth', 'role:docente'])->group(function () {
  Route::get('/dashboard', [App\Http\Controllers\Docente\DashboardController::class, 'index'])->name('dashboard');
  // Aquí irán: clases, alumnos, asistencia
});

// =====================================================
// PANEL ALUMNO — solo rol alumno
// =====================================================
Route::prefix('alumno')->name('alumno.')->middleware(['auth', 'role:alumno'])->group(function () {
  Route::get('/dashboard', [App\Http\Controllers\Alumno\DashboardController::class, 'index'])->name('dashboard');
  // Aquí irán: horario, pagos, asistencia
});

// =====================================================
// PANEL ACUDIENTE — solo rol acudiente
// =====================================================
Route::prefix('acudiente')->name('acudiente.')->middleware(['auth', 'role:acudiente'])->group(function () {
  Route::get('/dashboard', [App\Http\Controllers\Acudiente\DashboardController::class, 'index'])->name('dashboard');
  // Aquí irán: ver hijo, pagos, horario
});
