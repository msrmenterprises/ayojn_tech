<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\MfaDelivery;
use App\Services\MfaChallenge;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;

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

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/partner/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('partner.login');
    }

    private function sendMfaChallenge(User $user, Request $request)
    {
        $delivery = new MfaDelivery();
        $recipients = $delivery->recipientFor($user);
        $challenge = new MfaChallenge();
        $code = $challenge->issue($user, 'partner', $request);

        if (! $delivery->send($user, $code, $recipients)) {
            $challenge->clear('partner', $request);

            return false;
        }

        return true;
    }


    public function direct_login_partner(Request $request)
    {
        $request->session()->forget([
            'direct_partner_email',
            'direct_partner_password',
            'p_email',
            'p_password',
        ]);

        return redirect('/partner/login')->with(
            'success_message',
            'Your email address is verified. Please sign in to continue.'
        );
    }

    public function login(Request $request)
    {
        (new MfaChallenge())->clear('partner', $request);
        $request->session()->forget([
            'p_email',
            'p_password',
            'direct_partner_email',
            'direct_partner_password',
        ]);

        $this->validateLogin($request);
        $credentials = ['email' => $request->get('p_email'), 'password' => $request->get('p_password')];
        $user = User::where('email', $credentials['email'])->first();

        if (
            ! $user
            || $user->sponsor_type != 3
            || ! Auth::guard('web')->validate($credentials)
        ) {
            return redirect()->back()->withErrors('Invalid Credentials');
        }

        if (! $this->sendMfaChallenge($user, $request)) {
            return redirect()->back()->withErrors('Unable to send the MFA code. Please try again.');
        }

        return redirect()->intended('/partner/mfa');
    }

    public function mfa(Request $request)
    {
        $request->session()->forget([
            'p_email',
            'p_password',
            'direct_partner_email',
            'direct_partner_password',
        ]);

        if ((new MfaChallenge())->pendingUserId('partner', $request) === null) {
            return redirect('/partner/login')->withErrors('Please sign in to request an MFA code.');
        }

        return view('partner.mfa');
    }

    public function verify_mfa(Request $request)
    {
        $request->session()->forget([
            'p_email',
            'p_password',
            'direct_partner_email',
            'direct_partner_password',
        ]);

        $challenge = new MfaChallenge();
        $userId = $challenge->pendingUserId('partner', $request);
        $user = $userId ? User::find($userId) : null;

        if (! $user || $user->sponsor_type != 3) {
            $challenge->clear('partner', $request);

            return redirect()->back()->withErrors('Please sign in again to request an MFA code.');
        }

        if (! $challenge->verify('partner', $user->id, $request->post('p_otp'), $request)) {
            return redirect()->back()->withErrors('MFA code is invalid or has expired. Please request a new code.');
        }

        Auth::guard('web')->login($user, $request->has('remember'));
        $request->session()->regenerate();

        if ($user->userstatus == 0) {
            $intended = 'partner/pending';
        } elseif ($user->is_suspend == 1) {
            $intended = 'partner/suspend';
        } else {
            $user->update([
                'last_login_at' => Carbon::now()->toDateTimeString(),
            ]);
            $intended = 'partner/home';
        }

        return redirect()->intended($intended);
    }


    public function resent_mfa(Request $request)
    {
        $request->session()->forget([
            'p_email',
            'p_password',
            'direct_partner_email',
            'direct_partner_password',
        ]);

        $challenge = new MfaChallenge();
        $userId = $challenge->pendingUserId('partner', $request);
        $user = $userId ? User::find($userId) : null;

        if (! $user || $user->sponsor_type != 3) {
            $challenge->clear('partner', $request);

            return redirect('/partner/login')->withErrors('Please sign in again to request an MFA code.');
        }

        if (! $this->sendMfaChallenge($user, $request)) {
            return redirect()->back()->withErrors('Unable to send the MFA code. Please try again.');
        }

        return redirect()->back()->with('success', 'A new MFA code has been sent.');
    }


    /**
     * Validate the user login request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            'p_email' => 'required|string',
            'p_password' => 'required|string',
        ]);
    }
}
