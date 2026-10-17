<?php

namespace App\Http\Controllers\ControlpanelAuth;

use App\Country;
use App\Exports\ArrayExport;
use App\Http\Controllers\Controller;
use App\Industry;
use App\Imports\AdminUserRowsImport;
use App\ReferAFriend;
use App\SponsorrSpecify;
use App\SponsorrSpecifyList;
use App\User;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Validator;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    private const USER_EXPORT_HEADINGS = [
        'Email',
        'Sponsor Type',
        'Sponsor For',
        'Sponsor Deal Size',
        'Spex Plateform',
        "Sponsor's industry",
        'Country of origin',
        'Country Directed To',
        'Register Date',
        'Last Login',
    ];

    private const REFERRAL_EXPORT_HEADINGS = [
        'Name',
        'User Email',
        'Mobile No',
        'Email',
        'Refer Date',
    ];

    public function index()
    {
        return view('controlpanel.user.userlist');
    }


    /*Edit Profile View PAge */
    public function editprofile()
    {
        $user = Auth::guard('controlpanel')->user();

//        dd($user->toArray());
        return view('controlpanel.user.editprofile', compact('user'));
    }

    /*function to update the profile*/
    public function updateprofile(Request $request)
    {
        try {
            $id = $request->get('userid');
            $validator = Validator::make($request->all(), [
                'username' => 'required',
                'phone_no' => [
                    Rule::unique('users')->where(function ($q) use ($id) {
                        $q->where('deleted_at', null)->where('id', '!=', $id);
                    })
                ],
                'email' => [
                    'required',
                    Rule::unique('users')->where(function ($q) use ($id) {
                        $q->where('deleted_at', null)->where('id', '!=', $id);
                    })
                ],
            ], [
                'username.required' => 'Full Name is required',
                'email.required' => 'Email id is required',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            } else {

                $data = [
                    'name' => $request->get('username'),
                    //'email'=>$request->get('email'),
                    'phone_no' => $request->get('phone_no'),
                    'gender' => $request->get('gender'),
                    'company_name' => $request->get('company_name'),
                    'address' => $request->get('address'),
                    'about_me' => $request->get('about_me'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                if ($request->hasFile('profile_image')) {
                    $image = $request->file('profile_image');
                    $profile_image = time() . '.' . $image->getClientOriginalExtension();
                    $destinationPath = public_path('/profile_image');
                    $image->move($destinationPath, $profile_image);
                    $data['profile_pic'] = $profile_image;
                }
                $where = [
                    'id' => $request->get('userid'),
                ];
                $userid = User::where($where)->update($data);

                return redirect()->back()->with('success', 'Profile Updated Successfully');
            }
        } catch (\exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /*Change Password View */
    public function changepassword()
    {
        return view('controlpanel.user.changepassword');
    }

    /*Update password */
    public function updatepassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required|same:new_password',
        ], [
            'old_password.required' => 'Old password is required',
            'new_password.required' => 'New password is required',
            'confirm_password.required' => 'Confirm password is required',
        ]);
        if ($validator->fails()) {
            $result = [
                'status' => 0,
                'msg' => implode(",", $validator->errors()->all())
            ];
        } else {
            $user = Auth::guard('controlpanel')->user();
            //$oldpassword=Hash::make($request->old_password);
            if (Hash::check($request->old_password, $user->password)) {
                $data = [
                    'password' => Hash::make($request->new_password)
                ];
                $where = [
                    'id' => $user->id,
                ];
                $userid = User::where($where)->update($data);
                if (isset($userid)) {
                    Auth::logout();

                    return redirect('/controlpanel/')
                        ->with('status', 'Password updated successfully');
                } else {
                    return redirect()->back()
                        ->withErrors(['something went wrong'])
                        ->withInput();
                }
            } else {
                return redirect()->back()
                    ->withErrors(['Old password does not match with your record'])
                    ->withInput();
            }
        }
        //return json_encode($result);
    }

    public function userList()
    {        
       return view('controlpanel.user.userlist');
    }

    public function userReviewList()
    {

        return view('controlpanel.user.review-list');
    }

    public function listReviewUser($type = '')
    {
        $user_type = '';

        DB::enableQueryLog();
        DB::statement(DB::raw('set @rownum=0'));

        $userlist = User::with('country_name', 'country_sponsorr', 'industry', 'specification')
            ->select('*', DB::raw('@rownum  := @rownum  + 1 AS rownum'))->where('id', '!=', Auth::user()->id)
            ->orderBy('id', 'desc')->where('is_edited', 1)->where('sponsor_type', '!=', 3)->get();




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
                        strtotime($response->created_at)) . "<br>Valid Upto: " . $disapproveDate;

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
            ->editColumn('sponsor_type', function ($response) {
                if ($response->sponsor_type == 1)
                    return "Offer Sponsorship";
                elseif ($response->sponsor_type == 2)
                    return "Manage Or Receive Sponsorship";


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
                if ($response->refer_by) {
                    return 'Refer By: <br>' . $response->referBy->email;
                } else {
                    return 'Self Registered';
                }
            })
            ->addColumn('country_sponsorr', function ($response) {
                //dd($response['country_name']);
                if ($response->country_sponsorr) {
                    return $response->country_sponsorr->country_name;
                } else {
                    return '-';
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

                return $edit . $usertype . $delete;
            })
            ->addIndexColumn()
            ->rawColumns(['email', 'check', 'action', 'refer_by_user', 'sponsor_type', 'specification', 'userstatus', 'sponsor_budget', 'country_name', 'referral_count', 'city_name'])
            ->make(true);
    }

    function exportUser(request $request)
    {
        $userlist = User::with('country_name', 'country_sponsorr', 'industry', 'specification')
            ->where('id', '!=', Auth::user()->id)->orderBy('id', 'desc')->get();
        $userData = [];
        if (! empty($userlist->first())) {
            foreach ($userlist as $key => $value) {
                $userData[$key]['Email'] = $value->email;
                if ($value->sponsor_type == 1) {
                    $userData[$key]['Sponsor Type'] = 'Offer Sponsorship';
                } else {
                    $userData[$key]['Sponsor Type'] = 'Manage Or Receive Sponsorship';
                }
                $userData[$key]['Sponsor For'] = $value->sponsor_for;
                $userData[$key]['Sponsor Deal Size'] = $value->sponsor_budget;
                $getSpecifyList = SponsorrSpecify::where('user_id', $value->id)->get();
                $specifylist = SponsorrSpecifyList::get();
                if (isset($getSpecifyList) && $getSpecifyList != null) {
                    $specifyarray = [];
                    foreach ($getSpecifyList as $userspecify) {
                        foreach ($specifylist as $item) {
                            if ($userspecify->specify_name == $item->id) {
                                $specifyarray[] = $item->specify_name;
                            }
                        }
                    }
                    $userData[$key]['Spex Plateform'] = implode(',', $specifyarray);
                } else {
                    $userData[$key]['Spex Plateform'] = '';
                }
                if ($value->sponsor_industry == 'test' || $value->sponsor_industry == '') {
                    $userData[$key]["Sponsor's industry"] = '';
                } else {
                    $userData[$key]["Sponsor's industry"] = $value->industry
                        ? $value->industry->name
                        : '';
                }
                if ($value->country_name) {
                    $userData[$key]["Country of origin"] = $value->country_name->country_name;
                } else {
                    $userData[$key]["Country of origin"] = '';
                }
                if ($value->country_sponsorr) {
                    $userData[$key]["Country Directed To"] = $value->country_sponsorr->country_name;
                } else {
                    $userData[$key]["Country Directed To"] = '';
                }
                $userData[$key]["Register Date"] = Date('d-m-Y', strtotime($value->created_at));
                $userData[$key]["Last Login"] = $value->last_login_at;
            }
        }

        return Excel::download(
            new ArrayExport($userData, self::USER_EXPORT_HEADINGS),
            'User List.xlsx'
        );
    }

    function exportReferUser(request $request)
    {
        $userlist = ReferAFriend::with('userDetail')->orderBy('id', 'desc')->get();

        $userData = [];
        foreach ($userlist as $key => $value) {
            $user = $value->userDetail;
            $userData[$key] = [
                'Name' => $user ? $user->name : '',
                'User Email' => $user ? $user->email : '',
                'Mobile No' => $user ? $user->phone_no : '',
                'Email' => $value->email_address,
                'Refer Date' => date('d-m-Y', strtotime($value->created_at)),
            ];
        }

        return Excel::download(
            new ArrayExport($userData, self::REFERRAL_EXPORT_HEADINGS),
            'User Refer List.xlsx'
        );
    }

    public function importUser()
    {
        return view('controlpanel.user.upload_user');
    }

    public function importExcel(request $request)
    {
        $validator = Validator::make($request->all(), [
            'lead_file' => 'required|file|mimes:csv,txt,xls,xlsx',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $import = new AdminUserRowsImport();
        Excel::import($import, $request->file('lead_file'));
        $data = $import->rows();
        if ($data->isEmpty()) {
            return back()->with('error', 'No importable users found.');
        }

        $defaultUserType = DB::table('user_type')->orderBy('id')->value('id');
        if (! $defaultUserType) {
            return back()->with('error', 'No user type is configured for imported users.');
        }

        $message = '';
        foreach ($data as $key => $value) {
            $line = $key + 2;
            if (! isset($value['sponsorr_type']) || trim($value['sponsorr_type']) === '') {
                continue;
            }

            $userType = $value['sponsorr_type'] == 'Manage Or Receive Sponsorship' ? 2 : 1;
            $email = $value['email'];
            if (User::where('email', $email)->exists()) {
                $message .= " Line No $line : User already exists";
                continue;
            }

            $country = Country::where('country_name', $value['country_of_origin'])->first();
            if (! $country) {
                $message .= " Line No $line : Country not Found";
                continue;
            }

            $countryDirectedTo = Country::where('country_name', $value['country_directed_to'])->first();
            if (! $countryDirectedTo) {
                $message .= " Line No $line : Sponsor Country not Found";
                continue;
            }

            $industry = Industry::where('name', $value['sponsors_industry'])->first();
            if (! $industry) {
                $message .= " Line No $line : Industry not Found";
                continue;
            }

            $user = new User([
                'name' => 'test',
                'email' => $email,
                'sponsor_type' => $userType,
                'country' => $country->country_code,
                'sponsor_for' => $value['sponsor_for'],
                'sponsor_budget' => $value['sponsor_deal_size'],
                'password' => Hash::make('123456'),
                'sponsor_industry' => $industry->id,
                'userstatus' => 1,
                'sponsor_country' => $countryDirectedTo->country_code,
            ]);
            $user->user_type = $defaultUserType;
            $user->email_verified = 1;
            $user->save();

            $specification = SponsorrSpecifyList::where('specify_name', $value['spex_platform'])->first();
            if ($specification) {
                SponsorrSpecify::create([
                    'specify_name' => $specification->id,
                    'user_id' => $user->id,
                ]);
            } else {
                $message .= " Line No $line : Spex Platform not inserted";
            }
        }

        if ($message === '') {
            return back()->with('success', 'Users Upload successfully!');
        }

        return back()->with('error', $message);
    }

    public function dataExport()
    {
        $rows = SponsorrSpecifyList::all()->map(function ($item) {
            return [
                'Spex Platform' => $item->specify_name,
                'Sponsor For' => $item->sponsorr_type,
            ];
        })->all();

        return Excel::download(
            new ArrayExport($rows, ['Spex Platform', 'Sponsor For']),
            'Sample Data List.xlsx'
        );
    }
}
