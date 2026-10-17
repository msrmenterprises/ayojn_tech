<?php

namespace Tests\Feature;

use App\User;
use App\VouchCode;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\Concerns\CreatesTestUsers;
use Tests\TestCase;

class UserBidWorkflowTest extends TestCase
{
    use DatabaseTransactions;
    use CreatesTestUsers;

    public function test_user_can_create_a_bid_with_specifications_and_share_link()
    {
        $user = User::findOrFail($this->createUser('bid-create-workflow@example.test', 1));

        $this->actingAs($user, 'web')
            ->postJson('/add-bid', [
                'status' => 'On',
                'sponsorr_type_bid' => 'Synthetic event',
                'specify_bid' => ['Synthetic specification'],
                'country_bid' => 'ZZ',
                'industry_bid' => 1,
                'budget_bid' => 2500,
                'city_bid' => 'Synthetic City',
                'city_bidder_from' => 'Synthetic Origin',
                'contacted_by' => 'Synthetic Contact',
                'identity_bid' => 'Synthetic Identity',
                'likeSponsorr' => ['Branding', 'Leads'],
            ])
            ->assertJson([
                'status' => true,
                'message' => 'Bid Has been Added.',
            ]);

        $bid = DB::table('bids')->where('user_id', $user->id)->first();
        $this->assertNotNull($bid);
        $this->assertSame('Synthetic event', $bid->sponsor_for);
        $this->assertSame('2500', (string) $bid->sponsor_budget);
        $this->assertSame('ZZ', $bid->sponsor_country);
        $this->assertSame('Synthetic Origin', $bid->city_bidder_from);
        $this->assertSame('Branding,Leads', $bid->likeSponsorr);
        $this->assertSame(1, (int) $bid->admin_status);
        $this->assertNotEmpty($bid->share_id);
        $this->assertDatabaseHas('sponsorr_specify_bids', [
            'bid_id' => $bid->id,
            'user_id' => $user->id,
            'specify_name' => 'Synthetic specification',
        ]);
    }

    public function test_bid_owner_reading_a_response_consumes_the_receivers_free_response_once()
    {
        $owner = User::findOrFail($this->createUser('bid-read-owner@example.test', 1));
        $receiver = User::findOrFail($this->createUser('bid-read-receiver@example.test', 2, '000000', [
            'free_response' => 1,
        ]));
        $bidId = DB::table('bids')->insertGetId([
            'user_id' => $owner->id,
            'status' => 'On',
            'country' => 'ZZ',
            'admin_status' => 1,
            'is_paid' => 1,
            'per' => 0,
        ]);
        $responseId = DB::table('response_bids')->insertGetId([
            'respond_user_id' => $receiver->id,
            'bid_id' => $bidId,
            'description' => 'Synthetic response awaiting review',
            'portfolio_link' => 'https://example.test/response-review',
            'is_spam' => 0,
            'is_accepted' => 0,
            'is_counted' => 0,
        ]);

        $this->actingAs($owner, 'web')
            ->get('/get-bid-response/' . $responseId)
            ->assertOk()
            ->assertJsonStructure(['html']);

        $this->assertSame(0, (int) DB::table('users')->where('id', $receiver->id)->value('free_response'));
        $this->assertDatabaseHas('response_bids', [
            'id' => $responseId,
            'is_accepted' => 1,
            'is_counted' => 1,
        ]);

        $this->get('/get-bid-response/' . $responseId)->assertOk();
        $this->assertSame(0, (int) DB::table('users')->where('id', $receiver->id)->value('free_response'));
    }

