<?php

namespace App\Http\Controllers\UserAuth;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Hesto\MultiAuth\Traits\LogsoutGuard;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use DB;
use Illuminate\Support\Facades\Schema;
use Exception;
use Session;
use App\Bid;
use App\User;
use App\ResponseBid;
use App\Helpers\CommonHelper;
use App\Offer;
use App\Models\Collaborate;
use PDF;
use Illuminate\Support\Facades\Mail;

class XsquareController extends Controller
{

    use AuthenticatesUsers, LogsoutGuard {
        LogsoutGuard::logout insteadof AuthenticatesUsers;
    }

 
    public function __construct()
    {
        $this->middleware('user.guest', ['except' => 'logout']);
        $this->apiConfig = [
            'API_KEY' => config('sitevars.xsquare_local.API_KEY'),
            'ENDPOINT' => config('sitevars.xsquare_local.ENDPOINT'),
            'SECRET_KEY' => config('sitevars.xsquare_local.SECRET_KEY')
        ];
    }

    public function offlinepayment(Request $request)
    {

        
            //$email = Auth::user()->email;
            $email = "rambthm376@gmail.com";
            $amount = $request->amount;
            $purpose = $request->purpose;
            $beneficiary_id = $request->beneficiary_id;

            $curl = curl_init();
            $token = session()->get('payment_token');
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://qa-core-api.xsquare.biz/transaction/collection-add',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "first_name": "Arjun",
                "last_name": "Nayak",
                "email": "'.$email.'",
                "purpose": "'.$purpose.'",
                "amount": "'.$amount.'",
                "currencies": {
                    "currency_id": 1,
                    "name": "United Arab Emirates - AED",
                    "code": "AED"
                },
                "charges": {
                    "platform_fees": 2.5,
                    "platform_fees_intl": 3.25,
                    "fee_type": "Percentage",
                    "fee_type_intl": "Percentage",
                    "customer_percentage": 0,
                    "beneficiary_percentage": 0,
                    "payer": "Customer"
                },
                "vat": 5,
                "vat_intl": 5,
                "ni_ref_id": "e7ca0e81-b0dd-44ab-b463-0faf4f693afa",
                "charge_region": "Domestic"
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer '.$token
            ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
           
            $response = json_decode($response,true);
            //echo "<pre/>";
            //print_r($response);
            //if(!$response['detail']??true){
                if($response['status_code'] == '201')
                {

                    $data = array(
                        'user_id'=> Auth::user()->id, 
                        'beneficiary_id' => $beneficiary_id,
                        'collection_id'=> $response['details']['collection_id'], 
                        'purpose' => $purpose,
                        'amount'=> $amount
                    );
                    $rstorder = DB::table('direct_payment')->insert($data);
                }
            //}
            

            $dropdown = $this->purposedropdown();
            $beneficiary = DB::table('beneficiary')->where('user_id', Auth::user()->id)->get();
            return view('user.offlinepay')->with('response',$response)->with('purpose',$dropdown)->with('beneficiary',$beneficiary);
    }


    public function searchUsername(Request $request)
    {
        $query = $request->get('q');
        $users = User::where('username', 'like', "%$query%")->get();

        if ($users->isEmpty()) {
            return '<div class="text-danger">No users found.</div>';
        }

        $html = '<table class="table table-bordered"><tr><th>ID</th><th>Username</th><th>Name</th><th>Action</th></tr>';
        $x=0;
        foreach ($users as $user) {
            $x+=1;
         
            $html .= '<tr><td>'.$x.'</td><td>'.$user->username.'</td><td>'.$user->entity.'</td><td><a class="btn btn-success btn-sm" href="'.url('beneficiary_search', $user->id).'">Select</a></td></tr>';
        }
        $html .= '</table>';
        return $html;
    }



    public function purposedropdown()
    {
        // $xsquare = $this->xsquarelogin();
        // session()->put('payment_token', $xsquare->details->access_token);
        // session()->put('payment_refresh_token', $xsquare->details->refresh_token);
        
        // $dropdown = [];
        // $curl = curl_init();
        // $token = session()->get('payment_token');
        // curl_setopt_array($curl, array(
        //   CURLOPT_URL => 'https://qa-core-api.xsquare.biz/transaction/common/pop-dropdown',
        //   CURLOPT_RETURNTRANSFER => true,
        //   CURLOPT_ENCODING => '',
        //   CURLOPT_MAXREDIRS => 10,
        //   CURLOPT_TIMEOUT => 0,
        //   CURLOPT_FOLLOWLOCATION => true,
        //   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //   CURLOPT_CUSTOMREQUEST => 'GET',
        //   CURLOPT_HTTPHEADER => array(
        //     'Authorization: Bearer '.$token
        //   ),
        // ));
        // $purpose = curl_exec($curl);
        // curl_close($curl);
        // $dropdown = json_decode($purpose,true);
        // //print_r($dropdown); 
         return $dropdown['details']??array();
    }

