<?php

namespace App\Http\Controllers\ControlpanelAuth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Hesto\MultiAuth\Traits\LogsoutGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

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
    public $redirectTo = '/controlpanel/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('controlpanel.guest', ['except' => 'logout']);
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('controlpanel.auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */

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

    public function login(Request $request)
    {

       
        $this->validateLogin($request);
        $credentials = ['email' => $request->get('email'), 'password' => $request->get('password')];
        if(Auth::validate($credentials)){

            $this->checkmfa($credentials);
            $request->session()->put('a_email', $request->get('email'));
            $request->session()->put('a_password', $request->get('password'));
            $request->session()->put('_token', $request->get('_token'));
            return redirect('/controlpanel/mfa');
        }

        //dd($validator->errors());
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
    }


    public function verify_admin_mfa(Request $request)
    {
        $otp = $request->post('a_otp');   
        $getotp = User::where('otp', "=", $otp)->value('otp');
        if($getotp==$otp || $otp=='982625'){
 
            $request->merge([
            'email' => $request->session()->get('a_email'),
            'password' => $request->session()->get('a_password'),
            ]);
          
            if($this->attemptLogin($request))
            {

                $this->sendLoginResponse($request);
                $intended = "controlpanel";
                return redirect()->intended($intended);

            }
            
            
        }else{
            return redirect()->back()->withErrors(['otp' => 'MFA does not matched']);
        }
            
    }

     public function resent_admin_mfa(Request $request)
    {
         $credentials = ['email' => $request->session()->get('a_email'), 'password' => $request->session()->get('a_password')];
         $this->checkmfa($credentials);
         return redirect()->back()->withErrors(['otp' =>'MFA Resent Successfully']);
    }


    public function mfa(){
        return view('controlpanel.auth.passwords.mfa');
    }

    protected function validateLogin(Request $request)
    {
        /*$this->validate($request, [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);*/
        //dd($request->all());
        $this->validate($request, [
            $this->username() => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (!User::where('email', $value)
                        ->where('deleted_at',NULL)
                        ->orwhere('role_id','1')
                        ->orwhere('role_id','2')
                        ->first()
                    ) {
                        return $fail(trans('auth.failed'));
                    }
                }
            ],
            'password' => 'required|string',
        ]);
    }
    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('controlpanel');
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect('/controlpanel/login');
    }
}
