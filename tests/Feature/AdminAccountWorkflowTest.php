<?php

namespace Tests\Feature;

use App\RedeemRequest;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\Concerns\CreatesTestUsers;
use Tests\TestCase;

class AdminAccountWorkflowTest extends TestCase
{
    use DatabaseTransactions;
    use CreatesTestUsers;

    public function test_admin_review_table_includes_synthetic_profile_edits()
    {
        $admin = User::findOrFail($this->createUser('admin-review-list@example.test', 0, '000000', [
            'role_id' => 1,
        ]));
        $reviewUser = User::findOrFail($this->createUser('review-list-synthetic@example.test', 1, '000000', [
            'is_edited' => 1,
            'sponsor_for' => 'Synthetic Event',
            'sponsor_budget' => 'Less than 2000',
        ]));

        $this->actingAs($admin, 'controlpanel')
            ->postJson('/controlpanel/review-list-data')
            ->assertOk()
            ->assertSee($reviewUser->email)
            ->assertSee('Offer Sponsorship')
            ->assertSee('Synthetic Test Country');
    }

    public function test_admin_can_update_profile_fields_without_changing_email()
    {
        $admin = User::findOrFail($this->createUser('admin-profile@example.test', 0, '000000', [
            'role_id' => 1,
        ]));

        $this->actingAs($admin, 'controlpanel')
            ->from('/controlpanel/editprofile')
            ->post('/controlpanel/updateprofile', [
                'userid' => $admin->id,
                'username' => 'Synthetic Admin Updated',
                'email' => $admin->email,
                'phone_no' => '5550100100',
                'gender' => 'Other',
                'company_name' => 'Synthetic Admin Company',
                'address' => 'Synthetic Admin Address',
                'about_me' => 'Synthetic profile update',
            ])
            ->assertRedirect('/controlpanel/editprofile')
            ->assertSessionHas('success', 'Profile Updated Successfully');

        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'name' => 'Synthetic Admin Updated',
            'email' => 'admin-profile@example.test',
            'phone_no' => '5550100100',
            'company_name' => 'Synthetic Admin Company',
            'address' => 'Synthetic Admin Address',
            'about_me' => 'Synthetic profile update',
        ]);
    }

    public function test_admin_profile_update_rejects_an_email_used_by_another_account()
    {
        $admin = User::findOrFail($this->createUser('admin-profile-owner@example.test', 0, '000000', [
            'role_id' => 1,
        ]));
        $otherUser = User::findOrFail($this->createUser('admin-profile-other@example.test', 2));

        $this->actingAs($admin, 'controlpanel')
            ->from('/controlpanel/editprofile')
            ->post('/controlpanel/updateprofile', [
                'userid' => $admin->id,
                'username' => 'Should Not Be Saved',
                'email' => $otherUser->email,
                'phone_no' => '5550100101',
            ])
            ->assertRedirect('/controlpanel/editprofile')
            ->assertSessionHasErrors('email');

        $this->assertSame('Regression Test User', DB::table('users')->where('id', $admin->id)->value('name'));
    }

    public function test_admin_password_update_rejects_a_wrong_old_password_and_accepts_a_valid_change()
    {
        $admin = User::findOrFail($this->createUser('admin-password@example.test', 0, '000000', [
            'role_id' => 1,
        ]));

        $this->actingAs($admin, 'controlpanel')
            ->from('/controlpanel/changepassword')
            ->post('/controlpanel/changepassword', [
                'old_password' => 'wrong-password',
                'new_password' => 'replacement-password',
                'confirm_password' => 'replacement-password',
            ])
            ->assertRedirect('/controlpanel/changepassword')
            ->assertSessionHasErrors();

        $this->assertTrue(Hash::check(
            'valid-password',
            DB::table('users')->where('id', $admin->id)->value('password')
        ));

        $this->post('/controlpanel/changepassword', [
            'old_password' => 'valid-password',
            'new_password' => 'replacement-password',
            'confirm_password' => 'replacement-password',
        ])
            ->assertRedirect('/controlpanel/')
            ->assertSessionHas('status', 'Password updated successfully');

        $this->assertTrue(Hash::check(
            'replacement-password',
            DB::table('users')->where('id', $admin->id)->value('password')
        ));
    }

    public function test_approving_a_redemption_request_pays_out_marketplace_wallet_once()
    {
        $admin = User::findOrFail($this->createUser('admin-redeem-approval@example.test', 0, '000000', [
            'role_id' => 1,
        ]));
        $user = User::findOrFail($this->createUser('redeem-approval-user@example.test', 2, '000000', [
            'wallet_marketplace' => 45,
        ]));
        $request = RedeemRequest::create([
            'user_id' => $user->id,
            'beneficiary_id' => 7,
            'notes' => 'Synthetic approval request',
            'status' => 0,
            'points' => 0,
        ]);

        $this->actingAs($admin, 'controlpanel');
        $response = $this->postJson('/controlpanel/request-change-status', [
            'request_id' => $request->id,
            'status' => 1,
        ]);
        $this->assertSame(1, json_decode($response->getContent(), true)['status']);

        $this->assertDatabaseHas('redeem_requests', [
            'id' => $request->id,
            'status' => 1,
            'points' => 45,
        ]);
        $this->assertEquals(0, (float) DB::table('users')->where('id', $user->id)->value('wallet_marketplace'));
        $this->assertDatabaseHas('wallet_logs', [
            'user_id' => $user->id,
            'points_by' => $admin->id,
            'point' => -45,
        ]);
    }

    public function test_disapproving_a_redemption_request_preserves_the_user_wallet()
    {
        $admin = User::findOrFail($this->createUser('admin-redeem-decline@example.test', 0, '000000', [
            'role_id' => 1,
        ]));
        $user = User::findOrFail($this->createUser('redeem-decline-user@example.test', 2, '000000', [
            'wallet_marketplace' => 45,
        ]));
        $request = RedeemRequest::create([
            'user_id' => $user->id,
            'beneficiary_id' => 7,
            'notes' => 'Synthetic disapproval request',
            'status' => 0,
            'points' => 0,
        ]);

        $this->actingAs($admin, 'controlpanel');
        $response = $this->postJson('/controlpanel/request-change-status', [
            'request_id' => $request->id,
            'status' => 2,
        ]);
        $this->assertSame(2, json_decode($response->getContent(), true)['status']);

        $this->assertDatabaseHas('redeem_requests', [
            'id' => $request->id,
            'status' => 2,
        ]);
        $this->assertSame(45, (int) DB::table('users')->where('id', $user->id)->value('wallet_marketplace'));
        $this->assertSame(0, DB::table('wallet_logs')->where('user_id', $user->id)->count());
    }
}
