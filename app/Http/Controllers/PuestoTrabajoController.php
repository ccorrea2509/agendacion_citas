<?php

namespace App\Http\Controllers;

use App\Models\PuestoTrabajo;
use Illuminate\Http\Request;
use App\Models\Personal;
use Inertia\Inertia;

class PuestoTrabajoController extends Controller
{
    public function index()
    {

        
        $puestos_trabajo = PuestoTrabajo::with('tecnico')->get();
        //dd($puestos_trabajo->toArray());
        return Inertia::render('dashboard/puesto_trabajo/index', compact('puestos_trabajo'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $personals = Personal::all();
        
        return Inertia::render('dashboard/puesto_trabajo/create', compact('personals'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $PuestoTrabajo = new PuestoTrabajo();

        $PuestoTrabajo->numero_puesto = $request->numero_puesto;
        $PuestoTrabajo->id_servicios = $request->servicios;
        $PuestoTrabajo->id_tecnico = $request->tecnico;

        $PuestoTrabajo->save();

        return redirect()->route('PuestoTrabajo.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(PuestoTrabajo $PuestoTrabajo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($puesto_trabajo)
    {
         $puestos_trabajo = PuestoTrabajo::find($puesto_trabajo);

        $personals = Personal::all();

        return Inertia::render('dashboard/puesto_trabajo/edit', compact('puestos_trabajo', 'personals'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $puesto_trabajo)
    {
        $PuestoTrabajo = PuestoTrabajo::find($puesto_trabajo);
        $PuestoTrabajo->numero_puesto = $request->numero_puesto;
        $PuestoTrabajo->id_servicios = $request->id_servicios;
        $PuestoTrabajo->id_tecnico = $request->id_tecnico;

        $PuestoTrabajo->save();

        return redirect()->route('PuestoTrabajo.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Personal $personal)
    {
        dd($personal);
    }
}