    public function test_user_can_activate_an_unpaid_bid_with_a_valid_voucher()
    {
        $user = User::findOrFail($this->createUser('bid-voucher-valid@example.test', 1));
        $bidId = $this->createUnpaidBid($user->id);
        VouchCode::create([
            'vouch_code' => 'USER-BID-VOUCHER-VALID',
            'vouch_amount' => 25,
            'expeiry_date' => Carbon::tomorrow()->format('Y-m-d'),
        ]);

        $this->actingAs($user, 'web')
            ->postJson('/add-new-vouch-code', [
                'bid_id' => $bidId,
                'vouch_code' => 'USER-BID-VOUCHER-VALID',
            ])
            ->assertJson([
                'status' => 1,
                'message' => 'This opportunity is now listed for Bids.',
            ]);

        $this->assertDatabaseHas('bids', [
            'id' => $bidId,
            'is_paid' => 1,
            'pay_via' => 2,
            'vouch_code' => 'USER-BID-VOUCHER-VALID',
        ]);
    }

    public function test_expired_or_unknown_voucher_does_not_activate_an_unpaid_bid()
    {
        $user = User::findOrFail($this->createUser('bid-voucher-expired@example.test', 1));
        $bidId = $this->createUnpaidBid($user->id);
        VouchCode::create([
            'vouch_code' => 'USER-BID-VOUCHER-EXPIRED',
            'vouch_amount' => 25,
            'expeiry_date' => Carbon::yesterday()->format('Y-m-d'),
        ]);

        $this->actingAs($user, 'web');
        $this->postJson('/add-new-vouch-code', [
            'bid_id' => $bidId,
            'vouch_code' => 'USER-BID-VOUCHER-EXPIRED',
        ])->assertJson([
            'status' => 0,
            'message' => 'Vouch code Expried',
        ]);
        $this->postJson('/add-new-vouch-code', [
            'bid_id' => $bidId,
            'vouch_code' => 'USER-BID-VOUCHER-MISSING',
        ])->assertJson([
            'status' => 0,
            'message' => "Vouch code doesn't Exist",
        ]);

        $this->assertSame(0, (int) DB::table('bids')->where('id', $bidId)->value('is_paid'));
    }

    public function test_user_payment_completes_only_a_booked_bid_response()
    {
        $user = User::findOrFail($this->createUser('bid-payment-booked@example.test', 1));
        $bidId = $this->createUnpaidBid($user->id);
        $responseId = DB::table('response_bids')->insertGetId([
            'respond_user_id' => $user->id,
            'bid_id' => $bidId,
            'description' => 'Synthetic booked response for payment',
            'portfolio_link' => 'https://example.test/booked-response',
            'is_accepted' => 3,
            'is_spam' => 0,
        ]);

        $this->actingAs($user, 'web')
            ->postJson('/paynow-page-user', [
                'bidId' => $responseId,
                'wallet' => 0,
                'amt' => 0,
                'bid_close_amount' => 250,
                'voucher_code' => '',
            ])
            ->assertJson([
                'flag' => 1,
                'link' => '',
            ]);

        $this->assertDatabaseHas('response_bids', [
            'id' => $responseId,
            'is_accepted' => 4,
            'bid_close_amount' => 250,
        ]);
    }

    public function test_user_payment_rejects_a_response_that_is_not_booked()
    {
        $user = User::findOrFail($this->createUser('bid-payment-unbooked@example.test', 1));
        $bidId = $this->createUnpaidBid($user->id);
        $responseId = DB::table('response_bids')->insertGetId([
            'respond_user_id' => $user->id,
            'bid_id' => $bidId,
            'description' => 'Synthetic unbooked response for payment',
            'portfolio_link' => 'https://example.test/unbooked-response',
            'is_accepted' => 1,
            'is_spam' => 0,
        ]);

        $this->actingAs($user, 'web')
            ->postJson('/paynow-page-user', [
                'bidId' => $responseId,
                'wallet' => 0,
                'amt' => 0,
                'bid_close_amount' => 250,
                'voucher_code' => '',
            ])
            ->assertStatus(422)
            ->assertJsonFragment(['status' => false]);

        $this->assertSame(1, (int) DB::table('response_bids')->where('id', $responseId)->value('is_accepted'));
    }

    private function createUnpaidBid($userId)
    {
        return DB::table('bids')->insertGetId([
            'user_id' => $userId,
            'status' => 'Off',
            'country' => 'ZZ',
            'admin_status' => 1,
            'is_paid' => 0,
            'per' => 0,
        ]);
    }
}
