<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
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
}
