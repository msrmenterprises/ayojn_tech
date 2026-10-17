<?php

namespace Tests\Feature;

use App\User;
use App\RedeemRequest;
use App\VouchCode;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\Concerns\CreatesTestUsers;
use Tests\TestCase;

class AdminBidWorkflowTest extends TestCase
{
    use DatabaseTransactions;
    use CreatesTestUsers;

    public function test_admin_can_approve_and_disapprove_a_bid()
    {
        $admin = User::findOrFail($this->createUser('admin-bid-status@example.test', 0, '000000', [
            'role_id' => 1,
        ]));
        $bidId = $this->createBid($this->createUser('admin-bid-owner@example.test', 1));

        $this->actingAs($admin, 'controlpanel');

        $approved = $this->postJson('/controlpanel/bid-change-status', ['user_id' => $bidId]);
        $approved->assertJsonFragment(['status' => 1, 'user_id' => $bidId]);
        $this->assertSame(1, (int) DB::table('bids')->where('id', $bidId)->value('admin_status'));

        $disapproved = $this->postJson('/controlpanel/bid-change-status', ['user_id' => $bidId]);
        $disapproved->assertJsonFragment(['status' => 0, 'user_id' => $bidId]);
        $this->assertSame(0, (int) DB::table('bids')->where('id', $bidId)->value('admin_status'));
    }

    public function test_admin_can_set_bid_percentage_and_soft_delete_bid_and_voucher()
    {
        $admin = User::findOrFail($this->createUser('admin-bid-maintenance@example.test', 0, '000000', [
            'role_id' => 1,
        ]));
        $bidId = $this->createBid($this->createUser('admin-bid-maintenance-owner@example.test', 1));
        $voucher = VouchCode::create([
            'vouch_code' => 'ADMIN-DELETE-SYNTHETIC',
            'vouch_amount' => 20,
            'expeiry_date' => '2030-01-01',
        ]);

        $this->actingAs($admin, 'controlpanel');

        $this->post('/controlpanel/addper', [
            'responce_id' => $bidId,
            'percentage' => 15,
        ])->assertRedirect('/controlpanel/bid-list');
        $this->assertSame(15, (int) DB::table('bids')->where('id', $bidId)->value('per'));

        $this->postJson('/controlpanel/delete-bid', ['user_id' => $bidId])
            ->assertJsonFragment(['bid_id' => $bidId]);
        $this->assertNotNull(DB::table('bids')->where('id', $bidId)->value('deleted_at'));

        $this->postJson('/controlpanel/delete-code', ['user_id' => $voucher->id])
            ->assertJsonFragment(['bid_id' => $voucher->id]);
        $this->assertNotNull(DB::table('vouch_codes')->where('id', $voucher->id)->value('deleted_at'));
    }

    public function test_admin_can_mark_a_booked_response_as_paid_once()
    {
        $admin = User::findOrFail($this->createUser('admin-pay-response@example.test', 0, '000000', [
            'role_id' => 1,
        ]));
        $ownerId = $this->createUser('admin-pay-response-owner@example.test', 1);
        $respondentId = $this->createUser('admin-pay-response-user@example.test', 2);
        $bidId = $this->createBid($ownerId);
        $responseId = DB::table('response_bids')->insertGetId([
            'bid_id' => $bidId,
            'respond_user_id' => $respondentId,
            'description' => 'Synthetic booked response',
            'portfolio_link' => 'https://example.test/portfolio',
            'is_accepted' => 3,
            'is_spam' => 0,
        ]);

        $response = $this->actingAs($admin, 'controlpanel')
            ->postJson('/controlpanel/paynow-page', ['bidId' => $responseId]);
        $response->assertOk();
        $this->assertSame($responseId, json_decode($response->getContent(), true));

        $this->assertSame(4, (int) DB::table('response_bids')->where('id', $responseId)->value('is_accepted'));
    }

    public function test_admin_cannot_mark_an_unbooked_response_as_paid()
    {
        $admin = User::findOrFail($this->createUser('admin-unbooked-response@example.test', 0, '000000', [
            'role_id' => 1,
        ]));
        $ownerId = $this->createUser('admin-unbooked-owner@example.test', 1);
        $respondentId = $this->createUser('admin-unbooked-user@example.test', 2);
        $bidId = $this->createBid($ownerId);
        $responseId = DB::table('response_bids')->insertGetId([
            'bid_id' => $bidId,
            'respond_user_id' => $respondentId,
            'description' => 'Synthetic unread response',
            'portfolio_link' => 'https://example.test/portfolio',
            'is_accepted' => 1,
            'is_spam' => 0,
        ]);

        $this->actingAs($admin, 'controlpanel')
            ->postJson('/controlpanel/paynow-page', ['bidId' => $responseId])
            ->assertStatus(422)
            ->assertJsonFragment(['status' => false]);

        $this->assertSame(1, (int) DB::table('response_bids')->where('id', $responseId)->value('is_accepted'));
    }

    public function test_admin_can_view_voucher_redemption_and_bid_response_tables()
    {
        $admin = User::findOrFail($this->createUser('admin-bid-tables@example.test', 0, '000000', [
            'role_id' => 1,
        ]));
        $userId = $this->createUser('admin-bid-tables-user@example.test', 2, '000000', [
            'wallet_marketplace' => 30,
        ]);
        RedeemRequest::create([
            'user_id' => $userId,
            'beneficiary_id' => 7,
            'notes' => 'Synthetic pending redemption',
            'status' => 0,
            'points' => 0,
        ]);
        $bidId = $this->createBid($userId);
        DB::table('response_bids')->insert([
            'bid_id' => $bidId,
            'respond_user_id' => $userId,
            'description' => 'Synthetic response table entry',
            'portfolio_link' => 'https://example.test/portfolio',
            'is_accepted' => 3,
            'is_spam' => 1,
        ]);
        DB::table('vouch_codes')->insert([
            'vouch_code' => 'ADMIN-TABLE-SYNTHETIC',
            'vouch_amount' => 25,
            'expeiry_date' => '2030-01-01',
        ]);

        $this->actingAs($admin, 'controlpanel');

        $this->postJson('/controlpanel/list-code')
            ->assertOk()
            ->assertSee('ADMIN-TABLE-SYNTHETIC')
            ->assertSee('created');

        $this->postJson('/controlpanel/list-redeem-request')
            ->assertOk()
            ->assertSee('Pending');

        $this->getJson('/controlpanel/list-response/' . $bidId)
            ->assertOk()
            ->assertSee('Synthetic response table entry')
            ->assertSee('Booked')
            ->assertSee('Spam');
    }

    public function test_admin_voucher_import_requires_a_spreadsheet_file()
    {
        $admin = User::findOrFail($this->createUser('admin-voucher-import@example.test', 0, '000000', [
            'role_id' => 1,
        ]));

        $this->actingAs($admin, 'controlpanel')
            ->from('/controlpanel/bulk-vouch-upload')
            ->post('/controlpanel/bulk-vouch-upload-store')
            ->assertRedirect('/controlpanel/bulk-vouch-upload')
            ->assertSessionHasErrors('vouch_file');
    }

    private function createBid($userId)
    {
        return DB::table('bids')->insertGetId([
            'user_id' => $userId,
            'status' => 'On',
            'country' => 'ZZ',
            'sponsor_country' => 'ZZ',
            'admin_status' => 0,
            'is_paid' => 0,
            'per' => 0,
        ]);
    }
}
