<?php

namespace Tests\Feature;

use App\Http\Controllers\UserAuth\BidController;
use App\Offer;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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

        $this->patch('/update-cart', [
            'id' => $cart->cart_id,
            'quantity' => 1,
            'type' => 'minus',
        ]);
        $this->assertSame(1, (int) DB::table('cart')->where('cart_id', $cart->cart_id)->value('quantity'));
        $this->assertSame(4, (int) DB::table('offers')->where('id', $offer->id)->value('qty'));

        $this->delete('/remove-from-cart', ['id' => $cart->cart_id]);
        $this->assertSame(0, DB::table('cart')->where('cart_id', $cart->cart_id)->count());
        $this->assertSame(5, (int) DB::table('offers')->where('id', $offer->id)->value('qty'));
    }

    public function test_cart_rejects_quantity_above_available_inventory()
    {
        $user = User::findOrFail($this->createUser('cart-stock-limit-user@example.test', 1));
        $partnerId = $this->createUser('cart-stock-limit-partner@example.test', 3);
        $offer = $this->createOffer($partnerId, 2);

        $this->actingAs($user, 'web')->get('/add-to-cart/' . $offer->id);
        $cart = DB::table('cart')->where('user_id', $user->id)->where('id', $offer->id)->first();

        $this->from('/cart')
            ->patch('/update-cart', [
                'id' => $cart->cart_id,
                'quantity' => 1,
                'type' => 'plus',
            ])
            ->assertOk()
            ->assertSessionHas('error', 'Product quantity is not available!');

        $this->assertSame(1, (int) DB::table('cart')->where('cart_id', $cart->cart_id)->value('quantity'));
        $this->assertSame(1, (int) DB::table('offers')->where('id', $offer->id)->value('qty'));
    }

    public function test_out_of_stock_offer_cannot_be_added_to_cart_or_wishlist()
    {
        $user = User::findOrFail($this->createUser('out-of-stock-user@example.test', 1));
        $partnerId = $this->createUser('out-of-stock-partner@example.test', 3);
        $cartOffer = $this->createOffer($partnerId, 0, ['title' => 'Synthetic Out of Stock Cart Offer']);
        $wishOffer = $this->createOffer($partnerId, 0, ['title' => 'Synthetic Out of Stock Wish Offer']);

        $this->actingAs($user, 'web')
            ->from('/offers')
            ->get('/add-to-cart/' . $cartOffer->id)
            ->assertRedirect('/offers')
            ->assertSessionHas('error', 'Product quantity is not available or out of stock!');

        $this->from('/offers')
            ->get('/add-to-wish/' . $wishOffer->id)
            ->assertRedirect('/offers')
            ->assertSessionHas('error', 'Product quantity is not available or out of stock!');

        $this->assertSame(0, DB::table('cart')->where('user_id', $user->id)->count());
        $this->assertSame(0, DB::table('wishlist')->where('user_id', $user->id)->count());
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

    public function test_wishlisted_offer_moves_to_cart_and_merges_with_an_existing_cart_item()
    {
        $user = User::findOrFail($this->createUser('wishlist-cart-user@example.test', 1));
        DB::table('user_type')->where('id', $user->user_type)->update(['user_type' => 'delta']);
        $partnerId = $this->createUser('wishlist-cart-partner@example.test', 3);
        $offer = $this->createOffer($partnerId, 4, ['deal_amount_delta' => 25]);
        $this->actingAs($user, 'web');

        $this->get('/add-to-wish/' . $offer->id);
        $this->assertSame(1, DB::table('wishlist')->where('user_id', $user->id)->count());
        $this->get('/add-to-cart-wishlist/' . $offer->id);

        $this->assertSame(1, (int) DB::table('cart')->where('user_id', $user->id)->where('id', $offer->id)->value('quantity'));
        $this->assertSame(0, DB::table('wishlist')->where('user_id', $user->id)->count());
        $this->assertSame(3, (int) DB::table('offers')->where('id', $offer->id)->value('qty'));

        DB::table('wishlist')->insert($this->cartItemData($user->id, $offer));
        $this->get('/add-to-cart-wishlist/' . $offer->id);

        $this->assertSame(2, (int) DB::table('cart')->where('user_id', $user->id)->where('id', $offer->id)->value('quantity'));
        $this->assertSame(0, DB::table('wishlist')->where('user_id', $user->id)->count());
        $this->assertSame(3, (int) DB::table('offers')->where('id', $offer->id)->value('qty'));
    }

    public function test_wishlisted_offer_without_a_user_tier_price_moves_to_cart_at_zero_price()
    {
        $user = User::findOrFail($this->createUser('wishlist-zero-price-user@example.test', 1));
        $partnerId = $this->createUser('wishlist-zero-price-partner@example.test', 3);
        $offer = $this->createOffer($partnerId, 2);
        $this->actingAs($user, 'web');

        $this->get('/add-to-wish/' . $offer->id);
        $this->get('/add-to-cart-wishlist/' . $offer->id);

        $cartItem = DB::table('cart')->where('user_id', $user->id)->where('id', $offer->id)->first();
        $this->assertNotNull($cartItem);
        $this->assertSame(0, (int) $cartItem->deal_amount);
        $this->assertSame(0, DB::table('wishlist')->where('user_id', $user->id)->count());
    }

    public function test_readding_a_wishlisted_offer_increments_the_wishlist_entry()
    {
        $user = User::findOrFail($this->createUser('wishlist-duplicate-user@example.test', 1));
        DB::table('user_type')->where('id', $user->user_type)->update(['user_type' => 'delta']);
        $partnerId = $this->createUser('wishlist-duplicate-partner@example.test', 3);
        $offer = $this->createOffer($partnerId, 3, ['deal_amount_delta' => 20]);
        $this->actingAs($user, 'web');

        $this->get('/add-to-wish/' . $offer->id);
        $this->get('/add-to-wish/' . $offer->id);

        $wishlist = DB::table('wishlist')->where('user_id', $user->id)->where('id', $offer->id)->first();
        $this->assertNotNull($wishlist);
        $this->assertSame(2, (int) $wishlist->quantity);
        $this->assertSame(1, (int) DB::table('offers')->where('id', $offer->id)->value('qty'));
        $this->assertSame(0, DB::table('cart')->where('user_id', $user->id)->count());
    }

    public function test_cart_and_wishlist_pages_render_saved_offers()
    {
        $user = User::findOrFail($this->createUser('saved-marketplace-items@example.test', 1));
        $partnerId = $this->createUser('saved-marketplace-items-partner@example.test', 3);
        $cartOffer = $this->createOffer($partnerId, 3, ['title' => 'Synthetic Saved Cart Offer']);
        $wishOffer = $this->createOffer($partnerId, 3, ['title' => 'Synthetic Saved Wish Offer']);

        $this->actingAs($user, 'web')->get('/add-to-cart/' . $cartOffer->id);
        $this->get('/add-to-wish/' . $wishOffer->id);

        $this->get('/cart')->assertOk()->assertSee('Synthetic Saved Cart Offer');
        $this->get('/wishlist')->assertOk()->assertSee('Synthetic Saved Wish Offer');
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

    public function test_free_checkout_marks_a_voucher_used_without_calling_the_gateway()
    {
        $user = User::findOrFail($this->createUser('cart-voucher-user@example.test', 1));
        $partnerId = $this->createUser('cart-voucher-partner@example.test', 3);
        $offer = $this->createOffer($partnerId, 2);
        DB::table('vouch_codes')->insert([
            'vouch_code' => 'SYNTHETIC-MARKETPLACE-FREE',
            'vouch_amount' => 10,
            'expeiry_date' => '2035-01-01',
            'status' => 0,
        ]);
        $controller = $this->bindGatewayController(['error' => 'Gateway should not be called']);

        $this->actingAs($user, 'web')->get('/add-to-cart/' . $offer->id);
        $this->post('/checkout', [
            'btn_name' => 'free',
            'pay_hidden' => 0,
            'walletamt_hidden' => 0,
            'wallet_balance' => 0,
            'wallet_use' => 0,
            'paidamt_hidden' => 0,
            'voucher_code_new' => 'SYNTHETIC-MARKETPLACE-FREE',
        ])->assertOk();

        $this->assertSame(0, $controller->requestCount());
        $this->assertDatabaseHas('vouch_codes', [
            'vouch_code' => 'SYNTHETIC-MARKETPLACE-FREE',
            'used_by' => $user->id,
            'status' => 1,
        ]);
        $this->assertSame(1, DB::table('offers_order_item')->where('offer_id', $offer->id)->count());
        $this->assertSame(0, DB::table('cart')->where('user_id', $user->id)->count());
    }

    public function test_checkout_with_an_empty_cart_does_not_create_an_order()
    {
        $user = User::findOrFail($this->createUser('empty-checkout-user@example.test', 1));

        $this->actingAs($user, 'web')
            ->post('/checkout', [
                'btn_name' => 'free',
                'wallet_use' => 0,
                'paidamt_hidden' => 0,
            ])
            ->assertOk()
            ->assertViewIs('user.cart');

        $this->assertSame(0, DB::table('offers_order')->where('user_id', $user->id)->count());
    }

    public function test_paid_checkout_uses_stub_gateway_and_redirects_to_payment()
    {
        $user = User::findOrFail($this->createUser('paid-cart-user@example.test', 1, '000000', [
            'wallet_marketplace' => 50,
        ]));
        DB::table('user_type')->where('id', $user->user_type)->update(['user_type' => 'delta']);
        $partnerId = $this->createUser('paid-cart-partner@example.test', 3);
        $offer = $this->createOffer($partnerId, 3, ['deal_amount_delta' => 40]);
        $controller = $this->bindGatewayController([
            'status' => 201,
            'response' => [
                'details' => [
                    'collection_id' => 94007,
                    'order_reference' => 'SYNTHETIC-MARKETPLACE-REFERENCE',
                    'payment_url' => 'https://payments.example.test/marketplace',
                ],
            ],
        ]);

        $this->actingAs($user, 'web')->get('/add-to-cart/' . $offer->id);
        $this->from('/cart')
            ->post('/checkout', [
                'btn_name' => 'paid',
                'pay_hidden' => 25,
                'walletamt_hidden' => 15,
                'wallet_balance' => 50,
                'wallet_use' => 15,
                'paidamt_hidden' => 25,
                'voucher_code_new' => 'null',
            ])
            ->assertRedirect('https://payments.example.test/marketplace');

        $this->assertSame(25.0, (float) $controller->requestedAmount());
        $order = DB::table('offers_order')->where('user_id', $user->id)->first();
        $this->assertNotNull($order);
        $this->assertSame(25.0, (float) $order->pay_amt);
        $this->assertSame(15.0, (float) $order->wallet);
        $this->assertDatabaseHas('offers_order_item', [
            'order_id' => $order->id,
            'offer_id' => $offer->id,
            'qty' => 1,
            'final_amt' => 40,
        ]);
        $payment = DB::table('direct_payment')->where('refrence_id', $order->id)->first();
        $this->assertNotNull($payment);
        $this->assertSame(94007, (int) $payment->collection_id);
        $this->assertSame('SYNTHETIC-MARKETPLACE-REFERENCE', $payment->transaction_reference);
        $this->assertSame('marketplace_payment', $payment->form_name);
        $this->assertSame(1, DB::table('cart')->where('user_id', $user->id)->count());
        $this->assertSame(50, (int) DB::table('users')->where('id', $user->id)->value('wallet_marketplace'));
    }

    public function test_marketplace_payment_callback_updates_wallet_and_final_payment_history()
    {
        $user = User::findOrFail($this->createUser('marketplace-payment-callback@example.test', 1, '000000', [
            'wallet_marketplace' => 50,
        ]));
        DB::table('user_type')->where('id', $user->user_type)->update(['user_type' => 'delta']);
        $partnerId = $this->createUser('marketplace-payment-callback-partner@example.test', 3);
        $offer = $this->createOffer($partnerId, 3, [
            'title' => 'Synthetic Paid Marketplace Offer',
            'deal_amount_delta' => 40,
        ]);
        $this->bindGatewayController([
            'status' => 201,
            'response' => [
                'details' => [
                    'collection_id' => 94008,
                    'order_reference' => 'SYNTHETIC-MARKETPLACE-CALLBACK-REFERENCE',
                    'payment_url' => 'https://payments.example.test/marketplace-callback',
                ],
            ],
        ]);
        $this->actingAs($user, 'web')->get('/add-to-cart/' . $offer->id);
        $this->post('/checkout', [
            'btn_name' => 'paid',
            'pay_hidden' => 25,
            'walletamt_hidden' => 15,
            'wallet_balance' => 50,
            'wallet_use' => 15,
            'paidamt_hidden' => 25,
            'voucher_code_new' => 'null',
        ])->assertRedirect('https://payments.example.test/marketplace-callback');

        $pdf = \Mockery::mock();
        $pdf->shouldReceive('output')->twice()->andReturn('synthetic-pdf-content');
        \PDF::shouldReceive('loadView')->twice()->andReturn($pdf);
        $mailMessage = \Mockery::mock();
        $mailMessage->shouldReceive('to')->once()->andReturnSelf();
        $mailMessage->shouldReceive('cc')->once()->andReturnSelf();
        $mailMessage->shouldReceive('subject')->once()->andReturnSelf();
        $mailMessage->shouldReceive('attachData')->twice()->andReturnSelf();
        Mail::shouldReceive('send')->once()->andReturnUsing(function ($view, $data, $callback) use ($mailMessage) {
            $callback($mailMessage);
        });

        $this->get('/success?' . http_build_query([
            'amount' => 25,
            'payment_date' => '2026-10-18',
            'payment_mode' => 'Synthetic Card',
            'transaction_status' => 'Success',
            'transaction_reference' => 'SYNTHETIC-MARKETPLACE-CALLBACK-REFERENCE',
        ]))
            ->assertOk()
            ->assertViewIs('user.success')
            ->assertViewHas('redirection', 'marketplace-history');

        $order = DB::table('offers_order')->where('user_id', $user->id)->first();
        $this->assertDatabaseHas('direct_payment', [
            'transaction_reference' => 'SYNTHETIC-MARKETPLACE-CALLBACK-REFERENCE',
            'status_desc' => 'Success',
            'refrence_id' => $order->id,
        ]);
        $this->assertSame(35, (int) DB::table('users')->where('id', $user->id)->value('wallet_marketplace'));
        $this->assertDatabaseHas('wallet_logs', [
            'user_id' => $user->id,
            'points_by' => 4,
            'trans_id' => $order->id,
            'point' => 15,
            'wallet_type' => 1,
            'point_type' => 'DR',
        ]);
        $this->assertSame(0, DB::table('cart')->where('user_id', $user->id)->count());

        $this->get('/marketplace-history')
            ->assertOk()
            ->assertSee('Synthetic Paid Marketplace Offer')
            ->assertSee('Success');
    }

    public function test_paid_checkout_gateway_failure_preserves_cart_and_skips_payment_writes()
    {
        $user = User::findOrFail($this->createUser('failed-paid-cart-user@example.test', 1));
        $partnerId = $this->createUser('failed-paid-cart-partner@example.test', 3);
        $offer = $this->createOffer($partnerId, 2, ['deal_amount_delta' => 25]);
        $this->bindGatewayController(['error' => 'Synthetic gateway unavailable']);
        Log::shouldReceive('error')->once();

        $this->actingAs($user, 'web')->get('/add-to-cart/' . $offer->id);
        $this->from('/cart')
            ->post('/checkout', [
                'btn_name' => 'paid',
                'pay_hidden' => 25,
                'walletamt_hidden' => 0,
                'wallet_balance' => 0,
                'wallet_use' => 0,
                'paidamt_hidden' => 25,
                'voucher_code_new' => 'null',
            ])
            ->assertRedirect('/cart')
            ->assertSessionHas('error', 'The payment gateway is temporarily unavailable. Please try again.');

        $this->assertSame(0, DB::table('offers_order')->where('user_id', $user->id)->count());
        $this->assertSame(0, DB::table('direct_payment')->where('user_id', $user->id)->count());
        $this->assertSame(1, DB::table('cart')->where('user_id', $user->id)->count());
    }

    public function test_paid_checkout_rejects_invalid_gateway_response_before_order_writes()
    {
        $user = User::findOrFail($this->createUser('invalid-paid-cart-user@example.test', 1));
        $partnerId = $this->createUser('invalid-paid-cart-partner@example.test', 3);
        $offer = $this->createOffer($partnerId, 2, ['deal_amount_delta' => 25]);
        $this->bindGatewayController([
            'status' => 503,
            'response' => ['message' => 'Synthetic service unavailable'],
        ]);
        Log::shouldReceive('error')->once();

        $this->actingAs($user, 'web')->get('/add-to-cart/' . $offer->id);
        $this->from('/cart')
            ->post('/checkout', [
                'btn_name' => 'paid',
                'pay_hidden' => 25,
                'wallet_use' => 0,
                'paidamt_hidden' => 25,
                'voucher_code_new' => 'null',
            ])
            ->assertRedirect('/cart')
            ->assertSessionHas('error', 'The payment gateway is temporarily unavailable. Please try again.');

        $this->assertSame(0, DB::table('offers_order')->where('user_id', $user->id)->count());
        $this->assertSame(0, DB::table('direct_payment')->where('user_id', $user->id)->count());
    }

    private function createOffer($partnerId, $quantity, array $attributes = [])
    {
        return Offer::create(array_merge([
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
        ], $attributes));
    }

    private function cartItemData($userId, Offer $offer)
    {
        return [
            'id' => $offer->id,
            'identity' => $offer->identity,
            'industry' => '-',
            'title' => $offer->title,
            'discount' => $offer->discount,
            'deal_amount' => 0,
            'country_name' => 'Synthetic Test Country',
            'weblink' => $offer->weblink,
            'notification_email' => $offer->notification_email,
            'quantity' => 1,
            'user_id' => $userId,
            'status' => 1,
        ];
    }

    private function bindGatewayController(array $gatewayResult)
    {
        $controller = new class($gatewayResult) extends BidController {
            private $gatewayResult;
            private $requestCount = 0;
            private $requestedAmount;

            public function __construct(array $gatewayResult)
            {
                parent::__construct();
                $this->gatewayResult = $gatewayResult;
            }

            public function sendRequest($data)
            {
                $this->requestCount++;
                $this->requestedAmount = $data['amount'];

                return $this->gatewayResult;
            }

            public function requestCount()
            {
                return $this->requestCount;
            }

            public function requestedAmount()
            {
                return $this->requestedAmount;
            }
        };
        $this->app->instance(BidController::class, $controller);

        return $controller;
    }
}
