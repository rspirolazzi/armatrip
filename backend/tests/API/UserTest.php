<?php

namespace Tests\API;

use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCaseWithDatabase;
use Tests\traits\ApiTestTrait;
use Tests\traits\MakeUserTrait;

class UserTest extends TestCaseWithDatabase
{
    use MakeUserTrait, ApiTestTrait, WithoutMiddleware;

    /**
     * Se valida que al enviar datos vacios, el usuario no se pueda dar de alta
     */
    public function test_register_empty_user()
    {
        $user = [];
        $response = $this->json('POST', '/api/register', $user);
        $response->assertStatus(200);
        $response->assertJson([
            "success" => false,
            "error" => [
                "name" => [
                    "El campo Nombre es obligatorio."
                ],
                "last_name" => [
                    "El campo Apellido es obligatorio."
                ],
                "email" => [
                    "El campo Mail es obligatorio."
                ],
                "telephone" => [
                    "El campo Teléfono es obligatorio."
                ],
                "password" => [
                    "El campo Contraseña es obligatorio."
                ]
            ]
        ]);
    }

    public function test_register_duplicate_email_user()
    {
        $user_data = $this->fakeUserData(['telephone' => '1133332255', 'password' => 'qwe123', 'isVerified' => 'abcde']);
        User::create($user_data);
        $response = $this->json('POST', '/api/register', $user_data);
        $response->assertOk();
        $response->assertJson(['success' => true]);
    }

    public function test_register_duplicate_telephone_user()
    {
        $user_data = $this->fakeUserData(['telephone' => '1133332255', 'password' => 'qwe123', 'isVerified' => 'abcde']);
        User::create($user_data);
        $response = $this->json('POST', '/api/register', $user_data);
        $response->assertOk();
        $response->assertJson(['success' => true]);
    }

    /**
     * Se valida un caso de exito de registro
     */
    public function test_register_success_user()
    {
        $user_data = $this->fakeUserData(['telephone' => '1133332255', 'password' => 'qwe123', 'isVerified' => 'abcde']);
        $response = $this->json('POST', '/api/register', $user_data);
        $response->assertOk();
        $response->assertJson(['success' => true]);
        $user = User::where('email', $user_data['email'])->first();
        $this->assertEquals($user['email'], $user_data['email']);
        $this->assertEquals($user['is_verified'], 0);
    }
}
