<?php

namespace Tests\Feature;

use App\Offer;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\Concerns\CreatesTestUsers;
use Tests\TestCase;

class UserMarketplaceCartWorkflowTest extends TestCase
{
    use DatabaseTransactions;
    use CreatesTestUsers;

    public function test_adding_an_offer_to_cart_updates_quantity_and_inventory()
    {
        $user = User::findOrFail($this->createUser('cart-quantity-user@example.test', 1));
        $partnerId = $this->createUser('cart-quantity-partner@example.test', 3);
        $offer = $this->createOffer($partnerId, 4);

        $this->actingAs($user, 'web');
        $this->get('/add-to-cart/' . $offer->id);
        $this->assertDatabaseHas('cart', [
            'id' => $offer->id,
            'user_id' => $user->id,
            'quantity' => 1,
        ]);
        $this->assertSame(3, (int) DB::table('offers')->where('id', $offer->id)->value('qty'));

        $this->get('/add-to-cart/' . $offer->id);
        $this->assertSame(2, (int) DB::table('cart')->where('user_id', $user->id)->where('id', $offer->id)->value('quantity'));
        $this->assertSame(2, (int) DB::table('offers')->where('id', $offer->id)->value('qty'));
    }

    public function test_cart_quantity_updates_and_removal_restore_offer_inventory()
    {
        $user = User::findOrFail($this->createUser('cart-inventory-user@example.test', 1));
        $partnerId = $this->createUser('cart-inventory-partner@example.test', 3);
        $offer = $this->createOffer($partnerId, 5);

        $this->actingAs($user, 'web');
        $this->get('/add-to-cart/' . $offer->id);
        $cart = DB::table('cart')->where('user_id', $user->id)->where('id', $offer->id)->first();

        $this->patch('/update-cart', [
            'id' => $cart->cart_id,
            'quantity' => 1,
            'type' => 'plus',
        ]);

        $this->assertSame(2, (int) DB::table('cart')->where('cart_id', $cart->cart_id)->value('quantity'));
        $this->assertSame(3, (int) DB::table('offers')->where('id', $offer->id)->value('qty'));

        $this->delete('/remove-from-cart', ['id' => $cart->cart_id]);
        $this->assertSame(0, DB::table('cart')->where('cart_id', $cart->cart_id)->count());
        $this->assertSame(5, (int) DB::table('offers')->where('id', $offer->id)->value('qty'));
    }

    public function test_wishlist_addition_and_removal_update_offer_inventory()
    {
        $user = User::findOrFail($this->createUser('wishlist-inventory-user@example.test', 1));
        $partnerId = $this->createUser('wishlist-inventory-partner@example.test', 3);
        $offer = $this->createOffer($partnerId, 3);

        $this->actingAs($user, 'web');
        $this->get('/add-to-wish/' . $offer->id);
        $wishlist = DB::table('wishlist')->where('user_id', $user->id)->where('id', $offer->id)->first();
        $this->assertNotNull($wishlist);
        $this->assertSame(2, (int) DB::table('offers')->where('id', $offer->id)->value('qty'));

        $this->delete('/remove_from_wishlist', ['id' => $wishlist->cart_id]);
        $this->assertSame(0, DB::table('wishlist')->where('cart_id', $wishlist->cart_id)->count());
        $this->assertSame(3, (int) DB::table('offers')->where('id', $offer->id)->value('qty'));
    }

    public function test_free_cart_checkout_creates_an_order_and_debits_marketplace_wallet()
    {
        $user = User::findOrFail($this->createUser('cart-checkout-user@example.test', 1, '000000', [
            'wallet_marketplace' => 50,
        ]));
        $partnerId = $this->createUser('cart-checkout-partner@example.test', 3);
        $offer = $this->createOffer($partnerId, 2);

        $this->actingAs($user, 'web');
        $this->get('/add-to-cart/' . $offer->id);

        $this->post('/checkout', [
            'btn_name' => 'free',
            'pay_hidden' => 0,
            'walletamt_hidden' => 15,
            'wallet_balance' => 50,
            'wallet_use' => 15,
            'paidamt_hidden' => 15,
            'voucher_code_new' => '',
        ])->assertOk();

        $order = DB::table('offers_order')->where('user_id', $user->id)->first();
        $this->assertNotNull($order);
        $this->assertSame(15, (int) $order->wallet);
        $this->assertDatabaseHas('offers_order_item', [
            'order_id' => $order->id,
            'offer_id' => $offer->id,
            'qty' => 1,
        ]);
        $this->assertSame(35, (int) DB::table('users')->where('id', $user->id)->value('wallet_marketplace'));
        $this->assertDatabaseHas('wallet_logs', [
            'user_id' => $user->id,
            'points_by' => 2,
            'trans_id' => $order->id,
            'point' => 15,
            'wallet_type' => 1,
        ]);
        $this->assertSame(0, DB::table('cart')->where('user_id', $user->id)->count());
    }

    private function createOffer($partnerId, $quantity)
    {
        return Offer::create([
            'partner_id' => $partnerId,
            'offer_for' => 'Both',
            'offerred_by' => 'Synthetic Partner',
            'identity' => 'Synthetic Cart Offer',
            'function' => 1,
            'title' => 'Synthetic Marketplace Cart Offer',
            'available_in' => 'ZZ',
            'currency' => 'USD',
            'qty' => $quantity,
            'status' => 'On',
            'admin_status' => 1,
            'weblink' => 'https://example.test/offer',
            'notification_email' => 'partner@example.test',
        ]);
    }
}
