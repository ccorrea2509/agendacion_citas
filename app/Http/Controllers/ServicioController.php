<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ServicioController extends Controller
{
    // Catálogo de Servicios
    public function index()
    {
        $servicios = DB::table('servicios')->get();
        return Inertia::render('dashboard/servicios/Index', [
            'servicios' => $servicios
        ]);
    }

    // Mostrar Formulario de Registro
    public function create()
    {
        return Inertia::render('dashboard/servicios/Create');
    }

    // Guardar en la Base de Datos
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

        return redirect()->route('servicios.index')->with('success', 'Servicio creado correctamente.');
    }

    // Mostrar Formulario de Edición
    public function edit($id)
    {
        $servicio = DB::table('servicios')->where('id', $id)->first();
        return Inertia::render('dashboard/servicios/Edit', [
            'servicio' => $servicio
        ]);
    }

    // Actualizar Registro
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

        return redirect()->route('servicios.index')->with('success', 'Servicio actualizado.');
    }

    // Eliminar Registro
    public function destroy($id)
    {
        DB::table('servicios')->where('id', $id)->delete();
        return redirect()->route('servicios.index')->with('success', 'Servicio eliminado.');
    }
}
