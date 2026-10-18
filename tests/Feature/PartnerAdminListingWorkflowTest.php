<?php

namespace Tests\Feature;

use App\Offer;
use App\SponsorrSpecify;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\Concerns\CreatesTestUsers;
use Tests\TestCase;

class PartnerAdminListingWorkflowTest extends TestCase
{
    use DatabaseTransactions;
    use CreatesTestUsers;

    public function test_admin_partner_pages_and_datatables_render_partner_review_states()
    {
        $admin = User::findOrFail($this->createUser('partner-list-admin@example.test', 0, '000000', [
            'role_id' => 1,
        ]));
        $referrerId = $this->createUser('partner-list-referrer@example.test', 3);
        $approvedPartner = User::findOrFail($this->createUser('partner-list-approved@example.test', 3, '000000', [
            'email_verified' => 1,
            'userstatus' => 1,
            'is_suspend' => 0,
            'is_edited' => 1,
            'sponsor_industry' => 'test',
        ]));
        $disapprovedPartner = User::findOrFail($this->createUser('partner-list-disapproved@example.test', 3, '000000', [
            'email_verified' => 1,
            'userstatus' => 0,
            'is_suspend' => 1,
            'is_edited' => 1,
            'sponsor_industry' => '',
            'disapprove_time' => '2026-01-02 12:00:00',
            'refer_by' => $referrerId,
        ]));
        $unverifiedPartner = $this->createUser('partner-list-unverified@example.test', 3, '000000', [
            'email_verified' => 0,
            'userstatus' => 1,
            'is_suspend' => 1,
            'is_edited' => 0,
            'refer_by' => $approvedPartner->id,
        ]);
        $specificationId = DB::table('sponsorr_specify_lists')->insertGetId([
            'specify_name' => 'Synthetic partner listing specification',
        ]);
        SponsorrSpecify::create([
            'user_id' => $approvedPartner->id,
            'specify_name' => $specificationId,
        ]);

        $this->actingAs($admin, 'controlpanel');
        $this->get('/controlpanel/partners')->assertOk();
        $this->get('/controlpanel/partnersbank')->assertOk();
        $this->get('/controlpanel/partner-review-list')->assertOk();

        $partnerList = $this->postJson('/controlpanel/partner/list-users', [
            'draw' => 1,
            'start' => 0,
            'length' => 10,
            'search' => ['value' => '', 'regex' => false],
        ])->assertOk();

        $this->assertStringContainsString('partner-list-approved@example.test', $partnerList->getContent());
        $this->assertStringContainsString('Self Registered', $partnerList->getContent());
        $this->assertStringContainsString('Refer By:', $partnerList->getContent());
        $this->assertStringContainsString('partner-list-referrer@example.test', $partnerList->getContent());
        $this->assertStringContainsString('Approved', $partnerList->getContent());
        $this->assertStringContainsString('Disapproved', $partnerList->getContent());
        $this->assertStringContainsString('Not Email Verify', $partnerList->getContent());
        $this->assertStringContainsString('Suspended', $partnerList->getContent());

        $reviewList = $this->postJson('/controlpanel/partner-review-list-data', [
            'draw' => 1,
            'start' => 0,
            'length' => 10,
            'search' => ['value' => '', 'regex' => false],
        ])->assertOk();

        $this->assertStringContainsString('partner-list-approved@example.test', $reviewList->getContent());
        $this->assertStringContainsString('partner-list-disapproved@example.test', $reviewList->getContent());
        $this->assertStringNotContainsString('partner-list-unverified@example.test', $reviewList->getContent());
    }

    public function test_admin_offer_listing_renders_offer_country_partner_and_approval_state()
    {
        $admin = User::findOrFail($this->createUser('partner-offer-list-admin@example.test', 0, '000000', [
            'role_id' => 1,
        ]));
        $partnerId = $this->createUser('partner-offer-list-owner@example.test', 3);
        $offer = Offer::create([
            'partner_id' => $partnerId,
            'offer_for' => 'Both',
            'offerred_by' => 'Synthetic Partner',
            'identity' => 'Synthetic Listing Category',
            'function' => 1,
            'title' => 'Synthetic Admin Listing Offer',
            'available_in' => 'ZZ',
            'currency' => 'USD',
            'qty' => 3,
            'status' => 'On',
            'admin_status' => 0,
            'weblink' => 'https://example.test/admin-listing-offer',
            'notification_email' => 'partner-list@example.test',
        ]);

        $this->actingAs($admin, 'controlpanel')
            ->get('/controlpanel/offers')
            ->assertOk();
        $offerList = $this->postJson('/controlpanel/partner/list-offers', [
            'draw' => 1,
            'start' => 0,
            'length' => 10,
            'search' => ['value' => '', 'regex' => false],
        ])->assertOk();

        $this->assertStringContainsString($offer->title, $offerList->getContent());
        $this->assertStringContainsString('partner-offer-list-owner@example.test', $offerList->getContent());
        $this->assertStringContainsString('Disapproved', $offerList->getContent());
    }
}
