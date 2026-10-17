<?php

namespace App\Http\Controllers\UserAuth;

use App\Bid;
use App\Helpers\CommonHelper;
use App\Http\Controllers\Controller;
use App\Offer;
use App\ResponseBid;
use App\SponsorrSpecifyBid;
use App\Traits\ApiResponse;
use App\User;
use App\VouchCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mail;
use DB;
use Validator;
use App\UserType;
use Exception;

class BidController extends Controller
{

    use ApiResponse;

    public function __construct()
    {
        
        $this->apiConfig = [
            'API_KEY' => config('sitevars.xsquare_local.API_KEY'),
            'ENDPOINT' => config('sitevars.xsquare_local.ENDPOINT'),
            'SECRET_KEY' => config('sitevars.xsquare_local.SECRET_KEY')
        ];
    }



    public function myResponse(Request $request)
    {

        $bids = ResponseBid::with(['bid', 'userDetail', 'userDetail.country_name'])
            ->where('respond_user_id', Auth::user()->id)->get();

        return view('bid.my-response', compact('bids'));
    }

    public function shareBid(Request $request, $id)
    {
//        if (! empty(Auth::user())) {
//            return redirect('bid');
//        }
        $bidData = Bid::with(['country_name', 'bidResponse', 'bidSpecify', 'bidSpecify.specifyName'])
            ->where('share_id', $id)->first();
        if (!empty($bidData)) {
            session()->flash('url.intended', '/' . request()->path());

            return view('bid.share-bid', compact('bidData'));
        } else {
            return view('bid.datanotfound');
        }
    }

    public function index(Request $request)
    {
        if (Auth::user()->sponsor_type == 1) {
            $bids = new Bid();
            $bids = $bids->with(['country_name', 'bidResponseWithSpam', 'bidSpecify', 'bidSpecify.specifyName'])
                ->where('user_id', Auth::user()->id)->where('is_paid', 1);
            $unpaid = Bid::where('user_id', Auth::user()->id)->where('is_paid', 0)->count();

            if ($request->query('f')) {
                $bids = $bids->where('sponsor_for', 'like', $request->query('f'));
            }
            if ($request->query('c')) {
                $bids = $bids->where('sponsor_country', 'like', $request->query('c'));
            }
            if ($request->query('b')) {
                $bids = $bids->where('sponsor_budget', 'like', $request->query('b'));
            }
            if ($request->query('i')) {
                $bids = $bids->where('sponsor_industry', 'like', $request->query('i'));
            }
            $bids = $bids->get();

           
            return view('bid.bid', compact('bids', 'unpaid'));
        } else if (Auth::user()->sponsor_type == 2) {
            $bids = Bid::with(['bider', 'country_name', 'bidSpecify', 'bidSpecify.specifyName', 'bidResponse' => function ($query) {
                $query->where("respond_user_id", Auth::user()->id);
            }
            ])->where('status', '=', 'On')->where('is_paid', 1);
            if ($request->query('f')) {
                $bids = $bids->where('sponsor_for', 'like', $request->query('f'));
            }
            if ($request->query('c')) {
                $bids = $bids->where('sponsor_country', 'like', $request->query('c'));
            }
            if ($request->query('b')) {
                $bids = $bids->where('sponsor_budget', 'like', $request->query('b'));
            }
            if ($request->query('i')) {
                $bids = $bids->where('sponsor_industry', 'like', $request->query('i'));
            }
            if ($request->query('qa')) {
                $bids = $bids->where('identity', 'like', $request->query('qa'));
            }
            if ($request->query('f_c')) {
                $bids = $bids->where('city', 'like', $request->query('f_c'));
            }
            $bids = $bids->get();

            return view('bid.receive-bids', compact('bids'));
        }
    }


    public function unpaid_delete(Request $request)
    {
        $bid = Bid::find($request->id);
        if ($bid) {
            $bid->delete();
            return response()->json(['status' => true, 'message' => 'Bid deleted successfully.']);
        }
        return response()->json(['status' => false, 'message' => 'Bid not found.']);
    }


    public function unpaid(Request $request)
    {
        $bids = new Bid();
        $bids = $bids->with(['country_name', 'bidResponse', 'bidSpecify', 'bidSpecify.specifyName'])
            ->where('user_id', Auth::user()->id)->where('is_paid', 0)->get();

        return view('bid.unpaid-bid', compact('bids'));
    }

