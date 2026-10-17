<?php

namespace Tests\Feature;

use App\Http\Controllers\UserAuth\BidController;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tests\Concerns\CreatesTestUsers;
use Tests\TestCase;

class ReceiverBidPaymentFailureTest extends TestCase
{
    use DatabaseTransactions;
    use CreatesTestUsers;

    public function test_gateway_error_returns_a_service_failure_without_writing_payment_data()
    {
        $this->bindPaymentController(['error' => 'Synthetic gateway connection failure']);
        Log::shouldReceive('error')->once();
        $user = User::findOrFail($this->createUser('receiver-payment-error@example.test', 2));

        $this->actingAs($user, 'web')
            ->postJson('/paynow-bid-receiver', $this->paymentRequest())
            ->assertStatus(502)
            ->assertJson([
                'flag' => 0,
                'message' => 'Payment service is unavailable. Please try again later.',
            ]);

        $this->assertSame(0, DB::table('direct_payment')
            ->where('form_name', 'pay_bid_rec')
            ->where('refrence_id', 987654)
            ->count());
        $this->assertSame(0, DB::table('wallet_logs')
            ->where('user_id', $user->id)
            ->count());
        $this->assertSame(0, (int) DB::table('users')->where('id', $user->id)->value('wallet_balance'));
    }

    public function test_unexpected_gateway_response_returns_a_service_failure_without_writes()
    {
        $this->bindPaymentController([
            'status' => 200,
            'response' => ['message' => 'Synthetic response without payment details'],
        ]);
        Log::shouldReceive('error')->once();
        $user = User::findOrFail($this->createUser('receiver-payment-invalid@example.test', 2));

        $this->actingAs($user, 'web')
            ->postJson('/paynow-bid-receiver', $this->paymentRequest())
            ->assertStatus(502)
            ->assertJson([
                'flag' => 0,
                'message' => 'Payment service returned an invalid response. Please try again later.',
            ]);

        $this->assertSame(0, DB::table('direct_payment')
            ->where('form_name', 'pay_bid_rec')
            ->where('refrence_id', 987654)
            ->count());
    }

    private function bindPaymentController(array $gatewayResult)
    {
        $controller = new class($gatewayResult) extends BidController {
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

        $this->app->instance(BidController::class, $controller);
    }

    private function paymentRequest()
    {
        return [
            'bidId' => 987654,
            'wallet' => 10,
            'amt' => 5,
            'voucher_code' => 'SYNTHETIC-VOUCHER',
            'description' => 'Synthetic receiver bid',
            'portfolio' => 'https://example.test/portfolio',
        ];
    }
}
