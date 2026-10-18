<?php

namespace Tests\Feature;

use App\Offer;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\Concerns\CreatesTestUsers;
use Tests\TestCase;

class MarketplaceWorkflowTest extends TestCase
{
    use DatabaseTransactions;
    use CreatesTestUsers;

    public function test_offer_user_can_view_their_bid_marketplace()
    {
        $this->withoutExceptionHandling();
        $user = User::findOrFail($this->createUser('offer-bids@example.test', 1));

        $this->actingAs($user, 'web')
            ->get('/bid')
            ->assertStatus(200);
    }

    public function test_receive_user_can_view_available_bids()
    {
        $this->withoutExceptionHandling();
        $user = User::findOrFail($this->createUser('receive-bids@example.test', 2));

        $this->actingAs($user, 'web')
            ->get('/bids')
            ->assertStatus(200);
    }

    public function test_authenticated_user_can_view_marketplace_offers()
    {
        $user = User::findOrFail($this->createUser('marketplace-offers@example.test', 1));

        $this->actingAs($user, 'web')
            ->get('/offers')
            ->assertStatus(200);
    }

    public function test_marketplace_listing_filters_audience_price_and_offer_attributes()
    {
        $client = User::findOrFail($this->createUser('marketplace-list-client@example.test', 1));
        $agency = User::findOrFail($this->createUser('marketplace-list-agency@example.test', 2));
        DB::table('user_type')->whereIn('id', [$client->user_type, $agency->user_type])
            ->update(['user_type' => 'delta']);

        $partnerId = $this->createUser('marketplace-list-partner@example.test', 3);
        $this->createOffer($partnerId, [
            'title' => 'Synthetic Client Free Offer',
            'offer_for' => 'Clients',
            'identity' => 'Client category',
            'function' => 1,
            'currency' => 'USD',
            'refund' => 1,
            'deal_amount_delta' => null,
        ]);
        $this->createOffer($partnerId, [
            'title' => 'Synthetic Shared Paid Offer',
            'offer_for' => 'Both',
            'identity' => 'Shared category',
            'function' => 2,
            'currency' => 'EUR',
            'refund' => null,
            'deal_amount_delta' => 25,
        ]);
        $this->createOffer($partnerId, [
            'title' => 'Synthetic Agency Offer',
            'offer_for' => 'Agencies',
            'identity' => 'Agency category',
            'deal_amount_delta' => null,
        ]);
        $this->createOffer($partnerId, [
            'title' => 'Synthetic Disabled Offer',
            'status' => 'Off',
        ]);
        $this->createOffer($partnerId, [
            'title' => 'Synthetic Unapproved Offer',
            'admin_status' => 0,
        ]);

        $this->actingAs($client, 'web')
            ->get('/offers')
            ->assertOk()
            ->assertSee('Synthetic Client Free Offer')
            ->assertSee('Synthetic Shared Paid Offer')
            ->assertDontSee('Synthetic Agency Offer')
            ->assertDontSee('Synthetic Disabled Offer')
            ->assertDontSee('Synthetic Unapproved Offer');

        $this->actingAs($agency, 'web')
            ->get('/offers')
            ->assertOk()
            ->assertSee('Synthetic Agency Offer')
            ->assertSee('Synthetic Shared Paid Offer')
            ->assertDontSee('Synthetic Client Free Offer');

        $this->actingAs($client, 'web')
            ->get('/offers?f=Free&c=ZZ&b=Client%20category&i=1&cu=USD&refund=1')
            ->assertOk()
            ->assertSee('Synthetic Client Free Offer')
            ->assertDontSee('Synthetic Shared Paid Offer');

        $this->get('/offers?f=Paid&c=ZZ&b=Shared%20category&i=2&cu=EUR&refund=NULL')
            ->assertOk()
            ->assertSee('Synthetic Shared Paid Offer')
            ->assertDontSee('Synthetic Client Free Offer');
    }

    public function test_authenticated_user_can_view_an_empty_cart()
    {
        $this->withoutExceptionHandling();
        $user = User::findOrFail($this->createUser('empty-cart@example.test', 1));

        $this->actingAs($user, 'web')
            ->get('/cart')
            ->assertStatus(200);
    }

    public function test_authenticated_user_can_view_an_empty_wishlist()
    {
        $user = User::findOrFail($this->createUser('empty-wishlist@example.test', 1));

        $this->actingAs($user, 'web')
            ->get('/wishlist')
            ->assertStatus(200);
    }

    public function test_receive_user_can_view_their_bid_responses()
    {
        $user = User::findOrFail($this->createUser('my-bid-responses@example.test', 2));

        $this->actingAs($user, 'web')
            ->get('/my-bids')
            ->assertStatus(200);
    }

    private function createOffer($partnerId, array $attributes = [])
    {
        return Offer::create(array_merge([
            'partner_id' => $partnerId,
            'offer_for' => 'Both',
            'offerred_by' => 'Synthetic Partner',
            'identity' => 'Synthetic Marketplace Category',
            'function' => 1,
            'title' => 'Synthetic Marketplace Listing Offer',
            'available_in' => 'ZZ',
            'currency' => 'USD',
            'qty' => 5,
            'status' => 'On',
            'admin_status' => 1,
            'weblink' => 'https://example.test/offer',
            'notification_email' => 'partner@example.test',
        ], $attributes));
    }
}
