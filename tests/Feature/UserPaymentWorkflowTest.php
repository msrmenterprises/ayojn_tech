<?php

namespace Tests\Feature;

use App\Http\Controllers\UserAuth\XsquareController;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Tests\Concerns\CreatesTestUsers;
use Tests\TestCase;

class UserPaymentWorkflowTest extends TestCase
{
    use DatabaseTransactions;
    use CreatesTestUsers;

    public function test_user_can_refresh_a_direct_payment_status()
    {
        $user = User::findOrFail($this->createUser('direct-payment-status@example.test', 1));
        $paymentId = $this->createDirectPayment(
            $user->id,
            'SYNTHETIC-STATUS-REFERENCE',
            null,
            'direct_payment',
            94001
        );
        $controller = $this->bindStatusController('200', 'Completed');

        $this->actingAs($user, 'web')
            ->from('/directpay')
            ->post('/xsquare-status-check', [
                'collection_id' => 94001,
            ])
            ->assertRedirect('/directpay')
            ->assertSessionHas('payment_token', 'SYNTHETIC-ACCESS-TOKEN')
            ->assertSessionHas('payment_refresh_token', 'SYNTHETIC-REFRESH-TOKEN');

        $this->assertSame(94001, $controller->checkedCollectionId());
        $this->assertDatabaseHas('direct_payment', [
            'id' => $paymentId,
            'status_desc' => json_encode('Completed'),
        ]);
    }

    public function test_failed_direct_payment_status_lookup_preserves_the_existing_status()
    {
        $user = User::findOrFail($this->createUser('direct-payment-status-failure@example.test', 1));
        $paymentId = $this->createDirectPayment(
            $user->id,
            'SYNTHETIC-FAILED-STATUS-REFERENCE',
            'Pending',
            'direct_payment',
            94002
        );
        $this->bindStatusController('503', 'Unavailable');

        $this->actingAs($user, 'web')
            ->from('/directpay')
            ->post('/xsquare-status-check', [
                'collection_id' => 94002,
            ])
            ->assertRedirect('/directpay');

        $this->assertDatabaseHas('direct_payment', [
            'id' => $paymentId,
            'status_desc' => 'Pending',
        ]);
    }

    public function test_successful_direct_payment_callback_records_payment_and_sends_invoice()
    {
        $this->withoutExceptionHandling();
        $user = User::findOrFail($this->createUser('direct-payment-success@example.test', 1));
        $paymentId = $this->createDirectPayment(
            $user->id,
            'SYNTHETIC-DIRECT-PAYMENT-REFERENCE',
            null,
            'direct_payment',
            94003
        );
        $this->createDirectPaymentConfiguration($user->user_type);

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

        $this->actingAs($user, 'web')
            ->get('/success?' . http_build_query([
                'amount' => 25,
                'payment_date' => '2026-10-17',
                'payment_mode' => 'Synthetic Card',
                'transaction_status' => 'Success',
                'transaction_reference' => 'SYNTHETIC-DIRECT-PAYMENT-REFERENCE',
            ]))
            ->assertOk()
            ->assertViewIs('user.success')
            ->assertViewHas('redirection', 'directpay')
            ->assertSee('USD 25')
            ->assertSee('AYOJN-94003')
            ->assertSee('SYNTHETIC-DIRECT-PAYMENT-REFERENCE');

        $this->assertDatabaseHas('direct_payment', [
            'id' => $paymentId,
            'transaction_amount' => 25,
            'payment_date' => '2026-10-17',
            'payment_mode' => 'Synthetic Card',
            'status_desc' => 'Success',
        ]);
    }

