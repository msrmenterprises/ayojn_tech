<?php

namespace App\Http\Controllers;

use App\Bid;
use App\IPNStatus;
use App\Item;
use App\Models\BidResponseInvoice;
use App\Opportunity;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Srmklive\PayPal\Services\ExpressCheckout;

class BidResponsPaymentController extends Controller
{
    // protected $provider;
    protected $provider;

    public function __construct()
    {
        $this->provider = new ExpressCheckout();
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function getExpressCheckout(Request $request, $id)
    {
        $bidData = Bid::find($id);
        $price = 5;

        session(['bid_id' => $id]);
        session(['price' => $price]);
        $cart = $this->getCheckoutData(false);

        try {
            $options = [
                'BRANDNAME' => 'Ayojn',
                'LOGOIMG' => asset('images/sponsay_logo.png'),
            ];
            $response = $this->provider->addOptions($options)->setExpressCheckout($cart, false);
            if (! empty($response['paypal_link'])) {
                return redirect($response['paypal_link']);
            } else {
                return redirect('/bid')->with('error',
                    $response['L_LONGMESSAGE0']);
            }

        } catch (\Exception $e) {
            $invoice = $this->createInvoice($cart, 'Invalid');

            session()->put(['code' => 'danger', 'message' => "Error processing PayPal payment for Order $invoice->id!"]);
        }
    }

    /**
     * Set cart data for processing payment on PayPal.
     *
     * @param bool $recurring
     *
     * @return array
     */
    protected function getCheckoutData($recurring = false, $id = null)
    {
        $data = [];


        $order_id = BidResponseInvoice::all()->count() + 1;
        $bidId = session('bid_id');
        $price = session('price');


        $data['items'] = [
            [
                'name' => 'Bid Id',
                'price' => $price,
                'qty' => 1,
            ],
        ];
        $data['return_url'] = url('/response-paypal/success');

        $data['invoice_id'] = config('paypal.invoice_prefix') . $order_id . 'response' . $this->getName(5);
        $data['bid_id'] = $bidId;
        $data['invoice_description'] = "Order #" . $order_id . "_response_" . $bidId . " Invoice";
        $data['cancel_url'] = url('/response-paypal/cancel');

        $total = 0;
        foreach ($data['items'] as $item) {
            $total += $item['price'] * $item['qty'];
        }

        $data['total'] = $total;

        return $data;
    }

    /**
     * Process payment on PayPal.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getExpressCheckoutSuccess(Request $request)
    {
        $recurring = false;
        $token = $request->get('token');
        $PayerID = $request->get('PayerID');

        $cart = $this->getCheckoutData($recurring);

        // Verify Express Checkout Token
        $response = $this->provider->getExpressCheckoutDetails($token);
        $opportunityId = $cart['bid_id'];
        if (in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {
            // Perform transaction on PayPal
            $payment_status = $this->provider->doExpressCheckoutPayment($cart, $token, $PayerID);
            $status = $payment_status['PAYMENTINFO_0_PAYMENTSTATUS'];
            $invoice = $this->createInvoice($cart, $status, $payment_status['PAYMENTINFO_0_TRANSACTIONID']);

            if ($invoice->paid) {
                $price = session('price');

                session()->put(['code' => 'success', 'message' => "Order $invoice->id has been paid successfully!"]);

                return redirect('/bid')->with('success',
                    'Your payment is received with thanks. Please keep a tab on the Bids to see the response.');
            } else {
                session()->put(['code' => 'danger', 'message' => "Error processing PayPal payment for Order $invoice->id!"]);

                return redirect('/bid')->with('error',
                    'Error processing PayPal payment');
            }


        } else {
            return redirect('/bid')->with('error',
                'Error processing PayPal payment');
        }
    }

    protected function createInvoice($cart, $status, $trasactionID)
    {
        $invoice = new BidResponseInvoice();
        $invoice->title = $cart['invoice_description'];
        $invoice->price = $cart['total'];
        $invoice->bid_id = $cart['bid_id'];
        $invoice->user_id = Auth::user()->id;
        $invoice->transaction_id = $trasactionID;
        if (! strcasecmp($status, 'Completed') || ! strcasecmp($status, 'Processed')) {
            $invoice->paid = 1;
            $user = User::find(Auth::user()->id);
            $user->is_paid = 1;
            $user->save();
        } else {
            $invoice->paid = 0;

        }

        $invoice->save();

        return $invoice;
    }

    public function paymentCancel(Request $request)
    {

        return redirect('/bid')->with('error',
            'Error processing PayPal payment');

    }

    function getName($n)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        return $randomString;
    }

}
