<?php

namespace Tests\API;

use App\Models\Order;
use App\Models\Token;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Rspirolazzi\Sms\Facades\Sms;
use Tests\TestCaseWithDatabase;
use Tests\traits\MakeUserTrait;

/**
 * Registro web (POST /api/register): crea el usuario y su pedido, y envía el código por SMS.
 * La sesión (JWT) se entrega recién al validar el código (POST /api/sms/verify).
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

    /**
     * Simula el envío: nunca sale un SMS real desde los tests.
     */
    private function expectSms($times = 1)
    {
        Sms::shouldReceive('createMessage')->times($times)->andReturn(true);
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

    public function test_register_sends_sms_without_session()
    {
        $this->expectSms();
        $data = $this->registrationData();

        $response = $this->json('POST', '/api/register', $data);

        $response->assertOk();
        $response->assertJson(['success' => true]);
        $response->assertJsonStructure(['data' => ['number']]);
        $this->assertArrayNotHasKey('jwt', $response->json('data'));
        $this->assertArrayNotHasKey('user', $response->json('data'));

        $user = User::where('email', $data['email'])->first();
        $this->assertNotNull($user);
        $this->assertEquals(0, $user->is_verified);
        $this->assertEquals($data['phone_number'], $user->phone_number);
        $this->assertTrue(Token::where('user_id', $user->id)->where('source', Token::$_SOURCE_SMS)->exists());

        $order = Order::where('user_id', $user->id)->first();
        $this->assertNotNull($order);
        $this->assertEquals($order->id, $user->active_order_id);
        $this->assertEquals(Order::$STATUS_PENDING, $order->status);
    }

    public function test_register_ignores_client_verification_type()
    {
        $this->expectSms();

        $response = $this->json('POST', '/api/register', $this->registrationData(['verification_type' => 'facebook']));

        $response->assertOk();
        $this->assertArrayNotHasKey('jwt', $response->json('data'));
    }

    public function test_register_existing_email_keeps_user_data()
    {
        $this->expectSms();
        $existing = $this->fakeUserData(['name' => 'Nombre anterior', 'password' => bcrypt('secreto-de-prueba')]);
        User::create($existing);

        $data = $this->registrationData([
            'email' => $existing['email'],
            'phone_code' => $existing['phone_code'],
            'phone_number' => $existing['phone_number'],
            'name' => 'Nombre nuevo',
        ]);
        $response = $this->json('POST', '/api/register', $data);

        $response->assertOk();
        $this->assertArrayNotHasKey('jwt', $response->json('data'));
        $this->assertEquals(1, User::where('email', $existing['email'])->count());
        $this->assertEquals('Nombre anterior', User::where('email', $existing['email'])->value('name'));
    }

    public function test_register_existing_email_with_other_phone_fails()
    {
        $this->expectSms(0);
        $existing = $this->fakeUserData(['phone_number' => '44445555', 'password' => bcrypt('secreto-de-prueba')]);
        User::create($existing);

        $data = $this->registrationData(['email' => $existing['email'], 'phone_number' => '66667777']);
        $response = $this->json('POST', '/api/register', $data);

        $response->assertStatus(404);
        $response->assertJson(['success' => false]);
        $this->assertNotContains('44445555', $response->getContent());
        $this->assertEquals('44445555', User::where('email', $existing['email'])->value('phone_number'));
    }

    public function test_sms_verify_returns_session()
    {
        $this->expectSms();
        $data = $this->registrationData();
        $this->json('POST', '/api/register', $data)->assertOk();
        $user = User::where('email', $data['email'])->first();
        $code = Token::where('user_id', $user->id)->value('token');

        $response = $this->json('POST', '/api/sms/verify', [
            'verification_code' => $code,
            'phone_code' => $data['phone_code'],
            'phone_number' => $data['phone_number'],
        ]);

        $response->assertOk();
        $response->assertJsonStructure(['data' => ['token', 'user']]);
        $this->assertEquals(1, $user->fresh()->is_verified);
    }

    public function test_register_unavailable_dates_rolls_back()
    {
        $this->expectSms(0);
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
