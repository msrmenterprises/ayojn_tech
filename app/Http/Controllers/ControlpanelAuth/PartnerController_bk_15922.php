<?php

namespace App\Http\Controllers\ControlpanelAuth;

use App\Http\Controllers\Controller;
use App\Offer;
use App\SponsorrSpecify;
use App\SponsorrSpecifyList;
use App\User;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PartnerController extends Controller
{
    public function partner()
    {
        return view('controlpanel.partner.list');
    }

    public function listUser($type = '')
    {
        $user_type = '';

        DB::enableQueryLog();
        DB::statement(DB::raw('set @rownum=0'));
        $userlist = User::select('*', DB::raw('@rownum  := @rownum  + 1 AS rownum'))
            ->where('id', '!=', Auth::user()->id)->where('sponsor_type', '=', 3)
            ->orderBy('id', 'desc')->get();

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
                        strtotime($response->created_at));

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

            ->addColumn('refer_by_user', function ($response) {
                //dd($response['country_name']);
                if ($response->refer_by) {
                    return 'Refer By: <br>' . $response->referBy->email;
                } else {
                    return 'Self Registered';
                }
            })
            ->addColumn('referral_count', function ($response) {
                //dd($response['country_name']);
                //$referral_count = ReferAFriend::where('user_id', $response->id)->count();
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
                $edit = '<a href="' . url('controlpanel/editusers/' . $userlist->id) . '" title="Edit" class="btn btn-sm btn-primary pull-left"><i class="fa fa-edit"></i></a>';
                $usertype = '<a href="' . url('controlpanel/edituser-type/' . $userlist->id) . '" title="Edit Usertype" class="btn btn-sm btn-warning pull-left"><i class="fa fa-exchange"></i></a>';
                $delete = '<a href="javascript:void(0)" title="Delete" class="btn btn-sm btn-danger pull-left" onclick="deleteUser(' . $userlist->id . ')"><i class="fa fa-remove"></i></a>';

                return $edit . $delete;
            })
            ->addIndexColumn()
            ->rawColumns(['email', 'check', 'action', 'refer_by_user', 'userstatus', 'referral_count'])
            ->make(true);
    }

    public function userReviewList()
    {
        return view('controlpanel.partner.review-list');
    }

    public function listReviewUser()
    {

        DB::enableQueryLog();
        DB::statement(DB::raw('set @rownum=0'));
        $userlist = User::select('*', DB::raw('@rownum  := @rownum  + 1 AS rownum'))
            ->where('id', '!=', Auth::user()->id)->where('sponsor_type', '=', 3)
            ->orderBy('id', 'desc')->where('is_edited', 1)->get();

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
                        strtotime($response->created_at));

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

            ->addColumn('refer_by_user', function ($response) {
                //dd($response['country_name']);
                if ($response->refer_by) {
                    return 'Refer By: <br>' . $response->referBy->email;
                } else {
                    return 'Self Registered';
                }
            })
            ->addColumn('referral_count', function ($response) {
                //dd($response['country_name']);
                //$referral_count = ReferAFriend::where('user_id', $response->id)->count();
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
                $edit = '<a href="' . url('controlpanel/editusers/' . $userlist->id) . '" title="Edit" class="btn btn-sm btn-primary pull-left"><i class="fa fa-edit"></i></a>';
                $usertype = '<a href="' . url('controlpanel/edituser-type/' . $userlist->id) . '" title="Edit Usertype" class="btn btn-sm btn-warning pull-left"><i class="fa fa-exchange"></i></a>';
                $delete = '<a href="javascript:void(0)" title="Delete" class="btn btn-sm btn-danger pull-left" onclick="deleteUser(' . $userlist->id . ')"><i class="fa fa-remove"></i></a>';

                return $edit . $delete;
            })
            ->addIndexColumn()
            ->rawColumns(['email', 'check', 'action', 'refer_by_user', 'userstatus', 'referral_count'])
            ->make(true);
    }

    public function Offers()
    {
        return view('controlpanel.partner.offers');
    }

    public function listOffer()
    {
        DB::enableQueryLog();
        DB::statement(DB::raw('set @rownum=0'));
        $bidLists = Offer::with('industry')->with(['country', 'partner'])->get();

        return Datatables::of($bidLists)
            ->addColumn('country_name', function ($response) {
                //dd($response['country_name']);
                if ($response->available_in) {
                    return $response->country->country_name;
                } else {
                    return '-';
                }
            })->addColumn('partner', function ($response) {
                //dd($response['country_name']);
                if ($response->partner_id) {
                    return (!empty($response->partner)) ? $response->partner->email : '-';
                } else {
                    return '-';
                }
            })
            ->addColumn('admin_status', function ($response) {
                if ($response->admin_status == 1)
                    return '<a  href="javascript:void(0)"  onclick="changeStatus(' . $response->id . ')"><span class="label label-success my_lable_class" id="cust_id' . $response->id . '">Approved</span></a>';
                else
                    return '<a  href="javascript:void(0)"   onclick="changeStatus(' . $response->id . ')"><span class="label label-danger my_lable_class" id="cust_id' . $response->id . '"  >Disapproved</span></a>';
            })->addColumn('function', function ($response) {
                if (!empty($response->industry))
                    return $response->industry->name;
                else
                    return '-';
            })
            ->addColumn('action', function ($response) {
                $link = url('offer-share') . "/" . $response->share_id;
                $delete = '<a href="javascript:void(0)" title="Delete" class="btn btn-sm btn-danger pull-left" onclick="deleteUser(' . $response->id . ')"><i class="fa fa-remove"></i></a><span id="opportunity_' . $response->id . '" class="share-course-filed"
										  style="display: none;">' . $link . '</span><a
										href="javascript:void(0)" class="btn btn-primary read-more-btn  share-opp-btn"
										onclick="copyToClipboard(\'#opportunity_' . $response->id . '\')">Copy Web link</a>';

                return $delete;
            })
            ->addIndexColumn()
            ->rawColumns(['country_name', 'partner', 'action', 'admin_status'])
            ->make(true);
    }

    public function DeleteOffers(Request $request)
    {
        if (isset($request->user_id) && $request->user_id == '') {
            return json_encode(['err_msg' => 'User id  is required.', 'editItem_id' => $request->user_id]);
        }
        $user = Offer::findOrFail($request->user_id);

        $user->delete();


        return json_encode(['msg' => trans("Offer has been deleted successfully"), 'user_id' => $request->user_id]);
    }
}
