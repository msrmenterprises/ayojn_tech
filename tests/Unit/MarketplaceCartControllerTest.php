<?php

namespace Tests\Unit;

use App\Http\Controllers\UserAuth\BidController;
use App\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MarketplaceCartControllerTest extends TestCase
{
    public function test_check_qty_returns_missing_offer_status()
    {
        $response = (new BidController())->check_qty(2147483647, 1);

        $this->assertSame(2, $response->getData(true)['status']);
    }

    public function test_cart_returns_an_error_response_when_storage_fails()
    {
        $user = new User();
        $user->id = 1;
        $this->actingAs($user, 'web');
        DB::shouldReceive('table')
            ->once()
            ->with('cart')
            ->andThrow(new \RuntimeException('Synthetic storage failure'));

        $response = (new BidController())->cart();

        $this->assertSame(500, $response->getStatusCode());
        $this->assertSame([
            'error' => 'Failed to retrieve cart details. Please try again later.',
        ], $response->getData(true));
    }

    public function test_wishlist_returns_an_error_response_when_storage_fails()
    {
        $user = new User();
        $user->id = 1;
        $this->actingAs($user, 'web');
        DB::shouldReceive('table')
            ->once()
            ->with('wishlist')
            ->andThrow(new \RuntimeException('Synthetic storage failure'));

        $response = (new BidController())->wishlist();

        $this->assertSame(500, $response->getStatusCode());
        $this->assertSame([
            'error' => 'Failed to retrieve cart details. Please try again later.',
        ], $response->getData(true));
    }
}
