<?php

namespace Tests\Feature;

use App\Http\Controllers\Partner\UserController as PartnerUserController;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Tests\Concerns\CreatesTestUsers;
use Tests\TestCase;

class PartnerPaymentWorkflowTest extends TestCase
{
    use DatabaseTransactions;
    use CreatesTestUsers;

    public function test_partner_offer_payment_records_gateway_details_without_debiting_the_wallet()
    {
        $partner = User::findOrFail($this->createUser('partner-payment@example.test', 3));
        $this->bindPaymentController([
            'status' => 200,
            'response' => [
                'details' => [
                    'collection_id' => 'SYNTHETIC-PARTNER-COLLECTION',
                    'order_reference' => 'SYNTHETIC-PARTNER-REFERENCE',
                    'payment_url' => 'https://payments.example.test/partner-offer',
                ],
            ],
        ]);
        Mail::shouldReceive('send')->once();

        $this->actingAs($partner, 'web')
            ->postJson('/partner/paynow-partner-offer-create', [
                'form_data' => $this->offerFormData(),
                'amt' => 25,
                'wallet' => 0,
                'voucher_code' => '',
            ])
            ->assertOk()
            ->assertJson([
                'flag' => 1,
                'link' => 'https://payments.example.test/partner-offer',
            ]);

        $offer = DB::table('offers')
            ->where('partner_id', $partner->id)
            ->where('title', 'Synthetic Payment Offer')
            ->first();
        $this->assertNotNull($offer);
        $this->assertSame('On', $offer->status);
        $this->assertSame(0, (int) $offer->admin_status);
        $this->assertNotEmpty($offer->share_id);
        $this->assertSame(1, (int) DB::table('users')->where('id', $partner->id)->value('free_response'));

        $this->assertDatabaseHas('direct_payment', [
            'user_id' => $partner->id,
            'collection_id' => 'SYNTHETIC-PARTNER-COLLECTION',
            'transaction_reference' => 'SYNTHETIC-PARTNER-REFERENCE',
            'form_name' => 'partner_offer',
            'refrence_id' => $offer->id,
            'amount' => 25,
        ]);
        $this->assertSame(0, DB::table('wallet_logs')->where('user_id', $partner->id)->count());
        $this->assertSame(0, (int) DB::table('users')->where('id', $partner->id)->value('wallet_balance'));
    }

    public function test_partner_offer_without_a_gateway_charge_is_activated_immediately()
    {
        $partner = User::findOrFail($this->createUser('partner-free-offer-payment@example.test', 3));
        $this->bindPaymentController(null);
        Mail::shouldReceive('send')->once();

        $this->actingAs($partner, 'web')
            ->postJson('/partner/paynow-partner-offer-create', [
                'form_data' => $this->offerFormData('Synthetic Free Offer'),
                'amt' => 0,
                'wallet' => 0,
                'voucher_code' => '',
            ])
            ->assertOk()
            ->assertJson([
                'flag' => 0,
                'link' => '',
            ]);

        $this->assertDatabaseHas('offers', [
            'partner_id' => $partner->id,
            'title' => 'Synthetic Free Offer',
            'status' => 'On',
            'admin_status' => 1,
        ]);
        $this->assertSame(0, DB::table('direct_payment')
            ->where('user_id', $partner->id)
            ->where('form_name', 'partner_offer')
            ->count());
        $this->assertSame(0, DB::table('wallet_logs')->where('user_id', $partner->id)->count());
    }

    public function test_partner_offer_payment_debits_the_wallet_and_links_the_ledger_to_the_offer()
    {
        $this->withoutExceptionHandling();
        $partner = User::findOrFail($this->createUser('partner-wallet-payment@example.test', 3, '000000', [
            'wallet_balance' => 40,
        ]));
        $this->bindPaymentController([
            'status' => 200,
            'response' => [
                'details' => [
                    'collection_id' => 'SYNTHETIC-PARTNER-WALLET-COLLECTION',
                    'order_reference' => 'SYNTHETIC-PARTNER-WALLET-REFERENCE',
                    'payment_url' => 'https://payments.example.test/partner-offer-wallet',
                ],
            ],
        ]);
        Mail::shouldReceive('send')->once();

        $this->actingAs($partner, 'web')
            ->postJson('/partner/paynow-partner-offer-create', [
                'form_data' => $this->offerFormData('Synthetic Wallet Offer'),
                'amt' => 15,
                'wallet' => 10,
                'voucher_code' => '',
            ])
            ->assertOk()
            ->assertJson([
                'flag' => 1,
                'link' => 'https://payments.example.test/partner-offer-wallet',
            ]);

        $offer = DB::table('offers')
            ->where('partner_id', $partner->id)
            ->where('title', 'Synthetic Wallet Offer')
            ->first();
        $this->assertNotNull($offer);
        $this->assertEquals(30, (float) DB::table('users')->where('id', $partner->id)->value('wallet_balance'));
        $this->assertDatabaseHas('wallet_logs', [
            'user_id' => $partner->id,
            'points_by' => 1,
            'trans_id' => $offer->id,
            'point' => 10,
            'point_type' => 'DR',
        ]);
    }

