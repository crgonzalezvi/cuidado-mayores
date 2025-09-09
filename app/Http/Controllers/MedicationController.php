<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MedicationController extends Controller
{
    /**
     * Listar medicamentos del usuario autenticado
     */
    public function index()
    {
        $medications = Medication::where('user_id', Auth::id())->get();
        return response()->json($medications);
    }

    /**
     * Guardar un nuevo medicamento
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'dosage'    => 'required|string|max:255',
            'frequency' => 'required|string|max:255',
            'time'      => 'required',
            'notes'     => 'nullable|string',
        ]);

        // Normalizar la hora
        try {
            if (preg_match('/^\d{2}:\d{2}$/', $request->time)) {
                $time = Carbon::createFromFormat('H:i', $request->time)->format('H:i:s');
            } elseif (preg_match('/^\d{2}:\d{2}:\d{2}$/', $request->time)) {
                $time = $request->time;
            } elseif (preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/', $request->time)) {
                $time = Carbon::createFromFormat('Y-m-d\TH:i', $request->time)->format('H:i:s');
            } else {
                return response()->json(['error' => 'Formato de hora no válido'], 422);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error procesando la hora: '.$e->getMessage()], 500);
        }

        $medication = Medication::create([
            'user_id'   => Auth::id(),
            'name'      => $request->name,
            'dosage'    => $request->dosage,
            'frequency' => $request->frequency,
            'time'      => $time,
            'notes'     => $request->notes,
        ]);

        return response()->json($medication);
    }

    public function show(string $id) { /* ... */ }
    public function edit(string $id) { /* ... */ }
    public function update(Request $request, string $id) { /* ... */ }
    public function destroy(string $id) { /* ... */ }
}
