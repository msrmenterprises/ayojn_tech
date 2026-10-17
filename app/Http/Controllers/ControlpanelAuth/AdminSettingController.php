<?php

namespace App\Http\Controllers\ControlpanelAuth;

use App\Http\Controllers\Controller;
use App\Models\Collaborate;
use App\Models\ColResponse;
use App\Traits\ApiResponse;
use App\User;
use Illuminate\Http\Request;
use DataTables;
use DB;
class AdminSettingController extends Controller
{
    //
    use ApiResponse;

    public function buttons_setting()
    {
        $details = DB::table('buttons_setting')->get();
        return view('controlpanel.buttons_setting', compact('details'));
    }

    public function configuration()
    {
        $details = DB::table('outbound_email')->value('email');
        $user_type = DB::table('user_type')->get();
        $configurations = DB::table('configuration')->get();
        $mfaDemoSettings = DB::table('mfa_demo_settings')->where('id', 1)->first();
        $mfaDemoAccounts = DB::table('mfa_demo_users')
            ->join('users', 'users.id', '=', 'mfa_demo_users.user_id')
            ->whereNull('users.deleted_at')
            ->select(
                'mfa_demo_users.id',
                'mfa_demo_users.user_id',
                'mfa_demo_users.is_active',
                'users.name',
                'users.email',
                'users.role_id'
            )
            ->orderBy('users.email')
            ->get();

        return view('controlpanel.configuration-page', compact(
            'details',
            'user_type',
            'configurations',
            'mfaDemoSettings',
            'mfaDemoAccounts'
        ));
    }

    public function updateMfaDemoSettings(Request $request)
    {
        $this->validate($request, [
            'recipient_email' => 'required|email|max:255',
        ]);

        $settings = [
            'recipient_email' => $request->input('recipient_email'),
            'updated_at' => now(),
        ];

        if (DB::table('mfa_demo_settings')->where('id', 1)->exists()) {
            DB::table('mfa_demo_settings')->where('id', 1)->update($settings);
        } else {
            $settings['id'] = 1;
            $settings['created_at'] = now();
            DB::table('mfa_demo_settings')->insert($settings);
        }

        return redirect()->back()->with('success', 'Demo MFA recipient updated successfully.');
    }

    public function storeMfaDemoUser(Request $request)
    {
        $this->validate($request, [
            'user_email' => 'required|email|max:255|exists:users,email',
        ]);

        $user = User::where('email', $request->input('user_email'))
            ->whereNull('deleted_at')
            ->first();

        if (! $user || (int) $user->role_id === 1) {
            return redirect()->back()->withErrors('Choose an active non-admin account for demo MFA routing.');
        }

        $demoRecipient = DB::table('mfa_demo_settings')
            ->where('id', 1)
            ->value('recipient_email');
        if (! is_string($demoRecipient) || filter_var($demoRecipient, FILTER_VALIDATE_EMAIL) === false) {
            return redirect()->back()->withErrors('Save a valid shared demo inbox before adding accounts.');
        }

        if (DB::table('mfa_demo_users')->where('user_id', $user->id)->exists()) {
            return redirect()->back()->withErrors('This account is already on the demo MFA list.');
        }

        DB::table('mfa_demo_users')->insert([
            'user_id' => $user->id,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Demo MFA routing enabled for ' . $user->email . '.');
    }

    public function toggleMfaDemoUser($id)
    {
        $demoAccount = DB::table('mfa_demo_users')->where('id', $id)->first();
        if (! $demoAccount) {
            return redirect()->back()->withErrors('Demo MFA account was not found.');
        }

        $isActive = ! (bool) $demoAccount->is_active;
        DB::table('mfa_demo_users')->where('id', $id)->update([
            'is_active' => $isActive,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with(
            'success',
            $isActive ? 'Demo MFA routing enabled.' : 'Demo MFA routing disabled.'
        );
    }

    public function removeMfaDemoUser($id)
    {
        $deleted = DB::table('mfa_demo_users')->where('id', $id)->delete();
        if (! $deleted) {
            return redirect()->back()->withErrors('Demo MFA account was not found.');
        }

        return redirect()->back()->with('success', 'Account removed from the demo MFA list.');
    }

    public function configuration_user_update(Request $request)
    {
        $data = [
            'level' => $request->level,
            'refer_amount' => $request->refer_amount,
        ];
        DB::table('user_type')->where('user_type', $request->user_type)->update($data);

        return redirect()->back()->with('success', 'User updated successfully');

    }

    public function configuration_update(Request $request)
    {
        
        $data = [
            'config_amt' => $request->config_amt,
            'config_type' => $request->config_type, // Assuming you want the first value of the array
            'user_earn' => $request->user_earn,
        ];



        $check = DB::table('configuration')->where('config_text', $request->config_text)->first();
        if ($check) {
            DB::table('configuration')->where('config_text', $request->config_text)->update($data); 
        } else {
            DB::table('configuration')->insert($data);
        }

        return redirect()->back()->with('success', 'Configuration updated successfully');
    }

    public function outbound_email_update(Request $request)
    {
        $email = $request->email_host;
        DB::table('outbound_email')->updateOrInsert(
            ['id' => 1], // Assuming there's only one record
            ['email' => $email]
        );
        return redirect()->back()->with('success', 'Outbound email updated successfully');
    }

    public function view_buttons_setting()
    {
        $id = request()->id;
        $details = DB::table('buttons_setting')->where('id', $id)->get();
        if ($details->isEmpty()) {
            return $this->errorResponse('Button setting not found', 404);
        }else {
            return array('data'=>$details,'status'=>true);
        }
    }


    public function update_buttons_setting(Request $request)
    {
        $id = $request->id;
        $data = [
            'button_name' => $request->button_name,
            'button_icon' => $request->button_icon,
            'class_name' => $request->class_name,
            'button_hover' => $request->button_hover,
        ];
        DB::table('buttons_setting')->where('id', $id)->update($data);
        return redirect()->back()->with('success', 'Button setting updated successfully');

    }

   

}
