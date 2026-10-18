<?php

namespace Tests\Feature;

use App\Offer;
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

    public function test_marketplace_history_renders_order_statuses_cancellation_and_payment_state()
    {
        $user = User::findOrFail($this->createUser('marketplace-history-order@example.test', 1));
        $partnerId = $this->createUser('marketplace-history-partner@example.test', 3);
        $offer = Offer::create([
            'partner_id' => $partnerId,
            'offer_for' => 'Both',
            'offerred_by' => 'Synthetic Partner',
            'identity' => 'Synthetic History Category',
            'function' => 1,
            'title' => 'Synthetic History Offer',
            'available_in' => 'ZZ',
            'currency' => 'USD',
            'qty' => 5,
            'status' => 'On',
            'admin_status' => 1,
            'weblink' => 'https://example.test/history-offer',
            'notification_email' => 'partner@example.test',
            'refund' => 1,
        ]);
        $orderId = DB::table('offers_order')->insertGetId([
            'user_id' => $user->id,
            'pay_amt' => 30,
            'wallet' => 0,
            'total_amt' => 30,
        ]);
        DB::table('direct_payment')->insert([
            'user_id' => $user->id,
            'collection_id' => 'SYNTHETIC-HISTORY-COLLECTION',
            'purpose' => 1,
            'amount' => 30,
            'ayojn_earn' => 1,
            'transaction_reference' => 'SYNTHETIC-HISTORY-REFERENCE',
            'form_name' => 'marketplace_payment',
            'refrence_id' => $orderId,
            'status_desc' => 'Payment completed',
        ]);

        foreach (range(0, 5) as $status) {
            DB::table('offers_order_item')->insert([
                'order_id' => $orderId,
                'offer_id' => $offer->id,
                'offer_amt' => 10,
                'qty' => 1,
                'final_amt' => $status === 5 ? 0 : 10,
                'status' => $status,
                'created_at' => now()->subDay()->toDateTimeString(),
            ]);
        }
        DB::table('offers_order_item')->insert([
            'order_id' => $orderId,
            'offer_id' => $offer->id,
            'offer_amt' => 10,
            'qty' => 1,
            'final_amt' => 10,
            'status' => 2,
            'cancel_status' => 1,
            'cancel_date' => '2026-10-18 00:00:00',
            'cancel_reason' => 'Synthetic cancellation reason',
            'created_at' => now()->subDays(20)->toDateTimeString(),
        ]);

        $this->actingAs($user, 'web')
            ->get('/marketplace-history')
            ->assertOk()
            ->assertSee('Synthetic History Offer')
            ->assertSee('Payment completed')
            ->assertSee('Pending')
            ->assertSee('Progress')
            ->assertSee('Query')
            ->assertSee('Feedback')
            ->assertSee('Free')
            ->assertSee('Cancel')
            ->assertSee('Cancel Date:')
            ->assertSee('Synthetic cancellation reason');
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

    public function test_cancelling_a_marketplace_item_releases_its_voucher()
    {
        $user = User::findOrFail($this->createUser('marketplace-voucher-cancel@example.test', 1));
        $voucherCode = 'SYNTHETIC-MARKETPLACE-CANCEL';
        DB::table('vouch_codes')->insert([
            'vouch_code' => $voucherCode,
            'vouch_amount' => 15,
            'expeiry_date' => '2035-01-01',
            'used_by' => $user->id,
            'status' => 1,
        ]);
        $orderId = 987655;
        $itemId = DB::table('offers_order_item')->insertGetId([
            'order_id' => $orderId,
            'offer_id' => 987655,
            'offer_amt' => 15,
            'qty' => 1,
            'final_amt' => 15,
            'status' => 2,
        ]);
        DB::table('direct_payment')->insert([
            'user_id' => $user->id,
            'collection_id' => 'synthetic-voucher-collection',
            'purpose' => 1,
            'amount' => 15,
            'ayojn_earn' => 0,
            'refrence_id' => $orderId,
            'refrence_details' => json_encode(['wallet' => 0, 'vouch_code' => $voucherCode]),
            'form_name' => 'marketplace_payment',
        ]);

        $this->actingAs($user, 'web')
            ->post('/cancel-offer-user', [
                'coffer_item_id' => $itemId,
                'cancel_reason' => 'Synthetic voucher cancellation',
            ])
            ->assertRedirect(route('marketplace-history'));

        $this->assertDatabaseHas('vouch_codes', [
            'vouch_code' => $voucherCode,
            'used_by' => 0,
            'status' => 0,
        ]);
    }

    public function test_show_payment_returns_only_completed_marketplace_items()
    {
        $user = User::findOrFail($this->createUser('marketplace-show-payment@example.test', 1));
        $completedId = DB::table('offers_order_item')->insertGetId([
            'order_id' => 900001,
            'offer_id' => 900001,
            'offer_amt' => 25,
            'qty' => 1,
            'final_amt' => 25,
            'status' => 5,
        ]);
        DB::table('offers_order_item')->insert([
            'order_id' => 900001,
            'offer_id' => 900002,
            'offer_amt' => 15,
            'qty' => 1,
            'final_amt' => 15,
            'status' => 4,
        ]);

        $this->actingAs($user, 'web');
        ob_start();
        $response = $this->postJson('/show-payment', ['id' => $completedId]);
        $output = ob_get_clean();
        $response->assertOk();
        $items = json_decode($output, true);

        $this->assertCount(1, $items);
        $this->assertSame($completedId, (int) $items[0]['id']);
        $this->assertSame(5, (int) $items[0]['status']);
    }
}