    public function beneficiary_list(Request $request)
    {
        $table = DB::table('beneficiary')->where('user_id',Auth::user()->id)->get();
        return view('user.beneficiary_list')->with('data',$table);
    }

    public function beneficiary_add(Request $request)
    {
        return view('user.beneficiary_add')->with('response','');
    }

    public function beneficiary_add_direct(Request $request)
    {
        $insert = array(
            'company_name' => $request->post('company_name'),
            'contact_name' => $request->post('contact_name'),
            'email' => $request->post('email'),
            'mobile_no' => $request->post('mobile_no'),
            'tax_id' => $request->post('tax_id'),
            'bank_country' => $request->post('bank_country'),
            'currency' => $request->post('currency'),
            'bank_account' => $request->post('bank_account'),
            'bank_code' => $request->post('bank_code'),
            'code_no' => $request->post('code_no'),
            'beneficiary_country' => $request->post('beneficiary_country'),
            'beneficiary_city' => $request->post('beneficiary_city'),
            'beneficiary_address' => $request->post('beneficiary_address'),
            'user_id' => Auth::user()->id, 
        );

        $result = DB::table('beneficiary')->insertGetId($insert);
        if($result)
        {

            session()->flash('warning', 'Beneficiary added successfully.');
            session()->flash('selected', $result);
            return redirect()->route('offline-pay');
        }
         



    }

    public function beneficiary_save(Request $request)
    {
        $insert = array(
            'company_name' => $request->post('company_name'),
            'contact_name' => $request->post('contact_name'),
            'email' => $request->post('email'),
            'mobile_no' => $request->post('mobile_no'),
            'tax_id' => $request->post('tax_id'),
            'bank_country' => $request->post('bank_country'),
            'currency' => $request->post('currency'),
            'bank_account' => $request->post('bank_account'),
            'bank_code' => $request->post('bank_code'),
            'code_no' => $request->post('code_no'),
            'beneficiary_country' => $request->post('beneficiary_country'),
            'beneficiary_city' => $request->post('beneficiary_city'),
            'beneficiary_address' => $request->post('beneficiary_address'),
            'user_id' => Auth::user()->id, 
        );

             $validator = Validator::make($request->all(), [
            'email' => 'required|exists:beneficiary',
        ]);
    
        if (!$validator->fails()) {

            session()->flash('warning', 'Email Already Exists');
            return redirect()->back();
        }
        else{
            $result = DB::table('beneficiary')->insertGetId($insert);
            if($result)
            {
                session()->flash('success', 'Beneficiary has been added successfully!');
                return redirect()->back();
            }
            else{
                session()->flash('warning', 'Something went wrong');
                return redirect()->back();
            }
    
        }

    }


    public function beneficiary_update(Request $request)
    {
        $insert = array(
            'company_name' => $request->post('company_name'),
            'contact_name' => $request->post('contact_name'),
            'email' => $request->post('email'),
            'mobile_no' => $request->post('mobile_no'),
            'tax_id' => $request->post('tax_id'),
            'bank_country' => $request->post('bank_country'),
            'currency' => $request->post('currency'),
            'bank_account' => $request->post('bank_account'),
            'bank_code' => $request->post('bank_code'),
            'code_no' => $request->post('code_no'),
            'beneficiary_country' => $request->post('beneficiary_country'),
            'beneficiary_city' => $request->post('beneficiary_city'),
            'beneficiary_address' => $request->post('beneficiary_address'),
        );

        $result = DB::table('beneficiary')->where('id',$request->post('id'))->update($insert);
        if($result)
        {
            session()->flash('success', 'Beneficiary has been update successfully!');
            return redirect('beneficiary-list');
        }
        else{
            session()->flash('warning', 'Nothing is updated');
            return redirect('beneficiary-list');
        }
    
       

    }

    public function beneficiary_edit(Request $request)
    {
        $id = request('id');
        $dataa = DB::table('beneficiary')->where('id', $id)->first();
        return view('user.beneficiary_edit')->with('data', $dataa);
    }


