<?php

namespace App\Http\Controllers\UserAuth;

use App\Http\Controllers\Controller;
use App\Models\NotificationSetting;
use App\User;
use DB;
use App\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon;
use App\Traits\ApiResponse;


class SettingController extends Controller
{
    use ApiResponse;
    //
    public function getNotificationSettings(Request $request)
    {
        $notificationData = NotificationSetting::where('user_id', Auth::user()->id)->first();

        return view('notification-setting', compact('notificationData'));
    }

    public function updateNotificationSettings(Request $request)
    {
//        dd($request->all());
        $notificationData = NotificationSetting::where('id', $request->id)->first();
        if (! empty($notificationData)) {
            if(!empty($request->inApp) && $request->inApp == 1){
                $notificationData->inapp = 1;
            }else{
                $notificationData->inapp = 0;
            }
            if(!empty($request->emailNotification) && $request->emailNotification == 1){
                $notificationData->email = 1;
                $notificationData->days = $request->days;
            }else{
                $notificationData->email = 0;
                $notificationData->days = '';
            }
            $notificationData->save();
            return redirect()->back()->with('success', 'Notification settings successfully updated');
        } else {
            return redirect()->back()->withErrors("Notification Data not found");
        }
    }

    public function exitingUser()
    {
        $users = User::get();
        foreach ($users as $user) {
            $notifications = new NotificationSetting();
            $notifications->user_id = $user->id;
            $notifications->inapp = 1;
            $notifications->save();
        }
    }


