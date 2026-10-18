<?php

namespace Tests\Unit;

use App\Http\Controllers\UserAuth\OppertunityController;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class PaymentGatewayRequestTest extends TestCase
{
    public function test_curl_failure_is_caught_and_reported()
    {
        config([
            'sitevars.xsquare_local.API_KEY' => 'synthetic-api-key',
            'sitevars.xsquare_local.ENDPOINT' => 'unsupported://localhost',
            'sitevars.xsquare_local.SECRET_KEY' => 'synthetic-secret',
        ]);
        Log::shouldReceive('error')->once();

        $result = (new OppertunityController())->sendRequest(['amount' => 25]);

        $this->assertSame([
            'error' => 'The payment gateway is temporarily unavailable.',
        ], $result);
    }
}