    public function beneficiary_search(Request $request)
    {


        $id = request('id'); 
        //$check = DB::table('beneficiary')->where('user_id', $id)->first();
        $existEmail = User::where('id', $id)->first();
        $check = DB::table('beneficiary')->where('email', $existEmail->email)->where('user_id',Auth::user()->id)->first();

        if($check){
            session()->flash('success', 'Beneficiary selected successfully.');
            session()->flash('selected', $check->id);
            return redirect()->back();
        }
        $dataa = user::where('id', $id)->first();
        $bankdetails = DB::table('userbankdetails')->where('user_id', $id)->where('default_bank',0)->first();

        $insert = array(
            'company_name' => $dataa->company_name,
            'contact_name' => $dataa->entity,
            'email' => $dataa->email,
            'mobile_no' => $dataa->phone_no,
            'tax_id' => $bankdetails->my_taxid??'',
            'bank_country' => $bankdetails->my_bank_country??'',
            'currency' => $bankdetails->my_currency??'',
            'bank_account' => $bankdetails->my_bank_account??'',
            'bank_code' => $bankdetails->my_bank_code??'',
            'code_no' => $bankdetails->my_code_no??'',
            'beneficiary_country' => $dataa->country,
            'beneficiary_city' => $dataa->city,
            'beneficiary_address' => $dataa->address,
            'user_id' => Auth::user()->id, 
        );

        $result = DB::table('beneficiary')->insertGetId($insert);
        if($result)
        {

            session()->flash('warning', 'Beneficiary added successfully.');
            session()->flash('selected', $result);
            return redirect()->route('offline-pay');
        }
        

        //print_r($bankdetails ); die;
        //return view('user.beneficiary_search')->with('data', $dataa)->with('bankdetails',$bankdetails);
    }

    public function beneficiary_delete(Request $request)
    {
        $id = $request->post('id');
        $rst = DB::table('beneficiary')->where('id', $id)->delete();
        if($rst){
            return response()->json([
                'status' => 1,
               'message' => 'delete succesfully'
            ]);
        }
        else{
            return response()->json([
                'status' => 0,
               'message' => 'Something went wrong'
            ]);
        }
    }

    public function offlinepay()
    {
        $dropdown = $this->purposedropdown();
        $beneficiary = DB::table('beneficiary')->where('user_id', Auth::user()->id)->get();

        
        //if user coming from profile link then create automatic beneficiary
         $isUserSesseion = session()->get('user_profile');
        if($isUserSesseion !==''){
            
 
                $id = $isUserSesseion;   
                $existEmail = User::where('id', $id)->first();

                $check = DB::table('beneficiary')->where('email', $existEmail->email)->where('user_id',Auth::user()->id)->first();
                if($check){
                    //session()->flash('success', 'Beneficiary selected successfully.');
                    session()->flash('selected', $check->id);
                    //return redirect()->route('offline-pay');
                    return view('user.offlinepay')->with('response','')->with('purpose',$dropdown)->with('beneficiary', $beneficiary);
                    
                }
                $dataa = user::where('id', $id)->first();
                $bankdetails = DB::table('userbankdetails')->where('user_id', $id)->where('default_bank',0)->first();

                $insert = array(
                    'company_name' => $dataa->company_name,
                    'contact_name' => $dataa->entity,
                    'email' => $dataa->email,
                    'mobile_no' => $dataa->phone_no,
                    'tax_id' => $bankdetails->my_taxid??'',
                    'bank_country' => $bankdetails->my_bank_country??'',
                    'currency' => $bankdetails->my_currency??'',
                    'bank_account' => $bankdetails->my_bank_account??'',
                    'bank_code' => $bankdetails->my_bank_code??'',
                    'code_no' => $bankdetails->my_code_no??'',
                    'beneficiary_country' => $dataa->country,
                    'beneficiary_city' => $dataa->city,
                    'beneficiary_address' => $dataa->address,
                    'user_id' => Auth::user()->id, 
                );

                $result = DB::table('beneficiary')->insertGetId($insert);
                if($result)
                {
                    $beneficiary = DB::table('beneficiary')->where('user_id', Auth::user()->id)->get();
                    session()->flash('warning', 'Beneficiary added successfully.');
                    session()->flash('selected', $result);
                    session()->put('user_profile','');
                    //return redirect()->route('offline-pay');
                    return view('user.offlinepay')->with('response','')->with('purpose',$dropdown)->with('beneficiary', $beneficiary);
                }
        
        }
        


        return view('user.offlinepay')->with('response','')->with('purpose',$dropdown)->with('beneficiary', $beneficiary);
    }

