<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\Concerns\CreatesTestUsers;
use Tests\TestCase;

class PartnerWorkflowTest extends TestCase
{
    use DatabaseTransactions;
    use CreatesTestUsers;

    public function test_partner_can_view_their_offers_and_open_the_offer_form()
    {
        $partner = User::findOrFail($this->createUser('partner-pages@example.test', 3, '000000', [
            'company_name' => 'Synthetic Partner',
        ]));

        $this->actingAs($partner, 'web')
            ->get('/partner/home')
            ->assertStatus(200);

        $this->get('/partner/new-offer')
            ->assertStatus(200);
    }

    public function test_partner_offer_creation_validates_required_fields()
    {
        $partner = User::findOrFail($this->createUser('partner-validation@example.test', 3));

        $this->actingAs($partner, 'web')
            ->from('/partner/new-offer')
            ->post('/partner/store-offer', [])
            ->assertRedirect('/partner/new-offer')
            ->assertSessionHasErrors();
    }

    public function test_partner_can_create_an_offer_without_external_payment()
    {
        $partner = User::findOrFail($this->createUser('partner-offer@example.test', 3, '000000', [
            'company_name' => 'Synthetic Partner',
        ]));

        $this->actingAs($partner, 'web')
            ->post('/partner/store-offer', [
                'offer_for' => 'Synthetic event',
                'partner_identity' => 'Synthetic identity',
                'function' => 1,
                'title' => 'Synthetic regression offer',
                'available_in' => 'ZZ',
                'qty' => 3,
                'currency' => 'USD',
                'deal_amount_delta' => 10,
                'deal_amount_sigma' => 20,
                'deal_amount_alpha' => 30,
                'deal_amount_omega' => 40,
                'incentive' => 5,
                'discount' => 10,
                'weblink' => 'https://example.test/offer',
                'notification_email' => 'partner-offer@example.test',
                'refund' => 0,
            ])
            ->assertRedirect('/partner/home')
            ->assertSessionHas('success', 'offer successfully created');

        $offer = DB::table('offers')->where('partner_id', $partner->id)->first();
        $this->assertNotNull($offer);
        $this->assertSame('Synthetic regression offer', $offer->title);
        $this->assertSame('On', $offer->status);
        $this->assertSame(1, (int) $offer->admin_status);
        $this->assertNotEmpty($offer->share_id);
        $this->assertSame(1, (int) DB::table('users')->where('id', $partner->id)->value('free_response'));
    }

    public function test_partner_profile_edit_is_saved_and_sent_for_review()
    {
        $partner = User::findOrFail($this->createUser('partner-profile@example.test', 3));

        $this->actingAs($partner, 'web')
            ->postJson('/partner/update-userprofile', [
                'useremail_edit' => 'updated-partner@example.test',
                'contact_person' => 'Synthetic Contact',
                'edit_company_name' => 'Updated Synthetic Company',
                'phone_no_edit' => '5550199',
            ])
            ->assertJson([
                'status' => 1,
                'msg' => 'Profile Edited Successfully.',
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $partner->id,
            'email' => 'updated-partner@example.test',
            'contact_person' => 'Synthetic Contact',
            'company_name' => 'Updated Synthetic Company',
            'phone_no' => '5550199',
            'userstatus' => 0,
            'is_edited' => 1,
            'edit_count' => 1,
        ]);
    }

    public function test_partner_can_update_a_marketplace_order_item_status()
    {
        $partner = User::findOrFail($this->createUser('partner-order-owner@example.test', 3));
        $buyer = User::findOrFail($this->createUser('partner-order-buyer@example.test', 2));
        $orderId = DB::table('offers_order')->insertGetId([
            'user_id' => $buyer->id,
            'pay_amt' => 25,
            'total_amt' => 25,
        ]);
        $itemId = DB::table('offers_order_item')->insertGetId([
            'order_id' => $orderId,
            'offer_id' => 987654,
            'offer_amt' => 25,
            'qty' => 1,
            'final_amt' => 25,
            'status' => 2,
        ]);

        $this->actingAs($partner, 'web')
            ->postJson('/partner/change-status-user', [
                'id' => $itemId,
                'status' => 4,
            ])
            ->assertJson([
                'status' => true,
                'message' => 'Offer status has been changed.',
            ]);

        $this->assertSame(4, (int) DB::table('offers_order_item')->where('id', $itemId)->value('status'));
    }
}
