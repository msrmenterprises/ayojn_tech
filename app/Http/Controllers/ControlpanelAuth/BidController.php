<?php

namespace App\Http\Controllers\ControlpanelAuth;

use App\Bid;
use App\Helpers\CommonHelper;
use App\Http\Controllers\Controller;
use App\RedeemRequest;
use App\Traits\ApiResponse;
use App\User;
use App\VouchCode;
use App\WalletLog;
use Carbon\Carbon;
use DataTables;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Imports\VouchCodeImport;

class BidController extends Controller
{

    use ApiResponse;


    public function index()
    {
        return view('controlpanel.bid.bidlist');
    }

    public function codes()
    {
        return view('controlpanel.code.codelist');
    }

    public function redeemRequests()
    {

        return view('controlpanel.redeem-request');
    }

    public function VouchCreate()
    {

        return view('controlpanel.code.code-create');
    }

    public function VouchEdit($id)
    {
        $vouchCode = VouchCode::find($id);

        return view('controlpanel.code.code-edit', compact('vouchCode'));
    }

    public function VouchAdd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vouch_code' => ['required'],
            'vouch_amount' => ['required'],
        ], [
            'vouch_code.required' => 'Vouch Code is required',
            'vouch_amount.required' => 'Vouch Amount is required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        } else {

            $check = VouchCode::where('vouch_code',$request->vouch_code)->get();
            if($check->isEmpty())
            {
                $todayDate = Carbon::now();
                $expeiryDate = $todayDate->addDays(15);
                VouchCode::create(['vouch_code' => $request->vouch_code, 'vouch_amount' => $request->vouch_amount, 'expeiry_date' => $expeiryDate->format('Y-m-d')]);
    
                return redirect('controlpanel/codes')->with('success', 'Vouch code added Successfully');
            }
            else{
                return redirect('controlpanel/codes')->with('error', 'Vouch code already exist');
            }

           
        }
    }

    public function VouchUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vouch_code' => ['required'],
            'vouch_amount' => ['required'],
        ], [
            'vouch_code.required' => 'Vouch Code is required',
            'vouch_amount.required' => 'Vouch Amount is required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        } else {
            $todayDate = Carbon::now();
            $expeiryDate = $todayDate->addDays(15);
            $vouchData = VouchCode::find($request->code_id);
            $vouchData->vouch_code = $request->vouch_code;
            $vouchData->vouch_amount = $request->vouch_amount;
            $vouchData->expeiry_date = $expeiryDate;
            $vouchData->save();

            return redirect('controlpanel/codes')->with('success', 'Vouch code updated Successfully');
        }
    }

    public function response()
    {
        return view('controlpanel.bid.respondlist');
    }

    public function listUser($type = '')
    {
        $user_type = '';

        DB::enableQueryLog();
        DB::statement(DB::raw('set @rownum=0'));
        $bidLists = Bid::with(['bider', 'country_name', 'industry', 'bidResponse'])->get();

        return Datatables::of($bidLists)
             ->addColumn('is_flagged', function ($bidLists) {
             $flag = DB::table('flag_activity')->where('reference_id',$bidLists->id)->where('flag_type','bid')->get();
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
            })->addColumn('bider_email', function ($response) {
                //dd($response['country_name']);
                if ($response->bider) {
                    return $response->bider->email;
                } else {
                    return '-';
                }
            })
            ->addColumn('responseCount', function ($response) {
                $url = url('controlpanel/response/') . "/" . $response->id;

                return '<a href="' . $url . '" > <span class="badge badge-light" >' . $response->bidResponse->count() . '&nbsp;/&nbsp;' . $response->bidSpamResponse->count() . '</span ></a > ';
            })
            ->addColumn('bidStatus', function ($response) {
               $result = null;
                foreach ($response->bidResponse as $object) {
                    if ($object->is_accepted > 2) {
                        $result = $object->is_accepted;
                        break;
                    }
                }
                unset($object);
                $obj = $result;

                if ($obj == 0)
                    return '<span class="label label-warning ">Not Seen Yet</span>';
                elseif ($obj == 1)
                    return '<span class="label label-primary" > Read for Receiver </span >';
                elseif ($obj == 3)
                    return '<span class="label label-success" > Booked </span >';
                elseif ($obj == 4)
                    return '<span class="label label-primary" > Paid </span >';               
                else
                    return '<span class="label label-success" > Open for Negotiation </span >';
            })
            ->addColumn('sponsor_industry', function ($response) {
                if ($response->sponsor_industry == '') {
                    return "-";
                } else {
                    return @$response->industry->name;
                }
            })
            ->addColumn('action', function ($response) {

                  $flag = DB::table('flag_activity')->where('reference_id',$response->id)->where('flag_type','bid')->get();

               

                $link = url('share') . "/" . $response->share_id;
                $delete = '<a href="javascript:void(0)" title="Delete" class="btn btn-sm btn-danger btn-xs pull-left" onclick="deleteUser(' . $response->id . ')"><i class="fa fa-remove"></i></a><span id="opportunity_' . $response->id . '" class="share-course-filed"
										  style="display: none;">' . $link . '</span><a
										href="javascript:void(0)" class="btn btn-primary read-more-btn  share-opp-btn btn-xs"
										onclick="copyToClipboard(\'#opportunity_' . $response->id . '\')"><i class="fa fa-link"></i></a>';

                if(!$flag->isEmpty()){
                $delete .= '&nbsp;<a href="javascript:void(0)" class="btn btn-danger btn-xs fl_click"  onclick="flag_reason('.$response->id.')"><i class="fa fa-flag" title="Irrelevant"></i></a>';
                }
                        

                return $delete;
            })

            
            ->editColumn('status', function ($response) {
                if ($response->admin_status == 1)
                    return '<a  href="javascript:void(0)"  onclick="changeStatus(' . $response->id . ')"><span class="label label-success my_lable_class" id="cust_id' . $response->id . '">Approved</span></a>';
                else
                    return '<a  href="javascript:void(0)"   onclick="changeStatus(' . $response->id . ')"><span class="label label-danger my_lable_class" id="cust_id' . $response->id . '"  >Disapproved</span></a>';
            })


            ->editColumn('payment_status', function ($response) {
                if ($response->is_paid == 1){
                    $dl = asset('uploads').'/'.$response->payment_proof;
                    return '<span class="label label-success my_lable_class" id="cust_id' . $response->id . '">Paid</span><br/><a href='.$dl.' target="_blank">payment proof</a>';
                }
                else{
                    return '<span class="label label-danger my_lable_class" id="cust_id' . $response->id . '"  >Unpaid</span>';
                }
            })

            //add by ram 23 sept
            ->addColumn('per', function ($response) {
                $url = url('controlpanel/addper');
                if ($response->admin_status == 1)
               return '<form method="post" action="'.$url.'"><input type="text" value="'.$response->per.'" style="width:30px" name="percentage" /> <input type="hidden" value="'.$response->id.'" name="responce_id" /><button class="btn btn-success btn-xs" >Add</button></form>';
                else 
                return '';
            })
            ->addIndexColumn()
            ->rawColumns(['is_flagged','country_name', 'bider_email', 'sponsor_industry', 'responseCount', 'bidStatus', 'action', 'status', 'payment_status' ,'per'])
            ->make(true);
    }


    public function flagsactivity(Request $request)
    {
        $flag = DB::table('flag_activity')->where('reference_id',$request->id)->where('flag_type','bid')->get();
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

    public function listCode()
    {
        DB::enableQueryLog();
        DB::statement(DB::raw('set @rownum=0'));
        $codes = VouchCode::orderBy('id','desc')->get();

        return Datatables::of($codes)
        ->addcolumn('status',function($response){
            if($response->status==0)
            {
                return "<span class='badge badge-primary' style='background-color:green;'>created</span>";
            }
            if($response->status==1)
            {
                $user = User::where('id',$response->used_by)->first();
                return "<span class='badge badge-danger' data-toggle='tooltip' data-placement='top' title='Used By ".$user->entity.', '.$user->phone_no."' style='background-color:red;'>Used</span>";
            }
          
        })

        ->addcolumn('share',function($response){
           
            return "<a class='btn btn-primary' onclick='share($response->id)'><i class='fa fa-plane'></i> Share</a>";
          
        })

        ->addColumn('checkall', function($row) {
             
            if ($row->status == 1) {
                return '<input type="checkbox" name="selected_users[]" value="'.$row->id.'" disabled>';
            }else {
                return '<input type="checkbox" name="selected_users[]" value="'.$row->id.'">';
            }
        })

        ->addColumn('action', function ($response) {
                $url = url('controlpanel/edit-code/') . "/" . $response->id;
                $edit="";
                $delete ="";
                if($response->status==0)
                {
                     $edit = '<a href="' . $url . '" title="Edit" class="btn btn-sm btn-info pull-left" style="margin-right:5px;"><i class="fa fa-pencil"></i></a>';
                    $delete = '<a href="javascript:void(0)" title="Delete" class="btn btn-sm btn-danger pull-left" onclick="deleteCode(' . $response->id . ')"><i class="fa fa-remove"></i></a>';
                }
                return $edit . ' ' .$delete;
            })
            ->addIndexColumn()
            ->rawColumns(['action','status','share','checkall'])
            ->make(true);
    }


    public function bulkvouchupload(Request $request)
    {

        return view('controlpanel.code.bulk-vouch-upload');

    }

    public function bulkVouchUploadStore(Request $request)
    {
           // Validate the uploaded file
            $validator = Validator::make($request->all(), [
                'vouch_file' => 'required|mimes:csv,xls,xlsx', // csv files may come as txt sometimes
            ], [
                'vouch_file.required' => 'Vouch File is required.',
                'vouch_file.mimes' => 'Vouch File must be a file of type: csv,xls,xlsx.',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            try {
            
                $file = $request->file('vouch_file');
                $import = new VouchCodeImport();
                Excel::import($import, $file);

                $duplicates = $import->getDuplicates();

                
                if (!empty($duplicates)) {
                    $message = ' The following voucher codes were skipped because they already exist: ' . implode(', ', $duplicates);
                    return redirect()->route('bulk-vouch-upload')->with('warning', $message);
                   
                }else {
                    $message = ' Vouch codes uploaded successfully.';
                     return redirect()->route('bulk-vouch-upload')->with('success', $message);
                }

               
            } catch (\Exception $e) {
                print_r($e->getMessage());
                //return redirect()->back()->with('error', 'There was an error importing the file: ' . $e->getMessage());
            }
        
    }
   

    public function listRedeemRequest()
    {
        DB::enableQueryLog();
        DB::statement(DB::raw('set @rownum=0'));
        $redeemRequests = RedeemRequest::with('userDetail')->get();

        return Datatables::of($redeemRequests)
            ->addColumn('beneficiary_id', function ($response) {
                $name = DB::table('beneficiary')->where('id',$response->beneficiary_id)->value('contact_name');
               return $name;
            })
            ->addColumn('email', function ($response) {
                //dd($response['country_name']);
                if ($response->userDetail) {
                    return $response->userDetail->email;
                } else {
                    return '-';
                }
            })->addColumn('phone_no', function ($response) {
                //dd($response['country_name']);
                if ($response->userDetail) {
                    return $response->userDetail->phone_no;
                } else {
                    return '-';
                }
            })->addColumn('balance', function ($response) {
                //dd($response['country_name']);
                if ($response->userDetail) {
                    return $response->userDetail->wallet_marketplace;
                } else {
                    return '-';
                }
            })->addColumn('status', function ($response) {
                if ($response->status == 1) {
                    return '<a  href="javascript:void(0)" ><span class="label label-success my_lable_class" id="cust_id' . $response->id . '"  >Approved</span></a>';
                } else if ($response->status == 2) {
                    return '<a  href="javascript:void(0)" ><span class="label label-danger my_lable_class" id="cust_id' . $response->id . '"  >Disapproved</span></a>';
                } else {
                    return '<a  href="javascript:void(0)" ><span class="label label-warning my_lable_class" id="cust_id' . $response->id . '"  >Pending</span></a>';
                }

            })
            ->addColumn('action', function ($response) {
                if ($response->status == 0) {
                    $status2 = '<a href="javascript:void(0)"  onclick="changeStatus(' . $response->id . ',1)"><span class="label label-success my_lable_class" id="cust_id' . $response->id . '">Approve</span></a> &nbsp;';
                    $status3 = '<a  href="javascript:void(0)"   onclick="changeStatus(' . $response->id . ',2)"><span class="label label-danger my_lable_class" id="cust_id' . $response->id . '"  >Disapprove</span></a>';

                    return $status2 . $status3;
                } else {
                    return "Already Changed";
                }

            })
            ->addIndexColumn()
            ->rawColumns(['action', 'email', 'phone_no', 'balance', 'status'])
            ->make(true);
    }

    public function RequestChangeStatus(Request $request)
    {
        if (empty($request->request_id)) {
            return json_encode(['err_msg' => 'Please select valid request.', 'request_id' => $request->request_id]);
        }
        $requestData = RedeemRequest::findOrFail($request->request_id);
        $userData = User::find($requestData->user_id);
        if ($request->status == 1) {
            $requestData->status = $request->status;
            $requestData->points = $userData->wallet_marketplace;
            $requestData->approval_date = Carbon::now()->format('Y-m-d');
            $userData->wallet_marketplace = 0;

            $walletLogs = [
                'user_id' => $userData->id,
                'points_by' => Auth::user()->id,
                'point' => -$requestData->points
            ];
            WalletLog::create($walletLogs);
            $userData->save();

        } else {
            $requestData->status = $request->status;
        }
        $requestData->save();


        // $send_mail = send_mail($data);
        return json_encode(['status' => $requestData->status, 'msg' => trans("Request status has been updated successfully"), 'request_id' => $request->request_id]);
    }

    public function listResponse(Request $request)
    {
        DB::enableQueryLog();
        DB::statement(DB::raw('set @rownum=0'));
        $bidLists = \App\ResponseBid::with(['bid', 'userDetail', 'userDetail.country_name'])
            ->where('bid_id', $request->id)->get();

        return Datatables::of($bidLists)
            ->addColumn('userEmail', function ($response) {
                //dd($response['country_name']);
                if ($response->userDetail) {
                    return $response->userDetail->email;
                } else {
                    return '-';
                }
            })
            ->addColumn('id', function ($response) {
                //dd($response['country_name']);
                if ($response->is_spam == 1) {
                    return $response->id . ' &nbsp;' . '<span class="badge badge-danger" style="background-color: red;">Spam</span>';
                } else {
                    return $response->id;
                }
            })

           
            ->addColumn('status', function ($response) {

                if ($response->is_accepted == 0)
                    return '<a href="javascript:void(0)"><span
													class="label label-warning ">Not Seen Yet</span></a>';
                elseif ($response->is_accepted == 1)

                    return '<a href = "javascript:void(0)" ><span
													class="label label-primary" > Read for Receiver </span ></a>';
                elseif ($response->is_accepted == 3)

                    return '<a href = "javascript:void(0)" ><span
                                                    class="label label-success" > Booked </span ></a> <br /> <a href = "javascript:void(0)" class="paynow" data-bid="'.$response->id.'"><span
                                                    class="label label-primary paynow"> Pay Now </span ></a>';
                elseif ($response->is_accepted == 4)

                    return '<a href = "javascript:void(0)" ><span
                                                    class="label label-primary" > Paid </span ></a>';                                    
                else
                    return '<a href = "javascript:void(0)" ><span
													class="label label-success" > Open for Negotiation </span ></a>';

            })


          

            ->addIndexColumn()
            ->rawColumns(['id', 'status', 'userEmail'])
            ->make(true);
    }

    public function payNow(Request $request)
    {
       

        $bids = \App\ResponseBid::with(['bid', 'userDetail', 'userDetail.country_name'])->where('id', $bidId)->first();
            if ($bids->is_accepted = 3) {
                $bids->is_accepted = 4;
                $bids->save();
            }

        return response()->json($bidId);
    }

    public function deleteBid(Request $request)
    {
        Bid::where('id', $request->user_id)->delete();

        return json_encode(['msg' => trans("Bid has been deleted successfully"), 'bid_id' => $request->user_id]);
    }

    public function deleteCode(Request $request)
    {
        VouchCode::where('id', $request->user_id)->delete();

        return json_encode(['msg' => trans("Code has been deleted successfully"), 'bid_id' => $request->user_id]);
    }

    public function bidChangeStatus(Request $request)
    {
        if (empty($request->user_id)) {
            return json_encode(['err_msg' => 'Offer id  is required.', 'user_id' => $request->user_id]);
        }
        $user = Bid::findOrFail($request->user_id);
        if ($user->admin_status == 1) {
            $user->admin_status = 0;
            $user->save();

            return json_encode(['status' => $user->userstatus, 'msg' => trans("Bid status has been updated successfully"), 'user_id' => $request->user_id]);
        } else {
            $user->admin_status = 1;
            $user->save();
            $userDetails = User::find($user->user_id);
            $linkName = '<a href="' . url('unpaid-bid') . '">Click here</a>';
            $message = "Your opportunity is approved by our team, please " . $linkName . " to pay the activation fee.";
            $link = url('unpaid-bid');
            CommonHelper::sendNotification($userDetails, $message, $link);

            // $send_mail = send_mail($data);
            return json_encode(['status' => $user->admin_status, 'msg' => trans("Bid status has been updated successfully"), 'user_id' => $request->user_id]);
        }
    }


    //add by ram 23 sep 
    public function addper(Request $request)
    {
       $data = array(
            'per' => $request->percentage
       ); 
       $rst = DB::table('bids')->where('id',$request->responce_id)->update($data);
       return redirect("controlpanel/bid-list");
    }
}
