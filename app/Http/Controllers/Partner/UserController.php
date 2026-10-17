<?php

namespace App\Http\Controllers\Partner;

use App\Country;
use App\Currency;
use App\Http\Controllers\Controller;
use App\Offer;
use App\Traits\ApiResponse;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use DB;
use App\Helpers\CommonHelper;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    use ApiResponse;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->apiConfig = [
            'API_KEY' => config('sitevars.xsquare_local.API_KEY'),
            'ENDPOINT' => config('sitevars.xsquare_local.ENDPOINT'),
            'SECRET_KEY' => config('sitevars.xsquare_local.SECRET_KEY')
        ];

    }


    public function generateSignature($payload) {
        $timestamp = (string)time(); // Use current timestamp as string
        
        ksort($payload);
        
        $signaturePayload = $payload;
        unset($signaturePayload['currencies']);
        $signaturePayload['amount'] = strpos($payload['amount'], '.') === false ? number_format((float)$payload['amount'], 1, '.', '') : (string)floatval($payload['amount']);
        
        $message = $this->apiConfig['API_KEY'] . $timestamp . json_encode($signaturePayload);
        
        $signature = hash_hmac('sha256', $message, $this->apiConfig['SECRET_KEY']);
        
        return [
            'signature' => $signature,
            'timestamp' => $timestamp
        ];
    }

    public function sendRequest($data) {
        try {
           
            $data_array = array(
                'first_name'=> 'Ayojn',
                'last_name'=> 'Company',
                'email'=> 'ayojn@gmail.com',
                'purpose'=> '1',
                'amount'=> $data['amount'],
                'currencies'=> array('currency_id'=>1,'name'=>'United Arab Emirates - AED','code' => 'AED')
             );

    

            $signatureData = $this->generateSignature($data_array);

            //$signatureData = $this->generateSignature($this->formData);

            $ch = curl_init($this->apiConfig['ENDPOINT']);
            $customerId = config('sitevars.xsquare_local.x-customer-id');
            $customerId = is_numeric($customerId) ? (int)$customerId : 0;

            
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data_array));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'x-api-key: ' . $this->apiConfig['API_KEY'],
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
                    'payload' => $data_array,
                    'signature' => $signatureData
                ]
            ];

        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function index(Request $request)
    {

        $offers = Offer::with('industry')->where('partner_id', Auth::user()->id);

        if ($request->query('f')) {
            $offers = $offers->where('offer_for', $request->query('f'));
        }
        if ($request->query('c')) {
            $offers = $offers->where('available_in', $request->query('c'));
        }
        if ($request->query('b')) {
            $offers = $offers->where('identity', $request->query('b'));
        }
        if ($request->query('i')) {
            $offers = $offers->where('function', $request->query('i'));
        }
        if ($request->query('cu')) {
            $offers = $offers->where('currency', $request->query('cu'));
        }
        if ($request->query('refund')) {
            if($request->query('refund') !=1){
                $offers = $offers->whereNull('refund');
            }else{
                $offers = $offers->where('refund',$request->query('refund'));
            }
        }
        $offers = $offers->get();

        return view('partner.home', compact('offers'));
    }

    public function offer_details(Request $request)
    {

        $offers = Offer::with('industry')->where('partner_id', Auth::user()->id)->where('id',$request->id);

        if ($request->query('f')) {
            $offers = $offers->where('offer_for', $request->query('f'));
        }
        if ($request->query('c')) {
            $offers = $offers->where('available_in', $request->query('c'));
        }
        if ($request->query('b')) {
            $offers = $offers->where('identity', $request->query('b'));
        }
        if ($request->query('i')) {
            $offers = $offers->where('function', $request->query('i'));
        }
        if ($request->query('cu')) {
            $offers = $offers->where('currency', $request->query('cu'));
        }
        $offers = $offers->get();

        $orders = DB::table('offers_order_item')
        ->join('offers_order','offers_order.id', '=', 'offers_order_item.order_id')
        ->join('users','users.id', '=', 'offers_order.user_id')
        ->select('users.*','offers_order.*','offers_order_item.*','offers_order_item.id as mid')
        ->where('offer_id',$request->id)->get();

        return view('partner.offer-details', compact('offers','orders'));
    }

    public function changeOfferUser(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        $update = DB::table('offers_order_item')->where('id',$id)->update(array('status'=>$status));
        if ($update) {


            if($status==0){               
                    $status = "Pending";
                    $message = "Your Offer Status has been changed ".$status.".";
                }
            elseif($status==1){
            
                $status = "Progress";
                $message = "Your Offer Status has been changed ".$status.".";
            }
            elseif($status==2){
                $status = "Sent";
                $message = "Your Offer Status has been changed ".$status.".";
            }
            elseif($status==3){
                $status = 'Query';
                $message = "Your Offer Status has been changed ".$status.".";
            }
            elseif($status==4){
            
                $status ='Delivered';
                $message = "Thank you! Please sent your feedback.";
            }

            $order_id = DB::table('offers_order_item')->where('id',$id)->value('order_id');
            $user_id = DB::table('offers_order')->where('id',$order_id)->value('user_id');
            $userDetails = User::find($user_id);
            $linkName = '<a href="' . url('marketplace-history') . '">Click here</a>';
           
            $link = url('marketplace-history');
            CommonHelper::sendNotification($userDetails, $message, $link);



            return $this->successResponse("Offer status has been changed.");
        } else {
            return $this->failResponse("Error Occured!Please try again later")->setStatusCode(200);
        }


    }

    public function pending()
    {
        if (! empty(Auth::user()) && Auth::user()->is_edited == 1) {
            return redirect('review');
        }

        if (Auth::user()->userstatus) {
            return redirect('partner/home');
        } else {
            return view('partner.pending');
        }
    }

    public function suspend()
    {
        if (Auth::user()->is_suspend) {
            return view('partner.suspend');
        } else {
            return redirect('partner/home');
        }

    }

   
    public function updateUserProfile(Request $request)
    {
        $user = Auth::user();
        $alluser = User::where('id', '!=', $user->id)->where('email', $request->useremail_edit)->get();
        if (count($alluser) > 0) {
            $result = [
                'status' => 0,
                'msg' => 'Email Address is already in use'
            ];

            return response()->json($result)->setStatusCode(200);
        }
        $user_id = $user->id;
        $user->edit_count = (int)$user->edit_count + 1;
        $user->email = $request->useremail_edit;
        $user->contact_person = $request->contact_person;
        $user->company_name = $request->edit_company_name;
        $user->phone_no = $request->phone_no_edit;
        $user->userstatus = 0;
        $user->is_edited = 1;
        $user->save();
        $result = [
            'status' => 1,
            'msg' => 'Profile Edited Successfully.'
        ];

        return response()->json($result)->setStatusCode(200);

    }

    public function newOffer()
    {
        $countries = Country::all();
        $Currency = Currency::all();

        return view('partner.add-offer', compact('Currency', 'countries'));
    }

    public function storeOffer(Request $request)
    {
        $rules = [
            'offer_for' => 'required',
            'partner_identity' => 'required',
            'function' => 'required',
            'title' => 'required',
            'available_in' => 'required',
            'qty' => 'required',
            //'currency' => 'required',
            //'deal_amount' => 'required',
            //'incentive' => 'required',
            //'discount' => 'required',
            //'weblink' => 'required',
            //'notification_email' => 'required',
        ];
        $messsages = [
            'offer_for' => 'Please select offer for',
            'partner_identity' => 'Please select identity',
            'function' => 'Please select function',
            'title' => 'Please enter title',
            'available_in' => 'Please select country',
            'currency' => 'Please select currency',
            //'deal_amount_' => 'Please enter deal amount',
            'incentive' => 'Please enter Ayojn incentive',
            'discount' => 'Please enter discount',
            'qty' => 'Please enter quantity',
            //'weblink' => 'Please enter weblink',
            //'notification_email' => 'Please enter Email for Notification',
        ];
        $validator = Validator::make($request->all(), $rules, $messsages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors->first());
        }
        if ($request->refund == 0) {
            $refund = NULL;
        }else{
            $refund = $request->refund;
        }

        $offerData = [
            'partner_id' => Auth::user()->id,
            'offerred_by' => Auth::user()->company_name,
            'offer_for' => $request->offer_for,
            'identity' => $request->partner_identity,
            'function' => $request->function,
            'title' => $request->title,
            'available_in' => $request->available_in,
            'currency' => $request->currency,
            'deal_amount_delta' => $request->deal_amount_delta,
            'deal_amount_sigma' => $request->deal_amount_sigma,
            'deal_amount_alpha' => $request->deal_amount_alpha,
            'deal_amount_omega' => $request->deal_amount_omega,
            'incentive' => $request->incentive,
            'discount' => $request->discount,
            'weblink' => $request->weblink??'',
            'notification_email' => $request->notification_email??'',
            'status' => "On",
            'refund' => $refund,
            'admin_status' => 1,
            'qty' => $request->qty,
        ];

        
        $orderresponse = Offer::create($offerData);
        $orderresponse->share_id = $this->uniquiShareId($orderresponse->id);
        $orderresponse->save();
        if (! empty($orderresponse)) {

            $user = User::find(Auth::user()->id);
            $user->free_response = $user->free_response + 1;
            $user->save();


            //notification add
            $userDetails = User::find(Auth::user()->id);
            $url = url('partner/home');
            $linkName = '<a href="' . $url . '">Click here</a>';
            $message = "Your offer successfully created, please " . $linkName . " to view the details.";
            CommonHelper::sendNotification($userDetails, $message, $url);

            //email send
            $smsdata = [
                'to' => Auth::user()->email,
                'subject' => "Offer Created",
                'message' => $message,
            ];
            $send_mail = send_mail($smsdata);

            return redirect('partner/home')->with('success', 'offer successfully created');


        } else {
            return redirect()->back()->withErrors('Something went wrong');
        }
    }


    public function payOportunityrec(Request $request)
    {

        $bidId = $request->bidId;

        $wallet = $request->wallet;

        $userid = Auth::user()->id;

        $amt = $request->amt;

        $voucher_code = $request->voucher_code;

        $description = $request->description;

        $portfolio = $request->portfolio;
       
        $flag = 0;
           
            if($amt > 0){

                $send_data = array('amount' => $amt);
                $result = $this->sendRequest($send_data);

                $percentage = CommonHelper::getConfig('pay_bid')['ayojn_service'];
                $config_type = CommonHelper::getConfig('pay_bid')['config_type'];
                $ayojanEarnings = CommonHelper::calculateAyojanEarning($config_type,0,$percentage);
    
                $data = array(
                    'user_id'=> Auth::user()->id, 
                    'beneficiary_id' => 7,
                    'collection_id'=> $result['response']['details']['collection_id'], 
                    'purpose' => 1,
                    'amount'=> $amt,
                    'ayojn_earn' => $ayojanEarnings,
                    'transaction_reference' => $result['response']['details']['order_reference'],
                    'form_name' => 'pay_bid_rec',
                    'refrence_id' => $bidId,
                    'refrence_details' => json_encode(array('vouch_code'=>$voucher_code, 'wallet' => $wallet, 'bid_id' => $bidId, 'description'=>$description, 'portfolio' => $portfolio))
                );
                
                $rstorder = DB::table('direct_payment')->insert($data);
                $flag =1;

                if($wallet > 0)
                {
                    $walletupdate = DB::table('users')->where('id',$userid)->update(array('wallet_balance'=>DB::raw("wallet_balance - $wallet")));
                    $insarray = array('user_id'=>$userid,'points_by'=>1,'trans_id'=>$bidId,'point'=>$wallet,'point_type'=>'DR','created_at'=>now());
                    DB::table('wallet_logs')->insert($insarray);
                    $flag =1;
                }
        
                if($voucher_code != '')
                {
                    DB::table('vouch_codes')->where('vouch_code',$voucher_code)->update(array('used_by'=>$userid,'status'=>1));
                    $flag =1;
                }

                if (isset($result['error'])) {
                    // echo "<div style='color:red'>";
                    echo "Error: " . htmlspecialchars($result['error']);
                    // echo "</div>";
                }
            
                $link = $result['response']['details']['payment_url'];
                return array('flag'=>$flag, 'link' => $link);
                
            }
            else{

                $bidData = [
                    'respond_user_id' => Auth::user()->id,
                    'bid_id' => $bidId,
                    'description' => $description,
                    'portfolio_link' => $portfolio,
                ];

             
                $bidDetail = Bid::with('bider')->where('id', $bidId)->first();
                $userDetails = User::find($bidDetail->user_id);
                $url = url('bid-response/') . '/' . base64_encode($bidDetail->id);
                $linkName = '<a href="' . $url . '">Click here</a>';
                $message = "Your opportunity received a bid, please " . $linkName . " to view the details.";
                CommonHelper::sendNotification($userDetails, $message, $url); 
                $response = ResponseBid::create($bidData);

                if($response)
                {
                    if($wallet > 0)
                    {
                        $walletupdate = DB::table('users')->where('id',$userid)->update(array('wallet_balance'=>DB::raw("wallet_balance - $wallet")));
                        $insarray = array('user_id'=>$userid,'points_by'=>1,'trans_id'=>$bidId,'point'=>$wallet,'point_type'=>'DR','created_at'=>now());
                        DB::table('wallet_logs')->insert($insarray);
                        $flag = 1;
                    }
            
                    if($voucher_code != '')
                    {
                        DB::table('vouch_codes')->where('vouch_code',$voucher_code)->update(array('used_by'=>$userid,'status'=>1));
                        $flag = 1;
                    }
                }

                return array('flag'=>$flag, 'link' => "");
            }

    }

    public function changeOfferStatus(Request $request)
    {
        if ($request->status == 'On') {
            $status = 'Off';
            $cart_status = 0;
            $msg = "Deactivated";
        } else {
            $status = 'On';
            $cart_status = 1;
            $msg = "Activated";
        }
        $offer = Offer::find($request->id);
        $offer->status = $status;
        $offer->save();
        if ($offer) {
            DB::table('cart')->where('id', $request->id)->update(array('status' => $cart_status));

            $ids = DB::table('cart')->where('id', $request->id) // Assuming 'offer_id' relates to the request
            ->pluck('user_id')->toArray();

             //notification add
             //$ids = [14, 15, 16]; // Example array of user IDs
             $userDetails = User::whereIn('id', $ids)->get();
             foreach ($userDetails as $user) {
                $url = url('cart');
                $linkName = '<a href="' . $url . '">Click here</a>';
                $message = "Offer is " . $msg . ", please " . $linkName . " to view the details.";
                CommonHelper::sendNotification($user, $message, $url);
            }

           
            return $this->successResponse("Offer status has been changed.");
        } else {
            return $this->failResponse("Error Occured!Please try again later")->setStatusCode(200);
        }
    }

    public function uniquiShareId($id)
    {
        return md5(uniqid(rand() . $id, true));
    }

    public function shareOffer($id)
    {
        $offer = Offer::where('share_id', $id)->where('status', 'On')->first();
        session()->flash('url.intended', '/' . request()->path());

        return view('partner.share-offer', compact('offer'));
    }


    public function paynowstoreOffer(Request $request)
    {

       // print_r($request->form_data); die();
        if ($request->form_data[3]['value'] == 0) {
            $refund = NULL;
        }else{
            $refund = $request->form_data[3]['value'];
        }
        
        $offerData = [
            'partner_id' => Auth::user()->id,
            'offer_for' => $request->form_data[0]['value'],
            'offerred_by' => $request->form_data[1]['value'],
            'identity' => $request->form_data[2]['value'],
            'refund' => $refund,
            'function' => $request->form_data[4]['value'],
            'available_in' => $request->form_data[5]['value'],
            'title' => $request->form_data[6]['value'],
            'qty' => $request->form_data[7]['value'],
            'discount' => $request->form_data[8]['value'],
            'deal_amount_delta' => $request->form_data[9]['value'],
            'deal_amount_sigma' => $request->form_data[10]['value'],
            'deal_amount_alpha' => $request->form_data[11]['value'],
            'deal_amount_omega' => $request->form_data[12]['value'],
            'incentive' => $request->form_data[13]['value'],
            'currency' => $request->form_data[14]['value'],
            'weblink' => $request->form_data[15]['value']??'',
            'notification_email' => $request->form_data[16]['value']??'',
            'status' => "On",
            'admin_status' => 0,
        ];


        $orderresponse = Offer::create($offerData);
        $orderresponse->share_id = $this->uniquiShareId($orderresponse->id);
        $orderresponse->save();
        if (!empty($orderresponse)) 
        {

            $user = User::find(Auth::user()->id);
            $user->free_response = $user->free_response + 1;
            $user->save();

            //notification add
            $userDetails = User::find(Auth::user()->id);
            $url = url('partner/home');
            $linkName = '<a href="' . $url . '">Click here</a>';
            $message = "Your offer successfully created, please " . $linkName . " to view the details.";
            CommonHelper::sendNotification($userDetails, $message, $url);
        
            //email send
            $smsdata = [
                'to' => Auth::user()->email,
                'subject' => "Offer Created",
                'message' => $message,
            ];
            $send_mail = send_mail($smsdata);

            $wallet = $request->wallet;

            $userid = Auth::user()->id;

            $amt = $request->amt;

            $voucher_code = $request->voucher_code;
        
            $flag = 0;
           
            if($amt > 0){

                $send_data = array('amount' => $amt);
                $result = $this->sendRequest($send_data);

                 $percentage = CommonHelper::getConfig('partner_offer')['ayojn_service'];
                 $config_type = CommonHelper::getConfig('partner_offer')['config_type'];
                 $ayojanEarnings = CommonHelper::calculateAyojanEarning($config_type,0,$percentage);
    
                $data = array(
                    'user_id'=> $userid, 
                    'beneficiary_id' => 7,
                    'collection_id'=> $result['response']['details']['collection_id'], 
                    'purpose' => 1,
                    'amount'=> $amt,
                    'ayojn_earn' => $ayojanEarnings,
                    'transaction_reference' => $result['response']['details']['order_reference'],
                    'form_name' => 'partner_offer',
                    'refrence_id' => $orderresponse->id,
                    'refrence_details' => json_encode(array('vouch_code'=>$voucher_code, 'wallet' => $wallet, 'offer_id' => $orderresponse->id))
                );
                
                $rstorder = DB::table('direct_payment')->insert($data);
                $flag =1;

                if($wallet > 0)
                {
                    $walletupdate = DB::table('users')->where('id',$userid)->update(array('wallet_balance'=>DB::raw("wallet_balance - $wallet")));
                    $insarray = array('user_id'=>$userid,'points_by'=>1,'trans_id'=>$bidId,'point'=>$wallet,'point_type'=>'DR','created_at'=>now());
                    DB::table('wallet_logs')->insert($insarray);
                    $flag =1;
                }
        
                if($voucher_code != '')
                {
                    DB::table('vouch_codes')->where('vouch_code',$voucher_code)->update(array('used_by'=>$userid,'status'=>1));
                    $flag =1;
                }

                if (isset($result['error'])) {
                    // echo "<div style='color:red'>";
                    echo "Error: " . htmlspecialchars($result['error']);
                    // echo "</div>";
                }
            
                $link = $result['response']['details']['payment_url'];
                return array('flag'=>$flag, 'link' => $link);
                
            }else{

              
                $offer = Offer::find($orderresponse->id);
                $offer->admin_status = 1;
                $offer->save();
             
                if($offer)
                {
                    if($wallet > 0)
                    {
                        $walletupdate = DB::table('users')->where('id',$userid)->update(array('wallet_balance'=>DB::raw("wallet_balance - $wallet")));
                        $insarray = array('user_id'=>$userid,'points_by'=>1,'trans_id'=>$orderresponse->id,'point'=>$wallet,'point_type'=>'DR','created_at'=>now());
                        DB::table('wallet_logs')->insert($insarray);
                        $flag = 1;
                    }
            
                    if($voucher_code != '')
                    {
                        DB::table('vouch_codes')->where('vouch_code',$voucher_code)->update(array('used_by'=>$userid,'status'=>1));
                        $flag = 1;
                    }
                }

                return array('flag'=>$flag, 'link' => "");
            }

        }

    }

}
