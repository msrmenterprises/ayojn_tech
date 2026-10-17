<?php

namespace App\Http\Controllers\UserAuth;

use App\Bid;
use App\Country;
use App\Helpers\CommonHelper;
use App\Http\Controllers\Controller;
use App\Models\UniqueOpportunity;
use App\Offer;
use App\Opportunity;
use App\OpportunityUser;
use App\Traits\ApiResponse;
use App\User;
use App\Vouche;
use App\VouchUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Mail;
use DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class OppertunityController extends Controller
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


    public function index(Request $request)
    {
        
       echo $isUserSesseion = session()->get('user_profile');
        if($isUserSesseion){
            return redirect('/offline-pay');
        }


        //  dd(Auth::user()->sponsor_type);
        if (Auth::user()->sponsor_type == 1) {
           

            $uniqueOpportunity = UniqueOpportunity::pluck('opportunity_id')->toArray();
            $bids = Opportunity::with(['industry', 'country_name', 'opportunityUser', 'vouchResponse' => function ($query) {
                $query->where("offer_id", Auth::user()->id);
            }
            ])->whereIn('id', $uniqueOpportunity);
//            dd($bids->get());

            if ($request->query('f')) {
                $bids = $bids->where('hashtag', 'like', '%' . $request->query('f') . '%');
            }
            if ($request->query('c')) {
                $bids = $bids->where('country_code', 'like', $request->query('c'));
            }
            if ($request->query('city')) {
                $bids = $bids->where('opportunity_city', 'like', $request->query('city'));
            }
            if ($request->query('r')) {
                $bids = $bids->where('is_reserved', $request->query('r'));
            }
            if ($request->query('i')) {
                $bids = $bids->where('industry_id', 'like', $request->query('i'));
            }
            $bids = $bids->where('status', 1)->orderBy('id', 'desc')->get();

            //  dd($bids);

            return view('opportunity.lunched-opportunity', compact('bids'));
        } else if (Auth::user()->sponsor_type == 2) {
            if ($request->query('f')) {
                $bids = Opportunity::whereHas('OpportunityMapping', function ($query) use ($request) {
                    $query->where('user_id', Auth::user()->id)
                        ->where('hashtag', 'like', '%' . $request->query('f') . '%');
                })->with(['industry', 'country_name', 'opportunityUser', 'vouchesResponse']);
            } else {
                $bids = Opportunity::whereHas('OpportunityMapping', function ($query) {
                    $query->where('user_id', Auth::user()->id);
                })->with(['industry', 'country_name', 'opportunityUser', 'vouchesResponse'])->orderBy('id', 'desc');
            }
            if ($request->query('c')) {
                $bids = $bids->where('country_code', 'like', $request->query('c'));
            }
            if ($request->query('i')) {
                $bids = $bids->where('industry_id', 'like', $request->query('i'));
            }

            if ($request->query('re')) {
                
                $bids = $bids->where('is_reserved', $request->query('re'));
            }

            if ($request->query('b')) {
                $bids = $bids->whereHas('vouchesResponse', function ($query) use ($request) {
                    $query->where('vouch_value', 'like', '%' . $request->query('b') . '%');
                });
            }
            $bids = $bids->get();

            return view('opportunity.opportunity-list', compact('bids'));
        }
    }

    public function OpportunityStatus(Request $request)
    {
        if ($request->status == 'On') {
            $status = 'Off';
        } else {
            $status = 'On';
        }
        $bid = Vouche::find($request->id);

        $bid->is_active = $status;
        $bid->save();
        if ($bid) {
            return $this->successResponse("Vouch status Has been Changed.");
        } else {
            return $this->failResponse("Error Occured!Please try again later")->setStatusCode(200);
        }
    }

    public function myResponse(Request $request)
    {

        $bids = Vouche::with(['opportunity'])->where('offer_id', Auth::user()->id)->get();

        return view('opportunity.my-vouches', compact('bids'));
    }

    public function vouchList(Request $request, $id)
    {

        $vouchLists = Vouche::with(['opportunity', 'VouchUser'])->where('opportunity_id', $id);
        $vouchLists = $vouchLists->get();
        $identity = Auth::user()->identity;

        $opportunityData = Opportunity::find($id);

        return view('opportunity.vouch-response-list', compact('vouchLists', 'opportunityData', 'identity'));
    }

    public function vouchListExport(Request $request, $id)
    {
        $records = Vouche::with(['opportunity', 'userDetail'])->where('opportunity_id', $id)->get()->toArray();

        foreach ($records as $key => $record) {
            $data[$key]['Vouch Id'] = "#" . $record['id'];
            $data[$key]['Vouch Value'] = $record['vouch_value'];
            if ($record['vouch_contacted'] == "Mobile") {
                $data[$key]['Email'] = "";
                $data[$key]['Mobile'] = $record['user_detail']['phone_no'];
            } else if ($record['vouch_contacted'] == "Email") {
                $data[$key]['Email'] = $record['user_detail']['email'];
                $data[$key]['Mobile'] = '';
            } else {
                $data[$key]['Email'] = $record['user_detail']['email'];
                $data[$key]['Mobile'] = $record['user_detail']['phone_no'];
            }
        }

        $opportunityData = Opportunity::find($id);

        // return Excel::create($opportunityData->hashtag, function ($excel) use ($data) {
        //     $excel->sheet('mySheet', function ($sheet) use ($data) {
        //         $sheet->fromArray($data);
        //     });
        // })->download('xlsx');


        return Excel::download(
                new class($data) implements FromArray {
                    protected $data;

                    public function __construct(array $data)
                    {
                        $this->data = $data;
                    }

                    public function array(): array
                    {
                        return $this->data;
                    }
                },
                $opportunityData->hashtag . '.xlsx'
            );
    }


    public function updateVouch(Request $request, $id)
    {
        $vouchData = VouchUser::find($id);
        $vData = Vouche::where('id', $vouchData->vouches_id)->first();
        $userDetails = User::where('id', $vData->offer_id)->first();

        if ($vData->is_active == "On") {
            $vouchData->status = 2;
            $vouchData->save();

            $vData->status = 2;
            $vData->save();
            $linkName = '<a href="' . url('my-vouches') . '">Click here</a>';
            $message = "Your vouch is accepted by the opportunity holder. Please " . $linkName . " to watch their contact details";
            $link = url('my-vouches');
            CommonHelper::sendNotification($userDetails, $message, $link);

            return redirect('vouches/' . $vData->opportunity_id);
        } else {
            return redirect()->back()->with('success_messagesss', '');
        }

    }

    public function buildOpportunity()
    {
        return view('opportunity.build-opportunity');
    }

    public function getHashtags(Request $request)
    {
        $hashTags = Opportunity::where('country_code', $request->countryId)->where('industry_id', $request->industryId)
            ->where('opportunity_city', $request->opportunity_city)
            ->get();
        $hashs = '';
        $hashArrays = [];
        if (! empty($hashTags->first())) {
            foreach ($hashTags as $hash) {
                $hashArrays[] = ltrim($hash->hashtag, '#');

            }
            if (! empty($hashArrays)) {
                $hashs = implode(',', $hashArrays);
            }
        }

        return response()->json(['hashTags' => $hashs]);
    }

    public function addVouch(Request $request)
    {
        $id = $request->vouchId;
        $type = $request->type;
      
        $opportunityDetail = Opportunity::where('id', $id)->first();
        $opportunities = Opportunity::where('hashtag', $opportunityDetail->hashtag)
            ->where('industry_id', $opportunityDetail->industry_id)->groupBy('country_code')->where('status', 1)->get();
        $countries = Country::get();
        $view = view('opportunity.add-vouch',
                compact('opportunityDetail', 'countries', 'opportunities','type'))->render();

        return response()->json(['html' => $view, 'opportunityData' => $opportunityDetail]);
    }

    public function getCities(Request $request, $id)
    {
        $opportunity = Opportunity::find($id);
        $opportunities = Opportunity::with(['city_name'])->where('hashtag', $opportunity->hashtag)
            ->where('industry_id', $opportunity->industry_id)->where('country_code', $opportunity->country_code)
            ->where('status', 1)->get();

        return $this->jsonResponse('Success', [
            'opportunities' => $opportunities
        ]);
    }

    public function addNewVouch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vouch_value' => "required",
            'vouch_identity' => "required",
            'vouch_city' => "required",
            'vouch_country' => "required",
            'vouch_contacted' => "required",
        ], [
            'vouch_value.required' => "Vouch value is required",
            'vouch_identity.required' => "Identity is required",
            'vouch_city.required' => "City is required",
            'vouch_country.required' => "Country is required",
            'vouch_contacted.required' => "Contact type is required",
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => json_encode($validator->errors()->first())])
                ->setStatusCode(200);
        }
        $opps = Opportunity::find($request->opportunity_id);
        $opportunity = Opportunity::where('opportunity_city', $request->vouch_city)
            ->where('country_code', $request->vouch_country)->where('hashtag', $opps->hashtag)
            ->where('industry_id', $opps->industry_id)->first();
        $data = [
            'offer_id' => Auth::user()->id,
            'opportunity_id' => $opportunity->id,
            'vouch_value' => $request->vouch_value,
            'vouch_identity' => $request->vouch_identity,
            'vouch_city' => $request->vouch_city,
            'vouch_country' => $request->vouch_country,
            'vouch_contacted' => $request->vouch_contacted,
            'status' => 1
        ];

        //user table update user free vouch count
        $user = User::find(Auth::user()->id);
        $user->free_vouch = $user->free_vouch + 1;
        $user->is_paid = 0;
        $user->save();


        $getVouch = Vouche::where('opportunity_id', $opportunity->id)->where('offer_id', Auth::user()->id)->first();
        if (!empty($getVouch)) {
            return response()->json(['status' => 2, 'message' => "You have already vouched this opportunity"])
                ->setStatusCode(200);
        }

        $vouchData = Vouche::create($data);
        $opportunityUser = OpportunityUser::where('opportunity_id', $opportunity->id)->get();
        if (!empty($opportunityUser)) {
            foreach ($opportunityUser as $oUser) {
                $userDetail = User::find($oUser->user_id);
                VouchUser::create(['offer_id' => $oUser->user_id, 'vouches_id' => $vouchData->id, 'status' => 1]);
            }
        }
        if($request->type == 'paid'){
            return response()->json(['status' => 3, 'message' => "Vouche has been successfully added"])
                ->setStatusCode(200);
        }

        if (!empty($vouchData)) {
            return response()->json(['status' => 1, 'message' => "Vouche has been successfully added"])
                ->setStatusCode(200);
        } else {
            return response()->json(['status' => 0, 'message' => "Vouche has been not added"])->setStatusCode(200);
        }

    }

    public function addNewOpportunity(Request $request)
    {
        //$i = 1;

        $validator = Validator::make($request->all(), [
            'opportunity_country' => "required",
            'opportunity_city' => "required",
            'opportunity_industry' => "required",
            'hashtags' => "required",
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error_contact', 'Please fill all fields data')
                ->withInput();
        }
        $hashTags = preg_replace("/[^a-zA-Z0-9\s]/", "", str_replace(' ', '', $request->hashtags));

        $existHashTags = Opportunity::where('hashtag', '#' . $hashTags)
            ->where('country_code', $request->opportunity_country)->where('industry_id', $request->opportunity_industry)
            ->where('opportunity_city', $request->opportunity_city)
            ->first();


        $getSamehashTag = Opportunity::where('hashtag', '#' . $hashTags)->where('industry_id', $request->opportunity_industry)->first();
        if (empty($existHashTags)) 
        {
            $data = [
                'receiver_id' => Auth::user()->id,
                'hashtag' => "#" . preg_replace("/[^a-zA-Z0-9\s]/", "",str_replace(' ', '', $request->hashtags)),
                'country_code' => $request->opportunity_country,
                'opportunity_city' => $request->opportunity_city,
                'industry_id' => $request->opportunity_industry,
            ];
            $existHashTags = Opportunity::create($data);
            $existHashTags->share_id = $this->uniquiShareId($existHashTags->id);
            $existHashTags->save();
        
            if (empty($getSamehashTag)) {
                UniqueOpportunity::create(['opportunity_id' => $existHashTags->id]);
            }
            $opportunityUser = [
                'opportunity_id' => $existHashTags->id,
                'user_id' => Auth::user()->id
            ];

            $existingOppUserData = OpportunityUser::where($opportunityUser)->first();
            if (empty($existingOppUserData)) {
                $opportunityData = OpportunityUser::create($opportunityUser);
            }


            //for xsquare payment 
            $send_data = array('amount' => $request->amount);
            $result = $this->sendRequest($send_data);

            $percentage = CommonHelper::getConfig('build_hashtag')['ayojn_service'];
            $config_type = CommonHelper::getConfig('build_hashtag')['config_type'];
            $ayojanEarnings = CommonHelper::calculateAyojanEarning($config_type,0,$percentage);

            $data = array(
                'user_id'=> Auth::user()->id, 
                'beneficiary_id' => 7,
                'collection_id'=> $result['response']['details']['collection_id'], 
                'purpose' => 1,
                'amount'=> $request->amount,
                'ayojn_earn' => $ayojanEarnings,
                'transaction_reference' => $result['response']['details']['order_reference'],
                'form_name' => 'build_hashtag',
                'refrence_id' => $existHashTags->id,
                'refrence_details' => json_encode(array('opportunity_id'=>$existHashTags->id, 'hashtag' => $request->hashtags))
            );

            $rstorder = DB::table('direct_payment')->insert($data);

            if (isset($result['error'])) {
                // echo "<div style='color:red'>";
                 echo "Error: " . htmlspecialchars($result['error']);
                // echo "</div>";
             }
          
            header("Window-target: _parent");
            header('Location:'.$result['response']['details']['payment_url']);
            exit;
             

        }

        return redirect()->back()->with('success_messagesss', 'You are successfully added opportunity');


    }

    public function uniquiShareId($id)
    {
        return md5(uniqid(rand() . $id, true));
    }

    public function shareIdStore()
    {
        $opportunity = Bid::get();
        if (! empty($opportunity)) {
            foreach ($opportunity as $o) {
                $o->share_id = $this->uniquiShareId($o->id);
                $o->save();
            }
        }

        $opportunity = Opportunity::get();
        if (! empty($opportunity)) {
            foreach ($opportunity as $o) {
                $o->share_id = $this->uniquiShareId($o->id);
                $o->save();
            }
        }
    }

    public function shareBid(Request $request, $id)
    {
        $bidData = Opportunity::with(['industry', 'country_name', 'opportunityUser'])->where('share_id', $id)
            ->where('status', 1)->first();
        if (! empty($bidData)) {
            session()->flash('url.intended', '/' . request()->path());

            return view('opportunity.share-opportunity', compact('bidData'));
        } else {
            return view('bid.datanotfound');
        }
    }

    public function shareOffer($id)
    {
        $offer = Offer::where('share_id', $id)->where('status', 'On')->where('admin_status', 1)->first();
        session()->flash('url.intended', '/' . request()->path());

        return view('partner.share-offer', compact('offer'));
    }

    public function check_vouch_code(Request $request)
    {
        $code = $request->code;
        $today = date('Y-m-d');
        $rst = DB::table('vouch_codes')->where('vouch_code',$code)->where('status',0)->where('expeiry_date','>=',$today)->value('vouch_amount');
        if($rst>0)
        {
            return $rst;
        }
        else{
            return 0;
        }
        

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


     //add by ram for paynow button 21 sep 2023
     public function paynow_vouch(Request $request)
     {
         $opid = $request->opid;

         $vid = $request->vid;
 
         $wallet = $request->wallet;
 
         $userid = Auth::user()->id;
 
         $amt = $request->amt;

         $voucher_code = $request->voucher_code;

         $flag = 0;

         //$update = Opportunity::where('id',$opid)->update(array('status'=>1));

         //if($update)
         //{
            $flag = 1;

            if($amt > 0)
            {

                $send_data = array('amount' => $amt);
                $result = $this->sendRequest($send_data);


                $percentage = CommonHelper::getConfig('hashtag_vouch')['ayojn_service'];
                $config_type = CommonHelper::getConfig('hashtag_vouch')['config_type'];
                $ayojanEarnings = CommonHelper::calculateAyojanEarning($config_type,0,$percentage);
    
                $data = array(
                    'user_id'=> Auth::user()->id, 
                    'beneficiary_id' => 7,
                    'collection_id'=> $result['response']['details']['collection_id'], 
                    'purpose' => 1,
                    'amount'=> $amt,
                    'ayojn_earn' => $ayojanEarnings,
                    'transaction_reference' => $result['response']['details']['order_reference'],
                    'form_name' => 'hashtag_vouch',
                    'refrence_id' => $opid,
                    'refrence_details' => json_encode(array('vouch_code'=>$voucher_code, 'wallet' => $wallet, 'opportunity_id' => $vid))
                );
    
                $rstorder = DB::table('direct_payment')->insert($data);
    
                if (isset($result['error'])) {
                    // echo "<div style='color:red'>";
                     echo "Error: " . htmlspecialchars($result['error']);
                    // echo "</div>";
                 }
              
                 $link = $result['response']['details']['payment_url'];
                 return array('flag'=>$flag, 'link' => $link);

            }
            else{

                if($wallet > 0)
                {
                   $walletupdate = DB::table('users')->where('id',$userid)->update(array('wallet_balance'=>DB::raw("wallet_balance - $wallet")));
                   $insarray = array('user_id'=>$userid,'points_by'=>3,'trans_id'=>$vid,'point'=>$wallet,'point_type'=>'DR','created_at'=>now());
                   DB::table('wallet_logs')->insert($insarray);
                   $flag = 1;
                }
    
                if($voucher_code != '')
                {
                   DB::table('vouch_codes')->where('vouch_code',$voucher_code)->update(array('used_by'=>$userid,'status'=>1));
                   $flag = 1;
                }


                return array('flag'=>$flag, 'link' => "");
            }

           
        //}   
       
     }


    public function addNewOpportunitypaynow(Request $request)
    {
        
        $hashTags = preg_replace("/[^a-zA-Z0-9\s]/", "", str_replace(' ', '', $request->hashtags));

        $existHashTags = Opportunity::where('hashtag', '#' . $hashTags)
            ->where('country_code', $request->opportunity_country)->where('industry_id', $request->opportunity_industry)
            ->where('opportunity_city', $request->opportunity_city)
            ->first();


        $getSamehashTag = Opportunity::where('hashtag', '#' . $hashTags)->where('industry_id', $request->opportunity_industry)->first();
        if (empty($existHashTags)) 
        {
            $data = [
                'receiver_id' => Auth::user()->id,
                'hashtag' => "#" . preg_replace("/[^a-zA-Z0-9\s]/", "",str_replace(' ', '', $request->hashtags)),
                'country_code' => $request->opportunity_country,
                'opportunity_city' => $request->opportunity_city,
                'industry_id' => $request->opportunity_industry,
               
            ];
            $existHashTags = Opportunity::create($data);
            $existHashTags->share_id = $this->uniquiShareId($existHashTags->id);
            $existHashTags->save();
        
            if (empty($getSamehashTag)) {
                UniqueOpportunity::create(['opportunity_id' => $existHashTags->id]);
            }
            $opportunityUser = [
                'opportunity_id' => $existHashTags->id,
                'user_id' => Auth::user()->id
            ];

            $existingOppUserData = OpportunityUser::where($opportunityUser)->first();
            if (empty($existingOppUserData)) {
                $opportunityData = OpportunityUser::create($opportunityUser);
            }


            $wallet = $request->wallet;

            $userid = Auth::user()->id;

            $amt = $request->amount;

            $voucher_code = $request->voucher_code;
        
            $flag = 0;
           
            if($amt > 0){

            //for xsquare payment 
            $send_data = array('amount' => $request->amount);
            $result = $this->sendRequest($send_data);


            $percentage = CommonHelper::getConfig('build_hashtag')['ayojn_service'];
            $config_type = CommonHelper::getConfig('build_hashtag')['config_type'];
            $ayojanEarnings = CommonHelper::calculateAyojanEarning($config_type,0,$percentage);

            $data = array(
                'user_id'=> Auth::user()->id, 
                'beneficiary_id' => 7,
                'collection_id'=> $result['response']['details']['collection_id'], 
                'purpose' => 1,
                'amount'=> $request->amount,
                'ayojn_earn' => $ayojanEarnings,
                'transaction_reference' => $result['response']['details']['order_reference'],
                'form_name' => 'build_hashtag',
                'refrence_id' => $existHashTags->id,
                'refrence_details' => json_encode(
                    array('opportunity_id'=>$existHashTags->id, 'hashtag' => $request->hashtags, 'reserved' => $request->reserved,  'reserved_year' => $request->reserved_year)
                    )
            );

            $rstorder = DB::table('direct_payment')->insert($data);
            $flag =1;

            if($wallet > 0)
            {
                $walletupdate = DB::table('users')->where('id',$userid)->update(array('wallet_balance'=>DB::raw("wallet_balance - $wallet")));
                $insarray = array('user_id'=>$userid,'points_by'=>1,'trans_id'=>$existHashTags->id,'point'=>$wallet,'point_type'=>'DR','created_at'=>now());
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


                $Opportunity = Opportunity::find($existHashTags->id);
                $Opportunity->status = 1;
                $Opportunity->save();

                if($Opportunity){

                    $flag = 1;

                    if($wallet > 0)
                    {
                        $walletupdate = DB::table('users')->where('id',$userid)->update(array('wallet_balance'=>DB::raw("wallet_balance - $wallet")));
                        $insarray = array('user_id'=>$userid,'points_by'=>1,'trans_id'=>$existHashTags->id,'point'=>$wallet,'point_type'=>'DR','created_at'=>now());
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

    public function hashtagscheck(Request $request)
    {
        $opportunity = Opportunity::where('hashtag', "#".$request->hashtags)->where('is_reserved',1)->where('next_payment_date','>', date('Y-m-d')  )->first();
        if (empty($opportunity)) {
            return response()->json(['status' => 1, 'message' => "Hashtag Available"])->setStatusCode(200);
        } else {
            return response()->json(['status' => 0, 'message' => "Hashtag reserved!"])->setStatusCode(200);
        }
    }

    public function opp_transaction(Request $request)
    {
        $opportunity = DB::table('direct_payment')->where('refrence_id', $request->id)->whereIn('form_name',['build_hashtag','build_hashtag_renew'])->where('user_id', Auth::user()->id)->get();
        $table="";
        foreach ($opportunity as $key => $value) {
           
            $details = json_decode($value->refrence_details);
            
            $renewal_date = Opportunity::where('id', $request->id)->value('next_payment_date');

            $table .="<tr>";
            $table .="<td>".$value->id."</td>";
            $table .="<td>".$value->payment_date."</td>";
            $table .="<td>".$value->transaction_amount."</td>";
            $table .="<td>".$value->payment_mode."</td>";
            $table .="<td>".$value->transaction_reference."</td>";
            $table .="<td>" . (isset($details->reserved_year) ? $details->reserved_year : '') . "</td>";
            $table .="<td>".$renewal_date."</td>";
            $table .="</tr>";
        }

        return $table;
    }



    public function renewOpportunitypaynow(Request $request)
    {
         
            $wallet = $request->wallet;
            $userid = Auth::user()->id;
            $amt = $request->amount;
            $voucher_code = $request->voucher_code;
            $flag = 0;
            if($amt > 0){

            $Opportunity = Opportunity::where('id',$request->id)->first();   

          
            //for xsquare payment 
            $send_data = array('amount' => $request->amount);
            $result = $this->sendRequest($send_data);

            $percentage = CommonHelper::getConfig('build_hashtag_renew')['ayojn_service'];
            $config_type = CommonHelper::getConfig('build_hashtag_renew')['config_type'];
            $ayojanEarnings = CommonHelper::calculateAyojanEarning($config_type,0,$percentage);

            $data = array(
                'user_id'=> Auth::user()->id, 
                'beneficiary_id' => 7,
                'collection_id'=> $result['response']['details']['collection_id'], 
                'purpose' => 1,
                'amount'=> $request->amount,
                'ayojn_earn' => $ayojanEarnings,
                'transaction_reference' => $result['response']['details']['order_reference'],
                'form_name' => 'build_hashtag_renew',
                'refrence_id' => $request->id,
                'refrence_details' => json_encode(
                    array('opportunity_id'=>$request->id, 'hashtag' => $Opportunity->hashtag, 'reserved' => 1,  'reserved_year' => $request->reserved_year)
                    )
            );

            $rstorder = DB::table('direct_payment')->insert($data);
            $flag =1;

            if($wallet > 0)
            {
                $walletupdate = DB::table('users')->where('id',$userid)->update(array('wallet_balance'=>DB::raw("wallet_balance - $wallet")));
                $insarray = array('user_id'=>$userid,'points_by'=>5,'trans_id'=>$existHashTags->id,'point'=>$wallet,'point_type'=>'DR','created_at'=>now());
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


                $reserved_year = $request->reserved_year;
                $currentDateTime = Carbon::now();
                $newDateTime = Carbon::now()->addYears($reserved_year); 
                $next_renewal = $newDateTime->format('Y-m-d H:i:s');

                $Opportunity = Opportunity::find($request->id);
                $Opportunity->next_payment_date = $next_renewal;
                $Opportunity->reserved_year = $reserved_year;
                $Opportunity->save();

                if($Opportunity){
                    $flag = 1;

                    if($wallet > 0)
                    {
                        $walletupdate = DB::table('users')->where('id',$userid)->update(array('wallet_balance'=>DB::raw("wallet_balance - $wallet")));
                        $insarray = array('user_id'=>$userid,'points_by'=>5,'trans_id'=>$existHashTags->id,'point'=>$wallet,'point_type'=>'DR','created_at'=>now());
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
