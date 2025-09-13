<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\EmergencyContactController;
use App\Http\Controllers\MedicationController;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Medication;
use App\Models\EmergencyContact;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmergenciaMail;




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
    return redirect()->route('login');
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

// **Ruta Emergencia EMAIL**
Route::get('/emergencia', function () {
    $user = Auth::user();

    if (!$user) {
        return redirect()->back()->with('error', '⚠️ Debes iniciar sesión.');
    }

    // Buscar contacto de emergencia del usuario
    $contact = EmergencyContact::where('user_id', $user->id)->first();

    if (!$contact) {
        return redirect()->back()->with('error', '⚠️ No tienes un contacto de emergencia configurado.');
    }

    // Enviar correo al contacto
    Mail::to($contact->email)->send(new EmergenciaMail($user));

    return redirect()->back()->with('success', '🚨 Se envió la alerta al contacto de emergencia: ' . $contact->name);
})->name('emergencia');



 // **Ruta IA**

//  Route::post('/ai-chat', function (Request $request) {
//     $prompt = $request->input('prompt');

//     $response = Http::timeout(60)->post('http://127.0.0.1:11434/api/generate', [
//         'model' => 'llama3.2:1b',
//         'prompt' => $prompt,
//         'stream' => false,
//     ]);

//     return $response->json();
// })->name('ai-chat

Route::get('/emergencia', [EmergencyContactController::class, 'sendAlert'])
    ->name('emergencia');

Route::post('/ai-chat', function (Request $request) {
    try {
        $prompt = $request->input('prompt', '');
        $userId = \Illuminate\Support\Facades\Auth::id();

        if (!$userId) {
            return response()->json([
                'response' => "⚠️ No hay un usuario autenticado. Por favor inicia sesión."
            ]);
        }

        // Traer citas médicas (máximo 3)
        $appointments = \App\Models\Appointment::where('user_id', $userId)
            ->orderBy('date')
            ->limit(3)
            ->get(['date', 'time', 'title', 'location', 'notes'])
            ->toArray();

        // Traer medicamentos (máximo 3)
        $medications = \App\Models\Medication::where('user_id', $userId)
            ->limit(3)
            ->get(['name', 'dosage', 'frequency', 'time'])
            ->toArray();

        // Construir contexto resumido
        $context = "Información del usuario:\n\n";

        // 📅 Formato de citas en bloques claros
        $context .= "📅 Citas médicas:\n";
        if ($appointments) {
            foreach ($appointments as $cita) {
                $context .= "{$cita['title']}\n";
                $context .= "{$cita['date']} {$cita['time']} - {$cita['location']}\n";
                $context .= strtoupper($cita['notes'] ?? '') . "\n\n";
            }
        } else {
            $context .= "- No tiene citas registradas.\n";
        }

        // 💊 Medicamentos
        $context .= "\n💊 Medicamentos:\n";
        if ($medications) {
            foreach ($medications as $med) {
                $context .= "- {$med['name']} ({$med['dosage']}), {$med['frequency']} a las {$med['time']}\n";
            }
        } else {
            $context .= "- No tiene medicamentos registrados.\n";
        }

        // Prompt corto y claro
        $promptFinal = $context . "\n\nPregunta del usuario: $prompt\n\nResponde en frases simples, claras y amables, como si hablaras con un adulto mayor.";

        // Llamada a Gemma
        $response = Http::timeout(180)->post('http://localhost:11434/api/generate', [
            'model' => 'gemma:2b',
            'prompt' => $promptFinal,
            'stream' => false,
        ]);

        if ($response->failed()) {
            return response()->json([
                'error' => 'Fallo al contactar la IA',
                'details' => $response->body(),
            ], 500);
        }

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