    public function test_failed_direct_payment_callback_records_failure_details()
    {
        $user = User::findOrFail($this->createUser('direct-payment-failed@example.test', 1));
        $paymentId = $this->createDirectPayment(
            $user->id,
            'SYNTHETIC-DIRECT-PAYMENT-FAILED-REFERENCE'
        );

        $this->actingAs($user, 'web')
            ->get('/failed?' . http_build_query([
                'amount' => 25,
                'payment_date' => '2026-10-17',
                'payment_mode' => 'Synthetic Card',
                'transaction_status' => 'Failed',
                'transaction_reference' => 'SYNTHETIC-DIRECT-PAYMENT-FAILED-REFERENCE',
            ]))
            ->assertOk()
            ->assertViewIs('user.failed');

        $this->assertDatabaseHas('direct_payment', [
            'id' => $paymentId,
            'transaction_amount' => 25,
            'payment_date' => '2026-10-17',
            'payment_mode' => 'Synthetic Card',
            'status_desc' => 'Failed',
        ]);
    }

    public function test_user_can_start_a_direct_payment_and_is_redirected_to_the_gateway()
    {
        $user = User::findOrFail($this->createUser('direct-payment-initiation@example.test', 1));
        $beneficiaryId = $this->createBeneficiary($user);
        $this->bindGatewayController([
            'status' => 201,
            'response' => [
                'details' => [
                    'collection_id' => 94005,
                    'order_reference' => 'SYNTHETIC-DIRECT-PAYMENT-INITIATION',
                    'payment_url' => 'https://payments.example.test/collection/94005',
                ],
            ],
        ]);

        $this->actingAs($user, 'web')
            ->from('/offline-pay')
            ->post('/offline-payment-direct', [
                'beneficiary_id' => $beneficiaryId,
                'purpose' => 1,
                'amount' => 35,
                'ayojn_earn' => 0,
            ])
            ->assertRedirect('https://payments.example.test/collection/94005');

        $this->assertDatabaseHas('direct_payment', [
            'user_id' => $user->id,
            'beneficiary_id' => $beneficiaryId,
            'collection_id' => 94005,
            'purpose' => 1,
            'amount' => 35,
            'ayojn_earn' => 0,
            'receipt' => 1,
            'transaction_reference' => 'SYNTHETIC-DIRECT-PAYMENT-INITIATION',
            'form_name' => 'direct_payment',
        ]);
    }

    public function test_gateway_error_does_not_create_a_direct_payment()
    {
        $user = User::findOrFail($this->createUser('direct-payment-gateway-error@example.test', 1));
        $beneficiaryId = $this->createBeneficiary($user);
        $this->bindGatewayController(['error' => 'Synthetic gateway timeout']);
        Log::shouldReceive('error')->once();

        $this->actingAs($user, 'web')
            ->from('/offline-pay')
            ->post('/offline-payment-direct', [
                'beneficiary_id' => $beneficiaryId,
                'purpose' => 1,
                'amount' => 35,
                'ayojn_earn' => 0,
            ])
            ->assertRedirect('/offline-pay')
            ->assertSessionHasErrors();

        $this->assertSame(0, DB::table('direct_payment')->where('user_id', $user->id)->count());
    }

    public function test_invalid_receipt_is_rejected_before_contacting_the_gateway()
    {
        $user = User::findOrFail($this->createUser('direct-payment-invalid-receipt@example.test', 1));
        $beneficiaryId = $this->createBeneficiary($user);
        $controller = $this->bindGatewayController([
            'status' => 201,
            'response' => [
                'details' => [
                    'collection_id' => 94006,
                    'order_reference' => 'SYNTHETIC-INVALID-RECEIPT',
                    'payment_url' => 'https://payments.example.test/collection/94006',
                ],
            ],
        ]);

        $this->actingAs($user, 'web')
            ->from('/offline-pay')
            ->post('/offline-payment-direct', [
                'beneficiary_id' => $beneficiaryId,
                'purpose' => 1,
                'amount' => 35,
                'ayojn_earn' => 0,
                'receipt' => UploadedFile::fake()->create('receipt.txt'),
            ])
            ->assertRedirect('/offline-pay')
            ->assertSessionHasErrors('receipt');

        $this->assertSame(0, $controller->requestCount());
        $this->assertSame(0, DB::table('direct_payment')->where('user_id', $user->id)->count());
    }