    public function test_partner_offer_gateway_failure_cleans_up_and_preserves_wallet_balance()
    {
        $partner = User::findOrFail($this->createUser('partner-payment-error@example.test', 3, '000000', [
            'wallet_balance' => 40,
        ]));
        $this->bindPaymentController(['error' => 'Synthetic gateway connection failure']);
        \Illuminate\Support\Facades\Log::shouldReceive('error')->once();
        Mail::shouldReceive('send')->never();

        $this->actingAs($partner, 'web')
            ->postJson('/partner/paynow-partner-offer-create', [
                'form_data' => $this->offerFormData('Synthetic Failed Offer'),
                'amt' => 15,
                'wallet' => 10,
                'voucher_code' => '',
            ])
            ->assertStatus(502)
            ->assertJson([
                'flag' => 0,
                'message' => 'Payment service is unavailable. Please try again later.',
            ]);

        $this->assertSame(0, DB::table('offers')
            ->where('partner_id', $partner->id)
            ->where('title', 'Synthetic Failed Offer')
            ->whereNull('deleted_at')
            ->count());
        $this->assertSame(0, DB::table('direct_payment')
            ->where('user_id', $partner->id)
            ->where('form_name', 'partner_offer')
            ->count());
        $this->assertSame(40, (int) DB::table('users')->where('id', $partner->id)->value('wallet_balance'));
        $this->assertSame(0, DB::table('wallet_logs')->where('user_id', $partner->id)->count());
        $this->assertSame(0, (int) DB::table('users')->where('id', $partner->id)->value('free_response'));
    }

    public function test_partner_offer_invalid_gateway_response_is_rejected_before_payment_writes()
    {
        $partner = User::findOrFail($this->createUser('partner-payment-invalid@example.test', 3));
        $this->bindPaymentController([
            'status' => 200,
            'response' => ['message' => 'Synthetic response without payment details'],
        ]);
        \Illuminate\Support\Facades\Log::shouldReceive('error')->once();
        Mail::shouldReceive('send')->never();

        $this->actingAs($partner, 'web')
            ->postJson('/partner/paynow-partner-offer-create', [
                'form_data' => $this->offerFormData('Synthetic Invalid Offer'),
                'amt' => 15,
                'wallet' => 0,
                'voucher_code' => '',
            ])
            ->assertStatus(502)
            ->assertJson([
                'flag' => 0,
                'message' => 'Payment service returned an invalid response. Please try again later.',
            ]);

        $this->assertSame(0, DB::table('offers')
            ->where('partner_id', $partner->id)
            ->where('title', 'Synthetic Invalid Offer')
            ->whereNull('deleted_at')
            ->count());
        $this->assertSame(0, DB::table('direct_payment')
            ->where('user_id', $partner->id)
            ->where('form_name', 'partner_offer')
            ->count());
        $this->assertSame(0, (int) DB::table('users')->where('id', $partner->id)->value('free_response'));
    }

    private function bindPaymentController($gatewayResult)
    {
        $controller = new class($gatewayResult) extends PartnerUserController {
            private $gatewayResult;

            public function __construct($gatewayResult)
            {
                parent::__construct();
                $this->gatewayResult = $gatewayResult;
            }

            public function sendRequest($data)
            {
                if ($this->gatewayResult === null) {
                    throw new \RuntimeException('Gateway must not be called for a zero-charge offer.');
                }

                return $this->gatewayResult;
            }
        };

        $this->app->instance(PartnerUserController::class, $controller);
    }

    private function offerFormData($title = 'Synthetic Payment Offer')
    {
        return array_map(function ($value) {
            return ['value' => $value];
        }, [
            'Synthetic Partner Event',
            'Synthetic Partner',
            'Synthetic Partner Identity',
            0,
            1,
            'ZZ',
            $title,
            2,
            10,
            1,
            2,
            3,
            4,
            5,
            'USD',
            'https://example.test/partner-offer',
            'partner-payment@example.test',
        ]);
    }
}
