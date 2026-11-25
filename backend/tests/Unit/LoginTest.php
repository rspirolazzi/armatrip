<?php

namespace Tests\Unit;

use Tests\TestCase;
use Auth;

class LoginTest extends TestCase
{
    public function test_user_can_view_a_login_form()
    {
        $response = $this->get('/login');
        $response->assertSuccessful();
        $response->assertViewIs('auth.login');
    }
    public function test_user_cannot_login_with_incorrect_password()
    {
        $response = $this->from('/login')->post('/login', [
            'email' => 'usuario@example.com',
            'password' => env('TEST_USER_PASSWORD'),
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }
    /*public function test_user_receives_an_email_with_a_password_reset_link()
    {
        Notification::fake();

        $user = factory(User::class)->create();

        $response = $this->post('/password/email', [
            'email' => $user->email,
        ]);

        // assertions go here
    }*/
    public function test_user_login_successfully()
    {
        $response = $this->from('/login')->post('/login', [
            'email' => 'usuario@example.com',
            'password' => env('TEST_USER_PASSWORD'),
        ]);
        $response->assertRedirect('/admin');
        //dd(Auth::user());
        //$this->assertEquals(Auth::user()->email ,'usuario@example.com');
        //$this->assertAuthenticated();
    }
}
