<?php

namespace App\Http\Controllers;

use App\Models\AsignacionCita;
use App\Models\Procedimiento;
use App\Models\Servicio;
use App\Models\Vehiculo;
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
        
        $citas = AsignacionCita::with('vehiculo', 'usuario_creador')->get();
        
        return Inertia::render('dashboard/agendacion_cita/index', compact('citas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $servicios=Servicio::all();
        $vehiculos = Vehiculo::all();
        return Inertia::render('dashboard/agendacion_cita/create', compact('servicios', 'vehiculos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd(auth()->user()->name);
        //dd($request->all());
        $request->validate([
            'fecha' => 'required|date',
            'hora' => 'required|date_format:H:i',
            'puesto_trabajo' => 'required|string|max:255',
            'servicios' => 'required|array|min:1',
            'servicios.*' => 'exists:servicios,id',
        ]);

        $cita = new AsignacionCita();
        $cita->cita_fecha = $request->fecha . ' ' . $request->hora;
        $cita->id_vehiculo = 1; // Asignar un ID de vehículo válido según tu lógica
        $cita->id_user = auth()->user()->id; // Asignar el ID del usuario autenticado
        $cita->tiempo_aproximado = $request->tiempo; // Asignar un tiempo aproximado según tu lógica
        $cita->save();
        
        foreach ($request->servicios as $servicioId) {
            
            $procedimiento=new Procedimiento();
            $procedimiento->id_cita=$cita->id;
            $procedimiento->id_puesto_trabajo=$request->puesto_trabajo;
            $procedimiento->id_personal=1; // Asignar un ID de personal válido según tu lógica
            $procedimiento->id_user=auth()->user()->id; // Asignar el ID del usuario autenticado
            $procedimiento->precio=0; // Asignar un precio según tu lógica
            $procedimiento->id_servicio=$servicioId;
            $procedimiento->save();
        }
        
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
