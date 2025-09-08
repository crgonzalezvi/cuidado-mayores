<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    // Retorna las citas del usuario
    public function index(Request $request)
    {
        $appointments = Appointment::where('user_id', Auth::id())
                                   ->orderBy('time', 'asc')
                                   ->get();

        return response()->json($appointments); // Devuelve JSON para AJAX
    }

    // Crear nueva cita
    public function store(Request $request)
{
    $request->validate([
        'title'    => 'required|string|max:255',
        'date'     => 'required|date',
        'time'     => 'required',
        'location' => 'nullable|string|max:255',
        'notes'    => 'nullable|string|max:500',
    ]);

    $appointment = Appointment::create([
        'user_id'  => Auth::id(),
        'title'    => $request->title,
        'date'     => $request->date,
        'time'     => $request->time,
        'location' => $request->location,
        'notes'    => $request->notes,
    ]);

    return response()->json($appointment);
}

}
