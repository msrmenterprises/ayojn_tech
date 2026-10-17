<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthenticationBaselineTest extends TestCase
{
    public function test_user_login_rejects_invalid_credentials()
    {
        $response = $this->post('/login', [
            'email' => 'missing@example.test',
            'password' => 'invalid-password',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 8,
            'auth' => false,
            'intended' => 'Invalid Credentials',
        ]);
    }

    public function test_partner_login_rejects_invalid_credentials()
    {
        $this->postInvalidCredentials('/partner/login', [
            'email' => 'missing@example.test',
            'password' => 'invalid-password',
            'p_email' => 'missing@example.test',
            'p_password' => 'invalid-password',
        ]);
    }

    public function test_admin_login_rejects_invalid_credentials()
    {
        $this->postInvalidCredentials('/controlpanel/login', [
            'email' => 'missing@example.test',
            'password' => 'invalid-password',
        ]);
    }

    private function postInvalidCredentials($uri, array $credentials)
    {
        $response = $this->post($uri, $credentials);

        $response->assertStatus(302);
        $response->assertSessionHasErrors();
    }
}
