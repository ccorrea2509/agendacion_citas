<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ServicioController extends Controller
{
    public function index()
    {
        $servicios = DB::table('servicios')->get();
        return Inertia::render('dashboard/Servicios/Index', [
            'servicios' => $servicios
        ]);
    }

    public function create()
    {
        return Inertia::render('dashboard/Servicios/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'duracion_minutos' => 'required|integer|min:1',
        ]);

        DB::table('servicios')->insert([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'duracion_minutos' => $request->duracion_minutos,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('Servicios.index')->with('success', 'Servicio creado correctamente.');
    }

    public function edit($id)
    {
        $servicio = DB::table('servicios')->where('id', $id)->first();
        return Inertia::render('dashboard/Servicios/Edit', [
            'servicio' => $servicio
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'duracion_minutos' => 'required|integer|min:1',
        ]);

        DB::table('servicios')->where('id', $id)->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'duracion_minutos' => $request->duracion_minutos,
            'updated_at' => now(),
        ]);

        return redirect()->route('Servicios.index')->with('success', 'Servicio actualizado.');
    }

    public function destroy($id)
    {
        DB::table('servicios')->where('id', $id)->delete();
        
        return redirect()->route('Servicios.index')->with('success', 'Servicio eliminado.');
    }
}
