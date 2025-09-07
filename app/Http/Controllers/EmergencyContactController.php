<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmergencyContact;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class EmergencyContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $contact = EmergencyContact::where('user_id', $user->id)->first();

        // Si no hay contacto, redirige al formulario
        if (!$contact) {
            return redirect()->route('emergencia.contacto.form');
        }

        // Enviar correo si existe email
        if ($contact->email) {
             Mail::raw("🚨 Emergencia: El usuario {$user->name} ({$contact->relationship}) ha presionado el botón de emergencia.",
                 function ($message) use ($contact) {
                     $message->to($contact->email)
                             ->subject('🚨 Alerta de Emergencia');
                 });
     }

        // Si hay teléfono, intenta abrir la app de llamadas
        if ($contact->phone) {
            return redirect("tel:{$contact->phone}");
        }

        return back()->with('success', 'Se ha enviado la alerta de emergencia.');
    }

     public function form()
    {
        return view('emergency.form');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'relationship'=> 'required|string|max:100',
            'phone'       => 'nullable|string|max:20',
            'email'       => 'nullable|email',
        ]);

        EmergencyContact::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'name'        => $request->name,
                'relationship'=> $request->relationship,
                'phone'       => $request->phone,
                'email'       => $request->email,
            ]
        );

        return redirect()->route('emergencia')->with('success', 'Contacto de emergencia guardado.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
