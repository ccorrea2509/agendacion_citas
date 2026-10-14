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
        // La tabla en XAMPP se sigue llamando 'servicios' en minúscula
        $servicios = DB::table('servicios')->get(); 
        
        // Aquí SÍ usas la S mayúscula porque apunta a tu carpeta física en el disco duro
        return Inertia::render('dashboard/Servicios/Index', [
            'servicios' => $servicios
        ]);
    }

    // Mostrar Formulario de Registro
    public function create()
    {
        // Apuntamos a la carpeta física con la S mayúscula
        return Inertia::render('dashboard/Servicios/Create');
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

        // CORRECCIÓN: La ruta interna de Laravel de regreso DEBE ser en minúscula obligatoriamente
        return redirect()->route('servicios.index')->with('success', 'Servicio creado correctamente.');
    }

    // Mostrar Formulario de Edición
    public function edit($id)
    {
        // Tabla en minúscula ('servicios') e Inertia apuntando a tu carpeta en mayúscula ('Servicios/Edit')
        $servicio = DB::table('servicios')->where('id', $id)->first();
        return Inertia::render('dashboard/Servicios/Edit', [
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

        // CORRECCIÓN: Ruta de redirección interna en minúscula
        return redirect()->route('servicios.index')->with('success', 'Servicio actualizado.');
    }

    // Eliminar Registro
    public function destroy($id)
    {
        DB::table('servicios')->where('id', $id)->delete();
        
        // CORRECCIÓN: Ruta de redirección interna en minúscula
        return redirect()->route('servicios.index')->with('success', 'Servicio eliminado.');
    }
}