    public function getwallet(Request $request)
    {
       
        $logs = DB::table("wallet_logs")->where('user_id',Auth::user()->id)->get();
        $fieldset ="<table class='table_id display table table-bordered' id='table_id'><thead><th>ID</th><th>Transaction By</th><th>Point</th><th>Direction</th><th>Wallet Type</th><th>Transaction Date</th></thead>";
        $y=1;
        foreach ($logs as $key => $log) 
        {
           if($log->point_type=="DR"){$ptype="<span class='badge badge-danger' style='background-color:#dc3545;'><i class='fa fa-minus'></i></span> Out";}
           if($log->point_type=="CR"){$ptype="<span class='badge badge-success' style='background-color:#28a745;'><i class='fa fa-plus'></i></span> In";} 
           
           $tras_by = "";
           $id = base64_encode($log->trans_id);
           if($log->points_by ==1){$tras_by = "unpaid_opportunity"." <a href='".url('bid-response',$id)."'>click to view</a>";}
           if($log->points_by ==2){$tras_by = "pay_opportunity"." <a href='".url('marketplace-history')."'>click to view</a>";}
           if($log->points_by ==3){$tras_by = "hashtag_vouch"." <a href='".url('vouches',$log->trans_id)."'>click to view</a>";}
           if($log->points_by ==4){$tras_by = "marketplace_payment".$log->point." for registration by refer code.";}
           if($log->points_by ==5){$tras_by = "build_hashtag"." <a href='".url('opportunity')."'>click to view</a>";}
           if($log->points_by ==6){$tras_by = "build_hashtag_renew"." <a href='".url('bid-response',$id)."'>click to view</a>";}
           if($log->points_by ==7){$tras_by = "partner_offer"." <a href='".url('marketplace-history')."'>click to view</a>";}
           if($log->points_by ==8){$tras_by = "collaboration"." <a href='".url('vouches',$log->trans_id)."'>click to view</a>";}
           if($log->points_by ==9){$tras_by = "pay_bid_rec".$log->point." for registration by refer code.";}
           if($log->points_by ==10){$tras_by = "direct_payment"." <a href='".url('opportunity')."'>click to view</a>";}
           if($log->points_by ==11){$tras_by = "referal_earn"." <a href='".url('opportunity')."'>click to view</a>";}
           
           //voucher reedeem history
           if($log->points_by ==12){
                $voucher = DB::table('vouch_codes')->where('id', $log->trans_id)->first();
                $tras_by = "Voucher Reedeem - " . (($voucher && isset($voucher->vouch_code)) ? $voucher->vouch_code : '');
            }

           if($log->points_by ==13){$tras_by = "You received $".$log->point." for registration by refer code.";}
           
           
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
        return view('wallet-log', compact('fieldset'));
    }

    public function getmarketplace(Request $request)
    {
        $fieldset = "";
        $orders = DB::table("offers_order")->where('user_id',Auth::user()->id)->orderby('id','desc')->get();
        $x=1;
        foreach($orders as $order)
        {
            $cancel_data =""; $cancel_class ="";
            $payment_status = DB::table('direct_payment')->where('refrence_id',$order->id)->where('user_id',Auth::user()->id)->first();
            $items = DB::table("offers_order_item")->where('order_id',$order->id)->get();
            $y=1;
            $table ="<table class='table_id display table table-bordered'><thead><th>Sr.</th><th>Deal Type</th><th>Deal Name</th><th>Quantity</th><th>Avl</th><th>Amount</th><th>Link</th><th>Status</th><th>Payment Status</th></thead>";    
            foreach ($items as $key => $item) 
            {

            $offers = Offer::with('industry')->where('id', $item->offer_id)->first();    

            if($item->status==0){
                                
                $status = "Pending";
            }
            elseif($item->status==1){
          
                $status = "Progress";
            }
            elseif($item->status==2){
                $status = '<a data-id="'.$item->id.'" data-partner="'.$offers->partner_id.'" class="btn btn-primary query_button btn-sm" title="query"><i class="fa fa-question-circle"></i> Query</a>';
            }
            elseif($item->status==3){
                $status = '<a data-id="'.$item->id.'" data-partner="'.$offers->partner_id.'" class="btn btn-primary query_button btn-sm" title="query"><i class="fa fa-question-circle"></i> Query</a>';
                
            }
            elseif($item->status==4){
         
                $status ='<a data-id="'.$item->id.'" data-partner="'.$offers->partner_id.'" class="btn btn-primary query_button btn-sm" title="query"><i class="fa fa-question-circle"></i> Query</a>
                <a data-id="'.$item->id.'" class="btn btn-success feedback_button btn-sm" title="Feedback"><i class="fa fa-comments"></i> Feedback</a>';
            
              
            }
            elseif($item->status==5){
               
               $status ='<a data-id="'.$item->id.'" data-partner="'.$offers->partner_id.'" class="btn btn-primary query_button btn-sm" title="query"><i class="fa fa-question-circle"></i> Query</a>
                <a data-id="'.$item->id.'" class="btn btn-success feedback_button btn-sm" title="Feedback"><i class="fa fa-comments"></i> Feedback <i class="fa fa-check"></i></a>';
            }

            if($offers->refund == 1 && $item->cancel_status !=1){
                $todate =  \Carbon\Carbon::now()->format('Y-m-d');  
                $buydate = date('Y-m-d',strtotime($item->created_at. ' + 15 days'));

                if($buydate > $todate){
                    $status .= ' <a data-id="'.$item->id.'" class="btn btn-sm btn-danger cancel_btn"><i class="fa fa-close"></i> Cancel</a>';
                }
            }

            if($item->cancel_status == 1){
                $cancel_class ="style='background-color:#ffe5e5'";
                $cancel_data = "<tr><td colspan='9' style='background-color:#ffb9b9;'><b>Cancel Date: </b>".$item->cancel_date.", <b>Cancel Reason: </b>" . $item->cancel_reason."</td></tr>";
            }


            if($item->final_amt > 0 ){$oamt = $item->final_amt;}else{$oamt ="<span style='color:red; font-size:14px;'>Free</span>";}

                
                $table .= "<tr $cancel_class>
                <td width='1%'>$y</td>
                <td width='8%'>$offers->identity</td>
                <td width='25%'>$offers->title</td>
                <td width='5%'>$item->qty</td>
                <td width='5%'>$offers->available_in</td>
                <td width='2%'>$oamt</td>
                <td width='8%'><a href='$offers->weblink'>Click here</a></td>
                <td width='20%'>$status</td>
                <td width='15%'>" . (isset($payment_status->status_desc) ? $payment_status->status_desc : '') . "</td>
                </tr> $cancel_data";
                
                $y++;
            }
            $table .="</table>";  
            $date = date('m/d/Y H:i:s', strtotime($order->created_at));
            $fieldset .= "<fieldset><legend> <span class='pull-right'>Date: $date</span> Wallet used:$order->wallet</legend>
            $table
            </fieldset>";

            $x++;  
        }

        //$table = DB::table("offers_order_item")->get();
        //$notificationData = NotificationSetting::where('user_id', Auth::user()->id)->first();

        return view('marketplace-history', compact('fieldset'));
    }

    public function query_get(Request $request){
        $partner_id = $request->post('partner_id');
        $item_id = $request->post('id');
        $data = DB::table('query_communication')
        ->join('users', 'users.id', '=', 'query_communication.partner_id')
        ->join('users as u', 'u.id', '=', 'query_communication.user_id')
        ->where('offer_order_item_id', $request->post('id'))
        ->when($request->post('from') == "user", function ($data) use ($partner_id,$item_id) {
            $data->where('query_communication.user_id', Auth::user()->id);
            $data->orwhere('query_communication.user_id', $partner_id);
            $data->where('offer_order_item_id',$item_id);
        })
        ->when($request->post('from') == "partner", function ($data) {
            $data->where('query_communication.partner_id', Auth::user()->id);
        })
        ->orderby('level','asc')
        ->get();

        echo json_encode($data);
    }

    public function query_store(Request $request)
    {
       $query_id = $request->post('query_id');
       $partner_id = $request->post('partner_id');
       $query_msg = $request->post('query_msg');

        if($request->post('from')=='user'){
            $side = 'right';
        }else{
            $side = 'left';
        }

       $level = DB::table('query_communication')->where('offer_order_item_id',$query_id)->max('level');
       $data = array('partner_id'=>$partner_id, 'user_id' =>Auth::user()->id, 'offer_order_item_id' =>$query_id, 'query'=>$query_msg, 'type' => $side, 'level' => $level+1);
       $insert = DB::table('query_communication')->insert($data);
       if($insert){
        $stt = DB::table('offers_order_item')->where('id',$query_id)->value('status');
        if($stt <= 2)
        {
            DB::table('offers_order_item')->where('id',$query_id)->update(array('status'=> 3));
        }
        echo "1";
       }else{
        echo "0";
       }

    }


   
    public function feedback_store(Request $request)
    {
        $feedback = $request->feedback;
        $rating = $request->rating;
        $offer_item_id = $request->offer_item_id;
        $user_id = $request->user_id;

        $data = array('feedback'=>$feedback, 'rating'=>$rating, 'offer_item_id'=> $offer_item_id, 'user_id'=>$user_id);
        $rst = DB::table('offers_order_item_feedback')->insert($data);
        if($rst){
            echo "Your Feedback Save Successfully";
        }
        else{
            echo "Something went wrong";
        }
    }
    

    public function show_feedback(Request $request)
    {
        $id = $request->post('id');
        $result = DB::table("offers_order_item_feedback")->where('offer_item_id',$id)->latest()->first();
        if($result){
            echo json_encode($result);
        }
        
    }

    public function show_payment(Request $request)
    {
        $id = $request->id;
        $result = DB::table("offers_order_item")->where('id',$id)->where('status','5')->get();
        echo json_encode($result);
    }

    public function store_payment(Request $request)
    {
        $id = $request->id;
        $date = $request->date;
        $remark = $request->remark;
       
        $data = array('payment_remark'=>$remark, 'appove_date'=>$date, 'status'=>5);
        $rst = DB::table('offers_order_item')->where('id',$id)->update($data);
        if($rst){
            echo "Your Payment Details Save Successfully";
        }
        else{
            echo "Something went wrong";
        }
    }


    public function cancel_offer_item(Request $request)
    {
        $id = $request->coffer_item_id;
        $cancel_reason = $request->cancel_reason;
        $date = \Carbon\Carbon::now();
        $date->setTimezone('Asia/Kolkata');
        $formatedDate = $date->format('Y-m-d H:i:s');

        $update = DB::table('offers_order_item')->where('id',$id)->update(
            array('cancel_status'=> 1, 'cancel_reason' =>$cancel_reason , 'cancel_date' =>  $formatedDate));
        if($update)
        {
            $get_order_id = DB::table('offers_order_item')->where('id',$id)->value('order_id');
            $fetch = DB::table('direct_payment')->where('refrence_id','=',$get_order_id)->where('form_name','marketplace_payment')->get();
            
            $details = json_decode($fetch[0]->refrence_details);

           // print_r($details); die;

            if($details->wallet > 0)
            {
                $wamount = $details->wallet;
                $walletupdate = DB::table('users')
                ->where('id', Auth::user()->id)
                ->update(array('wallet_balance'=>DB::raw("wallet_balance + $wamount")));
            }

            if($details->vouch_code != 'null')
            {
                 DB::table('vouch_codes')
                ->where('vouch_code', $details->vouch_code)
                ->update(array('used_by'=>0,'status'=>0));
            }

            return redirect()->route('marketplace-history');
           
        }
    }

    public function addReasoncommon(Request $request)
    {
        $id = $request->id;
        $type = $request->form_type;
         $view = view('bid.add-reason-common',
            compact('id','type'))->render();

        return response()->json(['html' => $view]);
    }


    public function saveReasoncommon(Request $request)
    {
        $form_type = $request->form_type;
       
            $insert = DB::table('flag_activity')->insert([
                'user_id' => Auth::user()->id,
                'reference_id' => $request->id,
                'flag_type' => $form_type,
                'reason' => $request->response,
                'created_at' => now(),
            ]);
            if (!$insert) {
                return $this->errorResponse("Something went wrong, please try again.");
            }else{
                return $this->successResponse("This has been added to spam.", ['status' => 1]);
            }


        
    }



}
