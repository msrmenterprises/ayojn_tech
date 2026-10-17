<?php

namespace App\Http\Controllers\ControlpanelAuth;

use App\Http\Controllers\Controller;
use App\Opportunity;
use App\Traits\ApiResponse;
use DataTables;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use App\User;
use Illuminate\Support\Facades\Gate;

class OpportunityController extends Controller
{

    use ApiResponse;


    public function index()
    {
        if (!Gate::allows('isAdmin')) {
            abort(403);
        }
        return view('controlpanel.opportunity.opportunitylist');
    }

    public function vouchList()
    {
        return view('controlpanel.opportunity.vouchlist');
    }

    public function listOpportunity($type = '')
    {
        $user_type = '';

        DB::enableQueryLog();
        DB::statement(DB::raw('set @rownum=0'));
        $bidLists = Opportunity::with(['opportunityUsers', 'country_name', 'industry'])->orderBy('id','DESC')->get();
        


        return Datatables::of($bidLists)
            ->addColumn('is_flagged', function ($response) {
             $flag = DB::table('flag_activity')->where('reference_id',$response->id)->where('flag_type','opportunities')->get();
                if(!$flag->isEmpty()){
                    return true;
                }else{
                    return false;
                }     
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
            })
            ->addColumn('opportunity_email', function ($response) {
                //dd($response['country_name']);
                if ($response->opportunityUsers) {
                    $email = [];
                    foreach ($response->opportunityUsers as $user) {
                        $email[] = $user->email;
                    }
                    if (! empty($email)) {
                        return wordwrap(implode(", ", $email));
                    } else {
                        return "-";
                    }

                    return "-";
                    // return $response->opportunityUser->email;
                } else {
                    return '-';
                }
            })
            // ->editColumn('status', function ($response) {

            //     $payment_status = DB::table('direct_payment')->where('refrence_id',$response->id)->where('form_name','build_opportunity')->get();

            //     if(!$payment_status->isEmpty() && $payment_status[0]->status_desc == 'SUCCESS'){
            //         if ($response->status == 1)
            //             $st = '<a href="javascript:void(0)"  onclick="changeStatus(' . $response->id . ')"><span class="label label-success my_lable_class" id="cust_id' . $response->id . '">Active</span></a>';
            //         else
            //             $st = '<a  href="javascript:void(0)"   onclick="changeStatus(' . $response->id . ')"><span class="label label-danger my_lable_class" id="cust_id' . $response->id . '"  >Inactive</span></a>';
            //     }


            // })
            // ->addColumn('payment_status', function ($response) {

            //     $paystatus = DB::table('direct_payment')->where('refrence_id',$response->id)->where('form_name','build_opportunity')->value('status_desc');

            //     return $paystatus??'';
            // })



            ->addColumn('responseCount', function ($response) {
                $url = url('controlpanel/vouchlist/') . "/" . $response->id;

                return '<a href="' . $url . '" > <span class="badge badge-light" >' . $response->vouchesResponse->count() . '</span > </a > ';
            })
            ->addColumn('sponsor_industry', function ($response) {
                if (empty($response->industry)) {
                    return "-";
                } else {
                    return @$response->industry->name;
                }
            })
            ->addColumn('action', function ($response) {
                $link = url('share-opportunity') . "/" . $response->share_id;
                $delete = '<a href="javascript:void(0)" title="Delete" class="btn btn-xs btn-danger pull-left" onclick="deleteUser(' . $response->id . ')"><i class="fa fa-trash"></i></a><span id="opportunity_' . $response->id . '" class="share-course-filed"
										  style="display: none;">' . $link . '</span>
                                        &nbsp; <a href="javascript:void(0)" class="btn btn-primary read-more-btn btn-xs  share-opp-btn"
										onclick="copyToClipboard(\'#opportunity_' . $response->id . '\')" title="Copy Web link"><i class="fa fa-link"></i></a>&nbsp;';
                if($response->is_reserved == 1){
                    $delete .= '&nbsp;  <span class="btn btn-success btn-xs" title="Reserved till '.date('d-m-Y',strtotime($response->next_payment_date)).' "><i class="fa fa-calendar-check-o"></i></span>&nbsp;';
                }
                
                $flag = DB::table('flag_activity')->where('reference_id',$response->id)->where('flag_type','opportunities')->get();

                if(!$flag->isEmpty()){

                $delete .= '&nbsp;<a href="javascript:void(0)" class="btn btn-danger btn-xs fl_click"  onclick="flag_reason('.$response->id.')"><i class="fa fa-flag" title="Irrelevant"></i></a>';
                }

                $payment_status = DB::table('direct_payment')->where('refrence_id',$response->id)->where('form_name','build_hashtag')->get();
                if(!$payment_status->isEmpty() && $payment_status[0]->status_desc == 'SUCCESS'){
                    if ($response->status == 1)
                        $delete .= '&nbsp;<a href="javascript:void(0)"  onclick="changeStatus(' . $response->id . ')"><span class="label label-success my_lable_class" id="cust_id' . $response->id . '" title="Active"><i class="fa fa-check"></i></span></a>';
                    else
                        $delete .= '&nbsp;<a  href="javascript:void(0)"   onclick="changeStatus(' . $response->id . ')"><span class="label label-danger my_lable_class" id="cust_id' . $response->id . '" title="De-Active" ><i class="fa fa-close"></i></span></a>';
                }
                            
                            


                return $delete;
            })
            ->addIndexColumn()
            ->rawColumns(['country_name', 'opportunity_email', 'status', 'sponsor_industry', 'responseCount', 'action','city_name','is_flagged'])
            ->make(true);
    }

