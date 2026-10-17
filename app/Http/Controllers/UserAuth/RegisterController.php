<?php

namespace App\Http\Controllers\UserAuth;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use App\Models\NotificationSetting;
use App\User;
use Hash;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Validator;
use DB;
use App\Helpers\CommonHelper;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers, ApiResponse;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/user/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('user.guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     *
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view('user.auth.register');
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('user');
    }

    public function register(Request $request)
    {
        // dd($request->all());
        $rules = [
            'entity' => 'required',
            'username' => 'required|unique:users,username',
            'phone_no' => 'required',
            'email' => 'required',
            'password' => 'required',
            'country' => 'required',
            'city' => 'required',
            'sq1' => 'required',
        ];
        $messsages = [
            'entity.required' => 'Enter an Entity.',
            'username.required' => 'Enter an Username.',
            'email.required' => 'Enter an Email.',
            'email.unique' => 'Email is Used. Try Another',
            'phone_no.required' => 'Enter a Mobile no.',
            'password.required' => 'Enter a Password.',
            'password.min' => 'Enter a Combination of at least Six Character.',
            'country.required' => 'Please select country.',
            'city.required' => 'Please select city.',
            'sql.required' => 'Please enter your mother maiden name.',
        ];
        $validator = Validator::make($request->all(), $rules, $messsages);
        if ($validator->fails()) {
            return $this->validationErrors($validator)->setStatusCode(200);
        }
        $response = false;
        if (!empty($request->get('sponsor_type') == 2) && empty($request->get('indetity'))) {
            return $this->failResponse("please select identity")->setStatusCode(200);
        }
        $userData = User::where('email', "=", $request->email)->first();
        if(!empty($userData)) {
            return $this->failResponse("You are already registered with this emailID!")->setStatusCode(200);
        }
        $mobilexist = User::where('phone_no', "=", $request->phone_no)->first();
        if(!empty($mobilexist)) {
            return $this->failResponse("You are already registered with this Mobile No.!")->setStatusCode(200);
        } else {
            $emailVerificationCode = $this->createRandomPassword(45);

            $user = new User();
            $user->email = $request->get('email');
            $user->entity = $request->get('entity');
            $user->username = $request->get('username');
            $user->password = Hash::make($request->get('password'));
            $user->sponsor_type = $request->get('sponsor_type');
            $user->country = $request->get('country');
            $user->phone_no = $request->get('phone_no');
            $user->city = $request->get('city');
            $user->identity = $request->get('indetity');
            $user->sq1 = $request->get('sq1');
            $user->email_verified = 0;
            $user->email_verification_code = $emailVerificationCode;
            $value = $request->session()->get('referal');
            if (!empty($value)) {
                $getUser = User::where('username', $value)->first();
                if (!empty($getUser)) {
                    $user->refer_by = $getUser->id;
                    
                    //refer amount get by user type 8 may 2025 by ram
                    $user_type = $getUser->user_type; 
                    $refer_amount = DB::table('user_type')->where('id', $user_type)->value('refer_amount');
                    $user->wallet_marketplace = $refer_amount;
                   
                }
            }
            $response = $user->save();
            NotificationSetting::create(['user_id' => $user->id, 'inapp' => 1]);
            $referalCode = "Ayojn" . $this->createRandomPassword(6) . $user->id;
            $user->refer_code = $referalCode;
            $user->save();

            if(!empty($value)){

                 //wallet history maintain by ram 8 may 2025
                 $wallet_history = [
                    'user_id' => $user->id,
                    'points_by' => 13,
                    'point' => $refer_amount,
                    'trans_id' => 0,
                    'point_type' => "CR",
                    'wallet_type' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                DB::table('wallet_logs')->insert($wallet_history);

                
                //notification for referal amount by ram 8 may 2025
                $userDetails = User::find($user->id);
                $url = url('wallet-history');
                $linkName = '<a href="' . $url . '">Click here</a>';
                $message = "You received $".$refer_amount. " upon registration. Explore Ayojn's services to earn even more! click here " . $linkName . " to check wallet details.";
                CommonHelper::sendNotification($userDetails, $message, $url);
            }

            $request->session()->put('direct_email', $request->get('email'));
            $request->session()->put('direct_password', $request->get('password'));
            
            try{
              Mail::send('/mail/createAccount', ['user' => $user], function ($m) use ($user) {
                $m->to($user->email)->subject("Ayojn")->getSwiftMessage()
                    ->getHeaders()
                    ->addTextHeader('x-mailgun-native-send', 'true');
            });

            }
            catch(\Exception $e){
                // Get error here
                print $e->getMessage();
                exit;
            }

            

//            if (! empty($request->likeAyojn)) {
//                $user->likeAyojn = implode(',', $request->likeAyojn);
//                $user->save();
//            }
//            if (isset($request->sponsor_for_specify) && ! empty($request->sponsor_for_specify)) {
//                foreach ($request->sponsor_for_specify as $sponsor_specify) {
//                    $specifyUser_list = [
//                        'specify_name' => $sponsor_specify,
//                        'user_id' => $user->id
//                    ];
//                    $specifyUser = AyojnSpecify::create($specifyUser_list);
//                    if ($request->sponsor_type == 1) {
//                        $speciData = [
//                            'specify_name' => $sponsor_specify,
//                            'user_id' => $user->id,
//                            'bid_id' => 1
//                        ];
//                        $specifyUser = AyojnSpecifyBid::create($speciData);
//                    }
//                }
//            }
//            if ($request->sponsor_type == 1) {
//                $bidData = [
//                    'user_id' => $user->id,
//                    'sponsor_for' => $user->sponsor_for,
//                    'sponsor_budget' => $user->sponsor_budget,
//                    'sponsor_industry' => $user->sponsor_industry,
//                    'sponsor_country' => $user->sponsor_country,
//                    'status' => "Off",
//                    'bid_start_date' => date('Y-m-d H:i:s'),
//                    'country' => $user->country
//                ];
//
//                if (! empty($request->likeAyojn)) {
//                    $bidData['likeAyojn'] = implode(',', $request->likeAyojn);
//                }
//                $response = Bid::create($bidData);
//            }
        }
        if ($response) {
            return $this->successResponse("Thank you for the inputs please wait as you're being directed to the finish page.");
        } else {
            return $this->failResponse("Error Occured!Please try again later")->setStatusCode(200);
        }
    }

  function createRandomPassword($length = 8)
    {
        $pass = substr(md5(uniqid(mt_rand(), true)), 0, $length);

        return $pass;
    }

    public function VerifyEmail(Request $request)
    {
        $verificationCode = $request->verifyCode;
        $customerData = User::where('email_verification_code', $verificationCode)->first();
        if (!empty($customerData)) {
            if ($customerData->email_verified == 0) {
                $customerData = User::where('id', $customerData->id)
                    ->update(['email_verified' => 1]);
                if ($customerData) {
                   //return redirect(url('/'))->with('success_message', 'Your email address is succesfully verified');
                    return redirect()->route('direct_login')->with('success_message', 'Your email address is succesfully verified');
                } else {
                    return redirect(url('/'))->with('error_message', 'Something went wrong');
                }
            } else {
                return redirect(url('/'))->with('error_message', 'This link is already used');
            }
        } else {
            return redirect(url('/'))->with('error_message', 'verification link is not valid');
        }
    }

    public function halfRegister(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
            'country' => 'required',
            'sponsor_type' => 'required',

        ];
        $messsages = [
            'email.required' => 'Enter an Email.',
            'email.unique' => 'Email is Used. Try Another',
            'password.required' => 'Enter a Password.',
            'password.min' => 'Enter a Combination of at least Six Character.',
            'country.required' => 'Please select country.',
        ];
        $validator = Validator::make($request->all(), $rules, $messsages);
        if ($validator->fails()) {
            return $this->validationErrors($validator)->setStatusCode(200);
        }
        $response = false;
        $userData = User::where('email', "=", $request->email)->first();
        if (!empty($userData)) {
            if ($userData['sponsor_for'] != '') {
                return $this->failResponse("you are already registred with Ayojn")->setStatusCode(200);
            } else {
                $user = $userData;
                $user->name = 'test';
                $user->email = $request->get('email');
                $user->password = Hash::make($request->get('password'));
                $user->country = $request->get('country');
                $user->sponsor_type = $request->get('sponsor_type');
                $response = $user->save();
            }
        } else {
            $user = new User();
            $user->name = 'test';
            $user->email = $request->get('email');
            $user->password = Hash::make($request->get('password'));
            $user->country = $request->get('country');
            $user->entity = $request->get('entity');
            $user->sponsor_type = $request->get('sponsor_type');
            $response = $user->save();
        }
        if ($response) {
            return $this->successResponse("You are successfully registred with Ayojn.co! Please login to access your account");
        } else {
            return $this->failResponse("All Fileds are mandatory")->setStatusCode(200);
        }
    }

    public function existingUser()
    {
        $users = User::get();
        foreach ($users as $user) {
            $code = "Ayojn" . $this->createRandomPassword(6) . $user->id;
            $user->refer_code = $code;
            $user->save();
        }
    }
}
