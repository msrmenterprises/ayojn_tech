<?php

namespace Tests\Feature;

use App\User;
use App\Models\NotificationSetting;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\Concerns\CreatesTestUsers;
use Tests\TestCase;

class BidResponseWorkflowTest extends TestCase
{
    use DatabaseTransactions;
    use CreatesTestUsers;

    public function test_user_can_mark_a_response_as_spam_and_restore_it()
    {
        $user = User::findOrFail($this->createUser('bid-response@example.test', 1));
        $bidId = DB::table('bids')->insertGetId([
            'user_id' => $user->id,
            'status' => 'On',
            'country' => 'ZZ',
            'admin_status' => 1,
            'is_paid' => 1,
            'per' => 0,
        ]);
        $responseId = DB::table('response_bids')->insertGetId([
            'respond_user_id' => $user->id,
            'bid_id' => $bidId,
            'description' => 'Synthetic response',
            'portfolio_link' => 'https://example.test/portfolio',
            'is_spam' => 0,
            'is_accepted' => 2,
        ]);

        $this->actingAs($user, 'web')
            ->postJson('/add-reason', [
                'responseId' => $responseId,
                'response' => 'Synthetic moderation reason',
            ])
            ->assertJson([
                'status' => true,
                'message' => 'Bid response has been added to spam.',
            ]);

        $this->assertDatabaseHas('response_bids', [
            'id' => $responseId,
            'spam_reason' => 'Synthetic moderation reason',
            'is_spam' => 1,
        ]);

        $this->get('/restore-spam/' . $responseId)
            ->assertRedirect(url('bid-response') . '/' . base64_encode($bidId));

        $this->assertDatabaseHas('response_bids', [
            'id' => $responseId,
            'is_spam' => 0,
        ]);
    }

    public function test_receiver_can_respond_to_a_bid_and_notify_its_owner()
    {
        $owner = User::findOrFail($this->createUser('bid-owner@example.test', 1));
        $respondent = User::findOrFail($this->createUser('bid-respondent@example.test', 2));
        $bidId = DB::table('bids')->insertGetId([
            'user_id' => $owner->id,
            'status' => 'On',
            'country' => 'ZZ',
            'admin_status' => 1,
            'is_paid' => 1,
            'per' => 0,
        ]);
        NotificationSetting::create([
            'user_id' => $owner->id,
            'inapp' => 1,
            'email' => 0,
            'days' => null,
        ]);

        $this->actingAs($respondent, 'web')
            ->postJson('/add-bid-response', [
                'bid_id' => $bidId,
                'description' => 'Synthetic bid response',
                'portfolio_link' => 'https://example.test/bid-response',
            ])
            ->assertJson([
                'status' => true,
                'message' => 'Bid response Has been Added.',
            ]);

        $this->assertDatabaseHas('response_bids', [
            'bid_id' => $bidId,
            'respond_user_id' => $respondent->id,
            'description' => 'Synthetic bid response',
            'portfolio_link' => 'https://example.test/bid-response',
        ]);
        $this->assertDatabaseHas('users', [
            'id' => $respondent->id,
            'free_response' => 1,
            'is_paid' => 0,
        ]);
        $this->assertDatabaseHas('notifications', [
            'user_id' => $owner->id,
            'in_app' => 1,
        ]);
    }
}
