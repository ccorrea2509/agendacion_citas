<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register()
    {
        $response = $this->post('/register', [
            'tipo_documento' => 'CC',
            'documento' => '123456789',
            'nombre' => 'Test',
            'apellido' => 'User',
            'telefono' => '3001234567',
            'email' => 'test@example.com',
            'direccion' => 'Calle 1 # 2-3',
            'placa' => 'ABC123',
            'linea' => 'Sport',
            'marca' => 'Toyota',
            'modelo' => '2024',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
        $this->assertDatabaseHas('propietarios', [
            'user_id' => auth()->id(),
            'documento' => '123456789',
        ]);
        $this->assertDatabaseHas('vehiculos', [
            'placa' => 'ABC123',
        ]);
    }
}
