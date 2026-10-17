<?php

namespace Tests\Feature;

use App\User;
use App\VouchCode;
use App\Http\Controllers\UserAuth\OppertunityController;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\Concerns\CreatesTestUsers;
use Tests\TestCase;

class OpportunityWorkflowTest extends TestCase
{
    use DatabaseTransactions;
    use CreatesTestUsers;

    public function test_opportunity_sponsor_can_view_launched_opportunities()
    {
        $user = User::findOrFail($this->createUser('opportunity-sponsor@example.test', 1));

        $this->actingAs($user, 'web')
            ->get('/opportunity')
            ->assertStatus(200)
            ->assertSee('Launched Opportunities');
    }

    public function test_opportunity_receiver_can_view_built_opportunities()
    {
        $user = User::findOrFail($this->createUser('opportunity-receiver@example.test', 2));

        $this->actingAs($user, 'web')
            ->get('/opportunity')
            ->assertStatus(200)
            ->assertSee('Built Opportunities');
    }

    public function test_opportunity_receiver_can_open_the_build_form()
    {
        $user = User::findOrFail($this->createUser('opportunity-builder@example.test', 2));

        $this->actingAs($user, 'web')
            ->get('/build-opportunity')
            ->assertStatus(200);
    }

    public function test_hashtag_search_returns_an_empty_value_when_no_opportunities_match()
    {
        $user = User::findOrFail($this->createUser('opportunity-hashtags@example.test', 2));

        $this->actingAs($user, 'web')
            ->get('/get-hashtags?countryId=ZZ&industryId=999&opportunity_city=999')
            ->assertJson(['hashTags' => '']);
    }

    public function test_hashtag_reservation_and_voucher_checks_distinguish_available_codes()
    {
        $user = User::findOrFail($this->createUser('opportunity-reservation-checks@example.test', 2));
        DB::table('opportunities')->insert([
            'receiver_id' => $user->id,
            'hashtag' => '#synthetic-reserved',
            'country_code' => 'ZZ',
            'opportunity_city' => 123,
            'industry_id' => 999,
            'status' => 1,
            'is_reserved' => 1,
            'next_payment_date' => '2035-01-01',
        ]);

        $validVoucher = VouchCode::create([
            'vouch_code' => 'SYNTHETIC-CHECK-VALID',
            'vouch_amount' => 25,
            'expeiry_date' => '2035-01-01',
        ]);
        DB::table('vouch_codes')->where('id', $validVoucher->id)->update(['status' => 0]);

        $expiredVoucher = VouchCode::create([
            'vouch_code' => 'SYNTHETIC-CHECK-EXPIRED',
            'vouch_amount' => 30,
            'expeiry_date' => '2000-01-01',
        ]);
        DB::table('vouch_codes')->where('id', $expiredVoucher->id)->update(['status' => 0]);

        $this->actingAs($user, 'web')
            ->postJson('/hashtags-check', ['hashtags' => 'synthetic-reserved'])
            ->assertJson([
                'status' => 0,
                'message' => 'Hashtag reserved!',
            ]);

        $this->postJson('/hashtags-check', ['hashtags' => 'synthetic-available'])
            ->assertJson([
                'status' => 1,
                'message' => 'Hashtag Available',
            ]);

        $this->post('/check-vouch-code', ['code' => $validVoucher->vouch_code])
            ->assertStatus(200)
            ->assertSeeText('25');
        $this->post('/check-vouch-code', ['code' => $expiredVoucher->vouch_code])
            ->assertStatus(200)
            ->assertSeeText('0');
    }

    public function test_free_opportunity_renewal_updates_the_next_payment_date_without_gateway_calls()
    {
        $user = User::findOrFail($this->createUser('opportunity-renewal@example.test', 2));
        $opportunityId = DB::table('opportunities')->insertGetId([
            'receiver_id' => $user->id,
            'hashtag' => '#synthetic-renewal',
            'country_code' => 'ZZ',
            'industry_id' => 999,
            'status' => 1,
        ]);

        $this->actingAs($user, 'web')
            ->postJson('/renew-opportunity-paynow', [
                'id' => $opportunityId,
                'amount' => 0,
                'reserved_year' => 1,
                'wallet' => 0,
                'voucher_code' => '',
            ])
            ->assertStatus(200)
            ->assertJson([
                'flag' => 1,
                'link' => '',
            ]);

        $this->assertDatabaseHas('opportunities', [
            'id' => $opportunityId,
            'reserved_year' => 1,
        ]);
        $this->assertNotNull(DB::table('opportunities')
            ->where('id', $opportunityId)
            ->value('next_payment_date'));
    }

