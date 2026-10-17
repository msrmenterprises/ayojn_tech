<?php

namespace App\Http\Controllers\ControlpanelAuth;

use App\Http\Controllers\Controller;
use App\Services\MfaDelivery;
use App\Services\MfaChallenge;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Hesto\MultiAuth\Traits\LogsoutGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

    private function sendMfaChallenge(User $user, Request $request)
    {
        $delivery = new MfaDelivery();
        $recipients = $delivery->recipientFor($user);
        $challenge = new MfaChallenge();
        $code = $challenge->issue($user, 'controlpanel', $request);

        if (! $delivery->send($user, $code, $recipients)) {
            $challenge->clear('controlpanel', $request);

            return false;
        }

        return true;
    }

    public function login(Request $request)
    {
        (new MfaChallenge())->clear('controlpanel', $request);
        $request->session()->forget(['a_email', 'a_password']);
        $this->validateLogin($request);
        $credentials = ['email' => $request->get('email'), 'password' => $request->get('password')];
        $user = User::where('email', $credentials['email'])
            ->where('role_id', 1)
            ->whereNull('deleted_at')
            ->first();

        if ($user && $this->guard()->validate($credentials)) {
            if (! $this->sendMfaChallenge($user, $request)) {
                return redirect()->back()->withErrors(['email' => 'Unable to send the MFA code. Please try again.']);
            }

            return redirect('/controlpanel/mfa');
        }

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }


    public function verify_admin_mfa(Request $request)
    {
        $request->session()->forget(['a_email', 'a_password']);

        $challenge = new MfaChallenge();
        $userId = $challenge->pendingUserId('controlpanel', $request);
        $user = $userId
            ? User::where('id', $userId)->where('role_id', 1)->whereNull('deleted_at')->first()
            : null;

        if (! $user) {
            $challenge->clear('controlpanel', $request);

            return redirect()->back()->withErrors(['otp' => 'Please sign in again to request an MFA code.']);
        }

        if (! $challenge->verify('controlpanel', $user->id, $request->post('a_otp'), $request)) {
            return redirect()->back()->withErrors(['otp' => 'MFA code is invalid or has expired. Please request a new code.']);
        }

        $this->guard()->login($user);
        $this->clearLoginAttempts($request);
        $request->session()->regenerate();

        return redirect()->intended('controlpanel');
    }

    public function resent_admin_mfa(Request $request)
    {
        $request->session()->forget(['a_email', 'a_password']);

        $challenge = new MfaChallenge();
        $userId = $challenge->pendingUserId('controlpanel', $request);
        $user = $userId
            ? User::where('id', $userId)->where('role_id', 1)->whereNull('deleted_at')->first()
            : null;

        if (! $user) {
            $challenge->clear('controlpanel', $request);

            return redirect('/controlpanel/login')->withErrors(['email' => 'Please sign in again to request an MFA code.']);
        }

        if (! $this->sendMfaChallenge($user, $request)) {
            return redirect()->back()->withErrors(['otp' => 'Unable to send the MFA code. Please try again.']);
        }

        return redirect()->back()->with('success', 'A new MFA code has been sent.');
    }


    public function mfa(Request $request)
    {
        $request->session()->forget(['a_email', 'a_password']);

        if ((new MfaChallenge())->pendingUserId('controlpanel', $request) === null) {
            return redirect('/controlpanel/login')->withErrors(['email' => 'Please sign in to request an MFA code.']);
        }

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
                    if (! User::where('email', $value)
                        ->whereNull('deleted_at')
                        ->where('role_id', 1)
                        ->exists()) {
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
