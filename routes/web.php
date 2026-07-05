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

  // Acudientes
  Route::get('/acudientes', [App\Http\Controllers\AcudienteController::class, 'index'])->name('acudientes.index');
  Route::get('/acudientes/create', [App\Http\Controllers\AcudienteController::class, 'create'])->name('acudientes.create');
  Route::post('/acudientes', [App\Http\Controllers\AcudienteController::class, 'store'])->name('acudientes.store');
  Route::get('/acudientes/{id}/edit', [App\Http\Controllers\AcudienteController::class, 'edit'])->name('acudientes.edit');
  Route::put('/acudientes/{id}', [App\Http\Controllers\AcudienteController::class, 'update'])->name('acudientes.update');
  Route::delete('/acudientes/{id}', [App\Http\Controllers\AcudienteController::class, 'destroy'])->name('acudientes.destroy');

  // Alumnos
  Route::get('/alumnos', [App\Http\Controllers\AlumnoController::class, 'index'])->name('alumnos.index');
  Route::get('/alumnos/create', [App\Http\Controllers\AlumnoController::class, 'create'])->name('alumnos.create');
  Route::post('/alumnos', [App\Http\Controllers\AlumnoController::class, 'store'])->name('alumnos.store');
  Route::get('/alumnos/{id}/edit', [App\Http\Controllers\AlumnoController::class, 'edit'])->name('alumnos.edit');
  Route::put('/alumnos/{id}', [App\Http\Controllers\AlumnoController::class, 'update'])->name('alumnos.update');
  Route::delete('/alumnos/{id}', [App\Http\Controllers\AlumnoController::class, 'destroy'])->name('alumnos.destroy');

  // Cargos
  Route::get('/cargos', [App\Http\Controllers\CargoController::class, 'index'])->name('cargos.index');
  Route::get('/cargos/create', [App\Http\Controllers\CargoController::class, 'create'])->name('cargos.create');
  Route::post('/cargos', [App\Http\Controllers\CargoController::class, 'store'])->name('cargos.store');
  Route::get('/cargos/{id}/edit', [App\Http\Controllers\CargoController::class, 'edit'])->name('cargos.edit');
  Route::put('/cargos/{id}', [App\Http\Controllers\CargoController::class, 'update'])->name('cargos.update');
  Route::delete('/cargos/{id}', [App\Http\Controllers\CargoController::class, 'destroy'])->name('cargos.destroy');

  // Administrativos
  Route::get('/administrativos', [App\Http\Controllers\AdministrativoController::class, 'index'])->name('administrativos.index');
  Route::get('/administrativos/create', [App\Http\Controllers\AdministrativoController::class, 'create'])->name('administrativos.create');
  Route::post('/administrativos', [App\Http\Controllers\AdministrativoController::class, 'store'])->name('administrativos.store');
  Route::get('/administrativos/{id}/edit', [App\Http\Controllers\AdministrativoController::class, 'edit'])->name('administrativos.edit');
  Route::put('/administrativos/{id}', [App\Http\Controllers\AdministrativoController::class, 'update'])->name('administrativos.update');
  Route::delete('/administrativos/{id}', [App\Http\Controllers\AdministrativoController::class, 'destroy'])->name('administrativos.destroy');

  // Grupos
  Route::get('/grupos', [App\Http\Controllers\GrupoController::class, 'index'])->name('grupos.index');
  Route::get('/grupos/create', [App\Http\Controllers\GrupoController::class, 'create'])->name('grupos.create');
  Route::post('/grupos', [App\Http\Controllers\GrupoController::class, 'store'])->name('grupos.store');
  Route::get('/grupos/{id}/edit', [App\Http\Controllers\GrupoController::class, 'edit'])->name('grupos.edit');
  Route::put('/grupos/{id}', [App\Http\Controllers\GrupoController::class, 'update'])->name('grupos.update');
  Route::delete('/grupos/{id}', [App\Http\Controllers\GrupoController::class, 'destroy'])->name('grupos.destroy');

  // Horarios
  Route::get('/horarios', [App\Http\Controllers\HorarioController::class, 'index'])->name('horarios.index');
  Route::get('/horarios/create', [App\Http\Controllers\HorarioController::class, 'create'])->name('horarios.create');
  Route::post('/horarios', [App\Http\Controllers\HorarioController::class, 'store'])->name('horarios.store');
  Route::get('/horarios/{id}/edit', [App\Http\Controllers\HorarioController::class, 'edit'])->name('horarios.edit');
  Route::put('/horarios/{id}', [App\Http\Controllers\HorarioController::class, 'update'])->name('horarios.update');
  Route::delete('/horarios/{id}', [App\Http\Controllers\HorarioController::class, 'destroy'])->name('horarios.destroy');

  // Sedes
  Route::get('/sedes', [App\Http\Controllers\SedeController::class, 'index'])->name('sedes.index');
  Route::get('/sedes/create', [App\Http\Controllers\SedeController::class, 'create'])->name('sedes.create');
  Route::post('/sedes', [App\Http\Controllers\SedeController::class, 'store'])->name('sedes.store');
  Route::get('/sedes/{id}/edit', [App\Http\Controllers\SedeController::class, 'edit'])->name('sedes.edit');
  Route::put('/sedes/{id}', [App\Http\Controllers\SedeController::class, 'update'])->name('sedes.update');
  Route::delete('/sedes/{id}', [App\Http\Controllers\SedeController::class, 'destroy'])->name('sedes.destroy');

  // Niveles
  Route::get('/niveles', [App\Http\Controllers\NivelController::class, 'index'])->name('niveles.index');
  Route::get('/niveles/create', [App\Http\Controllers\NivelController::class, 'create'])->name('niveles.create');
  Route::post('/niveles', [App\Http\Controllers\NivelController::class, 'store'])->name('niveles.store');
  Route::get('/niveles/{id}/edit', [App\Http\Controllers\NivelController::class, 'edit'])->name('niveles.edit');
  Route::put('/niveles/{id}', [App\Http\Controllers\NivelController::class, 'update'])->name('niveles.update');
  Route::delete('/niveles/{id}', [App\Http\Controllers\NivelController::class, 'destroy'])->name('niveles.destroy');

  // Descuentos
  Route::get('/descuentos', [App\Http\Controllers\DescuentoController::class, 'index'])->name('descuentos.index');
  Route::get('/descuentos/create', [App\Http\Controllers\DescuentoController::class, 'create'])->name('descuentos.create');
  Route::post('/descuentos', [App\Http\Controllers\DescuentoController::class, 'store'])->name('descuentos.store');
  Route::get('/descuentos/{id}/edit', [App\Http\Controllers\DescuentoController::class, 'edit'])->name('descuentos.edit');
  Route::put('/descuentos/{id}', [App\Http\Controllers\DescuentoController::class, 'update'])->name('descuentos.update');
  Route::delete('/descuentos/{id}', [App\Http\Controllers\DescuentoController::class, 'destroy'])->name('descuentos.destroy');
});

// =====================================================
// PANEL DOCENTE — solo rol docente
// =====================================================
/* Route::prefix('docente')->name('docente.')->middleware(['auth', 'role:docente'])->group(function () {
  Route::get('/dashboard', [App\Http\Controllers\Docente\DashboardController::class, 'index'])->name('dashboard');
  // Aquí irán: clases, alumnos, asistencia
}); */

// =====================================================
// PANEL ALUMNO — solo rol alumno
// =====================================================
/* Route::prefix('alumno')->name('alumno.')->middleware(['auth', 'role:alumno'])->group(function () {
  Route::get('/dashboard', [App\Http\Controllers\Alumno\DashboardController::class, 'index'])->name('dashboard');
  // Aquí irán: horario, pagos, asistencia
}); */

// =====================================================
// PANEL ACUDIENTE — solo rol acudiente
// =====================================================
/* Route::prefix('acudiente')->name('acudiente.')->middleware(['auth', 'role:acudiente'])->group(function () {
  Route::get('/dashboard', [App\Http\Controllers\Acudiente\DashboardController::class, 'index'])->name('dashboard');
  // Aquí irán: ver hijo, pagos, horario
}); */