    public function test_paid_vouch_uses_a_stubbed_gateway_and_records_the_payment()
    {
        $user = User::findOrFail($this->createUser('paid-vouch@example.test', 1));
        $opportunityId = DB::table('opportunities')->insertGetId([
            'receiver_id' => $user->id,
            'hashtag' => '#paid-vouch',
            'country_code' => 'ZZ',
            'industry_id' => 999,
            'status' => 1,
        ]);
        $gatewayResult = [
            'response' => [
                'details' => [
                    'collection_id' => 'SYNTHETIC-COLLECTION',
                    'order_reference' => 'SYNTHETIC-PAYMENT-REFERENCE',
                    'payment_url' => 'https://payments.example.test/synthetic-checkout',
                ],
            ],
        ];
        $controller = new class($gatewayResult) extends OppertunityController {
            private $gatewayResult;

            public function __construct(array $gatewayResult)
            {
                parent::__construct();
                $this->gatewayResult = $gatewayResult;
            }

            public function sendRequest($data)
            {
                return $this->gatewayResult;
            }
        };
        $this->app->instance(OppertunityController::class, $controller);

        $this->actingAs($user, 'web')
            ->postJson('/paynow-vouch', [
                'opid' => $opportunityId,
                'vid' => 999999,
                'amt' => 25,
                'wallet' => 0,
                'voucher_code' => '',
            ])
            ->assertStatus(200)
            ->assertJson([
                'flag' => 1,
                'link' => 'https://payments.example.test/synthetic-checkout',
            ]);

        $this->assertDatabaseHas('direct_payment', [
            'user_id' => $user->id,
            'collection_id' => 'SYNTHETIC-COLLECTION',
            'transaction_reference' => 'SYNTHETIC-PAYMENT-REFERENCE',
            'form_name' => 'hashtag_vouch',
            'refrence_id' => $opportunityId,
        ]);
        $this->assertEquals(0, (float) DB::table('users')->where('id', $user->id)->value('wallet_balance'));
        $this->assertSame(0, DB::table('wallet_logs')->where('user_id', $user->id)->count());
    }

    public function test_wallet_and_voucher_vouch_payment_deducts_balance_and_marks_code_used()
    {
        $user = User::findOrFail($this->createUser('wallet-vouch@example.test', 1));
        $user->wallet_balance = 50;
        $user->save();
        $voucher = VouchCode::create([
            'vouch_code' => 'SYNTHETIC-VOUCH-PAYMENT',
            'vouch_amount' => 10,
            'expeiry_date' => '2035-01-01',
        ]);
        DB::table('vouch_codes')->where('id', $voucher->id)->update(['status' => 0]);

        $this->actingAs($user, 'web')
            ->postJson('/paynow-vouch', [
                'opid' => 987654,
                'vid' => 876543,
                'amt' => 0,
                'wallet' => 10,
                'voucher_code' => $voucher->vouch_code,
            ])
            ->assertStatus(200)
            ->assertJson([
                'flag' => 1,
                'link' => '',
            ]);

        $this->assertEquals(40, (float) DB::table('users')->where('id', $user->id)->value('wallet_balance'));
        $this->assertDatabaseHas('vouch_codes', [
            'id' => $voucher->id,
            'used_by' => $user->id,
            'status' => 1,
        ]);
        $this->assertDatabaseHas('wallet_logs', [
            'user_id' => $user->id,
            'points_by' => 3,
            'trans_id' => 876543,
            'point' => 10,
            'point_type' => 'DR',
        ]);
    }