    public function myBidResponse(Request $request, $bidId)
    {

       
        if ($bidId != '') {
            $id = base64_decode($bidId);
        } else {
            $id = '';
        }
        $bidDetail = Bid::with(['bidResponse', 'bidResponse.userDetail', 'bidResponse.userDetail.country_name'])
            ->where('id', $id)->first();

        $bidResponse = ResponseBid::with(['bid', 'userDetail', 'userDetail.country_name'])->where('bid_id', $id)
            ->where('is_spam', 0);
        if ($request->query('i')) {
            $bidResponse = $bidResponse->where('is_accepted', $request->query('i'));
        }
        if ($request->query('c')) {
            $bidResponse = $bidResponse->whereHas('userDetail', function ($query) use ($request) {
                $query->where('country', $request->query('c'));
            });
        }
        if ($request->query('city')) {
            $bidResponse = $bidResponse->whereHas('userDetail', function ($query) use ($request) {
                $query->where('city', $request->query('city'));
            });
        }
        if ($request->query('f')) {
            $bidResponse = $bidResponse->whereHas('userDetail', function ($query) use ($request) {
                $query->where('identity', 'like', '%' . $request->query('f') . '%');
            });
        }

        $bidResponses = $bidResponse->get();
        //dd($bidResponse);

        // $bidId = $id;

        return view('bid.my-bid-response', compact('bidDetail', 'bidId', 'bidResponses'));
    }
    public function bookNegotiation(Request $request)
    {
        $bidId = $request->bidId;

        $bids = ResponseBid::with(['bid', 'userDetail', 'userDetail.country_name'])->where('id', $bidId)->first();
            if ($bids->is_accepted = 2) {
                $bids->is_accepted = 3;
                $bids->save();
            }

        return response()->json($bidId);
    }
    

    public function spamBid(Request $request, $bidId)
    {
        if ($bidId != '') {
            $id = base64_decode($bidId);
        } else {
            $id = '';
        }
//        $bidDetail = Bid::with(['bidSpamResponse', 'bidSpamResponse.userDetail', 'bidSpamResponse.userDetail.country_name'])
//            ->where('id', $id)->first();
        $spamBidResponse = ResponseBid::with(['userDetail', 'userDetail.country_name'])->where('bid_id', $id)
            ->where('is_spam', 1)->get();

        // $bidId = $id;

        return view('bid.my-bid-spam-response', compact('spamBidResponse', 'bidId', 'id'));
    }

    public function RestoreSpamBid(Request $request, $id)
    {
        ResponseBid::where('id', $id)->update(['is_spam' => 0]);
        $bidData = ResponseBid::where('id', $id)->first();

        return redirect(url('bid-response') . '/' . base64_encode($bidData->bid_id));
    }

    public function addReason(Request $request, $id)
    {
        $responseDetail = ResponseBid::where('id', $id)->first();
        $view = view('bid.add-reason',
            compact('responseDetail'))->render();

        return response()->json(['html' => $view, 'responseDetail' => $responseDetail]);
    }

    public function addReasonOfReason(Request $request)
    {
        $responses = ResponseBid::where('id', $request->responseId)
            ->update(['spam_reason' => $request->response, 'is_spam' => 1]);

        return $this->successResponse("Bid response has been added to spam.", ['status' => 1]);
    }

    public function recieveBids()
    {
        $bids = Bid::with(['country_name', 'bidResponse'])->get();

        return view('bid.receive-bids', compact('bids'));
    }

    public function getBidResponse(Request $request, $id)
    {
        $bids = ResponseBid::with(['bid', 'userDetail', 'userDetail.country_name'])->where('id', $id)->first();
        if (Auth::user()->sponsor_type == 1) {
            if ($bids->is_accepted < 2) {
                $bids->is_accepted = 1;
                $bids->save();
            }

            if ($bids->is_counted == 0) {

                $receiverUser = User::find($bids->respond_user_id);

                if (!empty($receiverUser) && $receiverUser->free_response > 0) {

                    $receiverUser->free_response = $receiverUser->free_response - 1;
                    $receiverUser->save();
                    $bids->is_counted = 1;
                    $bids->save();
                }
            }
        }


        //dd($bids);
        $view = view('bid.read-response', compact('bids'))->render();

        return response()->json(['html' => $view]);
    }

    public function acceptBid(Request $request, $responseId)
    {
       
        $responses = ResponseBid::where('id', $responseId)->first();
        $responses->is_accepted = $request->status;
        $responses->save();
        if($request->status==1)
        {
            return $this->successResponse("Bid Has been Accepted.", ['status' => 1]);
        }
        if($request->status==2)
        {
            return $this->successResponse("Bid Has been Booked.", ['status' => 1]);
        }
        if($request->status==3)
        {
            return $this->successResponse("Bid Has been Paid.", ['status' => 1]);
        }
       
    }

    public function addNewBid(Request $request)
    {

        $rules = [
            'status' => 'required',
            'sponsorr_type_bid' => 'required',
            'specify_bid' => 'required',
            'country_bid' => 'required',
            'industry_bid' => 'required',
            'budget_bid' => 'required',
            'city_bidder_from' => 'required',

        ];
        $messsages = [
            'status.required' => 'Select Status',
            'sponsorr_type_bid.required' => 'Select Sponsor For',
            'specify_bid.required' => 'Select Specify',
            'country_bid.required' => 'Select Country for Bid',
            'industry_bid.required' => 'Select Industry',
            'budget_bid.required' => 'Select budget of Bid',
            'city_bidder_from.required' => 'Select bidder city',
        ];
        $validator = Validator::make($request->all(), $rules, $messsages);
        if ($validator->fails()) {
            return $this->validationErrors($validator)->setStatusCode(200);
        }
        $bidData = [
            'user_id' => Auth::user()->id,
            'sponsor_for' => $request->sponsorr_type_bid,
            'sponsor_budget' => $request->budget_bid,
            'sponsor_industry' => $request->industry_bid,
            'sponsor_country' => $request->country_bid,
            'city' => $request->city_bid,
            'city_bidder_from' => $request->city_bidder_from,
            'contacted_by' => $request->contacted_by,
            'identity' => $request->identity_bid,
            'admin_status' => 1, 
            'status' => $request->status,
            'bid_start_date' => date('Y-m-d H:i:s'),
            'country' => Auth::user()->country
        ];
        if (!empty($request->likeSponsorr)) {
            $bidData['likeSponsorr'] = implode(',', $request->likeSponsorr);
        }
        $response = Bid::create($bidData);
        $response->share_id = $this->uniquiShareId($response->id);
        $response->save();

        if (isset($request->specify_bid) && !empty($request->specify_bid)) {
            foreach ($request->specify_bid as $sponsor_specify) {
                $specifyUser_list = [
                    'specify_name' => $sponsor_specify,
                    'user_id' => Auth::user()->id,
                    'bid_id' => $response->id
                ];

                $specifyUser = SponsorrSpecifyBid::create($specifyUser_list);
            }
        }
        if ($response) {
            return $this->successResponse("Bid Has been Added.");
        } else {
            return $this->failResponse("Error Occured!Please try again later")->setStatusCode(200);
        }
    }

