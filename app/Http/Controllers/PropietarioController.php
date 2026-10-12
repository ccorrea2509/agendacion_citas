<?php

namespace App\Http\Controllers;

use App\Models\Propietario;
use App\Models\User;
use App\Models\Vehiculo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $users = User::query()
            ->where('rol', 'Cliente')
            ->whereDoesntHave('propietario')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return Inertia::render('dashboard/propietarios/create', [
            'users' => $users,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where('rol', 'Cliente'),
                Rule::unique('propietarios', 'user_id'),
            ],
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
                Rule::unique('users', 'email')->ignore($request->input('user_id')),
            ],
            'direccion' => ['required', 'string', 'max:255'],
            'placa' => ['required', 'string', 'max:255', 'unique:vehiculos,placa'],
            'linea' => ['required', 'string', 'max:255'],
            'marca' => ['required', 'string', 'max:255'],
            'modelo' => ['required', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::findOrFail($validated['user_id']);

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

            $user->name = trim($validated['nombre'].' '.$validated['apellido']);
            $user->email = $validated['email'];
            $user->save();
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