    public function xsquarelogin(){

            
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://qa-core-api.xsquare.biz/customer/login',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "email": "devops@ayojn.com",
                "password": "Ayojn@2024"
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            return json_decode($response);
    }

    public function directpay_delete(Request $request)
    {
        DB::table("direct_payment")->where('id', $request->id)->delete();
        return redirect()->back();
    }

    public function reciept_download(Request $request)
    {

        $id = $request->id;
        $fetch = DB::table('direct_payment')->where('id', $id)->first();
        $user = User::find(Auth::user()->id);
        $details = array();
        $details['invoice_no'] = $fetch->transaction_reference;
        $details['issue_date']  = $fetch->payment_date;
        $details['due_date'] = $fetch->payment_date;
        $details['entity'] = $user->entity;
        $details['phone_no'] = $user->phone_no;
        $details['email'] = $user->email;
        $details['rec_no'] = $fetch->id;
        $details['payment_mode'] = $fetch->payment_mode;
        $details['amount'] = $fetch->transaction_amount;
        $details['invoice_item_title'] = $fetch->form_name;

        $pdf = PDF::loadView('user/receipt', $details);
        return $pdf->stream();
        //return view('user/receipt');
    }


    public function directpay(Request $request)
    {
        // Schema::table('direct_payment', function($table)
        // {
        //     $table->boolean('receipt')->default(true);
        // });
        
        $logs = DB::table("direct_payment")->where('user_id', Auth::user()->id)->orderby('id','desc')->get();
        $fieldset ="<table class='display table table-bordered' id='table_id'>
        <thead>
        <th>Transaction Details</th>
        <th>Beneficiary</th>
        <th>Purpose</th>
        <th>Amount(USD)</th>
        <th>Date</th>
        <th>Mode</th>
        <th>Status</th>
        <th>Receipt</th>
        </thead>";
        $y=1;
        foreach ($logs as $key => $log) 
        {
           $details = DB::table('buttons_setting')->where('id',39)->first();
           if($details->button_icon !=null){
           $icon = "<i class='".$details->button_icon."'></i>";
           }else{$icon = "";}
           $tb="";
           $beneficiary_data = DB::table('beneficiary')->where('id', $log->beneficiary_id)->first();
           if($log->purpose==1){$purpose = "Purchase of Goods";}
           if($log->purpose==2){$purpose = "Invoice payment";}
           if($log->purpose==3){$purpose = "Advance Payment";}
           if($log->purpose==4){$purpose = "Commission";}
           if($log->purpose==5){$purpose = "Others";}

           if($log->transaction_amount){$amount = $log->transaction_amount;}
           else{$amount=$log->amount;}

        if($log->status_desc !=NULL){
             if($log->receipt ==1){$rec = "<a target='_blank' class='".$details->class_name."' href='reciept_download/{$log->id}' data-tooltip='true' data-placement='top'  title='".$details->button_hover."'>".$icon.' '.$details->button_name."</a>";}
           else{
            $filePath = asset('receipt/' . $log->receipt); // Generate a URL for the receipt file
            $rec = "<a target='_blank' class='".$details->class_name."' href='$filePath' data-tooltip='true' data-placement='top'  title='".$details->button_hover."'>".$icon.' '.$details->button_name."</a>";}

        }else{$rec="";}

          
           if($log->status_desc){$status = $log->status_desc;}
           else{$status="Pending";}

           $url = route('xsquare-status-check');
           $delete_url = route('xsquare-payment-delete');
           $fieldset .= "<tr>
                
                <td><b>Collection Id:-</b> $log->collection_id <br/> <b>Reference:-</b> $log->transaction_reference</td>
                <td>$beneficiary_data->contact_name</td>
                <td>$purpose</td>
                <td>$amount</td>
                <td>".date('m/d/Y',strtotime($log->payment_date??$log->created_at))."</td>
                <td>$log->payment_mode</td>
                <td>$status</td>
                <td>$rec</td>
                <!--td>
                    form action='$url' method='POST'>
                         <input type='hidden' value='$log->collection_id' name='collection_id' />   
                         <button type='submit' class='btn btn-primary btn-sm'><i class='fa fa-refresh'></i> Check Status</button>
                    </form>

                     <form action='$delete_url' method='POST'>
                         <input type='hidden' value='$log->id' name='id' />  
                        <button class='btn btn-danger btn-sm'>Delete</button>
                    </form>
                </td-->
                </tr>";
            $y++;    

        }
        $fieldset .= "</table>";
        return view('directpay-log', compact('fieldset'));
    }

