<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use App\Services\MfaDelivery;
use Tests\Concerns\CreatesTestUsers;

class AuthenticationBaselineTest extends TestCase
{
    use DatabaseTransactions;
    use CreatesTestUsers;

    private $mfaCode;

    public function test_mfa_delivery_routes_only_allowlisted_demo_user_ids_to_the_controlled_inbox()
    {
        $demoUserId = $this->createUser('dummy-user@example.test', 1);
        $regularUserId = $this->createUser('regular-user@example.test', 1);
        DB::table('mfa_demo_settings')->insert([
            'id' => 1,
            'recipient_email' => 'demo-inbox@example.test',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('mfa_demo_users')->insert([
            'user_id' => $demoUserId,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $delivery = new MfaDelivery();
        $demoUser = \App\User::findOrFail($demoUserId);
        $regularUser = \App\User::findOrFail($regularUserId);

        $this->assertSame([
            'dummy-user@example.test',
            'demo-inbox@example.test',
        ], $delivery->recipientFor($demoUser));
        $this->assertSame(['regular-user@example.test'], $delivery->recipientFor($regularUser));
    }

    public function test_mfa_delivery_requires_a_valid_inbox_for_allowlisted_demo_users()
    {
        $userId = $this->createUser('dummy-user-no-inbox@example.test', 1);
        DB::table('mfa_demo_users')->insert([
            'user_id' => $userId,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->expectException(\InvalidArgumentException::class);
        (new MfaDelivery())->recipientFor(\App\User::findOrFail($userId));
    }

    public function test_mfa_delivery_sends_the_shared_copy_as_bcc()
    {
        $headers = \Mockery::mock();
        $headers->shouldReceive('addTextHeader')
            ->once()
            ->with('x-mailgun-native-send', 'true');
        $swiftMessage = \Mockery::mock();
        $swiftMessage->shouldReceive('getHeaders')->once()->andReturn($headers);
        $message = \Mockery::mock();
        $message->shouldReceive('to')->once()->with('demo-user@example.test')->andReturnSelf();
        $message->shouldReceive('bcc')->once()->with('demo-inbox@example.test')->andReturnSelf();
        $message->shouldReceive('subject')->once()->with('MFA Verification')->andReturnSelf();
        $message->shouldReceive('getSwiftMessage')->once()->andReturn($swiftMessage);

        Mail::shouldReceive('send')->once()->andReturnUsing(function ($view, $data, $callback) use ($message) {
            $this->assertSame('/mail/MFAVerification', $view);
            $callback($message);
        });
        Mail::shouldReceive('failures')->once()->andReturn([]);

        $user = new \App\User();
        $user->email = 'demo-user@example.test';

        $this->assertTrue((new MfaDelivery())->send($user, '123456', [
            'demo-user@example.test',
            'demo-inbox@example.test',
        ]));
    }

    public function test_admin_can_manage_demo_mfa_accounts_and_shared_recipient()
    {
        $adminId = $this->createUser('demo-mfa-admin@example.test', 0, '000000', ['role_id' => 1]);
        $demoUserId = $this->createUser('managed-demo-user@example.test', 1);
        $admin = \App\User::findOrFail($adminId);
        $this->actingAs($admin, 'controlpanel');

        $this->get('/controlpanel/configuration')
            ->assertOk()
            ->assertSee('Demo MFA Delivery');

        $this->post('/controlpanel/mfa-demo/settings', [
            'recipient_email' => 'demo-inbox@example.test',
        ])->assertRedirect();
        $this->assertDatabaseHas('mfa_demo_settings', [
            'id' => 1,
            'recipient_email' => 'demo-inbox@example.test',
        ]);
        $this->post('/controlpanel/mfa-demo/users', [
            'user_email' => 'managed-demo-user@example.test',
        ])->assertRedirect();

        $this->assertDatabaseHas('mfa_demo_users', [
            'user_id' => $demoUserId,
            'is_active' => 1,
        ]);
        $this->assertSame([
            'managed-demo-user@example.test',
            'demo-inbox@example.test',
        ], (new MfaDelivery())->recipientFor(\App\User::findOrFail($demoUserId)));

        $mappingId = DB::table('mfa_demo_users')->where('user_id', $demoUserId)->value('id');
        $this->post('/controlpanel/mfa-demo/users/' . $mappingId . '/toggle')->assertRedirect();
        $this->assertDatabaseHas('mfa_demo_users', [
            'user_id' => $demoUserId,
            'is_active' => 0,
        ]);
        $this->assertSame(['managed-demo-user@example.test'], (new MfaDelivery())->recipientFor(
            \App\User::findOrFail($demoUserId)
        ));

        $this->post('/controlpanel/mfa-demo/users/' . $mappingId . '/remove')->assertRedirect();
        $this->assertDatabaseMissing('mfa_demo_users', ['id' => $mappingId]);
    }

    public function test_non_admin_cannot_manage_demo_mfa_delivery()
    {
        $userId = $this->createUser('not-an-admin@example.test', 1);
        $this->actingAs(\App\User::findOrFail($userId), 'controlpanel');

        $this->post('/controlpanel/mfa-demo/settings', [
            'recipient_email' => 'demo-inbox@example.test',
        ])->assertForbidden();

        $this->assertSame(0, DB::table('mfa_demo_settings')->count());
    }

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
        $userId = $this->createUser($email, 1);
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
        $response->assertSessionHas('mfa_challenge.user.user_id', $userId);
        $this->assertSame('000000', DB::table('users')->where('email', $email)->value('otp'));
    }

    public function test_user_login_rejects_an_otp_not_stored_for_the_user()
    {
        $email = 'otp-mismatch@example.test';
        $this->createUser($email, 1, '123456');
        $this->createUser('otp-owned-by-another-user@example.test', 1, '654321');

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
        $this->expectMfaMail();

        $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
        ])->json('POST', '/login', [
            'email' => $email,
            'password' => 'valid-password',
            'otp' => '',
        ])->assertJson(['status' => 5]);

        $response = $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
        ])->json('POST', '/login', [
            'email' => $email,
            'password' => 'valid-password',
            'otp' => $this->mfaCode,
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
        $this->expectMfaMail();

        $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
        ])->json('POST', '/login', [
            'email' => $email,
            'password' => 'valid-password',
            'otp' => '',
        ])->assertJson(['status' => 5]);

        $response = $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
        ])->json('POST', '/login', [
            'email' => $email,
            'password' => 'valid-password',
            'otp' => $this->mfaCode,
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
        $this->expectMfaMail();

        $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
        ])->json('POST', '/login', [
            'email' => $email,
            'password' => 'valid-password',
            'otp' => '',
        ])->assertJson(['status' => 5]);

