<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\EmergencyContactController;
use App\Http\Controllers\MedicationController;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\Appointment;



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
    Route::get('/citas', [AppointmentController::class, 'index'])->name('citas');
    Route::get('/medicamentos', [MedicationController::class, 'index'])->name('medicamentos');

    Route::get('/emergencia', [\App\Http\Controllers\EmergencyContactController::class, 'index'])->name('emergencia');
    Route::get('/emergencia/contacto', [\App\Http\Controllers\EmergencyContactController::class, 'form'])->name('emergencia.contacto.form');
    Route::post('/emergencia/contacto', [\App\Http\Controllers\EmergencyContactController::class, 'store'])->name('emergencia.contacto.store');


    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
});


Route::middleware('auth')->group(function () {
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');

    Route::post('/medications', [MedicationController::class, 'store'])->name('medications.store');

    Route::resource('medications', MedicationController::class);

    
});



 // **Ruta IA**

//  Route::post('/ai-chat', function (Request $request) {
//     $prompt = $request->input('prompt');

//     $response = Http::timeout(60)->post('http://127.0.0.1:11434/api/generate', [
//         'model' => 'llama3.2:1b',
//         'prompt' => $prompt,
//         'stream' => false,
//     ]);

//     return $response->json();
// })->name('ai-chat');

Route::post('/ai-chat', function (\Illuminate\Http\Request $request) {
    try {
        $prompt = $request->input('prompt', '');
        $userId = \Illuminate\Support\Facades\Auth::id();

        // Si no hay usuario autenticado, manejamos el caso
        if (!$userId) {
            return response()->json([
                'response' => "⚠️ No hay un usuario autenticado. Por favor inicia sesión."
            ]);
        }

        // Traer citas médicas del usuario
        $appointments = \App\Models\Appointment::where('user_id', $userId)
            ->get(['date', 'time', 'notes'])
            ->toArray();

        $context = "El usuario tiene las siguientes citas médicas:\n";
        if (count($appointments) > 0) {
            foreach ($appointments as $cita) {
                $context .= "- {$cita['date']} a las {$cita['time']}: {$cita['notes']}\n";
            }
        } else {
            $context .= "- No tiene citas registradas actualmente.\n";
        }

        // Enviar prompt a Ollama
        $response = \Illuminate\Support\Facades\Http::timeout(120)->post(
            'http://localhost:11434/api/generate',
            [
                'model' => 'llama3.2:3b',
                'prompt' => $context . "\n\nPregunta del usuario: " . $prompt . "\nAsistente:",
                'stream' => false,
            ]
        );

        if ($response->failed()) {
            return response()->json([
                'error' => 'Fallo al contactar la IA',
                'details' => $response->body(),
            ], 500);
        }

        // Extraer solo la respuesta de Ollama
        $data = $response->json();
        return response()->json([
            'response' => $data['response'] ?? '⚠️ La IA no devolvió respuesta.',
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'line'  => $e->getLine(),
            'file'  => $e->getFile(),
        ], 500);
    }
})->name('ai-chat');



require __DIR__.'/auth.php';