    public function xsquare_status($id)
    {
        
        $curl = curl_init();
        $token = session()->get('payment_token');
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://qa-core-api.xsquare.biz/transaction/collection-details?collection_id='.$id,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$token 
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
         return json_decode($response);

    }

    public function directpay_status(Request $request)
    {
        $xsquare = $this->xsquarelogin();
        $request->session()->put('payment_token', $xsquare->details->access_token);
        $request->session()->put('payment_refresh_token', $xsquare->details->refresh_token);
        $collection_id = $request->collection_id;
        $check = $this->xsquare_status($collection_id);
        if($check->status_code == '200')
        {
            DB::table('direct_payment')->where('collection_id', $collection_id)->update(array('status_desc'=>json_encode($check->details->pay_status)));
        }
        //print_r($check->details->pay_status); die;

        return redirect()->back();
    }




    // private $formData = [
    //     'first_name' => 'John',
    //     'last_name' => 'Doe',
    //     'email' => 'rambthm376@gmail.com',
    //     'purpose' => '1',
    //     'amount' => '100.50',
    //     'currencies' => [
    //         'currency_id' => 1,
    //         'name' => 'United Arab Emirates - AED',
    //         'code' => 'AED'
    //     ]
    // ];


    //for xsquare payment link generate
    public function generateSignature($payload) {

        $timestamp = (string)time(); // Use current timestamp as string
        
        ksort($payload);
        
        $signaturePayload = $payload;

        unset($signaturePayload['currencies']);

        $signaturePayload['amount'] = number_format((float)$payload['amount'], 1, '.', '');

        $message =  config('sitevars.xsquare_local.API_KEY') . $timestamp . json_encode($signaturePayload, JSON_UNESCAPED_SLASHES);

        $signature = hash_hmac('sha256', $message,  config('sitevars.xsquare_local.SECRET_KEY'));
        
        return [
            'signature' => $signature,
            'timestamp' => $timestamp
        ];
    }

    public function sendRequest($data) {
        try {
            $beneficiary_id = $data['beneficiary_id'];
            $beneficiary_data = DB::table('beneficiary')->where('id', $beneficiary_id)->first();

            $data_array = array(
                'first_name'=> $beneficiary_data->company_name,
                'last_name'=> $beneficiary_data->contact_name,
                'email'=> $beneficiary_data->email,
                'purpose'=> $data['purpose'],
                'amount'=> $data['amount'],
                'currencies'=> array('currency_id'=>1,'name'=>'United Arab Emirates - AED','code' => 'AED')
             );
            $signatureData = $this->generateSignature($data_array);

            //$signatureData = $this->generateSignature($this->formData);

            $ch = curl_init();
            
            $customerId = config('sitevars.xsquare_local.x-customer-id');
            $customerId = is_numeric($customerId) ? (int)$customerId : 0;
            $apikey = config('sitevars.xsquare_local.API_KEY');

            // echo 'cid:- ' . $customerId.'<br/>';
            // echo 'endpoint:- https://core-api.xsquare.biz/transaction/collection-add/v3'."<br/>";
            // echo 'apikey:- ' . $apikey."<br/>";
            // echo 'signature:- ' . $signatureData['signature']."<br/>";
            // echo 'timestamp:- ' . $signatureData['timestamp']."<br/><br/>";




            curl_setopt($ch, CURLOPT_URL, config('sitevars.xsquare_local.ENDPOINT'));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data_array));
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
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($response === false) {
                $error = curl_error($ch);
                curl_close($ch);

                return ['error' => $error ?: 'Unable to connect to the payment gateway.'];
            }

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


