<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\Concerns\CreatesTestUsers;
use Tests\TestCase;

class MarketplaceHistoryWorkflowTest extends TestCase
{
    use DatabaseTransactions;
    use CreatesTestUsers;

    public function test_user_can_view_marketplace_history_without_orders()
    {
        $user = User::findOrFail($this->createUser('marketplace-history@example.test', 1));

        $this->actingAs($user, 'web')
            ->get('/marketplace-history')
            ->assertStatus(200);
    }

    public function test_wallet_history_formats_synthetic_voucher_entries()
    {
        $user = User::findOrFail($this->createUser('wallet-history@example.test', 1));
        DB::table('wallet_logs')->insert([
            'user_id' => $user->id,
            'points_by' => 12,
            'trans_id' => 999999,
            'point' => 25,
            'point_type' => 'CR',
            'wallet_type' => 0,
            'created_at' => '2030-01-01 00:00:00',
        ]);

        $this->actingAs($user, 'web')
            ->get('/wallet-history')
            ->assertStatus(200)
            ->assertSee('Voucher Reedeem -');
    }

    public function test_marketplace_query_updates_the_order_item_and_is_retrievable()
    {
        $user = User::findOrFail($this->createUser('query-owner@example.test', 1));
        $partner = User::findOrFail($this->createUser('query-partner@example.test', 3));
        $itemId = DB::table('offers_order_item')->insertGetId([
            'order_id' => 999999,
            'offer_id' => 999999,
            'offer_amt' => 25,
            'qty' => 1,
            'final_amt' => 25,
            'status' => 2,
        ]);

        $this->actingAs($user, 'web');
        ob_start();
        $storeResponse = $this->post('/query-store', [
                    'query_id' => $itemId,
                    'partner_id' => $partner->id,
                    'query_msg' => 'Synthetic order question',
                    'from' => 'user',
                ]);
        $storeOutput = ob_get_clean();
        $storeResponse->assertStatus(200);
        $this->assertSame('1', $storeOutput);

        $this->assertDatabaseHas('offers_order_item', [
            'id' => $itemId,
            'status' => 3,
        ]);
        $this->assertDatabaseHas('query_communication', [
            'partner_id' => $partner->id,
            'user_id' => $user->id,
            'offer_order_item_id' => $itemId,
            'query' => 'Synthetic order question',
            'type' => 'right',
            'level' => 1,
        ]);

        ob_start();
        $queryResponse = $this->post('/query-get', [
            'id' => $itemId,
            'partner_id' => $partner->id,
            'from' => 'user',
        ]);
        $queryOutput = ob_get_clean();
        $queryResponse->assertStatus(200);
        $query = json_decode($queryOutput, true);

        $this->assertSame('Synthetic order question', $query[0]['query']);
    }

    public function test_user_feedback_is_saved_and_can_be_retrieved()
    {
        $user = User::findOrFail($this->createUser('marketplace-feedback@example.test', 1));
        $this->actingAs($user, 'web');

        ob_start();
        $storeResponse = $this->post('/feedback-store', [
            'feedback' => 'Synthetic marketplace feedback',
            'rating' => 5,
            'offer_item_id' => 12345,
            'user_id' => $user->id,
        ]);
        $storeOutput = ob_get_clean();
        $storeResponse->assertStatus(200);
        $this->assertSame('Your Feedback Save Successfully', $storeOutput);

        $this->assertDatabaseHas('offers_order_item_feedback', [
            'offer_item_id' => 12345,
            'user_id' => $user->id,
            'rating' => 5,
            'feedback' => 'Synthetic marketplace feedback',
        ]);

        ob_start();
        $showResponse = $this->post('/show-feedback-user', ['id' => 12345]);
        $showOutput = ob_get_clean();
        $showResponse->assertStatus(200);
        $feedback = json_decode($showOutput, true);

        $this->assertSame('Synthetic marketplace feedback', $feedback['feedback']);
        $this->assertSame(5, (int) $feedback['rating']);
    }

    public function test_cancelling_a_marketplace_item_refunds_its_wallet_amount()
    {
        $user = User::findOrFail($this->createUser('marketplace-cancel@example.test', 1));
        DB::table('users')->where('id', $user->id)->update(['wallet_balance' => 10]);
        $orderId = 987654;
        $itemId = DB::table('offers_order_item')->insertGetId([
            'order_id' => $orderId,
            'offer_id' => 987654,
            'offer_amt' => 25,
            'qty' => 1,
            'final_amt' => 25,
            'status' => 2,
        ]);
        DB::table('direct_payment')->insert([
            'user_id' => $user->id,
            'collection_id' => 'synthetic-collection',
            'purpose' => 1,
            'amount' => 25,
            'ayojn_earn' => 0,
            'refrence_id' => $orderId,
            'refrence_details' => json_encode(['wallet' => 15, 'vouch_code' => 'null']),
            'form_name' => 'marketplace_payment',
        ]);

        $this->actingAs($user, 'web')
            ->post('/cancel-offer-user', [
                'coffer_item_id' => $itemId,
                'cancel_reason' => 'Synthetic cancellation',
            ])
            ->assertRedirect(route('marketplace-history'));

        $this->assertDatabaseHas('offers_order_item', [
            'id' => $itemId,
            'cancel_status' => 1,
            'cancel_reason' => 'Synthetic cancellation',
        ]);
        $this->assertSame(25, (int) DB::table('users')->where('id', $user->id)->value('wallet_balance'));
    }
}
