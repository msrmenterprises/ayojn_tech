<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\Concerns\CreatesTestUsers;

class AuthenticationBaselineTest extends TestCase
{
    use DatabaseTransactions;
    use CreatesTestUsers;

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

    public function test_user_login_sends_mfa_when_credentials_are_valid_and_otp_is_missing()
    {
        $email = 'offer-auth@example.test';
        $this->createUser($email, 1);
        $this->expectMfaMail();

        $response = $this->json('POST', '/login', [
            'email' => $email,
            'password' => 'valid-password',
            'otp' => '',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 5,
            'intended' => 'MFA has been send on your Register Email',
        ]);
        $this->assertSame(6, strlen(DB::table('users')->where('email', $email)->value('otp')));
    }

    public function test_user_login_rejects_an_otp_not_stored_for_the_user()
    {
        $email = 'otp-mismatch@example.test';
        $this->createUser($email, 1, '123456');

        $response = $this->json('POST', '/login', [
            'email' => $email,
            'password' => 'valid-password',
            'otp' => '654321',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 7,
            'intended' => 'MFA Not Matched, Please try again',
        ]);
    }

    public function test_offer_user_can_authenticate_with_a_matching_mfa_code()
    {
        $email = 'offer-valid@example.test';
        $userId = $this->createUser($email, 1, '123456');

        $response = $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
        ])->json('POST', '/login', [
            'email' => $email,
            'password' => 'valid-password',
            'otp' => '123456',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 1,
            'auth' => true,
            'intended' => '/opportunity',
        ]);
        $this->assertAuthenticatedAs(\App\User::findOrFail($userId));
    }

    public function test_pending_user_is_redirected_to_the_pending_workflow()
    {
        $email = 'offer-pending@example.test';
        $this->createUser($email, 1, '123456', ['userstatus' => 0]);

        $response = $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
        ])->json('POST', '/login', [
            'email' => $email,
            'password' => 'valid-password',
            'otp' => '123456',
        ]);

        $response->assertJson([
            'status' => 1,
            'auth' => true,
            'intended' => '/pending',
        ]);
    }

    public function test_suspended_user_is_redirected_to_the_suspended_workflow()
    {
        $email = 'offer-suspended@example.test';
        $this->createUser($email, 2, '123456', ['is_suspend' => 1]);

        $response = $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
        ])->json('POST', '/login', [
            'email' => $email,
            'password' => 'valid-password',
            'otp' => '123456',
        ]);

        $response->assertJson([
            'status' => 1,
            'auth' => true,
            'intended' => '/suspend',
        ]);
    }

    public function test_unverified_user_is_logged_out_after_matching_mfa()
    {
        $email = 'offer-unverified@example.test';
        $this->createUser($email, 1, '123456', ['email_verified' => 0]);

        $response = $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
        ])->json('POST', '/login', [
            'email' => $email,
            'password' => 'valid-password',
            'otp' => '123456',
        ]);

        $response->assertJson([
            'status' => 0,
            'auth' => true,
            'intended' => '/',
        ]);
        $this->assertGuest();
    }

    public function test_shared_user_login_does_not_authenticate_partner_accounts()
    {
        $email = 'partner-on-user-login@example.test';
        $this->createUser($email, 3);

        $response = $this->json('POST', '/login', [
            'email' => $email,
            'password' => 'valid-password',
            'otp' => '',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 8,
            'auth' => false,
            'intended' => 'Invalid Credentials or wrong Credential',
        ]);
        $this->assertGuest();
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

    public function test_partner_login_starts_mfa_after_valid_credentials()
    {
        $email = 'partner-valid@example.test';
        $this->createUser($email, 3);
        $this->expectMfaMail();

        $response = $this->post('/partner/login', [
            'p_email' => $email,
            'p_password' => 'valid-password',
        ]);

        $response->assertStatus(302);
        $this->assertSame('/partner/mfa', parse_url($response->headers->get('Location'), PHP_URL_PATH));
        $response->assertSessionHas('p_email', $email);
    }

    public function test_partner_login_rejects_an_unrecognized_mfa_code()
    {
        $email = 'partner-invalid-mfa@example.test';
        $this->createUser($email, 3, '123456');

        $response = $this->withSession([
            'p_email' => $email,
            'p_password' => 'valid-password',
        ])->post('/partner/verify_mfa', [
            'p_otp' => '654321',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    public function test_partner_login_completes_with_the_stored_mfa_code()
    {
        $email = 'partner-mfa@example.test';
        $userId = $this->createUser($email, 3, '123456');

        $response = $this->withSession([
            'p_email' => $email,
            'p_password' => 'valid-password',
        ])->post('/partner/verify_mfa', [
            'p_otp' => '123456',
        ]);

        $response->assertStatus(302);
        $this->assertSame('/partner/home', parse_url($response->headers->get('Location'), PHP_URL_PATH));
        $this->assertAuthenticatedAs(\App\User::findOrFail($userId));
    }

    public function test_admin_login_starts_mfa_after_valid_credentials()
    {
        $email = 'admin-valid@example.test';
        $this->createUser($email, 0, '000000', ['role_id' => 1]);
        $this->expectMfaMail();

        $response = $this->post('/controlpanel/login', [
            'email' => $email,
            'password' => 'valid-password',
        ]);

        $response->assertStatus(302);
        $this->assertSame('/controlpanel/mfa', parse_url($response->headers->get('Location'), PHP_URL_PATH));
        $response->assertSessionHas('a_email', $email);
    }

    public function test_admin_login_completes_with_the_stored_mfa_code()
    {
        $email = 'admin-mfa@example.test';
        $userId = $this->createUser($email, 0, '123456', ['role_id' => 1]);

        $response = $this->withSession([
            'a_email' => $email,
            'a_password' => 'valid-password',
        ])->post('/controlpanel/verify_admin_mfa', [
            'a_otp' => '123456',
        ]);

        $response->assertStatus(302);
        $this->assertSame('/controlpanel', parse_url($response->headers->get('Location'), PHP_URL_PATH));
        $this->assertAuthenticatedAs(\App\User::findOrFail($userId), 'controlpanel');
    }

    public function test_admin_login_rejects_an_unrecognized_mfa_code()
    {
        $email = 'admin-invalid-mfa@example.test';
        $this->createUser($email, 0, '123456', ['role_id' => 1]);

        $response = $this->withSession([
            'a_email' => $email,
            'a_password' => 'valid-password',
        ])->post('/controlpanel/verify_admin_mfa', [
            'a_otp' => '654321',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors();
        $this->assertGuest('controlpanel');
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

    public function test_user_password_reset_link_is_sent_for_a_valid_user()
    {
        $email = 'reset-user@example.test';
        $userId = $this->createUser($email, 1);
        Notification::fake();

        $response = $this->post('/password/email', ['email' => $email]);

        $response->assertStatus(302);
        $response->assertSessionHas('status');
        Notification::assertSentTo(
            \App\User::findOrFail($userId),
            \App\Notifications\UserResetPassword::class
        );
    }

    public function test_user_password_reset_rejects_invalid_email_input()
    {
        $response = $this->post('/password/email', ['email' => 'not-an-email']);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
    }

    public function test_shared_password_reset_does_not_send_links_to_admin_accounts()
    {
        $email = 'admin-reset@example.test';
        $this->createUser($email, 0, '000000', ['role_id' => 1]);
        Notification::fake();

        $response = $this->post('/password/email', ['email' => $email]);

        $response->assertStatus(302);
        $response->assertSessionHas('status', 'Email Address you enter did not match our records!.');
        Notification::assertNothingSent();
    }

    public function test_user_can_reset_password_with_a_valid_token()
    {
        $email = 'reset-token@example.test';
        $userId = $this->createUser($email, 1);
        $user = \App\User::findOrFail($userId);
        $token = Password::broker('users')->createToken($user);

        $response = $this->post('/password/reset', [
            'token' => $token,
            'email' => $email,
            'password' => 'replacement-password',
            'password_confirmation' => 'replacement-password',
        ]);

        $response->assertStatus(302);
        $this->assertTrue(Hash::check(
            'replacement-password',
            DB::table('users')->where('id', $userId)->value('password')
        ));
    }

    public function test_authenticated_user_can_logout()
    {
        $userId = $this->createUser('logout-user@example.test', 1);
        $user = \App\User::findOrFail($userId);

        $response = $this->actingAs($user, 'web')->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest('web');
    }

    public function test_user_cannot_access_controlpanel_protected_routes()
    {
        $userId = $this->createUser('non-admin@example.test', 1);
        $user = \App\User::findOrFail($userId);

        $response = $this->actingAs($user, 'web')->get('/controlpanel/bid-list');

        $response->assertRedirect('/controlpanel/login');
        $this->assertGuest('controlpanel');
    }

    public function test_guest_cannot_access_user_or_partner_protected_routes()
    {
        $this->get('/bid')->assertRedirect('/login');
        $this->get('/partner/home')->assertRedirect('/login');
    }

    private function expectMfaMail()
    {
        Mail::shouldReceive('send')->once();
        Mail::shouldReceive('failures')->once()->andReturn([]);
    }
}
