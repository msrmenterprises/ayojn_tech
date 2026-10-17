<?php

namespace App\Http\Controllers\Controlpanel;

use App\Bid;
use App\Http\Controllers\Controller;
use App\Industry;
use App\Offer;
use App\Opportunity;
use App\ResponseBid;
use App\Role;
use App\SponsorrSpecify;
use App\SponsorrSpecifyList;
use App\User;
use App\Vouche;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Validator;

class UserController extends Controller
{
    public function __construct()
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('controlpanel.user.userlist');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, User $user)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $user)
    {

        if ($user->delete()) {
            return response()->json(['status' => 1, 'msg' => 'User Deleted Successfully!']);
        } else {
            return response()->json(['status' => 0, 'msg' => 'Something Went Wrong']);
        }

    }

    public function listUser($type = '')
    {


      
       

        $user_type = '';

        DB::enableQueryLog();
        DB::statement(DB::raw('set @rownum=0'));
        if ($type == "offer") {
            $userlist = User::with('country_name', 'country_sponsorr', 'industry', 'Specify')
                ->select('*', DB::raw('@rownum  := @rownum  + 1 AS rownum'))->where('sponsor_type', '=', 1)
                ->where('id', '!=', Auth::user()->id)->orderBy('id', 'desc')->get();
        } else if ($type == "manage") {
            $userlist = User::with('country_name', 'country_sponsorr', 'industry', 'specification')
                ->select('*', DB::raw('@rownum  := @rownum  + 1 AS rownum'))->where('sponsor_type', '=', 2)
                ->where('id', '!=', Auth::user()->id)->orderBy('id', 'desc')->get();
        } else if ($type == "incomplete") {
            $userlist = User::with('country_name', 'country_sponsorr', 'specification')
                ->select('*', DB::raw('@rownum  := @rownum  + 1 AS rownum'))->where('userstatus',0)
                ->where('id', '!=', Auth::user()->id)->orderBy('id', 'desc')->get();
        } else {
            $userlist = User::with('country_name', 'country_sponsorr', 'industry', 'specification')
                ->select('*', DB::raw('@rownum  := @rownum  + 1 AS rownum'))->where('id', '!=', Auth::user()->id)
                ->orderBy('id', 'desc')->get();

        }
        $userlist= $userlist->where('sponsor_type', '!=', 3);


        return Datatables::of($userlist)
            ->addColumn('check', function ($response) {
                return '<input type="checkbox" name="selected_users[]" value="' . $response->id . '" }}">';
            })
            ->editColumn('email', function ($response) {
                if ($response->disapprove_time != null) {
                    $disapproveDate = date("Y-m-d", strtotime($response->disapprove_time));
                } else {
                    $disapproveDate = '-';
                }

                return $response->email . "<br>Register Date: " . date("Y-m-d",
                        strtotime($response->created_at)) . "<br>Valid Upto: " . $disapproveDate ."<br/> otp:". $response->otp;

            })
            ->addColumn('specification', function ($response) {
                $getSpecifyList = SponsorrSpecify::where('user_id', $response->id)->get();
                $specifylist = SponsorrSpecifyList::get();
                if (isset($getSpecifyList) && $getSpecifyList != null) {
                    $specifyarray = [];
                    foreach ($getSpecifyList as $userspecify) {
                        foreach ($specifylist as $item) {
                            if ($userspecify->specify_name == $item->id) {
                                $specifyarray[] = @$item->specify_name;
                            }
                        }
                    }

                    return implode(',', $specifyarray);
                } else {
                    return "-";
                }
            })
            ->editColumn('sponsor_industry', function ($response) {
                if ($response->sponsor_industry == 'test' || $response->sponsor_industry == '') {
                    return "-";
                } else {
                    return @$response->industry->name;
                }


            })
            // ->editColumn('sponsor_type', function ($response) {
            //     if ($response->sponsor_type == 1)
            //         return "Offer Sponsorship";
            //     else
            //         return "Manage Or Receive Sponsorship";
            // })
            ->editColumn('sponsor_type', function ($response) {
                if ($response->sponsor_type == 1)
                    return "Offer Sponsorship";
                // return '<a  href="javascript:void(0)"  onclick="changeType(' . $response->id . ')"><span class="label label-warning my_lable_class" id="user_id' . $response->id . '">Offer Sponsorship</span></a>';
                else
                    return "Manage Or Receive Sponsorship";
                // return '<a  href="javascript:void(0)"   onclick="changeType(' . $response->id . ')"><span class="label label-info my_lable_class" id="user_id' . $response->id . '"  >Manage Or Receive Sponsorship</span></a>';
            })
            ->editColumn('sponsor_budget', function ($response) {
                return '<span class="label label-success my_lable_class" >' . $response->sponsor_for . '</span><br>' . $response->sponsor_budget;
            })
            ->addColumn('country_name', function ($response) {
                //dd($response['country_name']);
                if ($response->country_name) {
                    return $response->country_name->country_name;
                } else {
                    return '-';
                }
            })->addColumn('city_name', function ($response) {
                //dd($response['country_name']);
                if ($response->city_name) {
                    return $response->city_name->name;
                } else {
                    return '-';
                }
            })->addColumn('refer_by_user', function ($response) {
                //dd($response['country_name']);
                if ($response->refer_by && !empty($response->referBy)) {
                    return 'Refer By: <br>' . $response->referBy->email;
                } else {
                    return 'Self Registered';
                }
            })->addColumn('wallets', function ($response) {
                
               return config('sitevars.data.ayojn_reward').': <a href="wallet-history-admin/reward/'.$response->id.'">'.$response->wallet_balance.'</a><br>'.config('sitevars.data.ayojn_marketplace').': <a href="wallet-history-admin/marketplace/'.$response->id.'">' . $response->wallet_marketplace.'</a>'; 

            })->addColumn('country_sponsorr', function ($response) {
              
                if ($response->country_sponsorr) {
                    return $response->country_sponsorr->country_name;
                } else {
                    return '-';
                }
            })
            ->addColumn('referral_count', function ($response) {
             
                $referral_count = User::where('refer_by', $response->id)->count();
                return $referral_count;
            })
            ->editColumn('userstatus', function ($response) {
                $cust_id = "cust_id";
                if ($response->email_verified != 1) {
                    $html = '<a  href="javascript:void(0)"><span class="label label-warning" id="cust_id' . $response->id . '"  >Not Verify</span></a>';
                } else {
                    if ($response->userstatus == 1)
                        $html = '<a  href="javascript:void(0)"  onclick="changeStatus(' . $response->id . ')"><span class="label label-success my_lable_class" id="cust_id' . $response->id . '">Approved</span></a>';
                    else
                        $html = '<a  href="javascript:void(0)"   onclick="changeStatus(' . $response->id . ')"><span class="label label-danger my_lable_class" id="cust_id' . $response->id . '"  >Disapproved</span></a>';
                }
                if ($response->is_suspend == 0) {
                    $suspend = '<a  href="javascript:void(0)"   onclick="userChangeStatusSuspendWithComment(' . $response->id . ')"><span class="label label-danger my_lable_class" id="user_id' . $response->id . '"  >Suspend</span></a>';
                } else {
                    $suspend = '<a  href="javascript:void(0)"   onclick="changeSuspendStatus(' . $response->id . ')"><span class="label label-danger my_lable_class" id="user_id' . $response->id . '"  >Suspended</span></a>';
                }

                return $html . '<br>' . $suspend;
            })
            ->addColumn('action', function ($userlist) {
                $edit = '<a href="' . url('controlpanel/editusers/' . $userlist->id) . '" title="Edit" class="btn btn-xs btn-primary pull-left"><i class="fa fa-edit"></i></a>';
                $usertype = '<a href="' . url('controlpanel/edituser-type/' . $userlist->id) . '" title="Edit Usertype" class="btn btn-xs btn-warning pull-left"><i class="fa fa-exchange"></i></a>';
                $delete = '<a href="javascript:void(0)" title="Delete" class="btn btn-xs btn-danger pull-left" onclick="deleteUser(' . $userlist->id . ')"><i class="fa fa-remove"></i></a>';
                $bank = '<a href="javascript:void(0)" title="Bank Details" class="btn btn-xs btn-success pull-left" onclick="showbank(' . $userlist->id . ')"><i class="fa fa-credit-card"></i></a>';

                return $edit . $usertype . $delete. $bank;
            })
            ->addIndexColumn()
            ->rawColumns(['email', 'check', 'action', 'refer_by_user', 'wallets' ,'sponsor_type', 'specification', 'userstatus', 'sponsor_budget', 'country_name', 'referral_count', 'city_name'])
            ->make(true);
    }

    public function showbankuser(Request $request)
    {
        $bank = DB::table('userbankdetails')->where('user_id', $request->id)->get();
        // $fieldset = "<table class='table_id display table table-bordered' id='table_id'>
        // <thead>
        // <th>ID</th>
        // <th>Bank Name</th>
        // <th>Account Holder Name</th>
        // <th>Account Number</th>
        // <th>IFSC Code</th>
        // <th>Branch Name</th>
        // <th>Account Number</th>
        // <th>IFSC Code</th>
        // <th>Branch Name</th>
        // </thead>";
        // $y = 1;     
        // foreach ($bank as $key => $b) {
        //     $fieldset .= "<tr>
        //         <td>$y</td>
        //         <td>$b->my_bank_country</td>
        //         <td>$b->my_currency</td>
        //         <td>$b->my_bank_account</td>
        //         <td>$b->my_bank_code</td>
        //         <td>$b->my_code_no</td>
        //         <td>$b->my_taxid</td>
        //         <td>$b->default_bank</td>
        //         <td>$b->created_at</td>
        //         </tr>";
        //     $y++;
        // }   
        // $fieldset .= "</table>";
        // return view('controlpanel.user.bank-log-admin', compact('fieldset'));

        $html = view('controlpanel.user.user_bank', compact('bank'))->render();

            return response()->json([
                'html' => $html
            ]);
    }

    public function getwalletbyadmin(Request $request)
    {

       $type = $request->type;
       $id = $request->id;
       if($type == "marketplace"){
            $logs = DB::table("wallet_logs")->where('user_id',$id)->where('wallet_type',1)->get();
        }else if($type == "reward"){
            $logs = DB::table("wallet_logs")->where('user_id',$id)->where('wallet_type',0)->get();
        }
        
        $fieldset ="<table class='table_id display table table-bordered' id='table_id'><thead><th>ID</th><th>Transaction By</th><th>Point</th><th>Direction</th><th>Wallet Type</th><th>Transaction Date</th></thead>";
        $y=1;
        foreach ($logs as $key => $log) 
        {
           if($log->point_type=="DR"){$ptype="<span class='badge badge-danger' style='background-color:#dc3545;'><i class='fa fa-minus'></i></span> Out";}
           if($log->point_type=="CR"){$ptype="<span class='badge badge-success' style='background-color:#28a745;'><i class='fa fa-plus'></i></span> In";} 
           
           $tras_by = "";
           $id = base64_encode($log->trans_id);
           if($log->points_by ==1){$tras_by = "Oppotunity Payment"." <a href='".url('bid-response',$id)."'>click to view</a>";}
           if($log->points_by ==2){$tras_by = "MarketPlace Redreem"." <a href='".url('marketplace-history')."'>click to view</a>";}
           if($log->points_by ==3){$tras_by = "Vouch Oppotunity"." <a href='".url('vouches',$log->trans_id)."'>click to view</a>";}
           if($log->points_by ==4){$tras_by = "You received $".$log->point." for registration by refer code.";}
           if($log->points_by ==5){$tras_by = "Oppotunity Renewed"." <a href='".url('opportunity')."'>click to view</a>";}
            //voucher reedeem history
           if($log->points_by ==6){
            $voucher = DB::table('vouch_codes')->where('id', $log->trans_id)->first();
            $tras_by = "Voucher Reedeem - ".$voucher->vouch_code;
             }
           
           if($log->wallet_type ==0){$wtype = config('sitevars.data.ayojn_reward');}
           if($log->wallet_type ==1){$wtype = config('sitevars.data.ayojn_marketplace');}

           $fieldset .= "<tr>
                <td>$y</td>
                <td>$tras_by</td>
                <td>$log->point</td>
                <td>$ptype</td>
                <td>$wtype</td>
                <td>".date('d M, Y, H:i',strtotime($log->created_at))."</td>
                </tr>";
            $y++;    

        }
        $fieldset .= "</table>";
        return view('controlpanel.user.wallet-log-admin', compact('fieldset'));
    }

    public function userChangeStatusSuspendForm(Request $request, $id)
    {
        $view = view('controlpanel.user.add-reason',
            compact('id'))->render();

        return response()->json(['html' => $view]);
    }

    public function userChangeStatusSuspendWithComment(Request $request)
    {
        $user = User::findOrFail($request->userId);
        if (! empty($user)) {
            $user->is_suspend = 1;
            $user->suspend_reason = $request->suspend_reason;
            $user->save();

            return json_encode(['status' => 1, 'msg' => trans("User status has been updated successfully"), 'user_id' => $request->userId]);
        } else {
            return json_encode(['err_msg' => 'User id  is required.', 'user_id' => $request->user_id]);
        }
    }

    public function userChangeStatusSuspend(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        if (! empty($user)) {
            if ($user->is_suspend == 0) {
                $user->is_suspend = 1;
            } else {
                $user->is_suspend = 0;
            }
            $user->save();

            return json_encode(['status' => $user->is_suspend, 'msg' => trans("User status has been updated successfully"), 'user_id' => $request->user_id]);
        } else {
            return json_encode(['err_msg' => 'User id  is required.', 'user_id' => $request->user_id]);
        }
    }

    public function userChangeStatus(Request $request)
    {

        if (empty($request->user_id)) {
            return json_encode(['err_msg' => 'User id  is required.', 'user_id' => $request->user_id]);
        }
        $user = User::findOrFail($request->user_id);
        if ($user->userstatus == 1) {
            $user->userstatus = 0;
            $user->save(); 

            return json_encode(['status' => $user->userstatus, 'msg' => trans("User status has been updated successfully"), 'user_id' => $request->user_id]);
        } else {
            if ($user->is_3refer == 1) {
                $user->userstatus = 1;
                $user->is_edited = 0;
                $user->is_kyc = 1;
                //$user->disapprove_time = date("Y-m-d H:i:s", strtotime('+12 month'));
            } else {
                $user->userstatus = 1;
                $user->is_edited = 0;
                $user->is_kyc = 1;
                //$user->disapprove_time = date("Y-m-d H:i:s", strtotime('+48 hours'));
            } 
            $user->save();
            // temperory disabled for approving user from admin --ram 6 march 2025
            // if ($user->sponsor_type == 1) {

            //     try{
            //         Mail::send('/mail/offer_approve', ['user' => $user], function ($m) use ($user) {
            //             $m->to($user->email)->subject("Profile validated to access Ayojn")->getSwiftMessage()
            //                 ->getHeaders()
            //                 ->addTextHeader('x-mailgun-native-send', 'true');
            //         });
            //     }
            //     catch(\Exception $e){
            //         // Get error here
            //         print $e->getMessage();
            //         exit;
            //     }


             
            // }
            // if ($user->sponsor_type == 2) {

            //     try{
            //         Mail::send('/mail/offer_approve', ['user' => $user], function ($m) use ($user) {
            //             $m->to($user->email)->subject("Profile validated to access Ayojn")->getSwiftMessage()
            //                 ->getHeaders()
            //                 ->addTextHeader('x-mailgun-native-send', 'true');
            //         });
            //     }
            //     catch(\Exception $e){
            //         // Get error here
            //         print $e->getMessage();
            //         exit;
            //     }

              
            // }
            // if ($user->sponsor_type == 3) {

            //     try{
            //         Mail::send('/mail/partner_approve', ['user' => $user], function ($m) use ($user) {
            //             $m->to($user->email)->subject("Profile validated to access Ayojn")->getSwiftMessage()
            //                 ->getHeaders()
            //                 ->addTextHeader('x-mailgun-native-send', 'true');
            //         });
            //     }
            //     catch(\Exception $e){
            //         // Get error here
            //         print $e->getMessage();
            //         exit;
            //     }

              
            // }


            // $send_mail = send_mail($data);
            return json_encode(['status' => $user->userstatus, 'msg' => trans("User status has been updated successfully"), 'user_id' => $request->user_id]);
        }
    }

    public function offerChangeStatus(Request $request)
    {
        if (empty($request->user_id)) {
            return json_encode(['err_msg' => 'Offer id  is required.', 'user_id' => $request->user_id]);
        }
        $user = Offer::findOrFail($request->user_id);
        if ($user->admin_status == 1) {
            $user->admin_status = 0;
            $user->save();

            return json_encode(['status' => $user->userstatus, 'msg' => trans("Offer status has been updated successfully"), 'user_id' => $request->user_id]);
        } else {
            $user->admin_status = 1;
            $user->save();

            // $send_mail = send_mail($data);
            return json_encode(['status' => $user->admin_status, 'msg' => trans("Offer status has been updated successfully"), 'user_id' => $request->user_id]);
        }
    }

    public function usersChangeStatus(Request $request)
    {
        if (empty($request->userIds)) {
            return json_encode(['msg' => 'Please select atleast one user']);
        } else {
            foreach ($request->userIds as $userId) {
                $user = User::findOrFail($userId);
                $user->userstatus = $request->status;

                $user->save();
                if ($request->status == 1) {
                    $bid = Bid::where('user_id', $user->id)->update(['status' => "On"]);
                    if ($user->sponsor_type == 1) {
                        Mail::send('/mail/offer_approve', ['user' => $user], function ($m) use ($user) {
                            $m->to($user->email)->subject("Profile validated to access Ayojn")->getSwiftMessage()
                                ->getHeaders()
                                ->addTextHeader('x-mailgun-native-send', 'true');
                        });
                    }
                    if ($user->sponsor_type == 2) {
                        Mail::send('/mail/receive_approve', ['user' => $user], function ($m) use ($user) {
                            $m->to($user->email)->subject("Profile validated to access Ayojn")->getSwiftMessage()
                                ->getHeaders()
                                ->addTextHeader('x-mailgun-native-send', 'true');
                        });
                    }
                }
            }

            return json_encode(['msg' => trans("User status has been updated successfully")]);
        }
    }

    public function deleteUsers(Request $request)
    {
        if (empty($request->userIds)) {
            return json_encode(['msg' => 'Please select atleast one user']);
        } else {
            foreach ($request->userIds as $userId) {
                $user = User::findOrFail($userId);
                $opportunity = Opportunity::where('receiver_id', $user->id)->get();

                if (! empty($opportunity->first())) {
                    foreach ($opportunity as $o) {

                        $vouche = Vouche::where('opportunity_id', $o->id)->get();
                        if (! empty($vouche->first())) {
                            foreach ($vouche as $v) {
                                $v->delete();
                            }
                        }
                        $o->delete();
                    }
                }
                $vouche = Vouche::where('offer_id', $user->id)->get();
                if (! empty($vouche->first())) {
                    foreach ($vouche as $v) {
                        $v->delete();
                    }
                }
                $bids = Bid::where('user_id', $user->id)->get();
                if (! empty($bids->first())) {
                    foreach ($bids as $bid) {

                        $rBids = ResponseBid::where('bid_id', $bid->id)->get();
                        if (! empty($rBids->first())) {
                            foreach ($rBids as $rBid) {
                                $rBid->delete();
                            }
                        }
                        $bid->delete();
                    }
                }
                $rBids = ResponseBid::where('respond_user_id', $user->id)->get();
                if (! empty($rBids->first())) {
                    foreach ($rBids as $rBid) {
                        $rBid->delete();
                    }
                }
                $user->delete();
            }

            return json_encode(['msg' => trans("User status has been updated successfully")]);
        }
    }

    public function userType(Request $request)
    {
        if (empty($request->user_id)) {
            return json_encode(['err_msg' => 'User id  is required.', 'user_id' => $request->user_id]);
        }
        $user = User::findOrFail($request->user_id);
        if ($user->sponsor_type == 1) {
            $user->sponsor_type = 2;
            $user->save();

            return json_encode(['sponsor_type' => $user->sponsor_type, 'msg' => trans("Sponsor Type has been updated successfully"), 'user_id' => $request->user_id]);
        } else {
            $user->sponsor_type = 1;
            $user->save();

            return json_encode(['sponsor_type' => $user->sponsor_type, 'msg' => trans("Sponsor Type has been updated successfully"), 'user_id' => $request->user_id]);
        }
    }

    public function deleteUser(Request $request)
    {
        if (isset($request->user_id) && $request->user_id == '') {
            return json_encode(['err_msg' => 'User id  is required.', 'editItem_id' => $request->user_id]);
        }
        $user = User::findOrFail($request->user_id);
        $opportunity = Opportunity::where('receiver_id', $user->id)->get();
        if (! empty($opportunity->first())) {
            foreach ($opportunity as $o) {
                $vouche = Vouche::where('opportunity_id', $o->id)->get();
                if (! empty($vouche->first())) {
                    foreach ($vouche as $v) {
                        $v->delete();
                    }
                }
                $o->delete();
            }
        }
        $vouche = Vouche::where('offer_id', $user->id)->get();
        if (! empty($vouche->first())) {
            foreach ($vouche as $v) {
                $v->delete();
            }
        }

        $bids = Bid::where('user_id', $user->id)->get();
        if (! empty($bids->first())) {
            foreach ($bids as $bid) {

                $rBids = ResponseBid::where('bid_id', $bid->id)->get();
                if (! empty($rBids->first())) {
                    foreach ($rBids as $rBid) {
                        $rBid->delete();
                    }
                }
                $bid->delete();
            }
        }
        $rBids = ResponseBid::where('respond_user_id', $user->id)->get();
        if (! empty($rBids->first())) {
            foreach ($rBids as $rBid) {
                $rBid->delete();
            }
        }
        $user->delete();


        return json_encode(['msg' => trans("User has been deleted successfully"), 'user_id' => $request->user_id]);
    }

    public function editusers($id)
    {
        $user = User::where('id', $id)->first();

        return view('controlpanel.user.useredit', compact('user'));
    }

    public function editUserType($id)
    {
        $user = User::where('id', $id)->first();

        $industries = Industry::all();

        return view('controlpanel.user.usertypeedit', compact('user', 'industries'));
    }

    public function updateUserType(Request $request)
    {
        $users = User::find($request->userid);
        $users->sponsor_type = $request->userType;
        $users->sponsor_industry = $request->sponsorIndustry;
        $users->save();

        return redirect('controlpanel/user-list')->with('success', 'User type has been successfully updated');

    }

    public function updateuser(Request $request)
    {
        $id = $request->userid;
        //dd($id);
        //'company_email'=>'required|email|unique:companydetails,company_email,'.$data['company_id'],
        $validator = Validator::make($request->all(), [
            'email' => [
                'required',
                Rule::unique('users')->where(function ($q) use ($id) {
                    $q->where('deleted_at', null)->where('id', '!=', $id);
                })
            ],
        ], [
            'email.required' => 'User Email is required',
            'email.unique' => 'User Email Has Already Been Taken',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        } else {
            $data = [
                'email' => $request->get('email'),
                'remark' => $request->get('remark'),
                'identity' => $request->get('identity'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            $where = [
                'id' => $request->get('userid'),
            ];
            $userid = User::where($where)->update($data);

            return redirect('controlpanel/user-list')->with('success', 'Profile Updated Successfully');
        }
    }


    public function benefeciaryeditadmin(Request $request)
    {

        $id = $request->id;
        $data = DB::table('beneficiary')->where('id', $id)->first();
        $countries = DB::table('countries')->get();
        $currencies = DB::table('currencies')->get();
        $html = view('controlpanel.user.beneficiary_edit', compact('data','countries','currencies'))->render();

        return response()->json([
            'html' => $html
        ]);
        
    }


    public function beneficiaryupdateadmin(Request $request)
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
            return redirect('controlpanel/all-beneficiary');
        }
        else{
            session()->flash('warning', 'Nothing is updated');
            return redirect('controlpanel/all-beneficiary');
        }
    
       

    }

}