        $response = $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
        ])->json('POST', '/login', [
            'email' => $email,
            'password' => 'valid-password',
            'otp' => $this->mfaCode,
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
        $this->expectMfaMail();

        $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
        ])->json('POST', '/login', [
            'email' => $email,
            'password' => 'valid-password',
            'otp' => '',
        ])->assertJson(['status' => 5]);

        $response = $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
        ])->json('POST', '/login', [
            'email' => $email,
            'password' => 'valid-password',
            'otp' => $this->mfaCode,
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

    public function test_partner_login_password_is_not_flashed_to_the_session_on_validation_failure()
    {
        $response = $this->from('/partner/login')->post('/partner/login', [
            'p_password' => 'synthetic-secret',
        ]);

        $response->assertRedirect('/partner/login');
        $response->assertSessionMissing('p_password');
    }

    public function test_partner_login_starts_mfa_after_valid_credentials()
    {
        $email = 'partner-valid@example.test';
        $userId = $this->createUser($email, 3);
        $this->expectMfaMail();

        $response = $this->post('/partner/login', [
            'p_email' => $email,
            'p_password' => 'valid-password',
        ]);

        $response->assertStatus(302);
        $this->assertSame('/partner/mfa', parse_url($response->headers->get('Location'), PHP_URL_PATH));
        $response->assertSessionHas('mfa_challenge.partner.user_id', $userId);
        $response->assertSessionMissing('p_password');
    }

    public function test_partner_login_rejects_an_unrecognized_mfa_code()
    {
        $email = 'partner-invalid-mfa@example.test';
        $this->createUser($email, 3, '123456');
        $this->expectMfaMail();

        $this->post('/partner/login', [
            'p_email' => $email,
            'p_password' => 'valid-password',
        ]);

        $response = $this->post('/partner/verify_mfa', [
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
        $this->expectMfaMail();

        $this->post('/partner/login', [
            'p_email' => $email,
            'p_password' => 'valid-password',
        ]);
        $sessionIdBeforeMfa = $this->app['session.store']->getId();

        $response = $this->post('/partner/verify_mfa', [
            'p_otp' => $this->mfaCode,
        ]);
        $sessionIdAfterMfa = $this->app['session.store']->getId();
        $response->assertStatus(302);
        $this->assertSame('/partner/home', parse_url($response->headers->get('Location'), PHP_URL_PATH));
        $this->assertAuthenticatedAs(\App\User::findOrFail($userId));
        $response->assertSessionMissing('mfa_challenge.partner');
        $response->assertSessionMissing('p_password');
        $this->assertNotSame($sessionIdBeforeMfa, $sessionIdAfterMfa);
    }

    public function test_partner_mfa_code_cannot_be_replayed()
    {
        $email = 'partner-mfa-replay@example.test';
        $this->createUser($email, 3);
        $this->expectMfaMail();

        $this->post('/partner/login', [
            'p_email' => $email,
            'p_password' => 'valid-password',
        ]);
        $this->post('/partner/verify_mfa', ['p_otp' => $this->mfaCode]);
        \Illuminate\Support\Facades\Auth::guard('web')->logout();

        $response = $this->post('/partner/verify_mfa', ['p_otp' => $this->mfaCode]);

        $response->assertRedirect();
        $response->assertSessionHasErrors();
        $this->assertGuest('web');
    }

    public function test_expired_partner_mfa_challenge_is_rejected()
    {
        $userId = $this->createUser('partner-mfa-expired@example.test', 3);

        $response = $this->withSession([
            'mfa_challenge' => [
                'partner' => [
                    'user_id' => $userId,
                    'code_hash' => Hash::make('123456'),
                    'expires_at' => time() - 1,
                    'attempts' => 0,
                ],
            ],
        ])->post('/partner/verify_mfa', ['p_otp' => '123456']);

        $response->assertRedirect();
        $response->assertSessionHasErrors();
        $response->assertSessionMissing('mfa_challenge.partner');
        $this->assertGuest('web');
    }

    public function test_admin_login_starts_mfa_after_valid_credentials()
    {
        $email = 'admin-valid@example.test';
        $userId = $this->createUser($email, 0, '000000', ['role_id' => 1]);
        $this->expectMfaMail();

        $response = $this->post('/controlpanel/login', [
            'email' => $email,
            'password' => 'valid-password',
        ]);

        $response->assertStatus(302);
        $this->assertSame('/controlpanel/mfa', parse_url($response->headers->get('Location'), PHP_URL_PATH));
        $response->assertSessionHas('mfa_challenge.controlpanel.user_id', $userId);
        $response->assertSessionMissing('a_password');
    }

    public function test_admin_login_completes_with_the_stored_mfa_code()
    {
        $email = 'admin-mfa@example.test';
        $userId = $this->createUser($email, 0, '123456', ['role_id' => 1]);
        $this->expectMfaMail();

        $this->post('/controlpanel/login', [
            'email' => $email,
            'password' => 'valid-password',
        ]);

        $response = $this->post('/controlpanel/verify_admin_mfa', [
            'a_otp' => $this->mfaCode,
        ]);
        $response->assertStatus(302);
        $this->assertSame('/controlpanel', parse_url($response->headers->get('Location'), PHP_URL_PATH));
        $this->assertAuthenticatedAs(\App\User::findOrFail($userId), 'controlpanel');
    }

    public function test_admin_login_rejects_an_unrecognized_mfa_code()
    {
        $email = 'admin-invalid-mfa@example.test';
        $this->createUser($email, 0, '123456', ['role_id' => 1]);
        $this->expectMfaMail();

        $this->post('/controlpanel/login', [
            'email' => $email,
            'password' => 'valid-password',
        ]);

        $response = $this->post('/controlpanel/verify_admin_mfa', [
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

    public function test_non_admin_role_cannot_start_controlpanel_mfa()
    {
        $email = 'regular-user-controlpanel@example.test';
        $this->createUser($email, 1);

        $response = $this->post('/controlpanel/login', [
            'email' => $email,
            'password' => 'valid-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest('controlpanel');
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
        Mail::shouldReceive('send')->once()->andReturnUsing(function ($view, $data, $callback) {
            $this->mfaCode = $data['code'];
        });
        Mail::shouldReceive('failures')->once()->andReturn([]);
    }
}
