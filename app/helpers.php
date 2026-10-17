<?php
use App\LogActivity;
use App\User;
use App\UserDetail;
use Carbon\Carbon;
use App\SendsmsLog;
use App\GlobalSetting;
use App\AdminSuggetions;
use App\CMS;

use App\SystemNotification;

use Illuminate\Support\Facades\DB;


/**
@return send_mail
 */
function send_mail($data) {
    
    $to        = isset($data['to'])?$data['to']:env('MAIL_USERNAME');
    $from      = isset($data['from'])?$data['from']:env('MAIL_USERNAME');
    $reply_to  = isset($data['reply_to'])?$data['reply_to']:$from;
    $from_name = isset($data['from_name'])?$data['from_name']:'';
    $subject   = isset($data['subject'])?$data['subject']:'Ayojn';
    $content   = isset($data['message'])?($data['message']):'Ayojn';
    $path      = 'mail/template';

    $data = array(
        'to'              => $to,
        'from'            => $from,
        'reply_to'        => $reply_to,
        'from_name'       => $from_name,
        'subject'         => $subject,
        'message'         => $content,
        'path'            => $path
    );


    return Mail::send('/mail/template', ['data'=>$data], function ($m) use ($data) {
        $m->to($data['to'])->subject($data['subject'])->getSwiftMessage()
            ->getHeaders()
            ->addTextHeader('x-mailgun-native-send', 'true');
    });
}
function replaceComma($value) {
    if (strpos($value, '.') != false) {
        $explode = explode('.', $value);
        if (strlen($explode[1]) < 2) {
            return $explode[0].','.$explode[1].'0';
        } else {
            return $explode[0].','.substr($explode[1], 0, 2);
        }
    } else {
        return $value.',00';
    }
}

function getSetting($key){
    $globalSetting = GlobalSetting::where('setting_name',$key)->select('setting_value')->first();
    if (!empty($globalSetting)) {
        return $globalSetting->setting_value;
    } else {
        return "";
    }
}

function getCms(){
    $cms = CMS::all();
    return $cms;
}
/**
@return parse_template
 */
function parse_template($template, $data) {
    if ($template == '') {
        return FALSE;
    }
    $l_delim = '{';
    $r_delim = '}';
    foreach ($data as $key => $val) {
        if (!is_array($val)) {
            $template = str_replace($l_delim.$key.$r_delim, (string) $val, $template);
        }
    }
    return $template;
}

/**
@return datetime
 */
function getTimeStamp($value,$type=0) {
    // return Carbon\Carbon::parse($value)->format('m/d/Y h:i:s A');
    if($type == 0)//default
    return date_format(date_create($value), 'd-M-Y h:i:s');
    else if($type == 1) //date only
        return date_format(date_create($value), 'd-M-Y');
    else if($type == 2)
        return date_format(date_create($value), 'h:i:s');
}

/**
@return response Array
 */
function getResponse($data, $status, $message, $err) {
    $response = [
        'data'            => $data,
        'status'          => $status,
        'responseMessage' => $message,
        'error_data'      => $err,
    ];
    return $response;
}

//write log

