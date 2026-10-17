<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\Concerns\CreatesTestUsers;
use Tests\TestCase;

class BidValidationWorkflowTest extends TestCase
{
    use DatabaseTransactions;
    use CreatesTestUsers;

    public function test_bid_creation_reports_each_required_field_in_order()
    {
        $user = User::findOrFail($this->createUser('bid-validation@example.test', 1));
        $this->actingAs($user, 'web');

        $payloadsAndErrors = [
            [[], 'Select Status'],
            [['status' => 1], 'Select Sponsor For'],
            [['status' => 1, 'sponsorr_type_bid' => 1], 'Select Specify'],
            [['status' => 1, 'sponsorr_type_bid' => 1, 'specify_bid' => ['Synthetic']], 'Select Country for Bid'],
            [['status' => 1, 'sponsorr_type_bid' => 1, 'specify_bid' => ['Synthetic'], 'country_bid' => 'ZZ'], 'Select Industry'],
            [['status' => 1, 'sponsorr_type_bid' => 1, 'specify_bid' => ['Synthetic'], 'country_bid' => 'ZZ', 'industry_bid' => 1], 'Select budget of Bid'],
            [['status' => 1, 'sponsorr_type_bid' => 1, 'specify_bid' => ['Synthetic'], 'country_bid' => 'ZZ', 'industry_bid' => 1, 'budget_bid' => 1], 'Select bidder city'],
        ];

        foreach ($payloadsAndErrors as $payloadAndError) {
            $this->postJson('/add-bid', $payloadAndError[0])
                ->assertStatus(200)
                ->assertJson([
                    'status' => false,
                    'errors' => $payloadAndError[1],
                ]);
        }
    }

    public function test_user_can_view_and_delete_an_unpaid_bid()
    {
        $user = User::findOrFail($this->createUser('unpaid-bid@example.test', 1));
        $bidId = DB::table('bids')->insertGetId([
                'user_id' => $user->id,
                'status' => 'Off',
                'country' => 'ZZ',
                'admin_status' => 1,
                'is_paid' => 0,
                'per' => 0,
        ]);

        $this->actingAs($user, 'web')
                ->get('/unpaid-bid')
                ->assertStatus(200);

        $this->postJson('/unpaid-bid-delete', ['id' => $bidId])
                ->assertJson(['status' => true, 'message' => 'Bid deleted successfully.']);

        $this->assertNotNull(DB::table('bids')->where('id', $bidId)->value('deleted_at'));

        $this->postJson('/unpaid-bid-delete', ['id' => $bidId])
                ->assertJson(['status' => false, 'message' => 'Bid not found.']);
    }
}
