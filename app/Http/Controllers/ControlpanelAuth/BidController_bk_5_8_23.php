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
            'vouch_code' => [
                'required',
            ],
        ], [
            'vouch_code.required' => 'Vouch Code is required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        } else {
            $todayDate = Carbon::now();
            $expeiryDate = $todayDate->addDays(7);
            VouchCode::create(['vouch_code' => $request->vouch_code, 'expeiry_date' => $expeiryDate->format('Y-m-d')]);

            return redirect('controlpanel/codes')->with('success', 'Vouch code added Successfully');
        }
    }

    public function VouchUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vouch_code' => [
                'required',
            ],
        ], [
            'vouch_code.required' => 'Vouch Code is required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        } else {
            $todayDate = Carbon::now();
            $expeiryDate = $todayDate->addDays(7);
            $vouchData = VouchCode::find($request->code_id);
            $vouchData->vouch_code = $request->vouch_code;
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
            ->addColumn('sponsor_industry', function ($response) {
                if ($response->sponsor_industry == '') {
                    return "-";
                } else {
                    return @$response->industry->name;
                }
            })
            ->addColumn('action', function ($response) {
                $link = url('share') . "/" . $response->share_id;
                $delete = '<a href="javascript:void(0)" title="Delete" class="btn btn-sm btn-danger pull-left" onclick="deleteUser(' . $response->id . ')"><i class="fa fa-remove"></i></a><span id="opportunity_' . $response->id . '" class="share-course-filed"
										  style="display: none;">' . $link . '</span><a
										href="javascript:void(0)" class="btn btn-primary read-more-btn  share-opp-btn"
										onclick="copyToClipboard(\'#opportunity_' . $response->id . '\')">Copy Web link</a>';

                return $delete;
            })
            ->editColumn('status', function ($response) {
                if ($response->admin_status == 1)
                    return '<a  href="javascript:void(0)"  onclick="changeStatus(' . $response->id . ')"><span class="label label-success my_lable_class" id="cust_id' . $response->id . '">Approved</span></a>';
                else
                    return '<a  href="javascript:void(0)"   onclick="changeStatus(' . $response->id . ')"><span class="label label-danger my_lable_class" id="cust_id' . $response->id . '"  >Disapproved</span></a>';
            })
            ->addIndexColumn()
            ->rawColumns(['country_name', 'bider_email', 'sponsor_industry', 'responseCount', 'action', 'status'])
            ->make(true);
    }

    public function listCode()
    {
        DB::enableQueryLog();
        DB::statement(DB::raw('set @rownum=0'));
        $codes = VouchCode::get();

        return Datatables::of($codes)
            ->addColumn('action', function ($response) {
                $url = url('controlpanel/edit-code/') . "/" . $response->id;
                $edit = '<a href="' . $url . '" title="Edit" class="btn btn-sm btn-info pull-left" ><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;&nbsp;';
                $delete = '<a href="javascript:void(0)" title="Delete" class="btn btn-sm btn-danger pull-left" onclick="deleteUser(' . $response->id . ')"><i class="fa fa-remove"></i></a>';

                return $edit . $delete;
            })
            ->addIndexColumn()
            ->rawColumns(['action'])
            ->make(true);
    }

    public function listRedeemRequest()
    {
        DB::enableQueryLog();
        DB::statement(DB::raw('set @rownum=0'));
        $redeemRequests = RedeemRequest::with('userDetail')->get();

        return Datatables::of($redeemRequests)
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
                    return $response->userDetail->wallet_balance;
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
            $requestData->points = $userData->wallet_balance;
            $requestData->approval_date = Carbon::now()->format('Y-m-d');
            $userData->wallet_balance = 0;

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
 //dd($response);
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
        $bidId = $request->bidId;

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
}