function logWrite($folder, $file, $data) {

    $fullDir = storage_path().'/logs/'.$folder;
    if (!file_exists($fullDir)) {
        mkdir(storage_path().'/logs/'.$folder, 0777);
    }
    $file = fopen(storage_path()."/logs/$folder/$file" .date('Y-m-d').".txt", "a+");
    fwrite($file, "=============start : ======================\n\n");
    fwrite($file, 'time : '.date('Y-m-d H:i:s'));
    fwrite($file, print_r($data, TRUE));
    fwrite($file, "=============Function called======================\n\n");
    fwrite($file, "=============end======================\n\n");
    return;
}
function saveNotification($postData){
	$notification = new SystemNotification();
	$notification->user_id = @$postData['user_id'];
	$notification->sender_id = @$postData['sender_id'];
	$notification->activity = @$postData['activity'];
	$notification->parent_id = @$postData['parent_id'];
	$notification->message = @$postData['message'];
	$notification->ip_address = $postData['ip_address'];
	$notification->created_at = date('Y-m-d H:i:s');
	$notification->created_at = date('Y-m-d H:i:s');
	$notification->save();
}
// Send push notification to android
function sendAndroidNotification($target, $message, $title = "Cupidknot") {
    //FCM api URL
    $url = 'https://fcm.googleapis.com/fcm/send';
    //api_key available in Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key
    $server_key = 'AIzaSyAJsvwLhppA_-K1TIBM9xL1mDAeuBdeYC0';
    $fields     = array();
    //$fields['notification'] = array("title" => $title, "body" => $message);
    $fields['data'] = array("message" => $message);

    if (is_array($target)) {
        $fields['registration_ids'] = $target;
    } else {
        $fields['to'] = $target;
    }

    //header with content_type api key
    $headers = array(
        'Content-Type:application/json',
        'Authorization:key='.$server_key,
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    //print_r($result);die;
    if ($result === FALSE) {
        die('FCM Send Error: '.curl_error($ch));
    }
    curl_close($ch);
}

// Send push notification to ios

function sendIosNotification($target, $message, $title = "Cupidknot") {
    $deviceToken = $target;
    $password    = 'cupidknot';
    $certificate = $_SERVER['DOCUMENT_ROOT'].'/cupidknot/storage/certificate/ck_Development.pem';
    $ctx         = stream_context_create();
    stream_context_set_option($ctx, 'ssl', 'local_cert', $certificate);
    stream_context_set_option($ctx, 'ssl', 'passphrase', $password);
    // Open a connection to the APNS server
    // $fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
    $fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

    if (!$fp) {
        exit("Failed to connect: $err $errstr" . PHP_EOL);
        //echo "Failed to connect: $err $errstr";
    } else {
        //echo 'Connected to APNS' . PHP_EOL;
        // Create the payload body

        $body['aps'] = array(
            'alert'          => $message,
            'sound'          => 'default',
           // 'reservation_id' => $reservation_id,
        );

        // Encode the payload as JSON
        $payload = json_encode($body);
        if (!is_array($deviceToken)) {
            $deviceToken = array($target);
        }

        foreach ($deviceToken as $token) {
            if (!ctype_xdigit($token)) {
                continue;
            }
            $msg    = chr(0).pack('n', 32).pack('H*', $token).pack('n', strlen($payload)).$payload;
            $result = fwrite($fp, $msg, strlen($msg));
        }

        // Close the connection to the server
        fclose($fp);
	}

}
function getmatchcount($userid,$partnerid)
{
    $user=\App\User::with('userdetails','userextradetails')
        ->where('id',$userid)
        ->first();
    $partner=\App\User::with('userdetails','userextradetails')
        ->where('id',$partnerid)
        ->first();
    $point=8.33;
    if($user->userdetails->date_of_birth != ''){
        $from = new Carbon($user->userdetails->date_of_birth);
        $to   = Carbon::today();
        $age=$from->diff($to)->y;
    }else{
        $age='';
    };
    if(isset($partner->userdetails)){
        $matchFieldArray=array();
        if(($partner->userdetails->partner_maxage != '' && $partner->userdetails->partner_minage != '') && ($age >= $partner->userdetails->partner_minage && $age <= $partner->userdetails->partner_maxage)){
            $persentage=$point;
            $matchFieldArray[] = "Age";
        }else if(($partner->userdetails->partner_maxage != '' && $partner->userdetails->partner_minage == '') && $age <= $partner->userdetails->partner_maxage){
            $persentage=$point;
            $matchFieldArray[] = "Age";
        }else if(($partner->userdetails->partner_maxage != '' && $partner->userdetails->partner_minage == '') && $age >= $partner->userdetails->partner_minage){
            $persentage=$point;
            $matchFieldArray[] = "Age";
        }else{
            $persentage=0;
            //$matchFieldArray[] = "";
        }

        if($user->userdetails->height){
            if (($partner->userdetails->partner_minheight != '' && $partner->userdetails->partner_maxheight != '') && ($user->userdetails->height >= $partner->userdetails->partner_minheight && $user->userdetails->height <= $partner->userdetails->partner_maxheight)) {
                $persentage = $persentage + $point;
                $matchFieldArray[] = 'Height';
            } else if (($partner->userdetails->partner_maxheight != '' && $partner->userdetails->partner_minheight == '') && $user->userdetails->height <= $partner->userdetails->partner_maxheight) {
                $persentage = $persentage + $point;
                $matchFieldArray[] = 'Height';
            } else if (($partner->userdetails->partner_maxheight == '' && $partner->userdetails->partner_minheight != '') && $user->userdetails->height >= $partner->userdetails->partner_minheight) {
                $persentage = $persentage + $point;
                $matchFieldArray[] = 'Height';
            }
        }
        if(in_array($user->userdetails->religion_id,explode(',',$partner->userdetails->partner_religion))){
            $persentage= $persentage + $point;
            $matchFieldArray[] = "Religion";
        }

        if($user->userdetails->caste_id == $partner->userdetails->partner_caste){
            $persentage= $persentage + $point;
            $matchFieldArray[] = "Caste";
        }

        if(in_array($user->userdetails->marital_status,explode(',',$partner->userdetails->partner_marital_status))){
            $persentage= $persentage + $point;
            $matchFieldArray[] = "Marital Status";
        }
        if($partner->userdetails->partner_occupation != ''){
            $poccupation=explode(',',$partner->userdetails->partner_occupation);
            foreach ($poccupation as $partner_occupation){
                if($partner_occupation == 'Business' && $user->userdetails->is_business == 1){
                    $persentage= $persentage + $point;
                    $matchFieldArray[] = "Occupation";
                }else if($partner_occupation == 'Job' && $user->userdetails->is_job == 1){
                    $persentage= $persentage + $point;
                    $matchFieldArray[] = "Occupation";
                }else if($partner_occupation == 'Other' && $user->userdetails->is_profession == 1){
                    $persentage= $persentage + $point;
                    $matchFieldArray[] = "Occupation";
                }
            }
        };
        if($partner->userdetails->partner_qualification != ''){
            $pqualification=explode(',',$partner->userdetails->partner_qualification);
            foreach ($pqualification as $partner_qualification){
                if($partner_qualification == 'High School' && $user->userdetails->is_highschool == 1){
                    $persentage= $persentage + $point;
                    $matchFieldArray[] = "Qualification";
                }else if($partner_qualification == 'Graduation' && $user->userdetails->is_graduated == 1){
                    $persentage= $persentage + $point;
                    $matchFieldArray[] = "Qualification";
                }else if($partner_qualification == 'Post Graduation' && $user->userdetails->is_postgraduate == 1){
                    $persentage= $persentage + $point;
                    $matchFieldArray[] = "Qualification";
                }
            }
        };
        if(in_array($user->userdetails->manglik,explode(',',$partner->userdetails->partner_mangalik))){
            $persentage= $persentage + $point;
            $matchFieldArray[] = "Manglik";
        }

        //Annual income
        $partnerAnnualIncome = $partner->userdetails->partner_income_level;
        if($partnerAnnualIncome != '' && ($user->userdetails->business_annual_income !='' || $user->userdetails->job_annual_income !=''||$user->userdetails->profession_annual_income !='' )){
            if($user->userdetails->is_business !='' || $user->userdetails->business_annual_income !=''){

                if($partnerAnnualIncome == 'Below 5 Lac'){
                    $persentage= $persentage + $point;
                    $matchFieldArray[] = "Annual Income";
                }else if($partnerAnnualIncome == 'INR 5 Lac to 10 Lac'){
                    $persentage= $persentage + $point;
                    $matchFieldArray[] = "Annual Income";
                }else if($partnerAnnualIncome == 'INR 10 Lac to 25 Lac'){
                    $persentage= $persentage + $point;
                    $matchFieldArray[] = "Annual Income";
                }else if($partnerAnnualIncome == 'INR 25 Lac to 50 Lac'){
                    $persentage= $persentage + $point;
                    $matchFieldArray[] = "Annual Income";
                }else if($partnerAnnualIncome == 'INR 50 Lac to 1 Cr'){
                    $persentage= $persentage + $point;
                    $matchFieldArray[] = "Annual Income";
                }else if($partnerAnnualIncome == 'INR 1 Cr to 2 Cr'){
                    $persentage= $persentage + $point;
                    $matchFieldArray[] = "Annual Income";
                }else if($partnerAnnualIncome == 'INR 2 Cr to 5 Cr'){
                    $persentage= $persentage + $point;
                    $matchFieldArray[] = "Annual Income";
                }else if($partnerAnnualIncome == 'INR 5 Cr to 10 Cr'){
                    $persentage= $persentage + $point;
                    $matchFieldArray[] = "Annual Income";
                }else if($partnerAnnualIncome == 'INR 10 Cr to 20 Cr'){
                    $persentage= $persentage + $point;
                    $matchFieldArray[] = "Annual Income";
                }else if($partnerAnnualIncome == 'INR 20 Cr to 50 Cr'){
                    $persentage= $persentage + $point;
                    $matchFieldArray[] = "Annual Income";
                }else if($partnerAnnualIncome == 'INR 50 Cr to 100 Cr'){
                    $persentage= $persentage + $point;
                    $matchFieldArray[] = "Annual Income";
                }
            }else if($user->userdetails->is_job !='' || $user->userdetails->job_annual_income !=''){
                if($partnerAnnualIncome == 'Below 5 Lac'){
                    $persentage= $persentage + $point;
                    $matchFieldArray[] = "Annual Income";
                }else if($partnerAnnualIncome == 'INR 5 Lac to 10 Lac'){
                    $persentage= $persentage + $point;
                    $matchFieldArray[] = "Annual Income";
                }else if($partnerAnnualIncome == 'INR 10 Lac to 25 Lac'){
                    $persentage= $persentage + $point;
                    $matchFieldArray[] = "Annual Income";
                }else if($partnerAnnualIncome == 'INR 25 Lac to 50 Lac'){
                    $persentage= $persentage + $point;
                    $matchFieldArray[] = "Annual Income";
                }else if($partnerAnnualIncome == 'INR 50 Lac to 1 Cr'){
                    $persentage= $persentage + $point;
                    $matchFieldArray[] = "Annual Income";
                }else if($partnerAnnualIncome == 'INR 1 Cr to 2 Cr'){
                    $persentage= $persentage + $point;
                    $matchFieldArray[] = "Annual Income";
                }else if($partnerAnnualIncome == 'INR 2 Cr to 5 Cr'){
                    $persentage= $persentage + $point;
                    $matchFieldArray[] = "Annual Income";
                }else if($partnerAnnualIncome == 'INR 5 Cr to 10 Cr'){
                    $persentage= $persentage + $point;
                    $matchFieldArray[] = "Annual Income";
                }else if($partnerAnnualIncome == 'INR 10 Cr to 20 Cr'){
                    $persentage= $persentage + $point;
                    $matchFieldArray[] = "Annual Income";
                }else if($partnerAnnualIncome == 'INR 20 Cr to 50 Cr'){
                    $persentage= $persentage + $point;
                    $matchFieldArray[] = "Annual Income";
                }else if($partnerAnnualIncome == 'INR 50 Cr to 100 Cr'){
                    $persentage= $persentage + $point;
                    $matchFieldArray[] = "Annual Income";
                }
            }else if($user->userdetails->is_profession !='' || $user->userdetails->profession_annual_income !=''){
                if($partnerAnnualIncome == 'Below 5 Lac'){
                    $persentage= $persentage + $point;
                    $matchFieldArray[] = "Annual Income";
                }else if($partnerAnnualIncome == 'INR 5 Lac to 10 Lac'){
                    $persentage= $persentage + $point;
                    $matchFieldArray[] = "Annual Income";
                }else if($partnerAnnualIncome == 'INR 10 Lac to 25 Lac'){
                    $persentage= $persentage + $point;
                    $matchFieldArray[] = "Annual Income";
                }else if($partnerAnnualIncome == 'INR 25 Lac to 50 Lac'){
                    $persentage= $persentage + $point;
                    $matchFieldArray[] = "Annual Income";
                }else if($partnerAnnualIncome == 'INR 50 Lac to 1 Cr'){
                    $persentage= $persentage + $point;
                    $matchFieldArray[] = "Annual Income";
                }else if($partnerAnnualIncome == 'INR 1 Cr to 2 Cr'){
                    $persentage= $persentage + $point;
                    $matchFieldArray[] = "Annual Income";
                }else if($partnerAnnualIncome == 'INR 2 Cr to 5 Cr'){
                    $persentage= $persentage + $point;
                    $matchFieldArray[] = "Annual Income";
                }else if($partnerAnnualIncome == 'INR 5 Cr to 10 Cr'){
                    $persentage= $persentage + $point;
                    $matchFieldArray[] = "Annual Income";
                }else if($partnerAnnualIncome == 'INR 10 Cr to 20 Cr'){
                    $persentage= $persentage + $point;
                    $matchFieldArray[] = "Annual Income";
                }else if($partnerAnnualIncome == 'INR 20 Cr to 50 Cr'){
                    $persentage= $persentage + $point;
                    $matchFieldArray[] = "Annual Income";
                }else if($partnerAnnualIncome == 'INR 50 Cr to 100 Cr'){
                    $persentage= $persentage + $point;
                    $matchFieldArray[] = "Annual Income";
                }
            }
        }

        if(in_array($user->userdetails->physical_status,explode(',',$partner->userdetails->partner_physical_status))){
            $persentage= $persentage + $point;
            $matchFieldArray[] = "Physical Status";
        }

        if(in_array($user->userdetails->complexion,explode(',',$partner->userdetails->partner_complexion))){
            $persentage= $persentage + $point;
            $matchFieldArray[] = "Complexion";
        }

        $partnerEducation = $partner->userdetails->partner_education;
        if($partnerEducation != '' && ($user->userdetails->is_highschool !='' || $user->userdetails->is_graduated !=''||$user->userdetails->is_postgraduate !='' )){
            if($user->userdetails->is_highschool != '' && in_array($user->userdetails->highschool_stream,explode(',',$partnerEducation))){
                $persentage= $persentage + $point;
                $matchFieldArray[] = "Education";
            }else if($user->userdetails->is_graduated != '' && in_array($user->userdetails->graduation_stream,explode(',',$partnerEducation))){
                $persentage= $persentage + $point;
                $matchFieldArray[] = "Education";
            }else if($user->userdetails->is_postgraduate != '' && in_array($user->userdetails->postgraduate_stream,explode(',',$partnerEducation))){
                $persentage= $persentage + $point;
                $matchFieldArray[] = "Education";
            }
        }

        $data=array(
            "persentage"=>$persentage,
            'matchfieldarray'=>$matchFieldArray
        );
    }else{
        $data=array(
            "persentage"=>0,
            'matchfieldarray'=>''
        );
    }
    return $data;
}

function getmatchresult($data){
    DB::enableQueryLog();
   //dd($data->user_id);
    $matchcount=User::select('users.*','user_details.city_id','user_details.date_of_birth','user_details.gender',DB::raw('YEAR(CURDATE()) - YEAR(user_details.date_of_birth) AS age'))
        ->join('user_details', 'user_details.user_id', '=', 'users.id')
        //->leftJoin('hideuser_profiles', 'users.id', '=', 'hideuser_profiles.user_id')
        ->where('users.id','!=',$data->user_id)
        //->where('hideuser_profiles.user_id','!=',$data->user_id)

        //->whereNotNull('users.user_package_id')
        //->where('users.user_package_id','!=',0)
        ->where('users.profile_status','=',1)
        ->where('user_details.gender', '!=', (isset($data->user_gender) && $data->user_gender != '')?$data->user_gender:'')
        ->where('users.role_id',0);
    /*$matchcount =$matchcount->where(function ($query) use($data) {
        $query->whereNull('hideuser_profiles.is_hidden')
        ->orWhere('hideuser_profiles.is_hidden','=',0);
    });*/
    if(Auth::guard('admin')->check() && Auth::User()->role_id == 4){
        $matchcount=$matchcount->where('assign_pandit',Auth::user()->id);
    }
    $matchcount=$matchcount->whereNotIn('users.id', DB::table('hideuser_profiles')->where('user_id',$data->user_id)->pluck('partner_id'));

    $matchcount=$matchcount->whereNotIn('users.id', DB::table('admin_suggetions')->where('user_id',$data->user_id)->orWhere('partner_id',$data->user_id)->pluck('partner_id'));
    $matchcount=$matchcount->whereNotIn('users.id', DB::table('admin_suggetions')->where('user_id',$data->user_id)->orWhere('partner_id',$data->user_id)->pluck('user_id'));

    if($data->max_age != ''){
        $from = new Carbon($data->date_of_birth);
        $to   = Carbon::today();
        $partner_age=$from->diff($to)->y;
        $partner_age=$data->max_age+0.6;
        $matchcount =$matchcount->whereRaw('YEAR(CURDATE()) - YEAR(user_details.date_of_birth) <= '.$partner_age);

    };

    if($data->min_age != ''){
        $from = new Carbon($data->date_of_birth);
        $to   = Carbon::today();
        $partner_age=$from->diff($to)->y;
        $partner_age=$data->min_age-0.6;
        $matchcount =$matchcount->whereRaw('YEAR(CURDATE()) - YEAR(user_details.date_of_birth) >= '. $partner_age);

    };

    if($data->religion != ''){
        $matchcount =$matchcount->whereRaw("find_in_set(user_details.religion_id,'".implode(',',$data->religion)."')");
    };

    if($data->caste != ''){
        $matchcount =$matchcount->whereRaw("find_in_set(user_details.caste_id,'".implode(',',$data->caste)."')");
    };

    if($data->marital_status != ''){
        $matchcount =$matchcount->whereRaw("find_in_set(user_details.marital_status,'".implode(',',$data->marital_status)."')");
    };

    if($data->minheight != ''){
        $matchcount =$matchcount->where ('user_details.height','>=',$data->minheight);
    };

    if($data->maxheight != ''){
        $matchcount =$matchcount->where ('user_details.height','<=',$data->maxheight);
    };

    if($data->physical_status != ''){
        $matchcount =$matchcount->whereRaw("find_in_set(user_details.physical_status,'".implode(',',$data->physical_status)."')");

    };

    if($data->education != ''){
        //dd($data->education);
            $matchcount =$matchcount->where(function ($query) use($data) {
                $query->whereRaw ("find_in_set(user_details.highschool_stream,'".implode(',',$data->education)."')")
                    ->orwhereRaw ("find_in_set(user_details.graduation_stream,'".implode(',',$data->education)."')")
                    ->orwhereRaw ("find_in_set(user_details.postgraduate_stream,'".implode(',',$data->education)."')");
            });
        }


    if($data->manglik != ''){
        $matchcount =$matchcount->where ('user_details.manglik','=',$data->manglik);
    };

    if($data->complextion != ''){
        $matchcount =$matchcount->whereRaw ("find_in_set(user_details.complexion,'".implode(',',$data->complextion)."')");

    };

    if($data->partner_qualification != ''){
        $matchcount =$matchcount->where(function ($query) use($data) {
            foreach ($data->partner_qualification as $partner_qualification){
                if($partner_qualification == 'High School'){
                    $query->where('user_details.is_highschool','=',1);
                }else if($partner_qualification == 'Graduation'){
                    $query->orwhere('user_details.is_graduated','=',1);
                }else if($partner_qualification == 'Post Graduation'){
                    $query->orwhere ('user_details.is_postgraduate','=',1);
                }
            }
        });

    };
    if($data->partner_occupation != ''){
        $matchcount =$matchcount->where(function ($query) use($data) {
            foreach ($data->partner_occupation as $partner_occupation){
                if($partner_occupation == 'Business'){
                    $query->where('user_details.is_business','=',1);
                }else if($partner_occupation == 'Job'){
                    $query->orwhere('user_details.is_job','=',1);
                }else if($partner_occupation == 'Other'){
                    $query->orwhere ('user_details.is_profession','=',1);
                }else if($partner_occupation == 'Not Working'){
                    $query->where ('user_details.is_business','=',0);
                    $query->where ('user_details.is_job','=',0);
                    $query->where ('user_details.is_profession','=',0);
                }
            }
        });
    };
    if($data->income_level != ''){
            $matchcount =$matchcount->where(function ($query) use($data) {
                $query->where('user_details.business_annual_income','like','%'. $data->income_level.'%')
                    ->orWhere('user_details.job_annual_income', 'like','%'. $data->income_level.'%')
                    ->orWhere('user_details.profession_annual_income', 'like','%'. $data->income_level.'%');
            });

    };


    $result=array(
        "user_id"=>$data->user_id,
        "matchcount"=>$matchcount->paginate(3)
    );
    //dd(DB::getQueryLog());
    return $result;
}

/**
* get response
* @param status,message,data,error-data
* @return response(error/success with data)
*/
function calculatepercentage($id){
    //return $columnData;
    $usercolumns = User::$CountFields;
    $userdetailcolumns = UserDetail::$CountFields;
    $totalfield=0;
    $totalfield +=count($usercolumns);
    $totalfield +=count($userdetailcolumns);
    $fillCol = 0;
    $userdetailfillcol=0;
    foreach($usercolumns as $key=>$value){
        //$fillCol += Test::where($value,'')->where('id',1)->count();
        $fillCol += User::where(function ($query) use ($value,$id) {
            $query->where($value, '=', '')
                ->orWhereNull($value);
        })->where(function ($query) use($id) {
            $query->where('id', '=', $id);
        })->count();

    }
    if(UserDetail::where('user_id',$id)->count() > 0){
        foreach($userdetailcolumns as $key=>$value){
            //$fillCol += Test::where($value,'')->where('id',1)->count();
            $userdetailfillcol += UserDetail::where(function ($query) use ($value,$id) {
                $query->where($value, '=', '')
                    ->orWhereNull($value);
            })->where(function ($query) use($id) {
                $query->where('user_id', '=', $id);
            })->count();
        }
    }else{
        $userdetailfillcol=count($userdetailcolumns);
    }
    $totalEmpty=$fillCol+$userdetailfillcol;

    if($totalfield > 0)
    {

        $filled_field=$totalfield-$totalEmpty;
        $filled_fields=$filled_field;
        //return $filled_field;
        $perfilled= round(($filled_field/$totalfield)*100,2);
    }else{
        $filled_fields=0;
        $perfilled=0;
    }
    $data=array(
        'empty_fields'=>$fillCol,
        'total_fields'=>$totalfield,
        'filled_field'=>$filled_fields,
        'percentage'=>$perfilled.'%',
        'percentageNum'=>$perfilled
    );
    return $data;
}
function apiResponse($status,$message,$data=null,$isLogout=0, $imgURL = null, $documentImages = null)
{
    try{
        $response = array(
            'data'            => $data,
            'status'          => $status,
            'responseMessage' => $message,
            'imgURL' => $imgURL,
            'documentImages' => $documentImages,
            'isLogout' => $isLogout,
        );
        return $response;
    }
    catch(\exception $e){
        $result = apiResponse(0,"Something Went to Wrong",$e->getMessage());
        return response()->json($result)->setStatusCode(400);
    }
}

/**
* send sms
* @param contact-number,message
* @return response(error/true with data)
*/
function send_sms($contactNo,$message,$logMessage)
{
    try{
        $url2="http://sms.thinkbuyget.com/api.php?username=".env('SMS_USERNAME')."&password=".env('SMS_PASSWORD')."&sender=".env('SMS_SENDER')."&sendto=".$contactNo."&message=".$message;
        $url2 = str_replace(" ","+",$url2);
        if($ch1 = file_get_contents($url2)){
            //save sms send log in sms-log table
            $data1=array(
                'mobile_no'=>$contactNo,
                'message'=>$logMessage,
                'response'=>$ch1,
                'created_at'=>date('Y-m-d H:i:s')
            );
            $religionid=SendsmsLog::create($data1);
        }
        return true;
    }
    catch(\exception $e)
    {
        $result = apiResponse(0,"Something Went to Wrong",$e->getMessage());
        return response()->json($result)->setStatusCode(400);
    }
}
/**
* upload image
* @param image array
* @return response(error/true with data)
*/
function uploadImage($imageArray,$imageType,$iswatermark)
{
    try{
        $imageCount = count($imageArray);
        $imageNameArray = array();
        //uploading multiple/single images
        for($imageArrayIndex=0;$imageArrayIndex<$imageCount;$imageArrayIndex++){
            $display_image = time().rand(0,100).$imageType.$imageArrayIndex.".".$imageArray[$imageArrayIndex]->getClientOriginalExtension();
            $imageNameArray[$imageArrayIndex] = $display_image;
            $destinationPath = public_path('/document_images');
            //uploading image

            $img = Image::make($imageArray[$imageArrayIndex]->getRealPath());
            $height = Image::make($imageArray[$imageArrayIndex]->getRealPath())->height();
            $watermark = Image::make(public_path('/images/water-mark1.png'));
            if($height <= 290 && $height > 163){
                $height=($height*50)/100;
                $watermark->resize(null,$height);
                $img->insert($watermark, 'bottom-left', 10, 10);
            }elseif($height <= 163){
                $height=($height*50)/100;
                $watermark->resize(null,$height);
                $img->insert($watermark, 'bottom-left', 10, 10);
            }else{
                $img->insert($watermark, 'bottom-left', 10, 70);
            }
            if($iswatermark == 0){
                $imageArray[$imageArrayIndex]->move($destinationPath, $display_image);
            } else {
                $img->save($destinationPath.'/'.$display_image);
            }
        }
        return $imageNameArray;
    }
    catch(\exception $e)
    {
        $result = apiResponse(0,"Something Went to Wrong",$e->getMessage());
        return response()->json($result)->setStatusCode(400);
    }
}

function addToLog($data)

{
    $log=array(
        'added_by'=>isset($data['added_by'])?$data['added_by']:NULL,
        'activity'=>isset($data['activity'])?$data['activity']:NULL,
        'parent_id'=>isset($data['parent_id'])?$data['parent_id']:NULL,
        'message'=>isset($data['message'])?$data['message']:NULL,
        'ip_address'=>isset($data['ip_address'])?$data['ip_address']:NULL,
        'is_viewed'=>0,
        'created_at'=>date('Y-m-d H:i:s')
    );
    LogActivity::create($log);
    $mobile_no=getSetting('notification_mobile');
    $email_id=getSetting('notification_email');
    //dd($email_id);
    //send sms
    $smsResult = send_sms($mobile_no,$data['message'],$data['message']);
    $email_data=array(
        'message'=>$data['message'],
        'name'=>"Cupid Knot Admin"
    );
    $message1 = view('email_template.sendsms',$email_data);
    //$message1 = html_entity_decode($message);
    /*$smsdata = array('to' => $email_id,
        'subject'         => $data['activity']." Log Alert",
        'message'         => $message1,
    );*/
    $to        = $email_id;
    $from      = env('MAIL_USERNAME');
    $reply_to  = env('MAIL_USERNAME');
    $from_name = 'Cupidknot';
    $subject = $data['activity']." LOG ALERT";
    /* $email_data=array(
         'message'=>$message,
         'name'=>$userdetail[0]['name']
     );*/
    /// $message1 = view('email_template.sendsms',$email_data);
    $message1 = $message1;

    $data = array('to' => $to,
        'from'            => $from,
        'reply_to'        => $reply_to,
        'from_name'       => $from_name,
        'subject'         => $subject,
        'message'         => $message1,
    );
    //$send_mail = send_mail($data);
   // $send_mail = send_mail($smsdata);
}

function getnotification(){
    $getnotification=LogActivity::where('is_viewed',0)->orderBy('id','DESC')->get();
    $notification_count=count($getnotification);
    $data=array(
        'notification_text'=>$getnotification,
        'notification_count'=>$notification_count
    );
    return $data;
}

function getInstagramPost(){
    $postArrayData = array();
    if(@$xml = simplexml_load_file('https://web.stagram.com/rss/n/cupidknot', null, LIBXML_NOCDATA)){
        foreach ($xml->channel->item as $main_key=>$item) {
            $data = [];
            $dom = new \DOMDocument();
            libxml_use_internal_errors(true);
            $dom->loadHTML($item->description);
            libxml_clear_errors();
            $img = '';
            if($dom->getElementsByTagName('img')->item(0)){
                $img = $dom->getElementsByTagName('img')->item(0)->getAttribute('src');
            }
            if(isset($img)){
                $item_encode = json_encode($item);
                $item_decode = json_decode($item_encode,true);
                $data['img'] = $img;
                $data['link'] = $item_decode['link'];
                $data['title'] = $item_decode['title'];
                array_push($postArrayData, $data);
            }
        }
    }
    $final_data = array_slice($postArrayData, 0, 5);
    return $final_data;
}

function getReason(){
    $delete_profile_reasons = DB::table('delete_profile_reasons')->where('status',1)->get();
    return $delete_profile_reasons;
}

function UserNotificationList(){
    if(isset(Auth::guard('user')->user()->id)){
        $notificationData = SystemNotification::with(['userimages' => function ($query) {
            return $query->where('image_type','display_photo');
        }])->where('user_id',Auth::guard('user')->user()->id)->limit(10)->orderBy('id','desc')->get();
        foreach ($notificationData as $key => $value) {
            if(!empty($value->user_id) && !empty($value->sender_id)){
                $id = $value->user_id;
                $partner_id =  $value->sender_id;
                $approvedData = AdminSuggetions::
                    where(function($q) use ($id,$partner_id) {
                        $q->where('user_id',$id)
                        ->where('partner_id',$partner_id);
                    })
                    ->orWhere(function($q) use ($id,$partner_id) {
                        $q->where('user_id',$partner_id)
                        ->where('partner_id',$id);
                    })
                    ->first();
                if($approvedData->user_id == $id){
                    $value['status'] = $approvedData->user_approval;
                } else {
                    $value['status'] = $approvedData->partner_approval;
                }
            }
        }
        $notificationCount = SystemNotification::where('user_id',Auth::guard('user')->user()->id)->where('is_counted',0)->count();
        $result = [
            'notificationCount' => $notificationCount,
            'data' => $notificationData,
            'url' => url('/').'/document_images/',
        ];
        return json_encode($result);
    }
}

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

// Send push notification to android
function sendAndroidNotificationChat($target, $message,$new_user_id,$user_name,$title = "Cupidknot") {
    //FCM api URL
    $url = 'https://fcm.googleapis.com/fcm/send';
    //api_key available in Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key
    $server_key = 'AIzaSyAJsvwLhppA_-K1TIBM9xL1mDAeuBdeYC0';
    $fields     = array();
    //$fields['notification'] = array("title" => $title, "body" => $message);
    $fields['data'] = array("message" => $message,'type'=>'chat','room_id'=>$new_user_id,'user_name'=>$user_name);

    if (is_array($target)) {
        $fields['registration_ids'] = $target;
    } else {
        $fields['to'] = $target;
    }

    //header with content_type api key
    $headers = array(
        'Content-Type:application/json',
        'Authorization:key='.$server_key
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    //print_r($result);die;
    if ($result === FALSE) {
        die('FCM Send Error: '.curl_error($ch));
    }
    curl_close($ch);
}

function getSuggetioncount(){
    $id=Auth::user()->id;
    $getcount=AdminSuggetions::where(function ($query) use($id) {
        $query->where('user_id',$id)
            ->orWhere('partner_id',$id);
    })->where(function ($query) use($id){
        $query->where(function ($query) use($id) {
            $query->where('user_id',$id)
                ->Where('user_is_read',0)
                ->Where('user_pandit_approval',1);
        })->orWhere(function ($query) use($id) {
                $query->where('partner_id',$id)
                    ->Where('partner_is_read',0)
                    ->Where('partner_pandit_approval',1);
            });
    })->get();
    if(count($getcount) > 0){
        return count($getcount);
    }else{
        return '0';
    }
}
