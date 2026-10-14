<?php

namespace App\Http\Controllers;

use App\Models\Personal;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
        
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'email' => ['required', 'email', 'unique:personals,email', 'unique:users,email'],
            'direccion' => 'required|string|max:255',
            'especialidad' => 'required|string',
            'horario_inicio' => 'required|date_format:H:i',
            'horario_fin' => 'required|date_format:H:i|after:horario_inicio',
        ]); 
        
        DB::transaction(function () use ($validated) {
            $personal = Personal::create($validated);

            $user = new User();
            $user->name = trim($validated['nombre'].' '.$validated['apellido']);
            $user->email = $validated['email'];
            $user->password = Hash::make($validated['telefono']);
            $user->rol = 'Tecnico';
            $user->id_tecnico = $personal->id;
            $user->save();
        });
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
        $personal = Personal::findOrFail($personal);
        $user = User::query()->where('id_tecnico', $personal->id)->first();

        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'telefono' => ['required', 'string', 'max:20'],
            'email' => [
                'required',
                'email',
                Rule::unique('personals', 'email')->ignore($personal->id),
                Rule::unique('users', 'email')->ignore($user?->id),
            ],
            'direccion' => ['required', 'string', 'max:255'],
            'especialidad' => ['required', 'string'],
            'horario_inicio' => ['required', 'date_format:H:i'],
            'horario_fin' => ['required', 'date_format:H:i', 'after:horario_inicio'],
        ]);

        DB::transaction(function () use ($personal, $user, $validated) {
            $personal->nombre = $validated['nombre'];
            $personal->apellido = $validated['apellido'];
            $personal->telefono = $validated['telefono'];
            $personal->email = $validated['email'];
            $personal->direccion = $validated['direccion'];
            $personal->especialidad = $validated['especialidad'];
            $personal->horario_inicio = $validated['horario_inicio'];
            $personal->horario_fin = $validated['horario_fin'];
            $personal->save();

            $user ??= new User();
            $user->name = trim($validated['nombre'].' '.$validated['apellido']);
            $user->email = $validated['email'];
            $user->rol = 'Tecnico';
            $user->id_tecnico = $personal->id;
            if (! $user->exists) {
                $user->password = Hash::make($validated['telefono']);
            }
            $user->save();
        });

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