    public function test_opportunity_city_lookup_and_vouch_status_use_saved_records()
    {
        $user = User::findOrFail($this->createUser('opportunity-vouch@example.test', 1));
        $opportunityId = DB::table('opportunities')->insertGetId([
            'receiver_id' => $user->id,
            'hashtag' => '#synthetic',
            'country_code' => 'ZZ',
            'industry_id' => 999,
            'status' => 1,
        ]);
        $vouchId = DB::table('vouches')->insertGetId([
            'offer_id' => $user->id,
            'opportunity_id' => $opportunityId,
            'vouch_value' => 'Synthetic value',
            'is_active' => 'On',
        ]);

        $this->actingAs($user, 'web')
            ->get('/get-cities/' . $opportunityId)
            ->assertJsonFragment([
                'hashtag' => '#synthetic',
                'country_code' => 'ZZ',
            ]);

        $this->postJson('/change-opportunity-status', [
            'id' => $vouchId,
            'status' => 'On',
        ])->assertJson([
            'status' => true,
            'message' => 'Vouch status Has been Changed.',
        ]);

        $this->assertSame('Off', DB::table('vouches')->where('id', $vouchId)->value('is_active'));
    }

    public function test_user_can_view_their_vouches()
    {
        $user = User::findOrFail($this->createUser('my-vouches@example.test', 1));

        $this->actingAs($user, 'web')
            ->get('/my-vouches')
            ->assertStatus(200);
    }

    public function test_opportunity_owner_can_accept_an_active_vouch()
    {
        $vouchAuthor = User::findOrFail($this->createUser('vouch-accept-author@example.test', 1));
        $opportunityOwner = User::findOrFail($this->createUser('vouch-accept-owner@example.test', 2));
        $opportunityId = DB::table('opportunities')->insertGetId([
            'receiver_id' => $opportunityOwner->id,
            'hashtag' => '#accept-vouch',
            'country_code' => 'ZZ',
            'industry_id' => 999,
            'status' => 1,
        ]);
        $vouchId = DB::table('vouches')->insertGetId([
            'offer_id' => $vouchAuthor->id,
            'opportunity_id' => $opportunityId,
            'vouch_value' => 'Synthetic active vouch',
            'is_active' => 'On',
            'status' => 1,
        ]);
        $responseId = DB::table('vouch_users')->insertGetId([
            'offer_id' => $opportunityOwner->id,
            'vouches_id' => $vouchId,
            'status' => 1,
        ]);

        $this->actingAs($opportunityOwner, 'web')
            ->get('/update-vouch/' . $responseId)
            ->assertRedirect(url('vouches/' . $opportunityId));

        $this->assertDatabaseHas('vouch_users', [
            'id' => $responseId,
            'status' => 2,
        ]);
        $this->assertDatabaseHas('vouches', [
            'id' => $vouchId,
            'status' => 2,
        ]);
    }

    public function test_inactive_vouch_is_not_accepted()
    {
        $vouchAuthor = User::findOrFail($this->createUser('inactive-vouch-author@example.test', 1));
        $opportunityOwner = User::findOrFail($this->createUser('inactive-vouch-owner@example.test', 2));
        $opportunityId = DB::table('opportunities')->insertGetId([
            'receiver_id' => $opportunityOwner->id,
            'hashtag' => '#inactive-vouch',
            'country_code' => 'ZZ',
            'industry_id' => 999,
            'status' => 1,
        ]);
        $vouchId = DB::table('vouches')->insertGetId([
            'offer_id' => $vouchAuthor->id,
            'opportunity_id' => $opportunityId,
            'vouch_value' => 'Synthetic inactive vouch',
            'is_active' => 'Off',
            'status' => 1,
        ]);
        $responseId = DB::table('vouch_users')->insertGetId([
            'offer_id' => $opportunityOwner->id,
            'vouches_id' => $vouchId,
            'status' => 1,
        ]);

        $this->actingAs($opportunityOwner, 'web')
            ->from('/my-vouches')
            ->get('/update-vouch/' . $responseId)
            ->assertRedirect('/my-vouches')
            ->assertSessionHas('success_messagesss', '');

        $this->assertDatabaseHas('vouch_users', [
            'id' => $responseId,
            'status' => 1,
        ]);
        $this->assertDatabaseHas('vouches', [
            'id' => $vouchId,
            'status' => 1,
        ]);
    }

