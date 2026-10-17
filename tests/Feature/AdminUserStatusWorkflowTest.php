<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Tests\Concerns\CreatesTestUsers;
use Tests\TestCase;

class AdminUserStatusWorkflowTest extends TestCase
{
    use DatabaseTransactions;
    use CreatesTestUsers;

    public function test_admin_can_toggle_approval_and_suspension_states()
    {
        $admin = User::findOrFail($this->createUser('admin-user-status@example.test', 0, '000000', [
            'role_id' => 1,
        ]));
        $user = User::findOrFail($this->createUser('user-status-target@example.test', 1, '000000', [
            'userstatus' => 1,
            'is_kyc' => 0,
            'is_suspend' => 0,
        ]));
        $this->actingAs($admin, 'controlpanel');

        $this->postJson('/controlpanel/user-change-status', ['user_id' => $user->id])
            ->assertJsonFragment(['status' => 0, 'user_id' => $user->id]);
        $this->assertDatabaseHas('users', ['id' => $user->id, 'userstatus' => 0]);

        $this->postJson('/controlpanel/user-change-status', ['user_id' => $user->id])
            ->assertJsonFragment(['status' => 1, 'user_id' => $user->id]);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'userstatus' => 1,
            'is_edited' => 0,
            'is_kyc' => 1,
        ]);

        $this->postJson('/controlpanel/user-change-status-suspand', ['user_id' => $user->id])
            ->assertJsonFragment(['status' => 1, 'user_id' => $user->id]);
        $this->assertDatabaseHas('users', ['id' => $user->id, 'is_suspend' => 1]);

        $this->postJson('/controlpanel/user-change-status-suspand', ['user_id' => $user->id])
            ->assertJsonFragment(['status' => 0, 'user_id' => $user->id]);
        $this->assertDatabaseHas('users', ['id' => $user->id, 'is_suspend' => 0]);

        $this->postJson('/controlpanel/user-suspand-comment', [
            'userId' => $user->id,
            'suspend_reason' => 'Synthetic admin review',
        ])->assertJsonFragment(['status' => 1, 'user_id' => $user->id]);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_suspend' => 1,
            'suspend_reason' => 'Synthetic admin review',
        ]);
    }

    public function test_bulk_admin_approval_activates_user_bids_and_sends_mail()
    {
        $admin = User::findOrFail($this->createUser('admin-bulk-user-status@example.test', 0, '000000', [
            'role_id' => 1,
        ]));
        $user = User::findOrFail($this->createUser('bulk-status-target@example.test', 1, '000000', [
            'userstatus' => 0,
        ]));
        $bidId = DB::table('bids')->insertGetId([
            'user_id' => $user->id,
            'status' => 'Off',
            'country' => 'ZZ',
            'admin_status' => 1,
            'is_paid' => 1,
            'per' => 0,
        ]);
        Mail::shouldReceive('send')->once();

        $this->actingAs($admin, 'controlpanel')
            ->postJson('/controlpanel/users-change-status', [
                'userIds' => [$user->id],
                'status' => 1,
            ])
            ->assertJsonFragment(['msg' => 'User status has been updated successfully']);

        $this->assertDatabaseHas('users', ['id' => $user->id, 'userstatus' => 1]);
        $this->assertDatabaseHas('bids', ['id' => $bidId, 'status' => 'On']);
    }

    public function test_admin_can_toggle_and_set_a_users_sponsor_type()
    {
        $admin = User::findOrFail($this->createUser('admin-user-type@example.test', 0, '000000', [
            'role_id' => 1,
        ]));
        $user = User::findOrFail($this->createUser('user-type-target@example.test', 1));
        $this->actingAs($admin, 'controlpanel');

        $this->postJson('/controlpanel/user-change-type', ['user_id' => $user->id])
            ->assertJsonFragment(['sponsor_type' => 2, 'user_id' => $user->id]);
        $this->assertSame(2, (int) DB::table('users')->where('id', $user->id)->value('sponsor_type'));

        $this->post('/controlpanel/updateusertype', [
            'userid' => $user->id,
            'userType' => 1,
            'sponsorIndustry' => 'synthetic-industry',
        ])->assertRedirect('/controlpanel/user-list');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'sponsor_type' => 1,
            'sponsor_industry' => 'synthetic-industry',
        ]);
    }

    public function test_admin_user_deletion_removes_the_user_and_soft_deletes_their_bid_responses()
    {
        $admin = User::findOrFail($this->createUser('admin-user-delete@example.test', 0, '000000', [
            'role_id' => 1,
        ]));
        $user = User::findOrFail($this->createUser('user-delete-target@example.test', 1));
        $respondentId = $this->createUser('user-delete-respondent@example.test', 2);
        $bidId = DB::table('bids')->insertGetId([
            'user_id' => $user->id,
            'status' => 'On',
            'country' => 'ZZ',
            'admin_status' => 1,
            'is_paid' => 1,
            'per' => 0,
        ]);
        $responseId = DB::table('response_bids')->insertGetId([
            'bid_id' => $bidId,
            'respond_user_id' => $respondentId,
            'description' => 'Synthetic response to deleted user',
            'portfolio_link' => 'https://example.test/deleted-user-response',
            'is_accepted' => 0,
            'is_spam' => 0,
        ]);

        $this->actingAs($admin, 'controlpanel')
            ->postJson('/controlpanel/delete-user', ['user_id' => $user->id])
            ->assertJsonFragment([
                'msg' => 'User has been deleted successfully',
                'user_id' => $user->id,
            ]);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertNotNull(DB::table('bids')->where('id', $bidId)->value('deleted_at'));
        $this->assertNotNull(DB::table('response_bids')->where('id', $responseId)->value('deleted_at'));
    }
}