    public function test_invalid_gateway_response_does_not_create_a_direct_payment()
    {
        $user = User::findOrFail($this->createUser('direct-payment-invalid-response@example.test', 1));
        $beneficiaryId = $this->createBeneficiary($user);
        $this->bindGatewayController([
            'status' => 503,
            'response' => ['message' => 'Synthetic unavailable response'],
        ]);
        Log::shouldReceive('error')->once();

        $this->actingAs($user, 'web')
            ->from('/offline-pay')
            ->post('/offline-payment-direct', [
                'beneficiary_id' => $beneficiaryId,
                'purpose' => 1,
                'amount' => 35,
                'ayojn_earn' => 0,
            ])
            ->assertRedirect('/offline-pay')
            ->assertSessionHasErrors();

        $this->assertSame(0, DB::table('direct_payment')->where('user_id', $user->id)->count());
    }

    private function createDirectPayment(
        $userId,
        $reference,
        $status = null,
        $formName = 'direct_payment',
        $collectionId = 94004
    )
    {
        return DB::table('direct_payment')->insertGetId([
            'user_id' => $userId,
            'collection_id' => $collectionId,
            'purpose' => 1,
            'amount' => 25,
            'ayojn_earn' => 0,
            'transaction_reference' => $reference,
            'form_name' => $formName,
            'status_desc' => $status,
        ]);
    }

    private function createBeneficiary(User $user)
    {
        return DB::table('beneficiary')->insertGetId([
            'company_name' => 'Synthetic payment beneficiary',
            'contact_name' => 'Synthetic contact',
            'email' => 'synthetic-payment-beneficiary@example.test',
            'mobile_no' => '0000000000',
            'tax_id' => 'SYNTHETIC-PAYMENT-TAX',
            'bank_country' => 'ZZ',
            'currency' => 'USD',
            'bank_account' => 'SYNTHETIC-PAYMENT-ACCOUNT',
            'bank_code' => 'SYNTHETIC-PAYMENT-BANK',
            'code_no' => 'SYNTHETIC-PAYMENT-CODE',
            'beneficiary_country' => 'ZZ',
            'beneficiary_city' => 'Synthetic City',
            'beneficiary_address' => 'Synthetic Address',
            'user_id' => $user->id,
        ]);
    }

    private function createDirectPaymentConfiguration($userTypeId)
    {
        DB::table('configuration')->insert([
            'config_text' => 'direct_payment',
            'user_type' => $userTypeId,
            'config_amt' => 1,
            'config_type' => 'fixed',
            'user_earn' => 0,
        ]);
    }

    private function bindStatusController($statusCode, $paymentStatus)
    {
        $controller = new class($statusCode, $paymentStatus) extends XsquareController {
            private $statusCode;
            private $paymentStatus;
            private $collectionId;

            public function __construct($statusCode, $paymentStatus)
            {
                parent::__construct();
                $this->statusCode = $statusCode;
                $this->paymentStatus = $paymentStatus;
            }

            public function xsquarelogin()
            {
                return (object) [
                    'details' => (object) [
                        'access_token' => 'SYNTHETIC-ACCESS-TOKEN',
                        'refresh_token' => 'SYNTHETIC-REFRESH-TOKEN',
                    ],
                ];
            }

            public function xsquare_status($collectionId)
            {
                $this->collectionId = $collectionId;

                return (object) [
                    'status_code' => $this->statusCode,
                    'details' => (object) ['pay_status' => $this->paymentStatus],
                ];
            }

            public function checkedCollectionId()
            {
                return $this->collectionId;
            }
        };

        $this->app->instance(XsquareController::class, $controller);

        return $controller;
    }

    private function bindGatewayController($gatewayResponse)
    {
        $controller = new class($gatewayResponse) extends XsquareController {
            private $gatewayResponse;
            private $requestCount = 0;

            public function __construct($gatewayResponse)
            {
                parent::__construct();
                $this->gatewayResponse = $gatewayResponse;
            }

            public function sendRequest($data)
            {
                $this->requestCount++;

                return $this->gatewayResponse;
            }

            public function requestCount()
            {
                return $this->requestCount;
            }
        };

        $this->app->instance(XsquareController::class, $controller);

        return $controller;
    }
}
