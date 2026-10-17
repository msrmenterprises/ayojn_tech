<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Concerns\CreatesTestUsers;
use Tests\TestCase;

class UserAccountStateWorkflowTest extends TestCase
{
    use DatabaseTransactions;
    use CreatesTestUsers;

    public function test_suspended_account_route_respects_account_status()
    {
        $user = User::findOrFail($this->createUser('suspend-state@example.test', 2, '000000', [
            'is_suspend' => 1,
        ]));

        $this->actingAs($user, 'web')
            ->get('/suspend')
            ->assertStatus(200);

        $user->is_suspend = 0;
        $user->save();

        $this->get('/suspend')->assertRedirect(url('/index'));
    }

    public function test_review_route_renders_for_edited_accounts_only()
    {
        $user = User::findOrFail($this->createUser('review-state@example.test', 2, '000000', [
            'is_edited' => 1,
        ]));

        $this->actingAs($user, 'web')
            ->get('/review')
            ->assertStatus(200);

        $user->is_edited = 0;
        $user->save();

        $this->get('/review')->assertRedirect(url('home'));
    }

    public function test_pending_route_redirects_active_users_and_renders_inactive_users()
    {
        $user = User::findOrFail($this->createUser('pending-state@example.test', 2));

        $this->actingAs($user, 'web')
            ->get('/pending')
            ->assertRedirect(url('home'));

        $user->userstatus = 0;
        $user->save();

        $this->get('/pending')->assertStatus(200);
    }

    public function test_public_referral_route_stores_the_referral_code()
    {
        $this->get('/profile/SYNTHETIC-REFERRAL')
            ->assertStatus(200)
            ->assertSessionHas('referal', 'SYNTHETIC-REFERRAL');
    }
}
