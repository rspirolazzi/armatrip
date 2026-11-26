<?php

namespace Tests\API;

use App\Models\Order;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCaseWithDatabase;
use Tests\traits\MakeUserTrait;

/**
 * Registro web (POST /api/register): crea o actualiza el usuario y su pedido.
 * Usa los datos de las semillas (empresa, provincia y paquete con fechas).
 */
class UserTest extends TestCaseWithDatabase
{
    use MakeUserTrait, WithoutMiddleware;

    /**
     * Datos de un registro válido para un paquete sembrado con fechas.
     */
    private function registrationData(array $overrides = [])
    {
        return array_merge($this->fakeUserData(), [
            'store_code' => 'secundarios',
            'province_code' => 'buenos-aires',
            'package_code' => 'bariloche',
            'year' => (int) date('Y') + 1,
            'month' => 8,
            'biweekly' => 1,
            'qty' => 30,
        ], $overrides);
    }

    public function test_register_empty_user()
    {
        $response = $this->json('POST', '/api/register', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'name',
            'email',
            'phone_code',
            'phone_number',
            'store_code',
            'province_code',
            'package_code',
            'year',
            'month',
            'biweekly',
            'qty',
        ]);
    }

    public function test_register_success_user()
    {
        $data = $this->registrationData();

        $response = $this->json('POST', '/api/register', $data);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'data' => ['user' => ['email' => $data['email']]],
        ]);
        $response->assertJsonStructure(['data' => ['jwt', 'number']]);

        $user = User::where('email', $data['email'])->first();
        $this->assertNotNull($user);
        $this->assertEquals(0, $user->is_verified);
        $this->assertEquals($data['phone_number'], $user->phone_number);

        $order = Order::where('user_id', $user->id)->first();
        $this->assertNotNull($order);
        $this->assertEquals($order->id, $user->active_order_id);
        $this->assertEquals(Order::$STATUS_PENDING, $order->status);
    }

    public function test_register_existing_email_updates_user()
    {
        $existing = $this->fakeUserData(['name' => 'Nombre anterior', 'password' => bcrypt('secreto-de-prueba')]);
        User::create($existing);

        $data = $this->registrationData(['email' => $existing['email'], 'name' => 'Nombre nuevo']);
        $response = $this->json('POST', '/api/register', $data);

        $response->assertOk();
        $response->assertJson(['success' => true]);
        $this->assertEquals(1, User::where('email', $existing['email'])->count());
        $this->assertEquals('Nombre nuevo', User::where('email', $existing['email'])->value('name'));
    }

    public function test_register_unavailable_dates_rolls_back()
    {
        $data = $this->registrationData(['month' => 1]);

        $response = $this->json('POST', '/api/register', $data);

        $response->assertStatus(404);
        $response->assertJson([
            'success' => false,
            'message' => 'Paquete no disponible para las fechas seleccionadas',
        ]);
        $this->assertDatabaseMissing('users', ['email' => $data['email']]);
    }
}
