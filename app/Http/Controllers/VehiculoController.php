<?php

namespace App\Http\Controllers;

use App\Models\Propietario;
use App\Models\Vehiculo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class VehiculoController extends Controller
{
    public function index(): Response
    {
        $vehiculos = Vehiculo::query()
            ->with('propietario:id,nombre,apellido,documento')
            ->latest()
            ->get()
            ->map(fn (Vehiculo $vehiculo) => [
                'id' => $vehiculo->id,
                'placa' => $vehiculo->placa,
                'linea' => $vehiculo->linea,
                'marca' => $vehiculo->marca,
                'modelo' => $vehiculo->modelo,
                'propietario_id' => $vehiculo->propietario?->id,
                'propietario_nombre' => trim(($vehiculo->propietario?->nombre ?? '').' '.($vehiculo->propietario?->apellido ?? '')),
                'propietario_documento' => $vehiculo->propietario?->documento,
            ]);

        return Inertia::render('dashboard/vehiculos/index', [
            'vehiculos' => $vehiculos,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('dashboard/vehiculos/create', [
            'propietarios' => $this->propietariosDisponibles(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'placa' => ['required', 'string', 'max:255', 'unique:vehiculos,placa'],
            'linea' => ['required', 'string', 'max:255'],
            'marca' => ['required', 'string', 'max:255'],
            'modelo' => ['required', 'string', 'max:255'],
            'id_propietario' => ['required', 'integer', Rule::exists('propietarios', 'id')],
        ]);

        $vehiculo = new Vehiculo();
        $vehiculo->placa = $validated['placa'];
        $vehiculo->linea = $validated['linea'];
        $vehiculo->marca = $validated['marca'];
        $vehiculo->modelo = $validated['modelo'];
        $vehiculo->id_propietario = $validated['id_propietario'];
        $vehiculo->save();

        return to_route('vehiculos.index');
    }

    public function edit(Vehiculo $vehiculo): Response
    {
        return Inertia::render('dashboard/vehiculos/edit', [
            'vehiculo' => $vehiculo,
            'propietarios' => $this->propietariosDisponibles(),
        ]);
    }

    public function update(Request $request, Vehiculo $vehiculo): RedirectResponse
    {
        $validated = $request->validate([
            'placa' => ['required', 'string', 'max:255', Rule::unique('vehiculos', 'placa')->ignore($vehiculo->id)],
            'linea' => ['required', 'string', 'max:255'],
            'marca' => ['required', 'string', 'max:255'],
            'modelo' => ['required', 'string', 'max:255'],
            'id_propietario' => ['required', 'integer', Rule::exists('propietarios', 'id')],
        ]);

        $vehiculo->placa = $validated['placa'];
        $vehiculo->linea = $validated['linea'];
        $vehiculo->marca = $validated['marca'];
        $vehiculo->modelo = $validated['modelo'];
        $vehiculo->id_propietario = $validated['id_propietario'];
        $vehiculo->save();

        return to_route('vehiculos.index');
    }

    public function destroy(Vehiculo $vehiculo): RedirectResponse
    {
        $vehiculo->delete();

        return to_route('vehiculos.index');
    }

    private function propietariosDisponibles()
    {
        return Propietario::query()
            ->select('id', 'nombre', 'apellido', 'documento')
            ->orderBy('apellido')
            ->orderBy('nombre')
            ->get();
    }
}
