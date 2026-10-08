<?php

namespace App\Http\Controllers;

use App\Models\Personal;
use Illuminate\Http\Request;

use Inertia\Inertia;

class PersonalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $personals = Personal::all();
        return Inertia::render('dashboard/personal/index', compact('personals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('dashboard/personal/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'email' => 'required|email|unique:personals,email',
            'direccion' => 'required|string|max:255',
            'especialidad' => 'required|string',
            'horario_inicio' => 'required|date_format:H:i',
            'horario_fin' => 'required|date_format:H:i|after:horario_inicio',
        ]); 
        
        $personal = Personal::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'direccion' => $request->direccion,
            'especialidad' => $request->especialidad,
            'horario_inicio' => $request->horario_inicio,
            'horario_fin' => $request->horario_fin
        ]);
        /*$personal = new Personal();
        $personal->nombre = $request->nombre;
        $personal->apellido = $request->apellido;
        $personal->telefono = $request->telefono;
        $personal->email = $request->email;
        $personal->direccion = $request->direccion;
        $personal->especialidad = $request->especialidad;
        $personal->horario_inicio = $request->horario_inicio;
        $personal->horario_fin = $request->horario_fin;
        $personal->save();*/

        return redirect()->route('Personal.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Personal $personal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($personal)
    {
        $personal = Personal::find($personal);
        /*return Inertia::render('dashboard/personal/edit', [
            'personal' => $personal
        ]);*/

        return Inertia::render('dashboard/personal/edit', compact('personal'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $personal)
    {
        $personal = Personal::find($personal);
        $personal->nombre = $request->nombre;
        $personal->apellido = $request->apellido;
        $personal->telefono = $request->telefono;
        $personal->email = $request->email;
        $personal->direccion = $request->direccion;
        $personal->especialidad = $request->especialidad;
        $personal->horario_inicio = $request->horario_inicio;
        $personal->horario_fin = $request->horario_fin;
        $personal->save();

        return redirect()->route('Personal.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Personal $personal)
    {
        dd($personal);
    }
}