    public function flagsactivity(Request $request)
    {
        $flag = DB::table('flag_activity')->where('reference_id',$request->id)->where('flag_type','opportunities')->get();
        if(!$flag->isEmpty()){
            $tr=""; $x=1;
            foreach ($flag as $key => $value) {
               $user = User::where('id', $value->user_id)->first();

                $tr = '<tr>
                        <td>' . $x . '</td>
                        <td>' . $value->reason . '</td>
                        <td>' . $user->email . '</td>
                        <td>' . $value->created_at . '</td>
                        
                    </tr>';
                    $x++;
            }
            return ['status' => true, 'msg' => 'Flag activity found', 'data' => $tr];
        }else{
            return ['status' => false, 'msg' => 'Flag activity not found'];
        }
    }

    public function opportunityChangeStatus(Request $request)
    {
        if (empty($request->opportunityId)) {
            return json_encode(['err_msg' => 'Please select opportunity', 'user_id' => $request->opportunityId]);
        }
        $user = Opportunity::findOrFail($request->opportunityId);
        if ($user->status == 1) {
            $user->status = 0;
            $user->save();

            return json_encode(['status' => $user->status, 'msg' => trans("Opportunity status has been updated successfully"), 'user_id' => $request->opportunityId]);
        } else {
            $user->status = 1;
            $user->save();

            // $send_mail = send_mail($data);
            return json_encode(['status' => $user->status, 'msg' => trans("Opportunity status has been updated successfully"), 'user_id' => $request->opportunityId]);
        }
    }

    public function listVouch(Request $request)
    {
        DB::enableQueryLog();
        DB::statement(DB::raw('set @rownum=0'));
        $bidLists = \App\Vouche::with(['opportunity', 'userDetail'])
            ->where('opportunity_id', $request->id)->get();

        return Datatables::of($bidLists)
            ->addColumn('userEmail', function ($response) {
                //dd($response['country_name']);
                if ($response->userDetail) {
                    return $response->userDetail->email;
                } else {
                    return '-';
                }
            })
            ->addColumn('status', function ($response) {

                if ($response->is_accepted != 2)
                    return '<a href="javascript:void(0)"><span
													class="label label-warning ">Accepted</span></a>';
                else
                    return '<a href = "javascript:void(0)" ><span
													class="label label-success" > Open for Negotiation </span ></a>';

            })
            ->addIndexColumn()
            ->rawColumns(['status', 'userEmail'])
            ->make(true);
    }

    public function deleteOpportunity(Request $request)
    {
        Opportunity::where('id', $request->user_id)->delete();

        return json_encode(['msg' => trans("Opportunity has been deleted successfully"), 'bid_id' => $request->user_id]);
    }

    public function opportunity_transaction(Request $request)
    {
        $opportunity = DB::table('direct_payment')->where('refrence_id', $request->id)->where('user_id', $request->user_id)->get();
        $table="";
        foreach ($opportunity as $key => $value) {
           
            $details = json_decode($value->refrence_details);
            
            $renewal_date = Opportunity::where('id', $request->id)->value('next_payment_date');

            $table .="<tr>";
            $table .="<td>".$value->id."</td>";
            $table .="<td>".$value->payment_date."</td>";
            $table .="<td>".$value->transaction_amount."</td>";
            $table .="<td>".$value->payment_mode."</td>";
            $table .="<td>".$value->transaction_reference."</td>";
            $table .="<td>".$details->reserved_year."</td>";
            $table .="<td>".$renewal_date."</td>";
            $table .="</tr>";
        }

        return $table;
    }
}
