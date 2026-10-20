<?php

namespace App\Http\Controllers;

use App\Models\AsignacionCita;
use App\Models\Servicio;
use App\Models\PuestoTrabajo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AsignacionCitaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function disponibles(Request $request)
    {
        
        $request->validate([
            'fecha' => ['required', 'date_format:Y-m-d'],
        ]);
        $fecha = $request->fecha;
        $citas = AsignacionCita::all();
        $puestosDisponibles = PuestoTrabajo::all();
        //dd($puestosDisponibles);
        return response()->json([
            'citas' => $citas,
            'fecha' => $fecha,
            'puestosDisponibles' => $puestosDisponibles,
        ]);
        //return Inertia::render('dashboard/agendacion_cita/disponibles', compact('citas','fecha','puestosDisponibles'));
    }
    public function index()
    {
        
        $citas = AsignacionCita::all();
        
        return Inertia::render('dashboard/agendacion_cita/index', compact('citas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $servicios=Servicio::all();
        return Inertia::render('dashboard/agendacion_cita/create', compact('servicios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        dd($request->all());
        $validated = $request->validate([
            'fecha' => 'required|date',
            'hora' => 'required|date_format:H:i',
            'puesto_trabajo' => 'required|string|max:255',
            'servicios' => 'required|array|min:1',
            'servicios.*' => 'exists:servicios,id',
        ]);

        $cita = AsignacionCita::create([
            'fecha' => $request->fecha,
            'hora' => $request->hora,
            'puesto_trabajo' => $request->puesto_trabajo,
        ]);

        // Attach selected services to the appointment
        $cita->servicios()->attach($request->servicios);

        return redirect()->route('AsignacionCita.index')->with('success', 'Cita creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AsignacionCita $asignacionCita)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AsignacionCita $asignacionCita)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AsignacionCita $asignacionCita)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AsignacionCita $asignacionCita)
    {
        //
    }
}
