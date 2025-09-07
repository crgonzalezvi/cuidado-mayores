<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/emergencia', [EmergencyContactController::class, 'index'])->name('emergencia');
    Route::get('/citas', [CitaController::class, 'index'])->name('citas');
    Route::get('/medicamentos', [MedicamentoController::class, 'index'])->name('medicamentos');

    Route::get('/emergencia', [\App\Http\Controllers\EmergencyContactController::class, 'index'])->name('emergencia');
    Route::get('/emergencia/contacto', [\App\Http\Controllers\EmergencyContactController::class, 'form'])->name('emergencia.contacto.form');
    Route::post('/emergencia/contacto', [\App\Http\Controllers\EmergencyContactController::class, 'store'])->name('emergencia.contacto.store');

});

require __DIR__.'/auth.php';
