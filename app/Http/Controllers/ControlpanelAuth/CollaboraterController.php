<?php

namespace App\Http\Controllers\ControlpanelAuth;

use App\Http\Controllers\Controller;
use App\Models\Collaborate;
use App\Models\ColResponse;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use DataTables;
use DB;
class CollaboraterController extends Controller
{
    //
    use ApiResponse;

    public function Collaborators()
    {
        return view('controlpanel.collaborators');
    }

    public function directpay()
    {
        $logs = DB::table("direct_payment")->get();
        return view('controlpanel.directpay')->with('log',$logs);
    }

    public function xsquarelogin(){

            
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://qa-core-api.xsquare.biz/customer/login',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
            "email": "devops@ayojn.com",
            "password": "Ayojn@2024"
        }',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response);
}

public function xsquare_status($id)
{
    
    $curl = curl_init();
    $token = session()->get('payment_token');
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://qa-core-api.xsquare.biz/transaction/collection-details?collection_id='.$id,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer '.$token 
      ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
     return json_decode($response);

}

    public function directpay_status(Request $request)
    {
        $xsquare = $this->xsquarelogin();
        $request->session()->put('payment_token', $xsquare->details->access_token);
        $request->session()->put('payment_refresh_token', $xsquare->details->refresh_token);
        $collection_id = $request->collection_id;
        $check = $this->xsquare_status($collection_id);
        if($check->status_code == '200')
        {
            DB::table('direct_payment')->where('collection_id', $collection_id)->update(array('status_desc'=>json_encode($check->details->pay_status)));
        }
        //print_r($check->details->pay_status); die;

        return redirect()->back();
    }

    public function ListCollaborators()
    {
        $bidLists = Collaborate::with('organizer')->get();

        return Datatables::of($bidLists)
            ->addColumn('organizer', function ($response) {
                //dd($response['country_name']);
                $url = url('controlpanel/collaborator/') . "/" . base64_encode($response->id);

                $attendee = '&nbsp;&nbsp;<a href="' . $url . '" > <span class="badge badge-light" >' . $response->attendes->count() . '</span ></a > ';
                if ($response->organizer) {
                    return $response->organizer->entity . $attendee;
                } else {
                    return '-';
                }
            })->addColumn('geo_focus', function ($response) {
                if ($response->country_name) {
                    return $response->country_name->country_name;
                } else {
                    return '-';
                }
            })->addColumn('with_local_focus', function ($response) {
                if ($response->with_local_focus == 1) {
                    return "Yes";
                } else {
                    return 'No';
                }
            })->addColumn('remote', function ($response) {
                if ($response->remote_opportunity == 1) {
                    $string = '';
                    if (!empty($response->remote_country) && !empty($response->remote_country_name)) {
                        $string .= $response->remote_country_name->country_name;
                    }
                    if (!empty($response->remote_city) && !empty($response->remote_city_name)) {
                        $string .= '<hr style="margin-top: 0;margin-bottom: 0">' . $response->remote_city_name->name;
                    }
                    return $string;
                } else {
                    return "No";
                }
                if ($response->country_name) {
                    return $response->country_name->country_name;
                } else {
                    return '-';
                }
            })->addColumn('industry', function ($response) {
                //dd($response['country_name']);
                if ($response->industry) {
                    return $response->industry->name;
                } else {
                    return '-';
                }
            })
            ->addColumn('status', function ($response) {
                if ($response->status == 1)
                    return '<a  href="javascript:void(0)"  onclick="changeStatus(' . $response->id . ')"><span class="label label-success my_lable_class" id="cust_id' . $response->id . '">Approved</span></a>';
                if ($response->status == 2)
                    return '<a  href="javascript:void(0)"  onclick="changeStatus(' . $response->id . ')" ><span class="label label-danger my_lable_class" id="cust_id' . $response->id . '"  >Disapproved</span></a>';
                else
                    return '<a  href="javascript:void(0)"  onclick="changeStatus(' . $response->id . ')" ><span class="label label-danger my_lable_class" id="cust_id' . $response->id . '"  >Pending</span></a>';
            })
            ->addColumn('action', function ($response) {
                $link = url('share-col') . "/" . $response->share_id;
                $delete = '<a href="javascript:void(0)" title="Delete" class="btn btn-sm btn-danger pull-left" onclick="deleteUser(' . $response->id . ')"><i class="fa fa-remove"></i></a><span id="opportunity_' . $response->id . '" class="share-course-filed"
										  style="display: none;">' . $link . '</span><a
										href="javascript:void(0)" class="btn btn-primary read-more-btn  share-opp-btn"
										onclick="copyToClipboard(\'#opportunity_' . $response->id . '\')">Copy Web link</a>';

                return $delete;
            })
            ->addIndexColumn()
            ->rawColumns(['organizer', 'status', 'action', 'industry', 'country_name', 'remote'])
            ->make(true);
    }

    public function collChangeStatus(Request $request)
    {
        $event = Collaborate::findOrFail($request->event_id);
        if (!empty($event)) {
            if ($event->status == 0) {
                $event->status = 1;
            } else {
                $event->status = 0;
            }
            $event->save();

            return json_encode(['status' => $event->status, 'msg' => trans("Collaboration status has been updated successfully"), 'user_id' => $request->id]);
        } else {
            return json_encode(['err_msg' => 'User id  is required.', 'user_id' => $request->id]);
        }
    }

    public function DeleteColloborate(Request $request)
    {
        if (isset($request->user_id) && $request->user_id == '') {
            return json_encode(['err_msg' => 'User id  is required.', 'editItem_id' => $request->user_id]);
        }
        $user = Collaborate::findOrFail($request->user_id);

        $user->delete();


        return json_encode(['msg' => trans("Collaborate has been deleted successfully"), 'user_id' => $request->user_id]);
    }

    public function ColloborateList(Request $request, $eventId)
    {
        $eventId = base64_decode($eventId);
        $attendeedList = ColResponse::with(['user'])->where('col_id', $eventId)->get();

        return view('controlpanel.collaborate-list', compact('attendeedList'));
    }

    public function all_beneficiary(Request $request)
    {
        
        $log = DB::table('beneficiary')->get();
        return view('controlpanel.beneficiary_list', compact('log'));
    }


    public function all_beneficiary_payment(Request $request)
    {
        if($request->id > 0){
            $log = DB::table("direct_payment")->where('beneficiary_id',$request->id)->get();
        }
        else{
            $log = DB::table("direct_payment")->get();
        }
        

        return view('controlpanel.beneficiary_payment_list', compact('log'));
    }

    public function query_get_admin(Request $request){
        $partner_id = $request->post('partner_id');
        $data = DB::table('query_communication')
        ->join('users', 'users.id', '=', 'query_communication.partner_id')
        ->join('users as u', 'u.id', '=', 'query_communication.user_id')
        ->where('offer_order_item_id',$request->post('id'))
       
        ->orderby('level','asc')
        ->get();

        echo json_encode($data);
    }

    public function show_feedback_admin(Request $request)
    {
        $id = $request->post('id');
        $result = DB::table("offers_order_item_feedback")->where('offer_item_id',$id)->latest()->first();
        if($result){
            echo json_encode($result);
        }
        
    }  


}
