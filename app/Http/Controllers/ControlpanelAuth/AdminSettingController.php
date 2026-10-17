<?php

namespace App\Http\Controllers\ControlpanelAuth;

use App\Http\Controllers\Controller;
use App\Models\Collaborate;
use App\Models\ColResponse;
use App\Traits\ApiResponse;
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
        return view('controlpanel.configuration-page', compact('details','user_type','configurations'));
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
