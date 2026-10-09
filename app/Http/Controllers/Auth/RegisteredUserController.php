<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Show the registration page.
     */
    public function create(): Response
    {
        return Inertia::render('auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'tipo_documento' => ['required', 'string', 'max:255'],
            'documento' => ['required', 'string', 'max:255', 'unique:propietarios,documento'],
            'nombre' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'telefono' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class, 'unique:propietarios,email'],
            'direccion' => ['required', 'string', 'max:255'],
            'placa' => ['required', 'string', 'max:255', 'unique:vehiculos,placa'],
            'linea' => ['required', 'string', 'max:255'],
            'marca' => ['required', 'string', 'max:255'],
            'modelo' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => trim($request->nombre.' '.$request->apellido),
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'rol' => 'Cliente',
            ]);

            $propietario = $user->propietario()->create([
                'tipo_documento' => $request->tipo_documento,
                'documento' => $request->documento,
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'telefono' => $request->telefono,
                'email' => $request->email,
                'direccion' => $request->direccion,
            ]);

            $propietario->vehiculos()->create([
                'placa' => $request->placa,
                'linea' => $request->linea,
                'marca' => $request->marca,
                'modelo' => $request->modelo,
            ]);

            return $user;
        });

        event(new Registered($user));

        Auth::login($user);

        return to_route('dashboard');
    }
}
