<?php

class PaymentHandler
{
    private $formData = [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'rambthm376@gmail.com',
        'purpose' => '1',
        'amount' => '100.50',
        'currencies' => [
            'currency_id' => 1,
            'name' => 'United Arab Emirates - AED',
            'code' => 'AED'
        ]
    ];

    public function generateSignature($payload)
    {
        $timestamp = (string) time();

        ksort($payload);

        $signaturePayload = $payload;
        unset($signaturePayload['currencies']);
        $signaturePayload['amount'] = number_format((float) $payload['amount'], 1, '.', '');

        $message = 'NgbuIXYCNTcDYVdHDzmdbbNyIddOPpN6' . $timestamp . json_encode($signaturePayload, JSON_UNESCAPED_SLASHES);

        $signature = hash_hmac('sha256', $message, '#h%eq!UAg<l!bgA');

        return [
            'signature' => $signature,
            'timestamp' => $timestamp,
            'message' => $message
        ];
    }

    public function sendRequest()
    {
        $apikey = 'NgbuIXYCNTcDYVdHDzmdbbNyIddOPpN6';

        try {
            $signatureData = $this->generateSignature($this->formData);

            $ch = curl_init();

            $customerId = '127';
            $customerId = is_numeric($customerId) ? (int) $customerId : 0;

            curl_setopt($ch, CURLOPT_URL, 'https://core-api.xsquare.biz/transaction/collection-add/v3');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->formData));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'x-api-key: ' . $apikey,
                'x-signature: ' . $signatureData['signature'],
                'x-timestamp: ' . $signatureData['timestamp'],
                'x-customer-id: ' . $customerId,
                'User-Agent: MyCustomUserAgent/1.0'
            ]);

            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                throw new Exception('cURL error: ' . curl_error($ch));
            }

            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            return [
                'status' => $httpCode,
                'response' => json_decode($response, true),
                'debug' => [
                    'payload' => $this->formData,
                    'signature' => $signatureData
                ]
            ];
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function run($request)
    {
        $result = $this->sendRequest();
        header("Window-target: _parent");
        header('Location:'.$result['response']['details']['payment_url']);
        exit;


        echo "<pre>";
        print_r($result);
        echo "</pre>";
    }
}

class SimpleRequest
{
    public function post()
    {
        return $_POST; // Return POST data
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Run Payment</title>
</head>
<body>

    <h1>Trigger Payment Request</h1>

    <form method="post">
        <button type="submit" name="run_payment">Run Payment</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['run_payment'])) {
        $handler = new PaymentHandler();
        $request = new SimpleRequest();
        $handler->run($request);

    }
    ?>

</body>
</html>
