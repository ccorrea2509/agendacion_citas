<?php

namespace Tests\Feature;

use App\Models\Propietario;
use App\Models\User;
use App\Models\Vehiculo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropietarioTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_manage_propietarios(): void
    {
        $admin = User::factory()->create([
            'rol' => 'Administrador',
        ]);
        $cliente = User::factory()->create([
            'rol' => 'Cliente',
        ]);
        $this->actingAs($admin);

        $this->get(route('propietarios.index'))->assertOk();
        $this->get(route('propietarios.create'))->assertOk();

        $this->post(route('propietarios.store'), [
            'user_id' => $cliente->id,
            'tipo_documento' => 'CC',
            'documento' => '123456789',
            'nombre' => 'Ana',
            'apellido' => 'García',
            'telefono' => '3001234567',
            'email' => $cliente->email,
            'direccion' => 'Calle 1 # 2-3',
            'placa' => 'ABC123',
            'linea' => 'Corolla',
            'marca' => 'Toyota',
            'modelo' => '2024',
        ])->assertRedirect(route('propietarios.index'));

        $propietario = Propietario::where('user_id', $cliente->id)->firstOrFail();
        $vehiculo = Vehiculo::where('id_propietario', $propietario->id)->firstOrFail();

        $this->get(route('propietarios.edit', $propietario))->assertOk();

        $this->put(route('propietarios.update', $propietario), [
            'tipo_documento' => 'CC',
            'documento' => '123456789',
            'nombre' => 'Ana María',
            'apellido' => 'García',
            'telefono' => '3007654321',
            'email' => $cliente->email,
            'direccion' => 'Carrera 4 # 5-6',
            'vehiculo_id' => $vehiculo->id,
            'placa' => 'XYZ789',
            'linea' => 'Corolla Cross',
            'marca' => 'Toyota',
            'modelo' => '2025',
        ])->assertRedirect(route('propietarios.index'));

        $this->assertDatabaseHas('propietarios', [
            'id' => $propietario->id,
            'nombre' => 'Ana María',
            'direccion' => 'Carrera 4 # 5-6',
        ]);
        $this->assertDatabaseHas('users', [
            'id' => $cliente->id,
            'name' => 'Ana María García',
        ]);
        $this->assertDatabaseHas('vehiculos', [
            'id' => $vehiculo->id,
            'placa' => 'XYZ789',
            'linea' => 'Corolla Cross',
            'modelo' => '2025',
        ]);

        $this->delete(route('propietarios.destroy', $propietario))
            ->assertRedirect(route('propietarios.index'));

        $this->assertDatabaseMissing('propietarios', ['id' => $propietario->id]);
        $this->assertDatabaseMissing('vehiculos', ['id' => $vehiculo->id]);
        $this->assertDatabaseMissing('users', ['id' => $cliente->id]);
    }

    public function test_clients_cannot_manage_propietarios(): void
    {
        $cliente = User::factory()->create([
            'rol' => 'Cliente',
        ]);

        $this->actingAs($cliente)
            ->get(route('propietarios.index'))
            ->assertForbidden();
    }
}
