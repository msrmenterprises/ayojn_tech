<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\IPNStatus;
use App\Item;
use App\Opportunity;
use App\User;
use App\WalletLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Srmklive\PayPal\Services\ExpressCheckout;

class PaypalController extends Controller
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
        $price = 100;

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
        session(['price' => $price]);
        $cart = $this->getCheckoutData(false);
        session(['opportunity_id' => $id]);
        try {
            $options = [
                'BRANDNAME' => 'Ayojn',
                'LOGOIMG' => asset('images/sponsay_logo.png'),
            ];
            $response = $this->provider->addOptions($options)->setExpressCheckout($cart, false);
            if(!empty($response['paypal_link'])){
                return redirect($response['paypal_link']);
            }else{
                return redirect('/vouches/' . $id)->with('error',
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

        $order_id = Invoice::all()->count() + 1;

        $price = session('price');
        $data['items'] = [
            [
                'name' => 'Opportunity Id',
                'price' => $price,
                'qty' => 1,
            ],
        ];

        $data['return_url'] = url('/paypal/ec-checkout-success');
        $dataId = str_random(6);
        $data['invoice_id'] = config('paypal.invoice_prefix')  . $order_id . '_opportunity_' . $dataId;
        $data['opportunity_id'] = session('opportunity_id');
        $data['invoice_description'] = "Order #$order_id Invoice . $order_id . '_opportunity_' . $dataId";
        $data['cancel_url'] = url('/paypal/cancel');

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
        $opportunityId = $cart['opportunity_id'];
        if (in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {
            // Perform transaction on PayPal
            $payment_status = $this->provider->doExpressCheckoutPayment($cart, $token, $PayerID);
            $status = $payment_status['PAYMENTINFO_0_PAYMENTSTATUS'];

            $invoice = $this->createInvoice($cart, $status);

            if ($invoice->paid) {
                $price = session('price');
                if (Auth::user()->is_bonus_used == 0) {
                    $loggedinUser = User::find(Auth::user()->id);
                    $loggedinUser->is_bonus_used = 1;
                    $loggedinUser->save();
                }
                if (! empty(Auth::user()->refer_by)) {
                    $userData = User::find(Auth::user()->refer_by);
                    if (! empty($userData)) {
                        $points = ($price * 10) / 100;
                        $userData->wallet_balance = $userData->wallet_balance + $points;
                        $userData->save();
                        $walletLogs = [
                            'user_id'=>$userData->id,
                            'points_by' => Auth::user()->id,
                            'point'=> $points
                        ];
                        WalletLog::create($walletLogs);

                    }

                }
                session()->put(['code' => 'success', 'message' => "Order $invoice->id has been paid successfully!"]);

                return redirect('/vouches/' . $opportunityId)->with('success',
                    'Your payment to access the vouchlist\'s opportunites is received with thanks. ');
            } else {
                session()->put(['code' => 'danger', 'message' => "Error processing PayPal payment for Order $invoice->id!"]);

                return redirect('/vouches/' . $opportunityId)->with('error',
                    'Error processing PayPal payment');
            }


        } else {
            $opportunityData = Opportunity::find($cart['opportunity_id']);
            $opportunityData->is_paid = 0;
            $opportunityData->save();

            return redirect('/vouches/' . $opportunityId)->with('error',
                'Error processing PayPal payment');
        }
    }

    protected function createInvoice($cart, $status)
    {
        $invoice = new Invoice();
        $invoice->title = $cart['invoice_description'];
        $invoice->price = $cart['total'];
        $invoice->opportunity_id = $cart['opportunity_id'];
        $opportunityData = Opportunity::find($cart['opportunity_id']);
        if (! strcasecmp($status, 'Completed') || ! strcasecmp($status, 'Processed')) {
            $invoice->paid = 1;
            $opportunityData->is_paid = 1;
            $opportunityData->next_payment_date = Carbon::now()->addYear();
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

        $opportunityId = session('opportunity_id');
        $opportunityData = Opportunity::find($opportunityId);
        $opportunityData->is_paid = 0;
        $opportunityData->save();

        return redirect('/vouches/' . $opportunityId)->with('error',
            'Error processing PayPal payment');

    }

    /**
     * Parse PayPal IPN.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function notify(Request $request)
    {
        if (! ($this->provider instanceof ExpressCheckout)) {
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