    public function addNewVouchCode(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'vouch_code' => "required",
        ], [
            'vouch_code.required' => "Vouch code is required",
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => $validator->errors()->first()])
                ->setStatusCode(200);
        }
        $vouchCode = VouchCode::where('vouch_code', $request->vouch_code)->first();
        if (!empty($vouchCode)) {
            $today = Carbon::now()->format('Y-m-d');
            if ($today <= $vouchCode->expeiry_date) {
                $bidData = Bid::find($request->bid_id);
                $bidData->is_paid = 1;
                $bidData->vouch_code = $request->vouch_code;
                $bidData->pay_via = 2;
                $bidData->save();

                return response()
                    ->json(['status' => 1, 'message' => "This opportunity is now listed for Bids."])
                    ->setStatusCode(200);
            } else {
                return response()->json(['status' => 0, 'message' => "Vouch code Expried"])
                    ->setStatusCode(200);
            }

        } else {
            return response()->json(['status' => 0, 'message' => "Vouch code doesn't Exist"])
                ->setStatusCode(200);
        }
    }

    public function uniquiShareId($id)
    {
        return md5(uniqid(rand() . $id, true));
    }

    public function addBidResponse(Request $request)
    {
        $rules = [
            'bid_id' => 'required',
            'description' => 'required',
            'portfolio_link' => 'required',
        ];
        $messsages = [
            'bid_id.required' => 'Select any bid for response',
            'description.required' => 'Enter Description',
            'portfolio_link.required' => 'Enter Portfolio Link',

        ];
        $validator = Validator::make($request->all(), $rules, $messsages);
        if ($validator->fails()) {
            return $this->validationErrors($validator)->setStatusCode(200);
        }
        $bidData = [
            'respond_user_id' => Auth::user()->id,
            'bid_id' => $request->bid_id,
            'description' => $request->description,
            'portfolio_link' => $request->portfolio_link,
//            'bid_start_date'=>date('Y-m-d H:i:s'),
        ];
        $user = User::find(Auth::user()->id);
        $user->free_response = $user->free_response + 1;
        $user->is_paid = 0;
        $user->save();
        $bidDetail = Bid::with('bider')->where('id', $request->bid_id)->first();
        $userDetails = User::find($bidDetail->user_id);
        $url = url('bid-response/') . '/' . base64_encode($bidDetail->id);
        $linkName = '<a href="' . $url . '">Click here</a>';
        $message = "Your opportunity received a bid, please " . $linkName . " to view the details.";
        CommonHelper::sendNotification($userDetails, $message, $url);
//        if (! empty($bidDetail->bider)) {
//
//            $smsdata = [
//                'to' => 'bhalani.akashb@gmail.com',
//                'subject' => "Bid Response at Sponsorr",
//                'message' => "There has been activity on your bids, please check it.",
//            ];
//            $path = 'mail/template';
//            //$send_mail = send_mail($smsdata);
//            $data = ['to' => $bidDetail->bider->email,
//                'from' => env('MAIL_USERNAME'),
//                'reply_to' => env('MAIL_USERNAME'),
//                'from_name' => '',
//                'subject' => "Bid Response at Sponsorr",
//                'message' => "There has been activity on your bids, please check it.",
//                'path' => $path];
//
//            Mail::send('/mail/template', ['data' => $data], function ($m) use ($data) {
//                $m->to($data['to'])->subject("Bid Response at Sponsorr")->getSwiftMessage()
//                    ->getHeaders()
//                    ->addTextHeader('x-mailgun-native-send', 'true');
//            });
//            $bidDetail->next_at = Carbon::tomorrow();
//            $bidDetail->save();
//        }

        $response = ResponseBid::create($bidData);
        if ($response) {
            return $this->successResponse("Bid response Has been Added.");
        } else {
            return $this->failResponse("Error Occured!Please try again later")->setStatusCode(200);
        }
    }

    public function cronForDailyMail()
    {
        echo "testsetsetset";
        $bids = Bid::with('bider')->where('next_at', Carbon::now()->format('Y-m-d'))->get();
        if (!empty($bids->first())) {
            foreach ($bids as $bid) {
                $smsdata = [
                    'to' => $bid->bider->email,
                    'subject' => "Bid Response at Ayojn",
                    'message' => "There has been activity on your bids, please check it.",
                ];
                $send_mail = send_mail($smsdata);

                $bid->next_at = Carbon::tomorrow();
                $bid->save();
            }
        }
    }

    public function changeBidStatus(Request $request)
    {
        if ($request->status == 'On') {
            $status = 'Off';
        } else {
            $status = 'On';
        }
        $bid = Bid::find($request->id);

        $bid->status = $status;
        $bid->save();
        if ($bid) {
            return $this->successResponse("Bid status Has been Changed.");
        } else {
            return $this->failResponse("Error Occured!Please try again later")->setStatusCode(200);
        }
    }

    public function editNewBid(Request $request)
    {
        $bid = Bid::with('bidSpecify')->where('id', $request->id)->first();

        return response()->json(['bid' => $bid, 'status' => 1]);
    }

    public function updateNewBid(Request $request)
    {
        $rules = [
            'status' => 'required',
            'sponsorr_type_bid' => 'required',
            //'specify_bid' => 'required',
            'country_bid' => 'required',
            'industry_bid' => 'required',
            'budget_bid' => 'required',

        ];
        $messsages = [
            'status.required' => 'Select Status',
            'sponsorr_type_bid.required' => 'Select Sponsor For',
            //'specify_bid.required' => 'Select Specify',
            'country_bid.required' => 'Select Country for Bid',
            'industry_bid.required' => 'Select Industry',
            'budget_bid.required' => 'Select budget of Bid',
        ];
        $validator = Validator::make($request->all(), $rules, $messsages);
        if ($validator->fails()) {
            return $this->validationErrors($validator)->setStatusCode(200);
        }
        $bid = Bid::find((int)$request->bidid);
        $bid->sponsor_for = $request->sponsorr_type_bid;
        $bid->sponsor_budget = $request->budget_bid;
        $bid->sponsor_industry = $request->industry_bid;
        $bid->sponsor_country = $request->country_bid;
        $bid->city = $request->city_bid;
        $bid->contacted_by = $request->contacted_by;
        $bid->identity = $request->identity_bid;
        $bid->status = $request->status;
        $bid->city_bidder_from = $request->city_bidder_from;
        $bid->likeSponsorr = isset($request->likeSponsorr) ? implode(',', $request->likeSponsorr) : '';
        $bid->save();
        if (isset($request->specify_bid) && !empty($request->specify_bid)) {
            SponsorrSpecifyBid::where('bid_id', $bid->id)->forceDelete();
            foreach ($request->specify_bid as $sponsor_specify) {
                $specifyUser_list = [
                    'specify_name' => $sponsor_specify,
                    'user_id' => Auth::user()->id,
                    'bid_id' => $bid->id
                ];

                $specifyUser = SponsorrSpecifyBid::create($specifyUser_list);
            }
        }
        if ($bid) {
            return $this->successResponse("Bid Has been Updated.");
        } else {
            return $this->failResponse("Error Occured!Please try again later")->setStatusCode(200);
        }
    }

    public function Offers(Request $request)
    {
        
        $offers = Offer::with('industry')->where('status', 'On')->where('admin_status', 1)->orderby('id','desc');
        if (Auth::user()->sponsor_type == 1) {
            $offers = $offers->where(function ($query) {
                $query->where('offer_for', '=', 'Clients')
                    ->orWhere('offer_for', '=', 'Both');
            });
        } else {
            $offers = $offers->where(function ($query) {
                $query->where('offer_for', '=', 'Agencies')
                    ->orWhere('offer_for', '=', 'Both');
            });
        }



        if ($request->query('f')) {
            $usertype = Auth::user()->user_type;
            $typename = DB::table('user_type')->where('id',$usertype)->value('user_type');
            $newtypename = "deal_amount_".$typename;
            if($request->query('f') == 'Free'){
                $offers = $offers->whereNull($newtypename);
            }else if($request->query('f') == 'Paid'){
                $offers = $offers->whereNotNull($newtypename); 
            }
            
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
        return view('user.offers', compact('offers'));
    }


    


    //add by ram 24 sept add cart
    public function addToCart($id)
    {
       
        $offer = Offer::with('industry')->findOrFail($id);

        $cart = [];
        $usertype = Auth::user()->user_type;
        $typename = DB::table('user_type')->where('id',$usertype)->value('user_type');
        $newtypename = "deal_amount_".$typename;
        if($offer->$newtypename != NULL){
            $dealamount = $offer->$newtypename;
        }else{$dealamount = 0; }
        
        $avalible_qty = $offer->qty;

        $cart_chk = DB::table('cart')->where('user_id', Auth::user()->id)->where('id',$id)->first();
        $cart_qty = $cart_chk->quantity??0;

        if($avalible_qty == 0){
            return redirect()->back()->with('error', 'Product quantity is not available or out of stock!');
        }
        
        if(isset($cart_chk)) {
            $cart_chk->quantity++;
            DB::table('cart')->where('id', $cart_chk->id)->update(['quantity' => $cart_chk->quantity]);
            DB::table('offers')->where('id', $cart_chk->id)->update(['qty' => DB::raw("qty - 1")]);

        }  else {
            $cart[$id] = [
                "id" => $offer->id,
                'identity' => $offer->identity,
                'industry' =>(!empty($offer->industry) ? $offer->industry->name :'-'),
                "title" => $offer->title,
                'discount'=>$offer->discount,
                'deal_amount' => $dealamount,
                'country_name'=>$offer->country->country_name,
                'weblink' =>$offer->weblink,
                'notification_email' => $offer->notification_email,
                "quantity" => 1,
                "user_id" => Auth::user()->id,
                "status" => 1,
            ];

            DB::table('offers')->where('id', $offer->id)->update(['qty' => DB::raw("qty - 1")]);
            DB::table('cart')->insert($cart[$id]);
        }
        
        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Offer add to cart successfully!');
    }

    public function addToCartBywishlist($id)
    {
       
        $offer = Offer::with('industry')->findOrFail($id);

        $cart = [];

        $usertype = Auth::user()->user_type;
        $typename = DB::table('user_type')->where('id',$usertype)->value('user_type');
        $newtypename = "deal_amount_".$typename;
        if($offer->$newtypename != NULL){
            $dealamount = $offer->$newtypename;
        }else{$dealamount = 0; }
        
        $cart_chk = DB::table('cart')->where('user_id', Auth::user()->id)->where('id',$id)->first();
        $cart_qty = $cart_chk->quantity??0;

        $cart_id = DB::table('wishlist')->where('user_id', Auth::user()->id)->where('id',$id)->value('cart_id');

        if(isset($cart_chk)) {
            $cart_chk->quantity++;
            DB::table('cart')->where('id', $cart_chk->id)->update(['quantity' => $cart_chk->quantity]);
            DB::table('wishlist')->where('cart_id', $cart_id)->delete();
        }  else {
            $cart[$id] = [
                "id" => $offer->id,
                'identity' => $offer->identity,
                'industry' =>(!empty($offer->industry) ? $offer->industry->name :'-'),
                "title" => $offer->title,
                'discount'=>$offer->discount,
                'deal_amount' => $dealamount,
                'country_name'=>$offer->country->country_name,
                'weblink' =>$offer->weblink,
                'notification_email' => $offer->notification_email,
                "quantity" => 1,
                "user_id" => Auth::user()->id,
                "status" => 1,
            ];

            DB::table('cart')->insert($cart[$id]);
            DB::table('wishlist')->where('cart_id', $cart_id)->delete();
        }
        
        
        return redirect()->back()->with('success', 'Offer add to cart successfully!');
    }

    

    public function check_qty($offer_id,$qty)
    {
        $offer = Offer::find($offer_id);
        if ($offer) {
            if ($offer->qty > $qty) {
                return response()->json(['status' => 1]);
            } else {
                return response()->json(['status' => 0]);
            }
        } else {
            return response()->json(['status' => 2]);
        }
    }
        

    


    public function update(Request $request)
    {
        if($request->id && $request->quantity){

            $qty_type = $request->type;

            $cart = DB::table('cart')->where('cart_id',$request->id)->first();
            $offer_id = $cart->id;
            $get_qty = $this->check_qty($offer_id,$request->quantity);
            if($get_qty->original['status'] == 0){
                session()->flash('error', 'Product quantity is not available!');
            }else{
                if($qty_type=='plus'){
                    
                    DB::table('cart')->where('cart_id', $request->id)->update(['quantity' => DB::raw("quantity + 1") ]);
                    DB::table('offers')->where('id', $offer_id)->update(['qty' => DB::raw("qty - 1")]);
                }
                if($qty_type=='minus'){
                    DB::table('cart')->where('cart_id', $request->id)->update(['quantity' => DB::raw("quantity - 1")]);
                    DB::table('offers')->where('id', $offer_id)->update(['qty' => DB::raw("qty + 1")]);
                }
                
                session()->flash('success', 'Cart successfully updated!');
            }


            
        }
    }

    public function remove(Request $request)
    {
        if($request->id) {

            $cart = DB::table('cart')->where('cart_id',$request->id)->first();
            $offer_id = $cart->id;
            $qty = $cart->quantity;
            $offer = Offer::find($offer_id);
            if($offer) {
                $qty = (int) $qty; 
                DB::table('offers')->where('id', $offer_id)->increment('qty', $qty);

            }

            $remove = DB::table('cart')->where('cart_id',$request->id)->delete();
            if($remove){
               
                session()->flash('success', 'Item successfully removed!');
              

            }
            

        }
    }

    public function removewishlist(Request $request)
    {


        if($request->id) {


            $cart = DB::table('wishlist')->where('cart_id',$request->id)->first();
            $offer_id = $cart->id;
            $qty = $cart->quantity;
            $offer = Offer::find($offer_id);
            if($offer) {
                $qty = (int) $qty; 
                DB::table('offers')->where('id', $offer_id)->increment('qty', $qty);
            }

            $remove = DB::table('wishlist')->where('cart_id',$request->id)->delete();
            if($remove){
               
                session()->flash('success', 'Item successfully removed!');
              
            }
            
        }
    }

    

    public function cart()
    {
        
        try {
            $cart_details = DB::table('cart')->where('user_id', Auth::user()->id)->get();
            $cart_details_array = json_decode(json_encode($cart_details), true);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve cart details. Please try again later.'], 500);
        }
        return view('user.cart', ['rstorder' => 0, 'cart_details' => $cart_details_array]);  
    }

    public function wishlist()
    {
        
        try {
            $cart_details = DB::table('wishlist')->where('user_id', Auth::user()->id)->get();
            $cart_details_array = json_decode(json_encode($cart_details), true);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve cart details. Please try again later.'], 500);
        }
        return view('user.wishlist', ['rstorder' => 0, 'cart_details' => $cart_details_array]);  
    }


    


    public function addToWish($id)
    {
       
        $offer = Offer::with('industry')->findOrFail($id);

        $usertype = Auth::user()->user_type;
        $typename = DB::table('user_type')->where('id',$usertype)->value('user_type');
        $newtypename = "deal_amount_".$typename;
        if($offer->$newtypename != NULL){
            $dealamount = $offer->$newtypename;
        }else{$dealamount = 0; }
        
        $avalible_qty = $offer->qty;

        $cart_chk = DB::table('cart')->where('user_id', Auth::user()->id)->where('id',$id)->first();
        $cart_qty = $cart_chk->quantity??0;

        if($avalible_qty == 0){
            return redirect()->back()->with('error', 'Product quantity is not available or out of stock!');
        }



        
        if(isset($cart_chk)) {
            $cart_chk->quantity++;
            DB::table('wishlist')->where('id', $cart_chk->id)->update(['quantity' => $cart_chk->quantity]);
            DB::table('offers')->where('id', $cart_chk->id)->update(['qty' => DB::raw("qty - 1")]);
        }  else {
            $cart[$id] = [
                "id" => $offer->id,
                'identity' => $offer->identity,
                'industry' =>(!empty($offer->industry) ? $offer->industry->name :'-'),
                "title" => $offer->title,
                'discount'=>$offer->discount,
                'deal_amount' => $dealamount,
                'country_name'=>$offer->country->country_name,
                'weblink' =>$offer->weblink,
                'notification_email' => $offer->notification_email,
                "quantity" => 1,
                "user_id" => Auth::user()->id,
                "status" => 1,
            ];

            DB::table('offers')->where('id', $offer->id)->update(['qty' => DB::raw("qty - 1")]);
            DB::table('wishlist')->insert($cart[$id]);
        }
        
       
        return redirect()->back()->with('success', 'Offer add to wishlist');
    }


    public function checkout(Request $request)
    {
       
       
        $carts = DB::table('cart')->where('user_id', Auth::user()->id)->get();
        $cart_details = json_decode(json_encode($carts), true);
         if($carts)
         {
            if($request->post('btn_name') == "free")
            {
                //$send_data = array('amount' => $request->post('paidamt_hidden'));
                //$result = $this->sendRequest($send_data);

                $pay_hidden = $request->post('pay_hidden');
                $usewallet = $request->post('walletamt_hidden');
                $wallet_balance = $request->post('wallet_balance')??'0';
                $wallet_use = $request->post('wallet_use');
                $paidamt = $request->post('paidamt_hidden');
                $voucher_code = $request->post('voucher_code_new'); 
                $data = array(
                    'user_id'=>Auth::user()->id, 
                    'pay_amt'=>$pay_hidden, 
                    'wallet' => $wallet_use??0,
                    'total_amt'=>$paidamt);
                $rstorder = DB::table('offers_order')->insertGetId($data);
                
                if($rstorder)
                {
                    foreach ($carts as $key => $cart) 
                    {

                        $finalamt = $cart->deal_amount*$cart->quantity;
                        $items = array('order_id'=>$rstorder,'offer_id'=>$cart->id,'offer_amt'=>$cart->deal_amount,'qty'=>$cart->quantity,'final_amt'=>$finalamt);
                        $rstitem = DB::table('offers_order_item')->insert($items);
                    }
        
                    if($wallet_use > 0)
                    {
                        $insarray = array(
                            'user_id'=>Auth::user()->id,
                            'points_by'=>2,
                            'trans_id'=>$rstorder,
                            'point'=>$wallet_use,
                            'point_type'=>'DR',
                            'wallet_type'=>1,
                            'created_at'=>now()
                        );
                        DB::table('wallet_logs')->insert($insarray);
                        $walletupdate = DB::table('users')->where('id',Auth::user()->id)->update(array('wallet_marketplace'=>DB::raw("wallet_marketplace - $wallet_use")));
            
                    }

                    if($voucher_code != '')
                    {
                        DB::table('vouch_codes')->where('vouch_code',$voucher_code)->update(array('used_by'=>Auth::user()->id,'status'=>1));
                        $flag = 1;
                    }
                    $remove = DB::table('cart')->where('user_id',Auth::user()->id)->delete();
                    return view('user.cart',array('rstorder'=>$rstorder,'cart_details'=>''));
                }else{
                    
                    return view('user.cart',array('rstorder'=>0,'cart_details'=>''));
                    }
            }
            else{
                

            $send_data = array('amount' => $request->post('paidamt_hidden'));
            $result = $this->sendRequest($send_data);

            if (isset($result['error'])) {
                // echo "<div style='color:red'>";
                 echo "Error: " . htmlspecialchars($result['error']);
                // echo "</div>";
             } else {



                    $pay_hidden = $request->post('pay_hidden');
                    $usewallet = $request->post('walletamt_hidden');
                    $wallet_balance = $request->post('wallet_balance')??'0';
                    $wallet_use = $request->post('wallet_use');
                    $paidamt = $request->post('paidamt_hidden');
                    $voucher_code = $request->post('voucher_code_new'); 
                    $data = array(
                        'user_id'=>Auth::user()->id, 
                        'pay_amt'=>$pay_hidden, 
                        'wallet' => $wallet_use??0,
                        'total_amt'=>$paidamt);
                    $rstorder = DB::table('offers_order')->insertGetId($data);
                    
                    if($rstorder)
                    {
                        foreach ($carts as $key => $cart) 
                        {
                            $finalamt = $cart->deal_amount*$cart->quantity;
                            $items = array('order_id'=>$rstorder,'offer_id'=>$cart->id,'offer_amt'=>$cart->deal_amount,'qty'=>$cart->quantity,'final_amt'=>$finalamt);
                            $rstitem = DB::table('offers_order_item')->insert($items);
        
                        }


                        $percentage = CommonHelper::getConfig('marketplace_payment')['ayojn_service'];
                        $config_type = CommonHelper::getConfig('marketplace_payment')['config_type'];
                        $ayojanEarnings = CommonHelper::calculateAyojanEarning($config_type,$request->post('paidamt_hidden'),$percentage);

                        
                        
                        $data = array(
                            'user_id'=> Auth::user()->id, 
                            'beneficiary_id' => 7,
                            'collection_id'=> $result['response']['details']['collection_id'], 
                            'purpose' => 1,
                            'amount'=> $request->post('paidamt_hidden'),
                            'ayojn_earn' => $ayojanEarnings,
                            'transaction_reference' => $result['response']['details']['order_reference'],
                            'form_name' => 'marketplace_payment',
                            'refrence_id' => $rstorder,
                            'refrence_details' => json_encode(array('vouch_code'=>$voucher_code, 'wallet' => $wallet_use, 'marketplace_id' => $rstorder))
                        );
            
                        $rstorder = DB::table('direct_payment')->insert($data);
                       // return view('user.cart',array('rstorder'=>$rstorder));
            
                    }
                  

                //   echo "<pre>";
                //   print_r($result['response']); 
                //   die;
                 header("Window-target: _parent");
                 header('Location:'.$result['response']['details']['payment_url']);
                 exit;
             }
                
            }


        }
        else{
            return view('user.cart',array('rstorder'=>0,'cart_details'=>$cart_details));
        }
        

    }


    //add by ram 7 jan 2024
    public function payOportunity(Request $request)
    {

        $bidId = $request->bidId;

        $wallet = $request->wallet;

        $userid = Auth::user()->id;

        $amt = $request->amt;

        $voucher_code = $request->voucher_code;
       
        $flag = 0;

        //$bid = Bid::find($bidId);

        // $bid->is_paid = 1;

        // $bid->save();

        // if($bid->save())
        // {
            
            if($amt > 0){

                $send_data = array('amount' => $amt);
                $result = $this->sendRequest($send_data);

                $percentage = CommonHelper::getConfig('unpaid_opportunity')['ayojn_service'];
                $config_type = CommonHelper::getConfig('unpaid_opportunity')['config_type'];
                $ayojanEarnings = CommonHelper::calculateAyojanEarning($config_type,0,$percentage);
    
                $data = array(
                    'user_id'=> Auth::user()->id, 
                    'beneficiary_id' => 7,
                    'collection_id'=> $result['response']['details']['collection_id'], 
                    'purpose' => 1,
                    'amount'=> $amt,
                    'ayojn_earn' => $ayojanEarnings,
                    'transaction_reference' => $result['response']['details']['order_reference'],
                    'form_name' => 'unpaid_opportunity',
                    'refrence_id' => $bidId,
                    'refrence_details' => json_encode(array('vouch_code'=>$voucher_code, 'wallet' => $wallet, 'bid_id' => $bidId))
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

                $per = DB::table('bids')->where('id',$bidId)->value('per');
                if($per>0){$rate = $per;}else{$rate = '10';}
        
                $wallet_amt = round($amt*$rate/100,0);
                $walletupdate = DB::table('users')->where('id',$userid)->update(array('wallet_balance'=>DB::raw("wallet_balance + $wallet_amt")));
                $insarray = array('user_id'=>$userid,'points_by'=>1,'trans_id'=>$bidId,'point'=>$wallet_amt,'point_type'=>'CR','created_at'=>now());
                DB::table('wallet_logs')->insert($insarray);
    
                if (isset($result['error'])) {
                    // echo "<div style='color:red'>";
                    echo "Error: " . htmlspecialchars($result['error']);
                    // echo "</div>";
                }
            
                $link = $result['response']['details']['payment_url'];
                return array('flag'=>$flag, 'link' => $link);
                
            }else{
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
        
        
                if($amt>0)
                {
                    $per = DB::table('bids')->where('id',$bidId)->value('per');
                    if($per>0){$rate = $per;}else{$rate = '10';}
            
                    $wallet_amt = round($amt*$rate/100,0);
                    $walletupdate = DB::table('users')->where('id',$userid)->update(array('wallet_balance'=>DB::raw("wallet_balance + $wallet_amt")));
                    $insarray = array('user_id'=>$userid,'points_by'=>1,'trans_id'=>$bidId,'point'=>$wallet_amt,'point_type'=>'CR','created_at'=>now());
                    DB::table('wallet_logs')->insert($insarray);
                    $flag = 1;
                }

                // Update the bid as paid
                $update = DB::table('bids')
                    ->where('id', $bidId)
                    ->update(array('is_paid'=>1));

                return array('flag'=>$flag, 'link' => "");
            }
            
           
       // }

      

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


    //add by ram for paynow button 21 sep 2023  ---- update function 07 jan 2024
    public function payNow(Request $request)
    {
        $bidId = $request->bidId;

        $wallet = $request->wallet;

        $bid_close_amount = $request->bid_close_amount;

        $userid = Auth::user()->id;

        $amt = $request->amt;

        $voucher_code = $request->voucher_code;

        $bids = \App\ResponseBid::with(['bid', 'userDetail', 'userDetail.country_name'])->where('id', $bidId)->first();

            if ($bids->is_accepted = 3) {
                $bids->is_accepted = 4;
                $bids->bid_close_amount = $bid_close_amount;
                $bids->save();
            }

            $flag = 0;

            if($bids->save())
            {
                
                if($amt > 0)
                {
                    $send_data = array('amount' => $amt);
                    $result = $this->sendRequest($send_data);

                      $percentage = CommonHelper::getConfig('pay_opportunity ')['ayojn_service'];
                      $config_type = CommonHelper::getConfig('pay_opportunity')['config_type'];
                      $ayojanEarnings = CommonHelper::calculateAyojanEarning($config_type,0,$percentage);
        
                    $data = array(
                        'user_id'=> Auth::user()->id, 
                        'beneficiary_id' => 7,
                        'collection_id'=> $result['response']['details']['collection_id'], 
                        'purpose' => 1,
                        'amount'=> $amt,
                        'ayojn_earn' => $ayojanEarnings,
                        'transaction_reference' => $result['response']['details']['order_reference'],
                        'form_name' => 'pay_opportunity',
                        'refrence_id' => $bidId,
                        'refrence_details' => json_encode(array('vouch_code'=>$voucher_code, 'wallet' => $wallet, 'bid_id' => $bids->bid_id))
                    );
        
                    $rstorder = DB::table('direct_payment')->insert($data);
                    $flag =1;
                    
                  

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
                else
                {

                    if($wallet > 0)
                    {
                        $walletupdate = DB::table('users')->where('id',$userid)->update(array('wallet_balance'=>DB::raw("wallet_balance - $wallet")));
                        $insarray = array('user_id'=>$userid,'points_by'=>1,'trans_id'=>$bids->bid_id,'point'=>$wallet,'point_type'=>'DR','created_at'=>now());
                        DB::table('wallet_logs')->insert($insarray);
                        $flag = 1;
                    }

                    if($voucher_code != '')
                    {
                    DB::table('vouch_codes')->where('vouch_code',$voucher_code)->update(array('used_by'=>$userid,'status'=>1));
                    $flag = 1;
                    }


                    $per = DB::table('bids')->where('id',$bids->bid_id)->value('per');
                    if($per>0){$rate = $per;}else{$rate = '10';}

                    $wallet_amt = round($amt*$rate/100,0);
                    $walletupdate = DB::table('users')->where('id',$userid)->update(array('wallet_balance'=>DB::raw("wallet_balance + $wallet_amt")));
                    $insarray = array('user_id'=>$userid,'points_by'=>1,'trans_id'=>$bids->bid_id,'point'=>$wallet_amt,'point_type'=>'CR','created_at'=>now());
                    DB::table('wallet_logs')->insert($insarray);
                    $flag = 1;

                    return array('flag'=>$flag, 'link' => "");

                }    
             
            }   

        
    }

    public function getBidbyid(Request $request, $id)
    {
        $bid_id = ResponseBid::where('id', $id)->value('bid_id');
        $bids = Bid::with(['user','bidResponse'])->where('id',$bid_id)->first();
        $view = view('bid.ram-response', compact('bids'))->render();
        return response()->json(['html' => $view]); 
    }


    public function paymentproofget(Request $request)
    {
        $id = $request->id;
        $file = DB::table('bids')->where('id',$id)->value('payment_proof');
        echo $file;
    }


    public function paymentproof(Request $request)
    {

        if($request->hasFile('payment_proof_img')) {
            $file = $request->file('payment_proof_img'); 

            $ext =  $file->getClientOriginalExtension();

            $allowed_extensions = array("jpg", "jpeg", "png", "pdf", "doc", "docx");
            if(in_array($ext, $allowed_extensions))
            {

                //you also need to keep file extension as well
                $name = "proof_".rand().'.'.$file->getClientOriginalExtension();
                
                //using the array instead of object
                $image['filePath'] = $name;
                $file->move(public_path().'/uploads/', $name);

                $data = array(
                    'payment_proof' => $name
                ); 
                $rst = DB::table('bids')->where('id',$request->proof_bid)->update($data); 
                if($rst)
                {
                    echo json_encode(array("status"=> true));
                } 
           
            }
            else{
                echo json_encode(array("status"=> false));
            }
                  
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


}
