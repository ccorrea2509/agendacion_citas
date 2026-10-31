<?php

namespace App\Http\Controllers;

use App\Models\PuestoTrabajo;
use App\Models\Personal;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PuestoTrabajoController extends Controller
{
    public function index()
    {
        $puestos_trabajo = PuestoTrabajo::with('tecnico')->get();
        return Inertia::render('dashboard/puesto_trabajo/index', compact('puestos_trabajo'));
    }

    public function create()
    {
        $personals = Personal::all();
        $servicios = Servicio::all(); // Carga los servicios disponibles para las casillas
        return Inertia::render('dashboard/puesto_trabajo/create', compact('personals', 'servicios'));
    }

    public function store(Request $request)
    {
        $puesto = new PuestoTrabajo();
        $puesto->numero_puesto = $request->numero_puesto;
        $puesto->id_tecnico = !empty($request->tecnico) ? $request->tecnico : null;

        // Lógica híbrida: Si marcan checkboxes guardamos JSON, si escriben texto manual guardamos el texto
        if (!empty($request->servicios) && is_array($request->servicios)) {
            $puesto->id_servicios = json_encode(array_map('strval', $request->servicios));
        } elseif (!empty($request->servicio_manual)) {
            $puesto->id_servicios = $request->servicio_manual;
        } else {
            $puesto->id_servicios = null;
        }

        $puesto->save();

        return redirect()->route('PuestoTrabajo.index');
    }

    public function show($puesto_trabajo)
    {
        $puestoTrabajo = PuestoTrabajo::with('tecnico')->findOrFail($puesto_trabajo);

        $serviciosAsignados = collect();
        $serviciosIds = json_decode($puestoTrabajo->id_servicios, true);

        if (is_array($serviciosIds) && !empty($serviciosIds)) {
            $serviciosAsignados = Servicio::whereIn('id', $serviciosIds)->get();
        } elseif (!empty($puestoTrabajo->id_servicios)) {
            // Si está guardado como texto libre, lo adaptamos limpiamente para la tabla
            $serviciosAsignados = collect([
                (object) [
                    'id' => 'Custom',
                    'nombre' => $puestoTrabajo->id_servicios,
                    'descripcion' => 'Servicio registrado manualmente',
                    'precio' => 'N/A',
                    'duracion_minutos' => 'N/A'
                ]
            ]);
        }

        return Inertia::render('dashboard/puesto_trabajo/show', [
            'puesto_trabajo' => $puestoTrabajo,
            'serviciosAsignados' => $serviciosAsignados,
        ]);
    }

    public function edit($puesto_trabajo)
    {
        $puestos_trabajo = PuestoTrabajo::findOrFail($puesto_trabajo);
        $personals = Personal::all();
        $servicios = Servicio::all();

        return Inertia::render('dashboard/puesto_trabajo/edit', compact('puestos_trabajo', 'personals', 'servicios'));
    }

    public function update(Request $request, $puesto_trabajo)
    {
        $puesto = PuestoTrabajo::findOrFail($puesto_trabajo);
        $puesto->numero_puesto = $request->numero_puesto;
        $puesto->id_tecnico = !empty($request->tecnico) ? $request->tecnico : null;

        if (!empty($request->servicios) && is_array($request->servicios)) {
            $puesto->id_servicios = json_encode(array_map('strval', $request->servicios));
        } elseif (!empty($request->servicio_manual)) {
            $puesto->id_servicios = $request->servicio_manual;
        } else {
            $puesto->id_servicios = null;
        }

        $puesto->save();

        return redirect()->route('PuestoTrabajo.index');
    }

    public function destroy($puesto_trabajo)
    {
        $puesto = PuestoTrabajo::findOrFail($puesto_trabajo);
        $puesto->delete();

        return redirect()->route('PuestoTrabajo.index');
    }
}