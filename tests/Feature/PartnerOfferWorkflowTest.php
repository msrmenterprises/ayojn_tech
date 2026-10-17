<?php

namespace Tests\Feature;

use App\Offer;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\Concerns\CreatesTestUsers;
use Tests\TestCase;

class PartnerOfferWorkflowTest extends TestCase
{
    use DatabaseTransactions;
    use CreatesTestUsers;

    public function test_partner_offer_listing_applies_filters_and_only_shows_owned_offers()
    {
        $partner = $this->createUser('partner-filter-owner@example.test', 3);
        $otherPartner = $this->createUser('partner-filter-other@example.test', 3);
        $matchingOffer = $this->createOffer($partner, [
            'offer_for' => 'Synthetic Event',
            'identity' => 'Synthetic Sponsor',
            'available_in' => 'ZZ',
            'function' => 1,
            'currency' => 'USD',
            'refund' => 1,
            'title' => 'Owned Matching Synthetic Offer',
        ]);
        $this->createOffer($partner, [
            'offer_for' => 'Different Event',
            'title' => 'Owned Nonmatching Synthetic Offer',
        ]);
        $this->createOffer($otherPartner, [
            'offer_for' => 'Synthetic Event',
            'identity' => 'Synthetic Sponsor',
            'available_in' => 'ZZ',
            'function' => 1,
            'currency' => 'USD',
            'refund' => 1,
            'title' => 'Other Partner Synthetic Offer',
        ]);

        $this->actingAs(User::findOrFail($partner), 'web')
            ->get('/partner/home?f=Synthetic%20Event&c=ZZ&b=Synthetic%20Sponsor&i=1&cu=USD&refund=1')
            ->assertOk()
            ->assertSee($matchingOffer->title)
            ->assertDontSee('Owned Nonmatching Synthetic Offer')
            ->assertDontSee('Other Partner Synthetic Offer');
    }

    public function test_partner_offer_details_include_ordered_items_for_that_offer()
    {
        $partner = User::findOrFail($this->createUser('partner-offer-detail@example.test', 3));
        $buyer = User::findOrFail($this->createUser('partner-offer-buyer@example.test', 2));
        $offer = $this->createOffer($partner->id, [
            'title' => 'Synthetic Offer With Order',
        ]);
        $orderId = DB::table('offers_order')->insertGetId([
            'user_id' => $buyer->id,
            'pay_amt' => 15,
            'total_amt' => 15,
        ]);
        DB::table('offers_order_item')->insert([
            'order_id' => $orderId,
            'offer_id' => $offer->id,
            'offer_amt' => 15,
            'qty' => 1,
            'final_amt' => 15,
            'status' => 2,
        ]);

        $this->actingAs($partner, 'web')
            ->get('/partner/offer-details/' . $offer->id)
            ->assertOk()
            ->assertSee('Synthetic Offer With Order');
    }

    public function test_partner_can_toggle_offer_status()
    {
        $partner = User::findOrFail($this->createUser('partner-toggle-offer@example.test', 3));
        $offer = $this->createOffer($partner->id, ['status' => 'On']);

        $this->actingAs($partner, 'web')
            ->postJson('/partner/change-offer-status', [
                'id' => $offer->id,
                'status' => 'On',
            ])
            ->assertJsonFragment([
                'status' => true,
                'message' => 'Offer status has been changed.',
            ]);

        $this->assertSame('Off', DB::table('offers')->where('id', $offer->id)->value('status'));
    }

    public function test_partner_account_review_and_suspension_pages_follow_account_state()
    {
        $partner = User::findOrFail($this->createUser('partner-account-state@example.test', 3));
        $partner->userstatus = 0;
        $partner->save();
        $this->actingAs($partner, 'web')
            ->get('/partner/pending')
            ->assertOk();

        $partner->userstatus = 1;
        $partner->save();
        $this->get('/partner/pending')
            ->assertRedirect('/partner/home');

        $partner->userstatus = 0;
        $partner->is_suspend = 0;
        $partner->save();
        $this->get('/partner/suspend')
            ->assertRedirect('/partner/home');

        $partner->is_suspend = 1;
        $partner->save();
        $this->get('/partner/suspend')
            ->assertOk();
    }

    public function test_partner_profile_update_rejects_an_email_used_by_another_account()
    {
        $partner = User::findOrFail($this->createUser('partner-profile-collision@example.test', 3));
        $otherUser = $this->createUser('partner-profile-existing@example.test', 2);

        $this->actingAs($partner, 'web')
            ->postJson('/partner/update-userprofile', [
                'useremail_edit' => 'partner-profile-existing@example.test',
                'contact_person' => 'Should Not Be Saved',
                'edit_company_name' => 'Should Not Be Saved',
                'phone_no_edit' => '5550177',
            ])
            ->assertJson([
                'status' => 0,
                'msg' => 'Email Address is already in use',
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $partner->id,
            'email' => 'partner-profile-collision@example.test',
            'userstatus' => 1,
            'is_edited' => 0,
        ]);
        $this->assertNotSame($partner->id, $otherUser);
    }

    private function createOffer($partnerId, array $attributes = [])
    {
        return Offer::create(array_merge([
            'partner_id' => $partnerId,
            'offer_for' => 'Synthetic Partner Event',
            'offerred_by' => 'Synthetic Partner',
            'identity' => 'Synthetic Identity',
            'function' => 1,
            'title' => 'Synthetic Partner Offer',
            'available_in' => 'ZZ',
            'currency' => 'USD',
            'qty' => 2,
            'status' => 'On',
            'admin_status' => 1,
            'refund' => null,
        ], $attributes));
    }
}
