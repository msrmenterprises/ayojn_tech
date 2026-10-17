<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\Concerns\CreatesTestUsers;
use Tests\TestCase;

class BidManagementWorkflowTest extends TestCase
{
    use DatabaseTransactions;
    use CreatesTestUsers;

    public function test_user_can_edit_and_toggle_their_bid()
    {
        $user = User::findOrFail($this->createUser('bid-management@example.test', 1));
        $bidId = DB::table('bids')->insertGetId([
            'user_id' => $user->id,
            'status' => 'On',
            'country' => 'ZZ',
            'sponsor_country' => 'ZZ',
            'admin_status' => 1,
            'is_paid' => 0,
            'per' => 0,
        ]);
        $this->actingAs($user, 'web');

        $this->postJson('/edit-bid', ['id' => $bidId])
            ->assertJsonFragment(['id' => $bidId])
            ->assertJson(['status' => 1]);

        $this->postJson('/change-bid-status', [
            'id' => $bidId,
            'status' => 'On',
        ])->assertJson([
            'status' => true,
            'message' => 'Bid status Has been Changed.',
        ]);
        $this->assertSame('Off', DB::table('bids')->where('id', $bidId)->value('status'));

        $this->postJson('/update-bid', [
            'bidid' => $bidId,
            'status' => 'On',
            'sponsorr_type_bid' => 'Synthetic event',
            'country_bid' => 'ZZ',
            'industry_bid' => 1,
            'budget_bid' => 1000,
            'city_bid' => 'Synthetic City',
            'contacted_by' => 'Synthetic contact',
            'identity_bid' => 'Synthetic identity',
            'city_bidder_from' => 'Synthetic source city',
            'likeSponsorr' => ['Branding', 'Leads'],
        ])->assertJson([
            'status' => true,
            'message' => 'Bid Has been Updated.',
        ]);

        $this->assertDatabaseHas('bids', [
            'id' => $bidId,
            'sponsor_for' => 'Synthetic event',
            'sponsor_budget' => '1000',
            'sponsor_industry' => '1',
            'sponsor_country' => 'ZZ',
            'status' => 'On',
            'likeSponsorr' => 'Branding,Leads',
        ]);
    }
}
