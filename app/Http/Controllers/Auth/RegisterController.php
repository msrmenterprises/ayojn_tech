<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Models\NotificationSetting;

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

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     *
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function register(Request $request)
    {
        $request->session()->forget(['direct_partner_email', 'direct_partner_password']);

        $rules = [
            'company_name' => 'required',
            'contact_person' => 'required',
            'partner_phone_no' => 'required',
            'partner_email' => 'required|unique:users,email',
            'partner_password' => 'required|min:8',
            'p_mother_name' => 'required',
        ];
        $messsages = [
            'company_name.required' => 'Enter an Company Name.',
            'contact_person.required' => 'Enter an Contact person.',
            'partner_email.required' => 'Enter an Email.',
            'partner_email.unique' => 'Email is Used. Try Another',
            'partner_phone_no.required' => 'Enter a Mobile no.',
            'partner_password.required' => 'Enter a Password.',
            'partner_password.min' => 'Enter a password with at least eight characters.',
            'p_mother_name.required' => 'Enter a mother name',
        ];
        $validator = Validator::make($request->all(), $rules, $messsages);
        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator->errors()->first());
        }
        $response = false;
        $userData = User::where('email', "=", $request->partner_email)->first();
        if (! empty($userData)) {
            return redirect()->back()->withErrors('you are already registred with sponsorr');
//            return $this->failResponse("you are already registred with sponsorr")->setStatusCode(200);
        } else {
            $defaultUserType = DB::table('user_type')->orderBy('id')->value('id');
            if (! $defaultUserType) {
                return redirect()->back()->withErrors('No user type is configured for new users.');
            }

            $emailVerificationCode = $this->createRandomPassword(45);

            $user = new User();
            $user->email = $request->get('partner_email');
            $user->company_name = $request->get('company_name');
            $user->contact_person = $request->get('contact_person');
            $user->HQ = $request->get('hq');
            $user->sq1 = $request->get('p_mother_name');
            $user->password = Hash::make($request->get('partner_password'));
            $user->user_type = $defaultUserType;
            $user->sponsor_type = 3;
            $user->phone_no = $request->get('partner_phone_no');
            $user->email_verified = 0;
            $user->email_verification_code = $emailVerificationCode;
            $value = $request->session()->get('referal');
            if (! empty($value)) {
                $getUser = User::where('refer_code', $value)->first();
                if (! empty($getUser)) {
                    $user->refer_by = $getUser->id;
                }
            }
            $response = $user->save();
            NotificationSetting::create(['user_id' => $user->id, 'inapp' => 1]);
            $referalCode = "SponSayPartner" . $this->createRandomPassword(6) . $user->id;
            $user->refer_code = $referalCode;
            $user->save();

            Mail::send('/mail/createPartnerAccount', ['user' => $user], function ($m) use ($user) {
                $m->to($user->email)->subject("Ayojn Partner Program")->getSwiftMessage()
                    ->getHeaders()
                    ->addTextHeader('x-mailgun-native-send', 'true');
            });
        }
        if ($response) {
            return redirect('partner-pending')->with('success',
                'Thank you for the inputs please wait as you\'re bring directed to the finish page.');
        } else {
            return redirect()->back()->withErrors('Error Occured!Please try again later');
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
        if (! empty($customerData)) {
            if ($customerData->email_verified == 0) {
                $customerData = User::where('id', $customerData->id)
                    ->update(['email_verified' => 1]);
                if ($customerData) {
                    //return redirect(url('/partner/login'))->with('success_message', 'Your email address succesfully verified');
                    return redirect()->route('direct_login_partner')->with('success_message', 'Your email address is succesfully verified');
                } else {
                    return redirect(url('/partner/login'))->with('error_message', 'Something went wrong');
                }
            } else {
                return redirect(url('/partner/login'))->with('error_message', 'This link is already used');
            }
        } else {
            return redirect(url('/partner/login'))->with('error_message', 'verification link is not valid');
        }
    }
}
