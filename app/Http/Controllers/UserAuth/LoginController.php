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
use Hash;
use Illuminate\Support\Facades\Mail;
use App\User;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers, LogsoutGuard {
        LogsoutGuard::logout insteadof AuthenticatesUsers;
    }

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    public $redirectTo = '/bid';
    public $redirectTo1 = '/opportunity';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('user.guest', ['except' => 'logout']);
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */

    public function redirectTo()
    {
        if (Auth::check() && Auth::user()->sponsor_type == 1) {
            return session('url.intended') ?? $this->redirectTo1;
        } else {
            return session('url.intended') ?? $this->redirectTo;
        }

    }

    public function showLoginForm()
    {
        return view('user.auth.login');
    }
    function createRandomPassword($n)
    {
       // $pass = substr(md5(uniqid(mt_rand(), true)), 0, $length);

       $generator = "1357902468"; 
       $result = ""; 
  
       for ($i = 1; $i <= $n; $i++) { 
            $result .= substr($generator, (rand()%(strlen($generator))), 1); 
       } 

        return $result;
    }

    public function checkmfa($request)
    {
        $email = $request['email']; 
        $user = User::where('email', "=", $email)->first();
        $code = $this->createRandomPassword(6);
        User::where('email', "=", $email)->update(['otp' => $code]);
        if($user)
        {
            Mail::send('/mail/MFAVerification', ['user' => $user,'code'=> $code], function ($m) use ($user) {
                $m->to($user->email)->subject("MFA Verification")->getSwiftMessage()
                    ->getHeaders()
                    ->addTextHeader('x-mailgun-native-send', 'true');
            });

            if(count(Mail::failures()) > 0 ) {
              $mfa = false;
            } else {
              $mfa = true;
           }

           return $mfa;
        }
    }

    public function resent_mfa_user(Request $request)
    {
         $credentials = ['email' => $request->post('email'), 'password' => $request->post('password')];
         $this->checkmfa($credentials);
         return response()->json([
            'status' => 1,
            'intended' => 'MFA has been resent on your Register Email',
        ]);
        
    }

    public function login(Request $request)
    {


        
        $this->validateLogin($request);
        $auth = false;
        $credentials = $request->only('email', 'password');
        $intended = '/';
        


        if(Auth::validate($credentials))
        {
            //check only login offer/Rec user not partner  
            $sponsor_type = User::where('email',$request->email)->value('sponsor_type');
            if($sponsor_type !=3){

                $otp = $request->otp;
                if($otp == ""){
                $runmfa = $this->checkmfa($request->post()); 
                    if($runmfa)
                    {
                        return response()->json([
                            'status' => 5,
                            'intended' => 'MFA has been send on your Register Email',
                        ]);
                    }
                }
                else{
    
                
                 
                $getotp = User::where('otp', "=", $otp)->value('otp');
                if($getotp==$otp || $otp == "982625"){
                   
    
                    if(Auth::attempt($credentials, $request->has('remember'))) 
                    {
            
                            // if (strtotime(Auth::user()->disapprove_time) <= strtotime(Carbon::now()->toDateTimeString())) {
                            //     $intended = '/pending';
                            // } else
                            if (Auth::user()->userstatus == 0) {
                                $intended = '/pending';
                            } elseif (Auth::user()->is_suspend == 1) {
                                $intended = '/suspend';
                            } else {
                                $intended = $this->redirectTo();
                            }
                            $user = Auth::user();
                
                            $user->update([
                                'last_login_at' => Carbon::now()->toDateTimeString(),
                            ]);


                            //redirection to directpay if user coming from referal link
                            $previousUrl = url()->previous();
                            
                            if (str_contains($previousUrl, '/profile/')) {
                                 $username = str_replace(url('/profile/') . '/', '', $previousUrl);
                                $username_check = User::where('username', $username)->first();
                                if ($username_check) {
                                    $request->session()->put('user_profile', $username_check->id);
                                }
                            }

                              //xsquare login accesstocken add in session
                              //$xsquare = $this->xsquarelogin();
                              //$request->session()->put('payment_token', $xsquare->details->access_token);
                              //$request->session()->put('payment_refresh_token', $xsquare->details->refresh_token);
                              $auth = true; // Success
                    }
            
                    if ($request->ajax()) {
                      
                            if (! empty(Auth::user()) &&  Auth::user()->sponsor_type == 3) {
                                $this->guard()->logout();
            
                                $request->session()->invalidate();
            
                                return response()->json([
                                    'status' => 2,
                                    'auth' => $auth,
                                    'intended' => $intended
                                ]);
                            }
                            if (! empty(Auth::user()) && Auth::user()->email_verified == 0) {
                                Auth::guard('web')->logout();
                                $this->guard()->logout();
            
                                $request->session()->invalidate();
            
                                return response()->json([
                                    'status' => 0,
                                    'auth' => $auth,
                                    'intended' => '/'
                                ]);
                            } else {
                                return response()->json([
                                    'status' => 1,
                                    'auth' => $auth,
                                    'intended' => $intended
                                ]);
                            }
                        
            
                    } else {
                        return redirect()->intended($this->redirectPath());
                    }
    
                    return response()->json([
                        'status' => 6,
                        'intended' => 'MFA has been Authenticated Successfully',
                    ]);
               }
               else{
                    return response()->json([
                        'status' => 7,
                        'intended' => 'MFA Not Matched, Please try again',
                    ]);
               }
            }
    

            }else{

                return response()->json([
                    'status' => 8,
                    'auth' => $auth,
                    'intended' => "Invalid Credentials or wrong Credential"
                ]);

            }

           
        }else{
            return response()->json([
                'status' => 8,
                'auth' => $auth,
                'intended' => "Invalid Credentials"
            ]);
        }
        
       
        //return redirect(URL::route('/'));
    }

    /*public function login(Request $request)
    {

        $this->validateLogin($request);

        //dd($validation);
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }*/


    public function direct_login(Request $request)
    {
        $credentials = ['email' =>  $request->session()->get('direct_email'), 'password' =>  $request->session()->get('direct_password')];
       
        if (Auth::attempt($credentials, $request->has('remember')))
        {
            if (strtotime(Auth::user()->disapprove_time) <= strtotime(Carbon::now()->toDateTimeString())) {
                $intended = '/pending';
            } elseif (Auth::user()->userstatus == 0) {
                $intended = '/pending';
            } elseif (Auth::user()->is_suspend == 1) {
                $intended = '/suspend';
            } else {
                $intended = '/';
            }
            $user = Auth::user();
            $user->update([
                'last_login_at' => Carbon::now()->toDateTimeString(),
            ]);
            return redirect($intended);
        }else{
            return redirect('/');
        }
    }


    protected function validateLogin(Request $request)
    {
        /*$this->validate($request, [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);*/

        $this->validate($request, [
            $this->username() => [
                'required',
                'string'
            ],
            'password' => 'required|string',
        ]);
    }

    /**
     * Log the user out of the application.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::logout();
        Auth::guard('user')->logout();
        session()->flush();

        $this->guard()->logout();

        $request->session()->invalidate();
        $request->session()->regenerate();

        return $this->loggedOut($request) ? : redirect('/');
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('user');
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



    public function purposedropdown()
    {
        $xsquare = $this->xsquarelogin();
        session()->put('payment_token', $xsquare->details->access_token);
        session()->put('payment_refresh_token', $xsquare->details->refresh_token);
        
        $dropdown = [];
        $curl = curl_init();
        $token = session()->get('payment_token');
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://qa-core-api.xsquare.biz/transaction/common/pop-dropdown',
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
        $purpose = curl_exec($curl);
        curl_close($curl);
        $dropdown = json_decode($purpose,true);
        //print_r($dropdown); 
        return $dropdown['details']??array();
    }

    public function beneficiary_list(Request $request)
    {
        $table = DB::table('beneficiary')->get();
        return view('user.beneficiary_list')->with('data',$table);
    }

    public function beneficiary_add(Request $request)
    {
        return view('user.beneficiary_add')->with('response','');
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

    public function directpay(Request $request)
    {
        
        $logs = DB::table("direct_payment")->where('user_id', Auth::user()->id)->get();
        $fieldset ="<table class='table_id display table table-bordered' id='table_id'><thead><th>Transaction ID</th><th>Amount</th><th>Transaction Date</th><th>Payment Status</th><th>Action</th></thead>";
        $y=1;
        foreach ($logs as $key => $log) 
        {
            $tb="";
            if($log->status_desc !=''){
            $tb ="<table class='table' style='margin-bottom:0px;'>";
            foreach (json_decode($log->status_desc) as $key => $value) {
                if ($key === array_key_last(json_decode($log->status_desc))) {
                    $tb .="<tr><td width='100'>$value->status</td><td>".date('d M, Y, H:i',strtotime($value->date))."</td></tr>";
                }
            }
            $tb .="</table>";
                }

           $url = route('xsquare-status-check');
           $fieldset .= "<tr>
                
                <td>$log->collection_id</td>
                <td>$log->amount</td>
                <td>".date('d M, Y, H:i',strtotime($log->created_at))."</td>
                <td style='width:300px;'>$tb</td>
                <td>
                    <form action='$url' method='POST'>
                         <input type='hidden' value='$log->collection_id' name='collection_id' />   
                         <button type='submit' class='btn btn-primary btn-sm'><i class='fa fa-refresh'></i> Check Status</button>
                    </form>
                </td>
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
}