    public function run(Request $request)
    {
        $receipt = $request->file('receipt');
        if ($receipt) {
            $validator = Validator::make($request->all(), [
                'receipt' => 'required|mimes:jpg,png,pdf',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }
        }

        $result = $this->sendRequest($request->post());
        if (is_array($result) && isset($result['error'])) {
            \Illuminate\Support\Facades\Log::error(
                'Payment gateway request failed while starting a direct payment.',
                ['user_id' => Auth::id(), 'error' => $result['error']]
            );

            return redirect()->back()->withErrors('Payment service is unavailable. Please try again later.');
        }

        $gatewayResponse = is_array($result) && isset($result['response']) ? $result['response'] : null;
        $paymentDetails = is_array($gatewayResponse) && isset($gatewayResponse['details'])
            ? $gatewayResponse['details']
            : null;
        if (
            ! is_array($result) ||
            ! isset($result['status']) ||
            $result['status'] < 200 ||
            $result['status'] >= 300 ||
            ! is_array($paymentDetails) ||
            ! isset(
                $paymentDetails['collection_id'],
                $paymentDetails['order_reference'],
                $paymentDetails['payment_url']
            )
        ) {
            \Illuminate\Support\Facades\Log::error(
                'Payment gateway returned an invalid response while starting a direct payment.',
                [
                    'user_id' => Auth::id(),
                    'status' => is_array($result) && isset($result['status']) ? $result['status'] : null,
                ]
            );

            return redirect()->back()->withErrors('Payment service returned an invalid response. Please try again later.');
        }

        if ($receipt) {
            $profileImage = time() . '.' . $receipt->getClientOriginalExtension();
            $destinationPath = public_path('receipt');

            if (! file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $receipt->move($destinationPath, $profileImage);
        } else {
            $profileImage = 1;
        }

        DB::table('direct_payment')->insert([
            'user_id' => Auth::user()->id,
            'beneficiary_id' => $request->beneficiary_id,
            'collection_id' => $paymentDetails['collection_id'],
            'purpose' => $request->purpose,
            'amount' => $request->amount,
            'ayojn_earn' => $request->ayojn_earn,
            'receipt' => $profileImage,
            'transaction_reference' => $paymentDetails['order_reference'],
            'form_name' => 'direct_payment',
        ]);

        return redirect()->away($paymentDetails['payment_url']);
    }


   
    public function success(Request $request)
    {
         
        $data = array(
            'transaction_amount'=> $request->get('amount'), 
            'payment_date' => $request->get('payment_date'),
            'payment_mode'=> $request->get('payment_mode'), 
            'status_desc' => $request->get('transaction_status')
        );

      
        $fetch = DB::table('direct_payment')->where('transaction_reference','=',$request->get('transaction_reference'))->get();
        
        
        /*-----offer user payment-----*/
        //unpaid_opportunity by offer user
        if($fetch[0]->form_name == "unpaid_opportunity")
        {
            $details = json_decode($fetch[0]->refrence_details);
            $bid_id = $details->bid_id;
            $update = DB::table('bids')->where('id', $bid_id)->update(array('is_paid'=>1));
  
            if($update){
                if($details->wallet > 0)
                {
                    $wamount = $details->wallet;
                    $walletupdate = DB::table('users')->where('id',$userid)->update(array('wallet_balance'=>DB::raw("wallet_balance - $wamount")));
                    $insarray = array('user_id'=>$userid,'points_by'=>8,'trans_id'=>$bidId,'point'=>$wamount,'point_type'=>'DR','created_at'=>now());
                    DB::table('wallet_logs')->insert($insarray);
                    $flag =1;
                }

                $userid = Auth::user()->id;
                CommonHelper::userGetAyojanReward("unpaid_opportunity", $userid, $bid_id, 1); 
            }    
            $redirection = "bid";    
            $invoice_item_title = "Pay Bid";
        }

        //pay_opportunity by offer user
        if($fetch[0]->form_name == "pay_opportunity")
        {
            $details = json_decode($fetch[0]->refrence_details);
           
            if($details->$wallet > 0)
            {
                $wamount = $details->wallet;
                $walletupdate = DB::table('users')->where('id',$userid)->update(array('wallet_balance'=>DB::raw("wallet_balance - $wamount")));
                $insarray = array('user_id'=>$userid,'points_by'=>1,'trans_id'=>$bids->bid_id,'point'=>$wamount,'point_type'=>'DR','created_at'=>now());
                DB::table('wallet_logs')->insert($insarray);
                $flag =1;
            }

            $userid = Auth::user()->id;
            CommonHelper::userGetAyojanReward("pay_opportunity", $userid, $bids->bid_id, 2);
            
            $redirection = "bid";
            $invoice_item_title = "Pay Opportunity";
        }

        //hashtag_vouch by offer user
        if($fetch[0]->form_name == "hashtag_vouch")
        {
            $details = json_decode($fetch[0]->refrence_details);
            
            if($details->wallet > 0)
            {
                $wamount = $details->wallet;
                $walletupdate = DB::table('users')
                ->where('id',Auth::user()->id)
                ->update(array('wallet_balance'=>DB::raw("wallet_balance - $wamount")));
                $insarray = array('user_id' => Auth::user()->id,'points_by'=>3,'trans_id'=>$details->opportunity_id,'point'=>$wamount,'point_type'=>'DR','created_at'=>now());
                DB::table('wallet_logs')->insert($insarray);
                
            }

            if($details->vouch_code != 'null')
            {
                DB::table('vouch_codes')
                ->where('vouch_code', $details->vouch_code)
                ->update(array('used_by'=>Auth::user()->id,'status'=>1));
            }

            $update = DB::table('opportunities')->where('id',$details->opportunity_id)->update(array('is_paid' => 3));

            if($update){
                $userid = Auth::user()->id;
                CommonHelper::userGetAyojanReward("hashtag_vouch", $userid, $details->opportunity_id,3); 
            }    

            $redirection = "opportunity";
            $invoice_item_title = "Vouch Hashtag";
            
        } 

        //marketplace_payment by offer and rec user
        if($fetch[0]->form_name == "marketplace_payment")
        {
            $details = json_decode($fetch[0]->refrence_details);

            if($details->wallet > 0)
            {
                $wamount = $details->wallet;
                $walletupdate = DB::table('users')
                ->where('id',Auth::user()->id)
                ->update(array('wallet_marketplace'=>DB::raw("wallet_marketplace - $wamount")));
                $insarray = array('user_id' => Auth::user()->id,'points_by'=>4,'trans_id'=>$details->marketplace_id,'point'=>$wamount,'point_type'=>'DR','wallet_type'=>1,'created_at'=>now());
                DB::table('wallet_logs')->insert($insarray);
                
            }

            if($details->vouch_code != 'null')
            {
                DB::table('vouch_codes')
                ->where('vouch_code', $details->vouch_code)
                ->update(array('used_by'=>Auth::user()->id,'status'=>1));
            }

            if($update){
                $userid = Auth::user()->id;
                CommonHelper::userGetAyojanReward("marketplace_payment", $userid, $details->marketplace_id,4); 
            }    

            $remove = DB::table('cart')->where('user_id',Auth::user()->id)->delete();
            $redirection = "marketplace-history";

            $invoice_item_title = "Payment for Marketplace";
        }



        //create hashtag by rec user
        if($fetch[0]->form_name == "build_hashtag")
        {
           
            $details = json_decode($fetch[0]->refrence_details);
            $opportunity_id = $details->opportunity_id;
            if($details->reserved_year > 0)
            {
                $reserved_year = $details->reserved_year;
                $currentDateTime = Carbon::now();
                $newDateTime = Carbon::now()->addYears($reserved_year); 
                $next_renewal = $newDateTime->format('Y-m-d H:i:s');
            }else{
                $next_renewal = null;
            }    

            $update = DB::table('opportunities')
                ->where('id', $opportunity_id)
                ->update(array('status'=>1,'is_reserved'=>$details->reserved,'reserved_year' => $details->reserved_year,'next_payment_date'=> $next_renewal));
                
            if($update){
                $userid = Auth::user()->id;
                CommonHelper::userGetAyojanReward("build_hashtag", $userid, $opportunity_id, 5); 
            }
         
            $redirection = "opportunity";    
            $invoice_item_title = "Build A New Hashtag";  
        }

        //renew hashtag by rec user
        if($fetch[0]->form_name == "build_hashtag_renew")
        {
            $details = json_decode($fetch[0]->refrence_details);
            $opportunity_id = $details->opportunity_id;
            if($details->reserved_year > 0)
            {
                $reserved_year = $details->reserved_year;
                $currentDateTime = Carbon::now();
                $newDateTime = Carbon::now()->addYears($reserved_year); 
                $next_renewal = $newDateTime->format('Y-m-d H:i:s');
            }else{
                $next_renewal = null;
            }
            

            $update = DB::table('opportunities')
                ->where('id', $opportunity_id)
                ->update(array('reserved_year' => $details->reserved_year,'next_payment_date'=> $next_renewal));

             if($update){
                $userid = Auth::user()->id;
                CommonHelper::userGetAyojanReward("build_hashtag_renew", $userid, $opportunity_id, 6); 
            }    
                
            $redirection = "opportunity";    
            $invoice_item_title = "Renew ".$details->hashtag." Opportunity";  
        }

        
        //create offer by partner
        if($fetch[0]->form_name == "partner_offer")
        {
            $details = json_decode($fetch[0]->refrence_details);
            $offer_id = $details->offer_id;
            $offer = Offer::find($offer_id);
            $offer->admin_status = 1;
            $offer->save();

            if($offer){
                $userid = Auth::user()->id;
                CommonHelper::userGetAyojanReward("partner_offer", $userid, $offer_id, 7); 
            }

            $redirection = "partner/home";    
            $invoice_item_title = "Create A New Offer";

        }

        //collaboration by rec user
        if($fetch[0]->form_name == "collaboration")
        {
            $details = json_decode($fetch[0]->refrence_details);
            $collaboration_id = $details->collaboration_id;
            $Collaborate = Collaborate::find($collaboration_id);
            $Collaborate->status = 1;
            $Collaborate->save();

            if($Collaborate){
                $userid = Auth::user()->id;
                CommonHelper::userGetAyojanReward("collaboration", $userid, $collaboration_id,8); 
            }

            $redirection = "collaborate";    
            $invoice_item_title = "Collaborate";

        }


        //Bid pay by rec user
        if($fetch[0]->form_name == "pay_bid_rec")
        {
            $details = json_decode($fetch[0]->refrence_details);
            $bid_id = $details->bid_id;
            $description = $details->description;
            $portfolio = $details->portfolio;

            $bidData = [
                'respond_user_id' => Auth::user()->id,
                'bid_id' => $bid_id,
                'description' => $description,
                'portfolio_link' => $portfolio,
            ];
          
            $bidDetail = Bid::with('bider')->where('id', $bid_id)->first();
            $userDetails = User::find($bidDetail->user_id);
            $url = url('bid-response/') . '/' . base64_encode($bidDetail->id);
            $linkName = '<a href="' . $url . '">Click here</a>';
            $message = "Your opportunity received a bid, please " . $linkName . " to view the details.";
            CommonHelper::sendNotification($userDetails, $message, $url); 
            $response = ResponseBid::create($bidData);

            if($response){
                $userid = Auth::user()->id;
                CommonHelper::userGetAyojanReward("pay_bid_rec", $userid, $collaboration_id,9); 
            }

            $redirection = "my-bids";    
            $invoice_item_title = "Opportunity Bid Payment";

        }

        //create offer by partner
        if($fetch[0]->form_name == "partner_offer")
        {
            $details = json_decode($fetch[0]->refrence_details);
            $offer_id = $details->offer_id;
            $offer = Offer::find($offer_id);
            $offer->admin_status = 1;
            $offer->save();

            $redirection = "partner/home";    
            $invoice_item_title = "Create A New Offer";

        }

        

        if($fetch[0]->form_name == "direct_payment")
        {
            $direct_payment_id = $fetch[0]->id;
            
            $userid = Auth::user()->id;
            CommonHelper::userGetAyojanReward("direct_payment", $userid, $direct_payment_id, 10 );  
            $redirection = "directpay";    
            $invoice_item_title = "Direct Payment";
        }


            
        //for sending invoice/rec in mail
        $user = User::find(Auth::user()->id);
        $details = array();
        $details['invoice_no'] = $request->get('transaction_reference');
        $details['issue_date']  = $request->get('payment_date');
        $details['due_date'] = $request->get('payment_date');
        $details['entity'] = $user->entity;
        $details['phone_no'] = $user->phone_no;
        $details['email'] = $user->email;
        $details['rec_no'] = $fetch[0]->id;
        $details['payment_mode'] = $request->get('payment_mode');
        $details['amount'] = $request->get('amount');
        $details['invoice_item_title'] = $invoice_item_title;

        
        //outbound email
        $cc_email = DB::table('outbound_email')->value('email');

        $invoice = PDF::loadView('user.invoice', $details);
        $receipt = PDF::loadView('user.receipt', $details);
        

        Mail::send([], $details, function($message)use($details, $invoice, $receipt, $user, $fetch, $invoice_item_title,$cc_email) {
                    $message->to($user->email)
                    ->cc($cc_email)
                    ->subject($invoice_item_title)
                    ->attachData($invoice->output(), "invoice.pdf")
                    ->attachData($receipt->output(), "receipt.pdf");
        });

        //-----------------end-------------------

     
        $update = DB::table('direct_payment')->where('transaction_reference','=',$request->get('transaction_reference'))->update($data);
        return view('user.success')
            ->with('redirection', $redirection)
            ->with('paymentData', $request->only([
                'amount',
                'transaction_reference',
                'payment_date',
                'payment_mode',
                'receiver_name',
                'payer_name',
            ]))
            ->with('collectionId', $fetch[0]->collection_id);
    }

    public function failed(Request $request)
    {
        $data = array(
            'transaction_amount'=> $request->input('amount'), 
            'payment_date' => $request->input('payment_date'),
            'payment_mode'=> $request->input('payment_mode'), 
            'status_desc' => $request->input('transaction_status')
        );
            
        $update = DB::table('direct_payment')->where('transaction_reference','=',$request->input('transaction_reference'))->update($data);
        return view('user.failed');
    }

}
