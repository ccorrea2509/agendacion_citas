<?php

namespace App\Http\Controllers;

use App\Models\Propietario;
use App\Models\User;
use App\Models\Vehiculo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class PropietarioController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('dashboard/propietarios/index', [
            'propietarios' => Propietario::query()->orderBy('apellido')->orderBy('nombre')->get(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('dashboard/propietarios/create');
    }

    public function show(Propietario $propietario): Response
    {
        return Inertia::render('dashboard/propietarios/show', [
            'propietario' => $propietario->load([
                'vehiculos' => fn ($query) => $query->latest(),
            ]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tipo_documento' => ['required', 'string', 'max:255'],
            'documento' => ['required', 'string', 'max:255', 'unique:propietarios,documento'],
            'nombre' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'telefono' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:propietarios,email',
                'unique:users,email',
            ],
            'direccion' => ['required', 'string', 'max:255'],
            'placa' => ['required', 'string', 'max:255', 'unique:vehiculos,placa'],
            'linea' => ['required', 'string', 'max:255'],
            'marca' => ['required', 'string', 'max:255'],
            'modelo' => ['required', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($validated) {
            $user = new User();
            $user->name = trim($validated['nombre'].' '.$validated['apellido']);
            $user->email = $validated['email'];
            $user->password = Hash::make($validated['documento']);
            $user->rol = 'Cliente';
            $user->save();

            $propietario = new Propietario();
            $propietario->user_id = $user->id;
            $propietario->tipo_documento = $validated['tipo_documento'];
            $propietario->documento = $validated['documento'];
            $propietario->nombre = $validated['nombre'];
            $propietario->apellido = $validated['apellido'];
            $propietario->telefono = $validated['telefono'];
            $propietario->email = $validated['email'];
            $propietario->direccion = $validated['direccion'];
            $propietario->save();

            $vehiculo = new Vehiculo();
            $vehiculo->placa = $validated['placa'];
            $vehiculo->linea = $validated['linea'];
            $vehiculo->marca = $validated['marca'];
            $vehiculo->modelo = $validated['modelo'];
            $vehiculo->id_propietario = $propietario->id;
            $vehiculo->save();

        });

        return to_route('propietarios.index');
    }

    public function edit(Propietario $propietario): Response
    {
        return Inertia::render('dashboard/propietarios/edit', [
            'propietario' => $propietario->load('vehiculos'),
        ]);
    }

    public function update(Request $request, $propietario): RedirectResponse
    {
        $propietario = Propietario::findOrFail($propietario);

        $placaRule = Rule::unique('vehiculos', 'placa');
        if ($request->filled('vehiculo_id')) {
            $placaRule->ignore($request->input('vehiculo_id'));
        }

        $validated = $request->validate([
            'tipo_documento' => ['required', 'string', 'max:255'],
            'documento' => ['required', 'string', 'max:255', Rule::unique('propietarios', 'documento')->ignore($propietario->id)],
            'nombre' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'telefono' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique('propietarios', 'email')->ignore($propietario->id),
                Rule::unique('users', 'email')->ignore($propietario->user_id),
            ],
            'direccion' => ['required', 'string', 'max:255'],
            'vehiculo_id' => [
                'nullable',
                'integer',
                Rule::exists('vehiculos', 'id')->where('id_propietario', $propietario->id),
            ],
            'placa' => ['required', 'string', 'max:255', $placaRule],
            'linea' => ['required', 'string', 'max:255'],
            'marca' => ['required', 'string', 'max:255'],
            'modelo' => ['required', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($validated, $propietario) {
            $propietario->tipo_documento = $validated['tipo_documento'];
            $propietario->documento = $validated['documento'];
            $propietario->nombre = $validated['nombre'];
            $propietario->apellido = $validated['apellido'];
            $propietario->telefono = $validated['telefono'];
            $propietario->email = $validated['email'];
            $propietario->direccion = $validated['direccion'];
            $propietario->save();

            $user = User::find($propietario->user_id);
            if ($user) {
                $user->name = trim($validated['nombre'].' '.$validated['apellido']);
                $user->email = $validated['email'];
                $user->save();
            }

            $vehiculo = ($validated['vehiculo_id'] ?? null)
                ? Vehiculo::query()->where('id_propietario', $propietario->id)->findOrFail($validated['vehiculo_id'])
                : new Vehiculo();

            $vehiculo->placa = $validated['placa'];
            $vehiculo->linea = $validated['linea'];
            $vehiculo->marca = $validated['marca'];
            $vehiculo->modelo = $validated['modelo'];
            $vehiculo->id_propietario = $propietario->id;
            $vehiculo->save();
        });

        return to_route('propietarios.index');
    }

    public function destroy($propietario): RedirectResponse
    {
        $propietario = Propietario::findOrFail($propietario);
        $user = User::find($propietario->user_id);

        DB::transaction(function () use ($propietario, $user) {
            $propietario->delete();
            $user?->delete();
        });

        return to_route('propietarios.index');
    }
}
