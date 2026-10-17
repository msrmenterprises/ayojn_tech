<?php

namespace App\Http\Controllers;

use App\Bid;
use App\BidInvoice;
use App\IPNStatus;
use App\Item;
use App\Opportunity;
use App\User;
use App\WalletLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Srmklive\PayPal\Services\ExpressCheckout;

class PayController extends Controller
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
        if ($bidData->sponsor_country == Auth::user()->country && $bidData->city == Auth::user()->city) {
            $price = config('paypal.INTERCITYPRICE');
        } else if ($bidData->sponsor_country == Auth::user()->country && $bidData->city != Auth::user()->city) {
            $price = config('paypal.INTERCOUNTRYPRICE');
        } else {
            $price = config('paypal.ALLOVERPRICE');
        }


        if (Auth::user()->refer_by != '' && Auth::user()->is_bonus_used == 0) {
            $discountPrice = 30;
            if (Auth::user()->sponsor_type != 3) {
                $discountPrice = 30;
            } else {
                $discountPrice = 100;
            }
            $price = $price - $discountPrice;
            if($price < 0){
                $price = 0;
            }
        }

        session(['bid_id' => $id]);
        session(['price' => $price]);
        $cart = $this->getCheckoutData(false);

        try {
            $options = [
                'BRANDNAME' => 'Ayojn',
                'LOGOIMG' => asset('images/sponsay_logo.png'),
            ];
            $response = $this->provider->addOptions($options)->setExpressCheckout($cart, false);
            if (!empty($response['paypal_link'])) {
                return redirect($response['paypal_link']);
            } else {
                return redirect('/unpaid-bid')->with('error',
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

        $order_id = BidInvoice::all()->count() + 1;
        $bidId = session('bid_id');
        $price = session('price');


        $data['items'] = [
            [
                'name' => 'Bid Id',
                'price' => $price,
                'qty' => 1,
            ],
        ];
        $data['return_url'] = url('/pay-pal/ec-checkout-success');

        $data['invoice_id'] = config('paypal.invoice_prefix') . $order_id . '_bid_' . $bidId;
        $data['bid_id'] = $bidId;
        $data['invoice_description'] = "Order #" . $order_id . "_bid_" . $bidId . " Invoice";
        $data['cancel_url'] = url('/pay-pal/cancel');

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
                if (Auth::user()->is_bonus_used == 0) {
                    $loggedinUser = User::find(Auth::user()->id);
                    $loggedinUser->is_bonus_used = 1;
                    $loggedinUser->save();
                }
                if (!empty(Auth::user()->refer_by)) {
                    $userData = User::find(Auth::user()->refer_by);
                    if (!empty($userData)) {
                        $points = ($price * 10) / 100;
                        $userData->wallet_balance = $userData->wallet_balance + $points;
                        $userData->save();
                        $walletLogs = [
                            'user_id' => $userData->id,
                            'points_by' => Auth::user()->id,
                            'point' => $points
                        ];
                        WalletLog::create($walletLogs);

                    }

                }
                session()->put(['code' => 'success', 'message' => "Order $invoice->id has been paid successfully!"]);

                return redirect('/bid')->with('success',
                    'Your payment is received with thanks. Please keep a tab on the Bids to see the response.');
            } else {
                session()->put(['code' => 'danger', 'message' => "Error processing PayPal payment for Order $invoice->id!"]);

                return redirect('/bid')->with('error',
                    'Error processing PayPal payment');
            }


        } else {
            $opportunityData = Opportunity::find($cart['bid_id']);
            $opportunityData->is_paid = 0;
            $opportunityData->save();

            return redirect('/bid')->with('error',
                'Error processing PayPal payment');
        }
    }

    protected function createInvoice($cart, $status, $trasactionID)
    {
        $invoice = new BidInvoice();
        $invoice->title = $cart['invoice_description'];
        $invoice->price = $cart['total'];
        $invoice->bid_id = $cart['bid_id'];
        $invoice->transaction_id = $trasactionID;
        $opportunityData = Bid::find($cart['bid_id']);
        if (!strcasecmp($status, 'Completed') || !strcasecmp($status, 'Processed')) {
            $invoice->paid = 1;
            $opportunityData->is_paid = 1;
            $opportunityData->pay_via = 1;
        } else {
            $invoice->paid = 0;
            $opportunityData->is_paid = 0;
        }
        $opportunityData->save();
        $invoice->save();

        return $invoice;
    }

    public function paymentCancel(Request $request)
    {

        $opportunityId = session('bid_id');
        $opportunityData = Bid::find($opportunityId);
        $opportunityData->is_paid = 0;
        $opportunityData->save();

        return redirect('/bid')->with('error',
            'Error processing PayPal payment');

    }

    /**
     * Parse PayPal IPN.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function notify(Request $request)
    {
        if (!($this->provider instanceof ExpressCheckout)) {
            $this->provider = new ExpressCheckout();
        }

        $post = [
            'cmd' => '_notify-validate',
        ];
        $data = $request->all();
        foreach ($data as $key => $value) {
            $post[$key] = $value;
        }

        $response = (string)$this->provider->verifyIPN($post);
        dd($response);

        $ipn = new IPNStatus();
        $ipn->payload = json_encode($post);
        $ipn->status = $response;
        $ipn->save();
    }

}