    public function test_user_can_add_a_vouch_for_a_matching_opportunity()
    {
        $offerUser = User::findOrFail($this->createUser('vouch-offer@example.test', 1));
        $receiver = User::findOrFail($this->createUser('vouch-receiver@example.test', 2));
        $opportunityId = DB::table('opportunities')->insertGetId([
            'receiver_id' => $receiver->id,
            'hashtag' => '#vouch-test',
            'country_code' => 'ZZ',
            'opportunity_city' => 123,
            'industry_id' => 999,
            'status' => 1,
        ]);

        $this->actingAs($offerUser, 'web')
            ->postJson('/add-new-vouch', [
                'opportunity_id' => $opportunityId,
                'vouch_value' => 'Synthetic contribution',
                'vouch_identity' => 'Synthetic identity',
                'vouch_city' => 123,
                'vouch_country' => 'ZZ',
                'vouch_contacted' => 'Email',
                'type' => 'free',
            ])
            ->assertJson([
                'status' => 1,
                'message' => 'Vouche has been successfully added',
            ]);

        $this->assertDatabaseHas('vouches', [
            'offer_id' => $offerUser->id,
            'opportunity_id' => $opportunityId,
            'vouch_value' => 'Synthetic contribution',
            'status' => 1,
        ]);
        $this->assertSame(1, (int) DB::table('users')->where('id', $offerUser->id)->value('free_vouch'));
    }

    public function test_duplicate_vouch_does_not_change_user_vouch_counters()
    {
        $offerUser = User::findOrFail($this->createUser('duplicate-vouch-offer@example.test', 1));
        $offerUser->free_vouch = 5;
        $offerUser->is_paid = 1;
        $offerUser->save();

        $receiver = User::findOrFail($this->createUser('duplicate-vouch-receiver@example.test', 2));
        $opportunityId = DB::table('opportunities')->insertGetId([
            'receiver_id' => $receiver->id,
            'hashtag' => '#duplicate-vouch',
            'country_code' => 'ZZ',
            'opportunity_city' => 123,
            'industry_id' => 999,
            'status' => 1,
        ]);
        DB::table('vouches')->insert([
            'offer_id' => $offerUser->id,
            'opportunity_id' => $opportunityId,
            'vouch_value' => 'Existing synthetic contribution',
            'vouch_identity' => 'Synthetic identity',
            'vouch_city' => 123,
            'vouch_country' => 'ZZ',
            'vouch_contacted' => 'Email',
            'status' => 1,
        ]);

        $this->actingAs($offerUser, 'web')
            ->postJson('/add-new-vouch', [
                'opportunity_id' => $opportunityId,
                'vouch_value' => 'Duplicate synthetic contribution',
                'vouch_identity' => 'Synthetic identity',
                'vouch_city' => 123,
                'vouch_country' => 'ZZ',
                'vouch_contacted' => 'Email',
                'type' => 'free',
            ])
            ->assertJson([
                'status' => 2,
                'message' => 'You have already vouched this opportunity',
            ]);

        $this->assertSame(1, DB::table('vouches')
            ->where('offer_id', $offerUser->id)
            ->where('opportunity_id', $opportunityId)
            ->count());
        $this->assertDatabaseHas('users', [
            'id' => $offerUser->id,
            'free_vouch' => 5,
            'is_paid' => 1,
        ]);
    }

    public function test_opportunity_and_vouch_creation_validate_required_fields_before_side_effects()
    {
        $user = User::findOrFail($this->createUser('opportunity-validation@example.test', 1));
        $this->actingAs($user, 'web');

        $this->postJson('/add-new-vouch', [])
            ->assertJson([
                'status' => 0,
                'message' => '"Vouch value is required"',
            ]);

        $this->from('/build-opportunity')
            ->post('/add-new-opportunity', [])
            ->assertRedirect('/build-opportunity')
            ->assertSessionHas('error_contact', 'Please fill all fields data');

        $this->assertSame(0, DB::table('opportunities')->count());
    }
}
