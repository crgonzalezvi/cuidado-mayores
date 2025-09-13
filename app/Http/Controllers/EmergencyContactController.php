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

    if (!$contact) {
        return redirect()->route('dashboard')->with('showEmergencyForm', true);
    }

    // Si el usuario acaba de registrarlo, no enviar alerta
    if (session('just_registered')) {
        return back()->with('success', 'Contacto de emergencia listo.');
    }

    if ($contact->email) {
         Mail::raw("🚨 Emergencia: El usuario {$user->name} ({$contact->relationship}) ha presionado el botón de emergencia.",
             function ($message) use ($contact) {
                 $message->to($contact->email)
                         ->subject('🚨 Alerta de Emergencia');
             });
    }

    if ($contact->phone) {
        return redirect("tel:{$contact->phone}");
    }

    return back()->with('success', 'Se ha enviado la alerta de emergencia.');
}

public function sendAlert()
{
    $user = Auth::user();
    $contacts = EmergencyContact::where('user_id', $user->id)->get();

    if ($contacts->isEmpty()) {
        return back()->with('error', '⚠️ No tienes contactos de emergencia registrados.');
    }

    foreach ($contacts as $contact) {
        if ($contact->email) {
            Mail::raw(
                "🚨 Emergencia: El usuario {$user->name} ({$contact->relationship}) ha presionado el botón de emergencia.",
                function ($message) use ($contact) {
                    $message->to($contact->email)
                            ->subject('🚨 Alerta de Emergencia');
                }
            );
        }
    }

    return back()->with('success', '🚨 Se ha enviado una alerta a tus contactos de emergencia.');
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

    // Guardamos mensaje de éxito
    session()->flash('success', 'Contacto de emergencia creado correctamente.');

    // Redirigir al dashboard
    return redirect()->route('dashboard');
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
