<?php

namespace App\Http\Controllers\UserAuth;

use App\City;
use App\Country;
use App\Http\Controllers\Controller;
use App\Industry;
use App\RedeemRequest;
use App\ReferAFriend;
use App\SponsorrSpecify;
use App\SponsorrSpecifyBid;
use App\SponsorrSpecifyList;
use App\SystemNotification;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class userController extends Controller
{

    public function thankYou()
    {
        return view('thankyou');
    }

    public function showLinkRequestForm()
    {
        return view('user.reset-username');
    }

    public function sendUsername(Request $request)
    {
        $userData = User::where('sq1', $request->mother)->where('phone_no', $request->mobile_no)->first();
        if ($userData) {
            $smsdata = [
                'to' => $userData->email,
                'subject' => "Forgot Username of Ayojn",
                'message' => "Here you username : " . $userData->email,
            ];
            Mail::send('/mail/forgot_username', ['user' => $smsdata], function ($m) use ($userData) {
                $m->to($userData->email)->subject("Username recover")->getSwiftMessage()
                    ->getHeaders()
                    ->addTextHeader('x-mailgun-native-send', 'true');
            });

            return redirect()->back()->with('sucess_message', 'Username successfully sent on registered email');
        } else {
            return redirect()->back()->with('message', 'Mobile number or mother name is not match with any record');
        }
    }

    public function getCity(Request $request)
    {
        $cities = City::where('country_code', $request->countryID)->get();

        return response($cities);
    }

    public function suspend()
    {
        if (Auth::user()->is_suspend) {
            return view('suspend');
        } else {
            return redirect('/index');
        }
    }

    public function review()
    {
        if (Auth::user()->is_edited) {
            return view('review');
        } else {
            if (Auth::user()->userstatus == 3) {

                return redirect('partner/home');
            } else {
                return redirect('home');
            }
        }
    }

    public function pending()
    {

        if (! empty(Auth::user())) {
            if (! empty(Auth::user()) && Auth::user()->is_edited == 1) {
                return view('review');
            }
            if (! empty(Auth::user()->disapprove_time) && strtotime(Auth::user()->disapprove_time) <= strtotime(Carbon::now()
                    ->toDateTimeString())) {
                return view('pending');
            } elseif (! empty(Auth::user()) && Auth::user()->userstatus) {
                return redirect('home');
            } else {
                return view('pending');
            }
        }

    }

    public function index(Request $request, $referCode = null)
    {
        if (! Auth::check()) {
            $referBy = '';
            if (! empty($referCode)) {
                $referBy = $referCode;

            }
            session(['referal' => $referBy]);
        } else {
            session(['referal' => '']);
        }

        return view('welcometest');
    }

    public function partner(Request $request, $referCode)
    {
        if (! Auth::check()) {
            $referBy = '';
            if (! empty($referCode)) {
                $referBy = $referCode;

            }
            session(['referal' => $referBy]);
        } else {
            session(['referal' => '']);
        }

        return redirect('partner/login');
    }

    public function map()
    {

        return view('map');
    }

    public function map2()
    {
        $userData = Auth::user();
        if ($userData->sponsor_type == 3) {
            return redirect('partner/home');
        } else if (Auth::check() && Auth::user()->sponsor_type == 1) {
            return redirect('opportunity');
        } else {
            return redirect('bid');
        }
        $sponsorrlist = SponsorrSpecifyList::where('sponsorr_type', Auth::user()->sponsor_for)->get();
        $userwisesponsor = SponsorrSpecify::where('user_id', Auth::user()->id)->get();
        $industries = Industry::all();
        $countries = Country::all();
        if ($userData->sponsor_type == 1) {
            if (empty($userData->sponsor_for) || empty($userData->likeSponsorr) || empty($userData->sponsor_budget) || empty($userData->sponsor_industry) || empty($userData->sponsor_country)) {
                return view('offer', compact('sponsorrlist', 'userwisesponsor', 'industries', 'countries'));
            }
        } else if ($userData->sponsor_type == '2') {
            if (empty($userData->sponsor_for) || empty($userData->sponsor_budget) || empty($userData->sponsor_country)) {
                return view('receive', compact('sponsorrlist', 'userwisesponsor', 'industries', 'countries'));
            }
        }

        return view('map2');
    }

    public function mapDisplay()
    {
        $userData = Auth::user();
        if ($userData->sponsor_type == 3) {
            return redirect('partner/home');
        }
        $sponsorrlist = SponsorrSpecifyList::where('sponsorr_type', Auth::user()->sponsor_for)->get();
        $userwisesponsor = SponsorrSpecify::where('user_id', Auth::user()->id)->get();
        $industries = Industry::all();
        $countries = Country::all();
        if ($userData->sponsor_type == 1) {
            if (empty($userData->sponsor_for) || empty($userData->likeSponsorr) || empty($userData->sponsor_budget) || empty($userData->sponsor_industry) || empty($userData->sponsor_country)) {
                return view('offer', compact('sponsorrlist', 'userwisesponsor', 'industries', 'countries'));
            }
        } else if ($userData->sponsor_type == '2') {
            if (empty($userData->sponsor_for) || empty($userData->sponsor_budget) || empty($userData->sponsor_country)) {
                return view('receive', compact('sponsorrlist', 'userwisesponsor', 'industries', 'countries'));
            }
        }

        return view('map2');
    }

    public function updateOfferData(Request $request)
    {
        $user = Auth::user();
        $user->sponsor_for = $request->offer_sponsorr_type;
        $user->sponsor_country = $request->offer_sponsorr_country;
        $user->sponsor_budget = $request->offer_sponsorr_budget;
        $user->sponsor_industry = $request->offer_sponsorr_industry;
        if (! empty($request->offer_sponsorr_specify)) {
            $specipy = SponsorrSpecify::where('user_id', $user->id)->delete();
            foreach ($request->offer_sponsorr_specify as $s) {
                SponsorrSpecify::create(['specify_name' => $s, 'user_id' => $user->id]);
            }
        }
        if (! empty($request->offer_sponsorr_like)) {
            $user->likeSponsorr = implode(',', $request->offer_sponsorr_like);
        }
        $user->save();

        $result = [
            'status' => 1,
            'msg' => 'Detail updated Successfully.'
        ];

        return response()->json($result)->setStatusCode(200);
    }

    public function edituserprofile(Request $request)
    {
        $user = Auth::user();
        $alluser = User::where('id', '!=', $user->id)->where('email', $request->useremail_edit)->get();
        if (count($alluser) > 0) {
            $result = [
                'status' => 0,
                'msg' => 'Email Address is already in use'
            ];

            return response()->json($result)->setStatusCode(200);
        }
        $user_id = $user->id;
        $user->edit_count = (int)$user->edit_count + 1;
        $user->email = $request->useremail_edit;
        $user->entity = $request->userentity_edit;
        $user->identity = $request->identity_edit;
        $user->phone_no = $request->phone_no_edit;
        $user->sq1 = $request->sq1_edit;
        $user->userstatus = 0;
        $user->is_edited = 1;
//        $user->sponsor_for = $request->sponsorType_edit;
//        $user->sponsor_country = $request->sponsorCountry_edit;
//        $user->sponsor_budget = $request->sponsorBudget_edit;
//        $user->sponsor_industry = $request->sponsorIndustry_edit;
        $user->save();
//        if (! empty($request->sponsorOtherSpecifyValue_edit)) {
//            $specipy = SponsorrSpecify::where('user_id', $user_id)->delete();
//            foreach ($request->sponsorOtherSpecifyValue_edit as $s) {
//                SponsorrSpecify::create(['specify_name' => $s, 'user_id' => $user_id]);
//            }
//        }
//        if (! empty($request->likeSponsorr_edit)) {
//            $user->likeSponsorr = implode(',', $request->likeSponsorr_edit);
//            $user->save();
//        }


        $result = [
            'status' => 1,
            'msg' => 'Profile Edited Successfully.'
        ];

        return response()->json($result)->setStatusCode(200);

    }

    public function getUserCount($id)
    {
        $user = User::find($id);

        return response()->json($user);
    }

    public function mapDataPopup(Request $request)
    {
        if (Auth::user()->sponsor_type == 1) {
            $sponsor_type = 2;
            $sponsorWord = "Manage Or Receive Sponsorship";
        } else {
            $sponsor_type = 1;
            $sponsorWord = "Offer Sponsorship";
        }
        $country_code = $request->countryId;
        DB::enableQueryLog();
        $userData = User::with('country')->where('country', $country_code)->where('sponsor_type', $sponsor_type)
            ->where('userstatus', 1)->get();
        // dd(DB::getQueryLog());
        // dd($userData);
        $eventUser = 0;
        $campaignUser = 0;
        $ContentUser = 0;
        $sportTeamUser = 0;
        $otherUser = 0;
        $conference = 0;
        $music_festival = 0;
        $tradeshow = 0;
        $exhibition = 0;
        $Online = 0;
        $Offline = 0;
        $SocialMedia = 0;
        $Infleucer = 0;
        $Blog = 0;
        $Video = 0;
        $Inforgraphics = 0;
        $CaseStudies = 0;
        $Whitpapers = 0;
        $Articles = 0;
        $Interviews = 0;
        $Memes = 0;
        $Football = 0;
        $Regional = 0;
        $AdventureSports = 0;
        $Racetrack = 0;
        $International = 0;
        $Outdoor = 0;
        $indoor = 0;
        $Sports = 0;
        $Entertainment = 0;
        $Shopping = 0;
        $Transport = 0;
        $CSR = 0;
        $Aid = 0;
        $Donation = 0;
        $Campaign = 0;
        $Lobbying = 0;
        $Charity = 0;
        $Masterclass = 0;
        $Seminar = 0;
        $Delegation = 0;
        $AwardsCompetitions = 0;
        $ProductLaunch = 0;
        $FestivalsParties = 0;
        $Email = 0;
        $Experiential = 0;
        $Canvassing = 0;
        $Cricket = 0;
        $Boxing = 0;
        $Golf = 0;
        $Polo = 0;
        $Skiing = 0;
        $PowerBoat = 0;
        $PR = 0;
        $OnlineGaming = 0;
        $BaseBall = 0;
        $eventlesstwo = 0;
        $eventbtwtwofive = 0;
        $eventbtwfivetwo = 0;
        $eventabovetwo = 0;
        $campaignlesstwo = 0;
        $campaignbtwtwofive = 0;
        $campaignbtwfivetwo = 0;
        $campaignabovetwo = 0;
        $contentlesstwo = 0;
        $contentbtwtwofive = 0;
        $contentbtwfivetwo = 0;
        $contentabovetwo = 0;
        $sportlesstwo = 0;
        $sportbtwtwofive = 0;
        $sportbtwfivetwo = 0;
        $sportabovetwo = 0;
        $otherlesstwo = 0;
        $otherbtwtwofive = 0;
        $otherbtwfivetwo = 0;
        $otherabovetwo = 0;
        $venuelesstwo = 0;
        $venuebtwtwofive = 0;
        $venuebtwfivetwo = 0;
        $venueabovetwo = 0;
        $venueUser = 0;
        $profitlesstwo = 0;
        $profitbtwtwofive = 0;
        $profitbtwfivetwo = 0;
        $profitabovetwo = 0;
        $profitUser = 0;
        $otherUser = 0;
        $finalArray = [];
        //dd($userData);
        foreach ($userData as $user) {
            if ($sponsor_type == 1) {
                if ($user->sponsor_for == "Event") {
                    if ($user->sponsor_budget == "Less than 2000") {
                        $eventlesstwo = $eventlesstwo + 1;
                    } elseif ($user->sponsor_budget == "Between 2000-5000") {
                        $eventbtwtwofive = $eventbtwtwofive + 1;
                    } elseif ($user->sponsor_budget == "Between 5000-20000") {
                        $eventbtwfivetwo = $eventbtwfivetwo + 1;
                    } elseif ($user->sponsor_budget == "Above 20000") {
                        $eventabovetwo = $eventabovetwo + 1;
                    }
                    $eventUser = $eventUser + 1;
                } elseif ($user->sponsor_for == "Campaign") {
                    if ($user->sponsor_budget == "Less than 2000") {
                        $campaignlesstwo = $campaignlesstwo + 1;
                    } elseif ($user->sponsor_budget == "Between 2000-5000") {
                        $campaignbtwtwofive = $campaignbtwtwofive + 1;
                    } elseif ($user->sponsor_budget == "Between 5000-20000") {
                        $campaignbtwfivetwo = $campaignbtwfivetwo + 1;
                    } elseif ($user->sponsor_budget == "Above 20000") {
                        $campaignabovetwo = $campaignabovetwo + 1;
                    }
                    $campaignUser = $campaignUser + 1;
                } else if ($user->sponsor_for == "Content") {
                    if ($user->sponsor_budget == "Less than 2000") {
                        $contentlesstwo = $contentlesstwo + 1;
                    } elseif ($user->sponsor_budget == "Between 2000-5000") {
                        $contentbtwtwofive = $contentbtwtwofive + 1;
                    } elseif ($user->sponsor_budget == "Between 5000-20000") {
                        $contentbtwfivetwo = $contentbtwfivetwo + 1;
                    } elseif ($user->sponsor_budget == "Above 20000") {
                        $contentabovetwo = $contentabovetwo + 1;
                    }
                    $ContentUser = $ContentUser + 1;
                } else if ($user->sponsor_for == "Sports Team") {
                    if ($user->sponsor_budget == "Less than 2000") {
                        $sportlesstwo = $sportlesstwo + 1;
                    } elseif ($user->sponsor_budget == "Between 2000-5000") {
                        $sportbtwtwofive = $sportbtwtwofive + 1;
                    } elseif ($user->sponsor_budget == "Between 5000-20000") {
                        $sportbtwfivetwo = $sportbtwfivetwo + 1;
                    } elseif ($user->sponsor_budget == "Above 20000") {
                        $sportabovetwo = $sportabovetwo + 1;
                    }
                    $sportTeamUser = $sportTeamUser + 1;
                } else if ($user->sponsor_for == "Other") {
                    if ($user->sponsor_budget == "Less than 2000") {
                        $otherlesstwo = $otherlesstwo + 1;
                    } elseif ($user->sponsor_budget == "Between 2000-5000") {
                        $otherbtwtwofive = $otherbtwtwofive + 1;
                    } elseif ($user->sponsor_budget == "Between 5000-20000") {
                        $otherbtwfivetwo = $otherbtwfivetwo + 1;
                    } elseif ($user->sponsor_budget == "Above 20000") {
                        $otherabovetwo = $otherabovetwo + 1;
                    }
                    $otherUser = $otherUser + 1;
                } else if ($user->sponsor_for == "Venue") {
                    if ($user->sponsor_budget == "Less than 2000") {
                        $venuelesstwo = $venuelesstwo + 1;
                    } elseif ($user->sponsor_budget == "Between 2000-5000") {
                        $venuebtwtwofive = $venuebtwtwofive + 1;
                    } elseif ($user->sponsor_budget == "Between 5000-20000") {
                        $venuebtwfivetwo = $venuebtwfivetwo + 1;
                    } elseif ($user->sponsor_budget == "Above 20000") {
                        $venueabovetwo = $venueabovetwo + 1;
                    }
                    $venueUser = $venueUser + 1;
                } else if ($user->sponsor_for == "Not for Profit") {
                    if ($user->sponsor_budget == "Less than 2000") {
                        $profitlesstwo = $profitlesstwo + 1;
                    } elseif ($user->sponsor_budget == "Between 2000-5000") {
                        $profitbtwtwofive = $profitbtwtwofive + 1;
                    } elseif ($user->sponsor_budget == "Between 5000-20000") {
                        $profitbtwfivetwo = $profitbtwfivetwo + 1;
                    } elseif ($user->sponsor_budget == "Above 20000") {
                        $profitabovetwo = $profitabovetwo + 1;
                    }
                    $profitUser = $profitUser + 1;
                }
            } else {
                if ($user->sponsor_for == "Event") {
                    if ($user->sponsor_budget == "Less than 2000") {
                        $eventlesstwo = $eventlesstwo + 1;
                    } elseif ($user->sponsor_budget == "Between 2000-5000") {
                        $eventbtwtwofive = $eventbtwtwofive + 1;
                    } elseif ($user->sponsor_budget == "Between 5000-20000") {
                        $eventbtwfivetwo = $eventbtwfivetwo + 1;
                    } elseif ($user->sponsor_budget == "Above 20000") {
                        $eventabovetwo = $eventabovetwo + 1;
                    }
                    $eventUser = $eventUser + 1;
                } elseif ($user->sponsor_for == "Campaign") {
                    if ($user->sponsor_budget == "Less than 2000") {
                        $campaignlesstwo = $campaignlesstwo + 1;
                    } elseif ($user->sponsor_budget == "Between 2000-5000") {
                        $campaignbtwtwofive = $campaignbtwtwofive + 1;
                    } elseif ($user->sponsor_budget == "Between 5000-20000") {
                        $campaignbtwfivetwo = $campaignbtwfivetwo + 1;
                    } elseif ($user->sponsor_budget == "Above 20000") {
                        $campaignabovetwo = $campaignabovetwo + 1;
                    }
                    $campaignUser = $campaignUser + 1;
                } else if ($user->sponsor_for == "Content") {
                    if ($user->sponsor_budget == "Less than 2000") {
                        $contentlesstwo = $contentlesstwo + 1;
                    } elseif ($user->sponsor_budget == "Between 2000-5000") {
                        $contentbtwtwofive = $contentbtwtwofive + 1;
                    } elseif ($user->sponsor_budget == "Between 5000-20000") {
                        $contentbtwfivetwo = $contentbtwfivetwo + 1;
                    } elseif ($user->sponsor_budget == "Above 20000") {
                        $contentabovetwo = $contentabovetwo + 1;
                    }
                    $ContentUser = $ContentUser + 1;
                } else if ($user->sponsor_for == "Sports Team") {
                    if ($user->sponsor_budget == "Less than 2000") {
                        $sportlesstwo = $sportlesstwo + 1;
                    } elseif ($user->sponsor_budget == "Between 2000-5000") {
                        $sportbtwtwofive = $sportbtwtwofive + 1;
                    } elseif ($user->sponsor_budget == "Between 5000-20000") {
                        $sportbtwfivetwo = $sportbtwfivetwo + 1;
                    } elseif ($user->sponsor_budget == "Above 20000") {
                        $sportabovetwo = $sportabovetwo + 1;
                    }
                    $sportTeamUser = $sportTeamUser + 1;
                } else if ($user->sponsor_for == "Other") {
                    if ($user->sponsor_budget == "Less than 2000") {
                        $otherlesstwo = $otherlesstwo + 1;
                    } elseif ($user->sponsor_budget == "Between 2000-5000") {
                        $otherbtwtwofive = $otherbtwtwofive + 1;
                    } elseif ($user->sponsor_budget == "Between 5000-20000") {
                        $otherbtwfivetwo = $otherbtwfivetwo + 1;
                    } elseif ($user->sponsor_budget == "Above 20000") {
                        $otherabovetwo = $otherabovetwo + 1;
                    }
                    $otherUser = $otherUser + 1;
                } else if ($user->sponsor_for == "Venue") {
                    if ($user->sponsor_budget == "Less than 2000") {
                        $venuelesstwo = $venuelesstwo + 1;
                    } elseif ($user->sponsor_budget == "Between 2000-5000") {
                        $venuebtwtwofive = $venuebtwtwofive + 1;
                    } elseif ($user->sponsor_budget == "Between 5000-20000") {
                        $venuebtwfivetwo = $venuebtwfivetwo + 1;
                    } elseif ($user->sponsor_budget == "Above 20000") {
                        $venueabovetwo = $venueabovetwo + 1;
                    }
                    $venueUser = $venueUser + 1;
                } else if ($user->sponsor_for == "Not for Profit") {
                    if ($user->sponsor_budget == "Less than 2000") {
                        $profitlesstwo = $profitlesstwo + 1;
                    } elseif ($user->sponsor_budget == "Between 2000-5000") {
                        $profitbtwtwofive = $profitbtwtwofive + 1;
                    } elseif ($user->sponsor_budget == "Between 5000-20000") {
                        $profitbtwfivetwo = $profitbtwfivetwo + 1;
                    } elseif ($user->sponsor_budget == "Above 20000") {
                        $profitabovetwo = $profitabovetwo + 1;
                    }
                    $profitUser = $profitUser + 1;
                }
            }
            $getSpecifyList = SponsorrSpecify::where('user_id', $user->id)->get();
            if (isset($getSpecifyList) && $getSpecifyList != null) {
                foreach ($getSpecifyList as $userspecify) {
                    if ($userspecify->specify_name == 1) {
                        $conference = $conference + 1;
                    } elseif ($userspecify->specify_name == 2) {
                        $music_festival = $music_festival + 1;
                    } elseif ($userspecify->specify_name == 3) {
                        $tradeshow = $tradeshow + 1;
                    } elseif ($userspecify->specify_name == 4) {
                        $exhibition = $exhibition + 1;
                    } elseif ($userspecify->specify_name == 34) {
                        $Masterclass = $Masterclass + 1;
                    } elseif ($userspecify->specify_name == 35) {
                        $Seminar = $Seminar + 1;
                    } elseif ($userspecify->specify_name == 36) {
                        $Delegation = $Delegation + 1;
                    } elseif ($userspecify->specify_name == 37) {
                        $AwardsCompetitions = $AwardsCompetitions + 1;
                    } elseif ($userspecify->specify_name == 38) {
                        $ProductLaunch = $ProductLaunch + 1;
                    } elseif ($userspecify->specify_name == 39) {
                        $FestivalsParties = $FestivalsParties + 1;
                    }
                }
            }
            if (isset($getSpecifyList) && $getSpecifyList != null) {
                foreach ($getSpecifyList as $userspecify) {
                    if ($userspecify->specify_name == 5) {
                        $Online = $Online + 1;
                    } elseif ($userspecify->specify_name == 6) {
                        $Offline = $Offline + 1;
                    } elseif ($userspecify->specify_name == 7) {
                        $SocialMedia = $SocialMedia + 1;
                    } elseif ($userspecify->specify_name == 8) {
                        $Infleucer = $Infleucer + 1;
                    } elseif ($userspecify->specify_name == 51) {
                        $PR = $PR + 1;
                    } elseif ($userspecify->specify_name == 52) {
                        $OnlineGaming = $OnlineGaming + 1;
                    }

                }
            }
            if (isset($getSpecifyList) && $getSpecifyList != null) {
                foreach ($getSpecifyList as $userspecify) {
                    if ($userspecify->specify_name == 9) {
                        $Blog = $Blog + 1;
                    } elseif ($userspecify->specify_name == 10) {
                        $Video = $Video + 1;
                    } elseif ($userspecify->specify_name == 11) {
                        $Inforgraphics = $Inforgraphics + 1;
                    } elseif ($userspecify->specify_name == 12) {
                        $CaseStudies = $CaseStudies + 1;
                    } elseif ($userspecify->specify_name == 13) {
                        $Whitpapers = $Whitpapers + 1;
                    } elseif ($userspecify->specify_name == 14) {
                        $Articles = $Articles + 1;
                    } elseif ($userspecify->specify_name == 15) {
                        $Interviews = $Interviews + 1;
                    } elseif ($userspecify->specify_name == 16) {
                        $Memes = $Memes + 1;
                    } elseif ($userspecify->specify_name == 40) {
                        $Email = $Email + 1;
                    } elseif ($userspecify->specify_name == 41) {
                        $Experiential = $Experiential + 1;
                    } elseif ($userspecify->specify_name == 42) {
                        $Canvassing = $Canvassing + 1;
                    }
                }
            }
            if (isset($getSpecifyList) && $getSpecifyList != null) {
                foreach ($getSpecifyList as $userspecify) {
                    if ($userspecify->specify_name == 17) {
                        $Football = $Football + 1;
                    } elseif ($userspecify->specify_name == 18) {
                        $Regional = $Regional + 1;
                    } elseif ($userspecify->specify_name == 19) {
                        $AdventureSports = $AdventureSports + 1;
                    } elseif ($userspecify->specify_name == 20) {
                        $Racetrack = $Racetrack + 1;
                    } elseif ($userspecify->specify_name == 21) {
                        $International = $International + 1;
                    } elseif ($userspecify->specify_name == 44) {
                        $Cricket = $Cricket + 1;
                    } elseif ($userspecify->specify_name == 45) {
                        $Boxing = $Boxing + 1;
                    } elseif ($userspecify->specify_name == 46) {
                        $Golf = $Golf + 1;
                    } elseif ($userspecify->specify_name == 47) {
                        $Polo = $Polo + 1;
                    } elseif ($userspecify->specify_name == 48) {
                        $Skiing = $Skiing + 1;
                    } elseif ($userspecify->specify_name == 49) {
                        $PowerBoat = $PowerBoat + 1;
                    } elseif ($userspecify->specify_name == 50) {
                        $BaseBall = $BaseBall + 1;
                    }
                }
            }
            if (isset($getSpecifyList) && $getSpecifyList != null) {
                foreach ($getSpecifyList as $userspecify) {
                    if ($userspecify->specify_name == 22) {
                        $Outdoor = $Outdoor + 1;
                    } elseif ($userspecify->specify_name == 23) {
                        $indoor = $indoor + 1;
                    } elseif ($userspecify->specify_name == 24) {
                        $Sports = $Sports + 1;
                    } elseif ($userspecify->specify_name == 25) {
                        $Entertainment = $Entertainment + 1;
                    } elseif ($userspecify->specify_name == 26) {
                        $Shopping = $Shopping + 1;
                    } elseif ($userspecify->specify_name == 27) {
                        $Transport = $Transport + 1;
                    } elseif ($userspecify->specify_name == 28) {
                        $CSR = $CSR + 1;
                    } elseif ($userspecify->specify_name == 29) {
                        $Charity = $Charity + 1;
                    } elseif ($userspecify->specify_name == 30) {
                        $Aid = $Aid + 1;
                    } elseif ($userspecify->specify_name == 31) {
                        $Donation = $Donation + 1;
                    } elseif ($userspecify->specify_name == 32) {
                        $Campaign = $Campaign + 1;
                    } elseif ($userspecify->specify_name == 33) {
                        $Lobbying = $Lobbying + 1;
                    }
                }
            }

        }
        $finalArray['userCount'] = $userData->count();

        $finalArray['EventMessage'] = "Total " . $eventUser . " user " . $sponsorWord;
        if ($eventlesstwo > $eventbtwtwofive && $eventlesstwo > $eventbtwfivetwo && $eventlesstwo > $eventabovetwo && $eventlesstwo > 0) {
            $finalArray['EventBudgetMessage'] = "Less than USD 2000 ";
        } elseif ($eventbtwtwofive > $eventlesstwo && $eventbtwtwofive > $eventbtwfivetwo && $eventbtwtwofive > $eventabovetwo && $eventbtwtwofive > 0) {
            $finalArray['EventBudgetMessage'] = "Between USD 2000-USD 5000 ";
        } elseif ($eventbtwfivetwo > $eventbtwtwofive && $eventbtwfivetwo > $eventlesstwo && $eventbtwfivetwo > $eventabovetwo && $eventbtwfivetwo > 0) {
            $finalArray['EventBudgetMessage'] = "Between USD 5000-USD 20000 ";
        } elseif ($eventabovetwo >= $eventbtwtwofive && $eventabovetwo >= $eventbtwfivetwo && $eventabovetwo >= $eventlesstwo && $eventabovetwo > 0) {
            $finalArray['EventBudgetMessage'] = "Above USD 20000 ";
        } else {
            $finalArray['EventBudgetMessage'] = '';
        }
        $finalArray['EventUser'] = $eventUser;
        $finalArray['conference'] = $conference;
        $finalArray['music_festival'] = $music_festival;
        $finalArray['tradeshow'] = $tradeshow;
        $finalArray['exhibition'] = $exhibition;
        $finalArray['Masterclass'] = $Masterclass;
        $finalArray['Seminar'] = $Seminar;
        $finalArray['Delegation'] = $Delegation;
        $finalArray['AwardsCompetitions'] = $AwardsCompetitions;
        $finalArray['ProductLaunch'] = $ProductLaunch;
        $finalArray['FestivalsParties'] = $FestivalsParties;
        $finalArray['EventPer'] = $eventUser * 100 / 1000;
        $finalArray['CampaignMessage'] = "Total " . $campaignUser . " user " . $sponsorWord;
        if ($campaignlesstwo > $campaignbtwtwofive && $campaignlesstwo > $campaignbtwfivetwo && $campaignlesstwo > $campaignabovetwo && $campaignlesstwo > 0) {
            $finalArray['CampaingBudgetMessage'] = "Less than USD 2000 ";
        } elseif ($campaignbtwtwofive > $campaignlesstwo && $campaignbtwtwofive > $campaignbtwfivetwo && $campaignbtwtwofive > $campaignabovetwo && $campaignbtwtwofive > 0) {
            $finalArray['CampaingBudgetMessage'] = "Between USD 2000-USD 5000 ";
        } elseif ($campaignbtwfivetwo > $campaignbtwtwofive && $campaignbtwfivetwo > $campaignlesstwo && $campaignbtwfivetwo > $campaignabovetwo && $campaignbtwfivetwo > 0) {
            $finalArray['CampaingBudgetMessage'] = "Between USD 5000-USD 20000 ";
        } elseif ($campaignabovetwo > $campaignbtwtwofive && $campaignabovetwo > $campaignbtwfivetwo && $campaignabovetwo > $campaignlesstwo && $campaignabovetwo > 0) {
            $finalArray['CampaingBudgetMessage'] = "Above USD 20000 ";
        } else {
            $finalArray['CampaingBudgetMessage'] = '';
        }
        $finalArray['CampaignUser'] = $campaignUser;
        $finalArray['Online'] = $Online;
        $finalArray['Offline'] = $Offline;
        $finalArray['SocialMedia'] = $SocialMedia;
        $finalArray['Infleucer'] = $Infleucer;
        $finalArray['Blog'] = $Blog;
        $finalArray['Email'] = $Email;
        $finalArray['Experiential'] = $Experiential;
        $finalArray['Canvassing'] = $Canvassing;
        $finalArray['CampaignPer'] = $campaignUser * 100 / 1000;
        $finalArray['ContentMessage'] = "Total " . $ContentUser . " user " . $sponsorWord;
        if ($contentlesstwo > $contentbtwtwofive && $contentlesstwo > $contentbtwfivetwo && $contentlesstwo > $contentabovetwo && $contentlesstwo > 0) {
            $finalArray['ContentBudgetMessage'] = "Less than USD 2000 ";
        } elseif ($contentbtwtwofive > $contentlesstwo && $contentbtwtwofive > $contentbtwfivetwo && $contentbtwtwofive > $contentabovetwo && $contentbtwtwofive > 0) {
            $finalArray['ContentBudgetMessage'] = "Between USD 2000-USD 5000 ";
        } elseif ($contentbtwfivetwo > $contentbtwtwofive && $contentbtwfivetwo > $contentlesstwo && $contentbtwfivetwo > $contentabovetwo && $contentbtwfivetwo > 0) {
            $finalArray['ContentBudgetMessage'] = "Between USD 5000-USD 20000 ";
        } elseif ($contentabovetwo > $contentbtwtwofive && $contentabovetwo > $contentbtwfivetwo && $contentabovetwo > $contentlesstwo && $contentabovetwo > 0) {
            $finalArray['ContentBudgetMessage'] = "Above USD 20000 ";
        } else {
            $finalArray['ContentBudgetMessage'] = '';
        }
        $finalArray['ContentUser'] = $ContentUser;
        $finalArray['Video'] = $Video;
        $finalArray['Inforgraphics'] = $Inforgraphics;
        $finalArray['CaseStudies'] = $CaseStudies;
        $finalArray['Whitpapers'] = $Whitpapers;
        $finalArray['Articles'] = $Articles;
        $finalArray['Interviews'] = $Interviews;
        $finalArray['Memes'] = $Memes;
        $finalArray['ContentUserPer'] = $ContentUser * 100 / 1000;
        $finalArray['SportTeamMessage'] = "Total " . $sportTeamUser . " user " . $sponsorWord;
        if ($sportlesstwo > $sportbtwtwofive && $sportlesstwo > $sportbtwfivetwo && $sportlesstwo > $sportabovetwo && $sportlesstwo > 0) {
            $finalArray['SportBudgetMessage'] = "Less than USD 2000 ";
        } elseif ($sportbtwtwofive > $sportlesstwo && $sportbtwtwofive > $sportbtwfivetwo && $sportbtwtwofive > $sportabovetwo && $sportbtwtwofive > 0) {
            $finalArray['SportBudgetMessage'] = "Between USD 2000-USD 5000 ";
        } elseif ($sportbtwfivetwo > $sportbtwtwofive && $sportbtwfivetwo > $sportlesstwo && $sportbtwfivetwo > $sportabovetwo && $sportbtwfivetwo > 0) {
            $finalArray['SportBudgetMessage'] = "Between USD 5000-USD 20000";
        } elseif ($sportabovetwo > $sportbtwtwofive && $sportabovetwo > $sportbtwfivetwo && $sportabovetwo > $sportlesstwo && $sportabovetwo > 0) {
            $finalArray['SportBudgetMessage'] = "Above USD 20000 ";
        } else {
            $finalArray['SportBudgetMessage'] = '';
        }
        $finalArray['SportTeamUser'] = $sportTeamUser;
        $finalArray['Football'] = $Football;
        $finalArray['Regional'] = $Regional;
        $finalArray['AdventureSports'] = $AdventureSports;
        $finalArray['Racetrack'] = $Racetrack;
        $finalArray['International'] = $International;
        $finalArray['Cricket'] = $Cricket;
        $finalArray['Boxing'] = $Boxing;
        $finalArray['Golf'] = $Golf;
        $finalArray['Polo'] = $Polo;
        $finalArray['Skiing'] = $Skiing;
        $finalArray['PowerBoat'] = $PowerBoat;
        $finalArray['BaseBall'] = $BaseBall;
        $finalArray['OnlineGaming'] = $OnlineGaming;
        $finalArray['PR'] = $PR;
        $finalArray['SportTeamUserPer'] = $sportTeamUser * 100 / 1000;
        $finalArray['OtherMessage'] = "Total " . $otherUser . " user " . $sponsorWord;
        if ($otherlesstwo > $otherbtwtwofive && $otherlesstwo > $otherbtwfivetwo && $otherlesstwo > $otherabovetwo && $otherlesstwo > 0) {
            $finalArray['OtherBudgetMessage'] = "Less than USD 2000 ";
        } elseif ($otherbtwtwofive > $otherlesstwo && $otherbtwtwofive > $otherbtwfivetwo && $otherbtwtwofive > $otherabovetwo && $otherbtwtwofive > 0) {
            $finalArray['OtherBudgetMessage'] = "Between USD 2000-USD 5000 ";
        } elseif ($otherbtwfivetwo > $otherbtwtwofive && $otherbtwfivetwo > $otherlesstwo && $otherbtwfivetwo > $otherabovetwo && $otherbtwfivetwo > 0) {
            $finalArray['OtherBudgetMessage'] = "Between USD 5000-USD 20000 ";
        } elseif ($otherabovetwo > $otherbtwtwofive && $otherabovetwo > $otherbtwfivetwo && $otherabovetwo > $otherlesstwo && $otherabovetwo > 0) {
            $finalArray['OtherBudgetMessage'] = "Above USD 20000";
        } else {
            $finalArray['OtherBudgetMessage'] = '';
        }
        $finalArray['OtherUser'] = $otherUser;

        if ($venuelesstwo > $venuebtwtwofive && $venuelesstwo > $venuebtwfivetwo && $venuelesstwo > $venueabovetwo && $venuelesstwo > 0) {
            $finalArray['VenueBudgetMessage'] = "Less than USD 2000 ";
        } elseif ($venuebtwtwofive > $venuelesstwo && $venuebtwtwofive > $venuebtwfivetwo && $venuebtwtwofive > $venueabovetwo && $venuebtwtwofive > 0) {
            $finalArray['VenueBudgetMessage'] = "Between USD 2000-USD 5000 ";
        } elseif ($venuebtwfivetwo > $venuebtwtwofive && $venuebtwfivetwo > $venuelesstwo && $venuebtwfivetwo > $venueabovetwo && $venuebtwfivetwo > 0) {
            $finalArray['VenueBudgetMessage'] = "Between USD 5000-USD 20000 ";
        } elseif ($venueabovetwo > $venuebtwtwofive && $venueabovetwo > $venuebtwfivetwo && $venueabovetwo > $venuelesstwo && $venueabovetwo > 0) {
            $finalArray['VenueBudgetMessage'] = "Above USD 20000 ";
        } else {
            $finalArray['VenueBudgetMessage'] = '';
        }
        $finalArray['profitUser'] = $profitUser;
        $finalArray['CSR'] = $CSR;
        $finalArray['Aid'] = $Aid;
        $finalArray['Donation'] = $Donation;
        $finalArray['Campaign'] = $Campaign;
        $finalArray['Lobbying'] = $Lobbying;
        $finalArray['Charity'] = $Charity;
        $finalArray['ProfitPer'] = $profitUser * 100 / 1000;
        $finalArray['ProfitMessage'] = "Total " . $profitUser . " user " . $sponsorWord;

        if ($profitlesstwo > $profitbtwtwofive && $profitlesstwo > $profitbtwfivetwo && $profitlesstwo > $profitabovetwo && $profitlesstwo > 0) {
            $finalArray['ProfitBudgetMessage'] = "Less than USD 2000 ";
        } elseif ($profitbtwtwofive > $profitlesstwo && $profitbtwtwofive > $profitbtwfivetwo && $profitbtwtwofive > $profitabovetwo && $profitbtwtwofive > 0) {
            $finalArray['ProfitBudgetMessage'] = "Between USD 2000-USD 5000 ";
        } elseif ($profitbtwfivetwo > $profitbtwtwofive && $profitbtwfivetwo > $profitlesstwo && $profitbtwfivetwo > $profitabovetwo && $profitbtwfivetwo > 0) {
            $finalArray['ProfitBudgetMessage'] = "Between USD 5000-USD 20000 ";
        } elseif ($profitabovetwo > $profitbtwtwofive && $profitabovetwo > $profitbtwfivetwo && $profitabovetwo > $profitlesstwo && $profitabovetwo > 0) {
            $finalArray['ProfitBudgetMessage'] = "Above USD 20000 ";
        } else {
            $finalArray['ProfitBudgetMessage'] = '';
        }
        $finalArray['VenueUser'] = $venueUser;
        $finalArray['Outdoor'] = $Outdoor;
        $finalArray['indoor'] = $indoor;
        $finalArray['Entertainment'] = $Entertainment;

        $finalArray['Sports'] = $Sports;
        $finalArray['Shopping'] = $Shopping;
        $finalArray['Transport'] = $Transport;
        $finalArray['VenuePer'] = $venueUser * 100 / 1000;
        $finalArray['VenueMessage'] = "Total " . $venueUser . " user " . $sponsorWord;
        $eventTotalUsers = User::where('sponsor_for', 'Event')->where('sponsor_type', $sponsor_type)
            ->where('country', $country_code)->where('userstatus', 1)->get()->count();
        $campaignTotalUsers = User::where('sponsor_for', 'Campaign')->where('sponsor_type', $sponsor_type)
            ->where('country', $country_code)->where('userstatus', 1)->get()->count();
        $contentTotalUsers = User::where('sponsor_for', 'Content')->where('sponsor_type', $sponsor_type)
            ->where('country', $country_code)->where('userstatus', 1)->get()->count();
        $SportsTotalUsers = User::where('sponsor_for', 'Sports Team')->where('sponsor_type', $sponsor_type)
            ->where('country', $country_code)->where('userstatus', 1)->get()->count();
        $VenueTotalUsers = User::where('sponsor_for', 'Venue')->where('sponsor_type', $sponsor_type)
            ->where('country', $country_code)->where('userstatus', 1)->get()->count();
        $ProfitTotalUsers = User::where('sponsor_for', 'Not for Profit')->where('sponsor_type', $sponsor_type)
            ->where('country', $country_code)->where('userstatus', 1)->get()->count();
        $OtherTotalUsers = User::where('sponsor_for', 'Other')->where('sponsor_type', $sponsor_type)
            ->where('country', $country_code)->where('userstatus', 1)->get()->count();
        $finalArray['OtherUserPer'] = $otherUser * 100 / 1000;

        $view = view('mappopup', compact('userData', 'finalArray', 'contry_userData', 'sponsor_type', 'eventTotalUsers',
            'campaignTotalUsers', 'contentTotalUsers', 'SportsTotalUsers', 'OtherTotalUsers', 'VenueTotalUsers',
            'ProfitTotalUsers'))->render();

        return response()->json($view);
    }

    public function topcountriesdata(Request $request)
    {
        if (Auth::user()->sponsor_type == 1) {
            $sponsor_type = 2;
            $sponsorWord = "Manage Or Receive Sponsorship";
        } else {
            $sponsor_type = 1;
            $sponsorWord = "Offer Sponsorship";
        }
        $contry_userData = User::with('counties')->where('sponsor_type', 1)->where('userstatus', 1)->groupBy('country')
            ->select(DB::raw('count(*) as user_count, country'))->orderBy('user_count', 'Desc')->limit(10)->get();

        $view = view('mappopupcountry', compact('contry_userData', 'sponsorWord'))->render();

        return response()->json($view);
    }

    public function topindustriesdata(Request $request)
    {
        if (Auth::user()->sponsor_type == 1) {
            $sponsor_type = 2;
            $sponsorWord = "Manage Or Receive Sponsorship";
        } else {
            $sponsor_type = 1;
            $sponsorWord = "Offer Sponsorship";
        }
        $contry_userData = User::with('industry')->where('sponsor_type', 1)->where('sponsor_industry', '!=', 'test')
            ->where('userstatus', 1)->where('sponsor_industry', '!=', null)->groupBy('sponsor_industry')
            ->select(DB::raw('count(*) as user_count, sponsor_industry'))->orderBy('user_count', 'Desc')->limit(10)
            ->get();

        $view = view('mappopupindustries', compact('contry_userData', 'sponsorWord'))->render();

        return response()->json($view);
    }

    public function toprecieptdsdata(Request $request)
    {
        if (Auth::user()->sponsor_type == 1) {
            $sponsor_type = 2;
            $sponsorWord = "Manage Or Receive Sponsorship";
        } else {
            $sponsor_type = 1;
            $sponsorWord = "Offer Sponsorship";
        }
        $specificationData = SponsorrSpecifyList::get();
        $resultArray = [];

        $orders = DB::select("SELECT specify_name,count(*) AS c
								FROM sponsorr_specifies
								JOIN users ON users.id = sponsorr_specifies.user_id
								WHERE users.userstatus = 1
								AND users.sponsor_type = 1
								AND users.id != 1
								AND users.deleted_at IS NULL
								AND sponsorr_specifies.deleted_at IS NULL
								GROUP BY specify_name HAVING c >= 1
								ORDER BY c DESC");
        if ($orders > 0) {
            foreach ($orders as $key => $value) {
                if ($value->c > 0) {
                    $resultArray[$key]['id'] = $value->specify_name;
                    $resultArray[$key]['count'] = $value->c;
                    $resultArray[$key][] = SponsorrSpecifyList::where('id', '=', $value->specify_name)
                        ->select('specify_name as specifyName')->first()->toArray();
                    //dd($resultArray[$key]);
                }

            }
        }

        $view = view('mappopuprecepists', compact('resultArray', 'sponsorWord'))->render();

        return response()->json($view);
    }


    public function topdestinationdata(Request $request)
    {
        if (Auth::user()->sponsor_type == 1) {
            $sponsor_type = 2;
            $sponsorWord = "Manage Or Receive Sponsorship";
        } else {
            $sponsor_type = 1;
            $sponsorWord = "Offer Sponsorship";
        }
        $contry_userData = User::with('sponsor_counties')->where('userstatus', 1)->where('sponsor_type', 1)
            ->where('sponsor_country', '!=', null)->groupBy('sponsor_country')
            ->select(DB::raw('count(*) as user_count, sponsor_country'))->orderBy('user_count', 'Desc')->limit(10)
            ->get();
        $view = view('mappopupdestination', compact('contry_userData', 'sponsorWord'))->render();

        return response()->json($view);
    }

    public function getspecify(Request $request)
    {
        $event_type = $request->event_type;
        $getSpecify = SponsorrSpecifyList::where('sponsorr_type', $event_type)->get();

        return response()->json($getSpecify);

    }

    public function getspecifyedit(Request $request)
    {
        $event_type = $request->event_type;
        $bidid = $request->bidid;
        $selectedbid = SponsorrSpecifyBid::where('bid_id', $bidid)->select('specify_name')->get();
        $getSpecify = SponsorrSpecifyList::where('sponsorr_type', $event_type)->get();
        $selectedb = [];
        foreach ($selectedbid as $sbid) {
            $selectedb[] = $sbid->specify_name;
        }
        foreach ($getSpecify as $specify) {
            if (in_array($specify->id, $selectedb)) {
                $specify->selected = 1;
            } else {
                $specify->selected = 0;
            }
        }

        return response()->json($getSpecify);

    }

    public function getCountry()
    {
        $country = Industry::all();
        $countries = [];
        foreach ($country as $c) {
            $countries[] = $c->name;
        }
        dd(implode(',', $countries));
    }

    public function referAFriend(Request $request)
    {
        if ($request->emailone == '' && $request->emailtwo == '' && $request->emailthree == '' && $request->emailfour == '' && $request->emailfive == '' && $request->emailsix == '' && $request->emailseven == '' && $request->emaileight == '' && $request->emailnine == '' && $request->emailten == '') {
            $result = [
                'msg' => "Atleast Enter One Email Address",
                'status' => '0'
            ];

            return response()->json($result);
        } else {
            $referId = '';
            $userAsRefer = ReferAFriend::where('user_id', Auth::user()->id)->count();
            $referCount = 0;
            $userisApprovedOne = User::where('id', Auth::user()->id)->first();
            if ($request->emailone != '') {
                $emailData = ReferAFriend::where('email_address', $request->emailone)
                    ->where('user_id', Auth::user()->id)->count();

                // $err_msg='Following Email Address Exist in System';
                if ($emailData) {

                } else {
                    $data = [
                        'user_id' => Auth::user()->id,
                        'email_address' => $request->emailone
                    ];
                    $smsdata = [
                        'to' => $request->emailone,
                        'subject' => "Recommended for Ayojn",
                        'message' => "Dear - you have been referred by " . Auth::user()->email . " to sign up at Ayojn. <br/>Ayojn is a discovery platform for Sponsored Outreach. <br/> We look forward to see you at Ayojn.<br/><br/><strong>Outreach Team</strong> <br/><strong>Ayojn</strong> <br/><strong><a href='https://www.ayojn.com'>www.ayojn.com</a></strong> ",
                    ];
                    $send_mail = send_mail($smsdata);
                    $referId = ReferAFriend::create($data);
                    $referCount++;
                    $edata = ReferAFriend::where('user_id', Auth::user()->id)->count();
                    if ($edata == 3) {
                        if ($userisApprovedOne->userstatus == 1 && $userisApprovedOne->email_verified == 1 && $userisApprovedOne->is_3refer != 1) {
                            $userisApprovedOne->is_3refer = 1;
                            $userisApprovedOne->disapprove_time = date("Y-m-d H:i:s", strtotime('+12 month'));
                            $userisApprovedOne->save();
                        } else {
                            $userisApprovedOne->is_3refer = 1;
                            $userisApprovedOne->save();
                        }
                        $smsdata = [
                            'to' => Auth::user()->email,
                            'subject' => "Ayojn access approved",
                            'message' => "Thank you for recommending Ayojn to your colleagues and feel free to do it more. For now your access to Sponsor has been approved for the next 12 months.<br/>See you at Ayojn. <br/><strong>Outreach Team</strong><br/><strong>Ayojn</strong>",
                        ];
                        //$send_mail = send_mail($smsdata);
                    }
                }
            }
            if ($request->emailtwo != '') {
                $emailDatatwo = ReferAFriend::where('email_address', $request->emailtwo)
                    ->where('user_id', Auth::user()->id)->count();

                if ($emailDatatwo) {

                } else {
                    $data = [
                        'user_id' => Auth::user()->id,
                        'email_address' => $request->emailtwo
                    ];
                    $smsdata = [
                        'to' => $request->emailtwo,
                        'subject' => "Recommended for Ayojn",
                        'message' => "Dear - you have been referred by " . Auth::user()->email . " to sign up at Ayojn. <br/>Ayojn is a discovery platform for Sponsored Outreach.<br/> We look forward to see you at Ayojn.<br/><br/><strong>Outreach Team</strong> <br/><strong>Ayojn</strong><br/><strong><a href='https://www.ayojn.com'>www.ayojn.com</a></strong> ",
                    ];
                    $send_mail = send_mail($smsdata);
                    $referId = ReferAFriend::create($data);
                    $referCount++;
                    $edata = ReferAFriend::where('user_id', Auth::user()->id)->count();
                    if ($edata == 3) {
                        if ($userisApprovedOne->userstatus == 1 && $userisApprovedOne->email_verified == 1 && $userisApprovedOne->is_3refer != 1) {
                            $userisApprovedOne->is_3refer = 1;
                            $userisApprovedOne->disapprove_time = date("Y-m-d H:i:s", strtotime('+12 month'));
                            $userisApprovedOne->save();
                        } else {
                            $userisApprovedOne->is_3refer = 1;
                            $userisApprovedOne->save();
                        }
                        $smsdata = [
                            'to' => Auth::user()->email,
                            'subject' => "Ayojn access approved",
                            'message' => "Thank you for recommending Ayojn to your colleagues and feel free to do it more. For now your access to Sponsor has been approved for the next 12 months.<br/>See you at Ayojn. <br/><strong>Outreach Team</strong><br/><strong>Ayojn</strong>",
                        ];
                        //$send_mail = send_mail($smsdata);
                    }
                }
            }
            if ($request->emailthree != '') {
                $emailDatathree = ReferAFriend::where('email_address', $request->emailthree)
                    ->where('user_id', Auth::user()->id)->count();

                if ($emailDatathree) {

                } else {
                    $data = [
                        'user_id' => Auth::user()->id,
                        'email_address' => $request->emailthree
                    ];
                    $smsdata = [
                        'to' => $request->emailthree,
                        'subject' => "Recommended for Ayojn",
                        'message' => "Dear - you have been referred by " . Auth::user()->email . " to sign up at Ayojn. <br/>Ayojn is a discovery platform for Sponsored Outreach. <br/> We look forward to see you at Ayojn.<br/><br/><strong>Outreach Team</strong> <br/><strong>Ayojn</strong><br/><strong><a href='https://www.ayojn.com'>www.ayojn.com</a></strong> ",
                    ];
                    $send_mail = send_mail($smsdata);
                    $referId = ReferAFriend::create($data);
                    $referCount++;
                    $edata = ReferAFriend::where('user_id', Auth::user()->id)->count();
                    if ($edata == 3) {
                        if ($userisApprovedOne->userstatus == 1 && $userisApprovedOne->email_verified == 1 && $userisApprovedOne->is_3refer != 1) {
                            $userisApprovedOne->is_3refer = 1;
                            $userisApprovedOne->disapprove_time = date("Y-m-d H:i:s", strtotime('+12 month'));
                            $userisApprovedOne->save();
                        } else {
                            $userisApprovedOne->is_3refer = 1;
                            $userisApprovedOne->save();
                        }
                        $smsdata = [
                            'to' => Auth::user()->email,
                            'subject' => "Ayojn access approved",
                            'message' => "Thank you for recommending Ayojn to your colleagues and feel free to do it more. For now your access to Sponsor has been approved for the next 12 months.<br/>See you at Ayojn. <br/><strong>Outreach Team</strong><br/><strong>Ayojn</strong>",
                        ];
                        //$send_mail = send_mail($smsdata);
                    }
                }
            }
            if ($request->emailfour != '') {
                $emailDatathree = ReferAFriend::where('email_address', $request->emailfour)
                    ->where('user_id', Auth::user()->id)->count();

                if ($emailDatathree) {

                } else {
                    $data = [
                        'user_id' => Auth::user()->id,
                        'email_address' => $request->emailfour
                    ];
                    $smsdata = [
                        'to' => $request->emailfour,
                        'subject' => "Recommended for Ayojn",
                        'message' => "Dear - you have been referred by " . Auth::user()->email . " to sign up at Ayojn. <br/>Ayojn is a discovery platform for Sponsored Outreach.<br/> We look forward to see you at Ayojn.<br/><br/><strong>Outreach Team</strong> <br/><strong>Ayojn</strong><br/><strong><a href='https://www.ayojn.com'>www.ayojn.com</a></strong> ",
                    ];
                    $send_mail = send_mail($smsdata);
                    $referId = ReferAFriend::create($data);
                    $referCount++;
                    $edata = ReferAFriend::where('user_id', Auth::user()->id)->count();
                    if ($edata == 3) {
                        if ($userisApprovedOne->userstatus == 1 && $userisApprovedOne->email_verified == 1 && $userisApprovedOne->is_3refer != 1) {
                            $userisApprovedOne->is_3refer = 1;
                            $userisApprovedOne->disapprove_time = date("Y-m-d H:i:s", strtotime('+12 month'));
                            $userisApprovedOne->save();
                        } else {
                            $userisApprovedOne->is_3refer = 1;
                            $userisApprovedOne->save();
                        }
                        $smsdata = [
                            'to' => Auth::user()->email,
                            'subject' => "Ayojn access approved",
                            'message' => "Thank you for recommending Ayojn to your colleagues and feel free to do it more. For now your access to Sponsor has been approved for the next 12 months.<br/>See you at Ayojn. <br/><strong>Outreach Team</strong><br/><strong>Ayojn</strong>",
                        ];
                        //$send_mail = send_mail($smsdata);
                    }
                }
            }
            if ($request->emailfive != '') {
                $emailDatathree = ReferAFriend::where('email_address', $request->emailfive)
                    ->where('user_id', Auth::user()->id)->count();

                //dd($emailDatathree);
                if ($emailDatathree) {

                } else {
                    $data = [
                        'user_id' => Auth::user()->id,
                        'email_address' => $request->emailfive
                    ];
                    $smsdata = [
                        'to' => $request->emailfive,
                        'subject' => "Recommended for Ayojn",
                        'message' => "Dear - you have been referred by " . Auth::user()->email . " to sign up at Ayojn. <br/>Ayojn is a discovery platform for Sponsored Outreach. <br/> We look forward to see you at Ayojn.<br/><br/><strong>Outreach Team</strong> <br/><strong>Ayojn</strong><br/><strong><a href='https://www.Ayojn.com'>www.Ayojn.com</a></strong> ",
                    ];
                    $send_mail = send_mail($smsdata);
                    $referId = ReferAFriend::create($data);
                    $referCount++;
                    $edata = ReferAFriend::where('user_id', Auth::user()->id)->count();
                    if ($edata == 3) {
                        if ($userisApprovedOne->userstatus == 1 && $userisApprovedOne->email_verified == 1 && $userisApprovedOne->is_3refer != 1) {
                            $userisApprovedOne->is_3refer = 1;
                            $userisApprovedOne->disapprove_time = date("Y-m-d H:i:s", strtotime('+12 month'));
                            $userisApprovedOne->save();
                        } else {
                            $userisApprovedOne->is_3refer = 1;
                            $userisApprovedOne->save();
                        }
                        $smsdata = [
                            'to' => Auth::user()->email,
                            'subject' => "Ayojn access approved",
                            'message' => "Thank you for recommending Ayojn to your colleagues and feel free to do it more. For now your access to Sponsor has been approved for the next 12 months.<br/>See you at Ayojn. <br/><strong>Outreach Team</strong><br/><strong>Ayojn</strong>",
                        ];
                        //$send_mail = send_mail($smsdata);
                    }
                }
            }
            if ($request->emailsix != '') {
                $emailDatathree = ReferAFriend::where('email_address', $request->emailsix)
                    ->where('user_id', Auth::user()->id)->count();

                if ($emailDatathree) {

                } else {
                    $data = [
                        'user_id' => Auth::user()->id,
                        'email_address' => $request->emailsix
                    ];
                    $smsdata = [
                        'to' => $request->emailsix,
                        'subject' => "Recommended for Ayojn",
                        'message' => "Dear - you have been referred by " . Auth::user()->email . " to sign up at Ayojn. <br/>Ayojn is a discovery platform for Sponsored Outreach.<br/> We look forward to see you at Ayojn.<br/><br/><strong>Outreach Team</strong> <br/><strong>Ayojn</strong><br/><strong><a href='https://www.ayojn.com'>www.ayojn.com</a></strong> ",
                    ];
                    $send_mail = send_mail($smsdata);
                    $referId = ReferAFriend::create($data);
                    $referCount++;
                    $edata = ReferAFriend::where('user_id', Auth::user()->id)->count();
                    if ($edata == 3) {
                        if ($userisApprovedOne->userstatus == 1 && $userisApprovedOne->email_verified == 1 && $userisApprovedOne->is_3refer != 1) {
                            $userisApprovedOne->is_3refer = 1;
                            $userisApprovedOne->disapprove_time = date("Y-m-d H:i:s", strtotime('+12 month'));
                            $userisApprovedOne->save();
                        } else {
                            $userisApprovedOne->is_3refer = 1;
                            $userisApprovedOne->save();
                        }
                        $smsdata = [
                            'to' => Auth::user()->email,
                            'subject' => "Ayojn access approved",
                            'message' => "Thank you for recommending Ayojn to your colleagues and feel free to do it more. For now your access to Sponsor has been approved for the next 12 months.<br/>See you at Ayojn. <br/><strong>Outreach Team</strong><br/><strong>Ayojn</strong>",
                        ];
                        //$send_mail = send_mail($smsdata);
                    }
                }
            }
            if ($request->emailseven != '') {
                $emailDatathree = ReferAFriend::where('email_address', $request->emailseven)
                    ->where('user_id', Auth::user()->id)->count();

                if ($emailDatathree) {

                } else {
                    $data = [
                        'user_id' => Auth::user()->id,
                        'email_address' => $request->emailseven
                    ];
                    $smsdata = [
                        'to' => $request->emailseven,
                        'subject' => "Recommended for Ayojn",
                        'message' => "Dear - you have been referred by " . Auth::user()->email . " to sign up at Ayojn. <br/>Ayojn is a discovery platform for Sponsored Outreach.<br/> We look forward to see you at Ayojn.<br/><br/><strong>Outreach Team</strong> <br/><strong>Ayojn</strong><br/><strong><a href='https://www.ayojn.com'>www.ayojn.com</a></strong> ",
                    ];
                    $send_mail = send_mail($smsdata);
                    $referId = ReferAFriend::create($data);
                    $referCount++;
                    $edata = ReferAFriend::where('user_id', Auth::user()->id)->count();
                    if ($edata == 3) {
                        if ($userisApprovedOne->userstatus == 1 && $userisApprovedOne->email_verified == 1 && $userisApprovedOne->is_3refer != 1) {
                            $userisApprovedOne->is_3refer = 1;
                            $userisApprovedOne->disapprove_time = date("Y-m-d H:i:s", strtotime('+12 month'));
                            $userisApprovedOne->save();
                        } else {
                            $userisApprovedOne->is_3refer = 1;
                            $userisApprovedOne->save();
                        }
                        $smsdata = [
                            'to' => Auth::user()->email,
                            'subject' => "Ayojn access approved",
                            'message' => "Thank you for recommending Ayojn to your colleagues and feel free to do it more. For now your access to Sponsor has been approved for the next 12 months.<br/>See you at Ayojn. <br/><strong>Outreach Team</strong><br/><strong>Ayojn</strong>",
                        ];
                        //$send_mail = send_mail($smsdata);
                    }
                }
            }
            if ($request->emaileight != '') {
                $emailDatathree = ReferAFriend::where('email_address', $request->emaileight)
                    ->where('user_id', Auth::user()->id)->count();

                if ($emailDatathree) {

                } else {
                    $data = [
                        'user_id' => Auth::user()->id,
                        'email_address' => $request->emaileight
                    ];
                    $smsdata = [
                        'to' => $request->emaileight,
                        'subject' => "Recommended for Ayojn",
                        'message' => "Dear - you have been referred by " . Auth::user()->email . " to sign up at Ayojn. <br/>Ayojn is a discovery platform for Sponsored Outreach.<br/> We look forward to see you at Ayojn.<br/><br/><strong>Outreach Team</strong> <br/><strong>Ayojn</strong><br/><strong><a href='https://www.ayojn.com'>www.ayojn.com</a></strong> ",
                    ];
                    $send_mail = send_mail($smsdata);
                    $referId = ReferAFriend::create($data);
                    $referCount++;
                    $edata = ReferAFriend::where('user_id', Auth::user()->id)->count();
                    if ($edata == 3) {
                        if ($userisApprovedOne->userstatus == 1 && $userisApprovedOne->email_verified == 1 && $userisApprovedOne->is_3refer != 1) {
                            $userisApprovedOne->is_3refer = 1;
                            $userisApprovedOne->disapprove_time = date("Y-m-d H:i:s", strtotime('+12 month'));
                            $userisApprovedOne->save();
                        } else {
                            $userisApprovedOne->is_3refer = 1;
                            $userisApprovedOne->save();
                        }
                        $smsdata = [
                            'to' => Auth::user()->email,
                            'subject' => "Ayojn access approved",
                            'message' => "Thank you for recommending Ayojn to your colleagues and feel free to do it more. For now your access to Sponsor has been approved for the next 12 months.<br/>See you at Ayojn. <br/><strong>Outreach Team</strong><br/><strong>Ayojn</strong>",
                        ];
                        //$send_mail = send_mail($smsdata);
                    }
                }
            }
            if ($request->emailnine != '') {
                $emailDatanine = ReferAFriend::where('email_address', $request->emailnine)
                    ->where('user_id', Auth::user()->id)->count();

                if ($emailDatanine) {

                } else {
                    $data = [
                        'user_id' => Auth::user()->id,
                        'email_address' => $request->emailnine
                    ];
                    $smsdata = [
                        'to' => $request->emailnine,
                        'subject' => "Recommended for Ayojn",
                        'message' => "Dear - you have been referred by " . Auth::user()->email . " to sign up at Ayojn. <br/>Ayojn is a discovery platform for Sponsored Outreach.<br/> We look forward to see you at Ayojn.<br/><br/><strong>Outreach Team</strong> <br/><strong>Ayojn</strong><br/><strong><a href='https://www.ayojn.com'>www.ayojn.com</a></strong> ",
                    ];
                    $send_mail = send_mail($smsdata);
                    $referId = ReferAFriend::create($data);
                    $referCount++;
                    $edata = ReferAFriend::where('user_id', Auth::user()->id)->count();
                    if ($edata == 3) {
                        if ($userisApprovedOne->userstatus == 1 && $userisApprovedOne->email_verified == 1 && $userisApprovedOne->is_3refer != 1) {
                            $userisApprovedOne->is_3refer = 1;
                            $userisApprovedOne->disapprove_time = date("Y-m-d H:i:s", strtotime('+12 month'));
                            $userisApprovedOne->save();
                        } else {
                            $userisApprovedOne->is_3refer = 1;
                            $userisApprovedOne->save();
                        }
                        $smsdata = [
                            'to' => Auth::user()->email,
                            'subject' => "Ayojn access approved",
                            'message' => "Thank you for recommending Ayojn to your colleagues and feel free to do it more. For now your access to Sponsor has been approved for the next 12 months.<br/>See you at Ayojn. <br/><strong>Outreach Team</strong><br/><strong>Ayojn</strong>",
                        ];
                        //$send_mail = send_mail($smsdata);
                    }
                }
            }
            if ($request->emailten != '') {
                $emailDatathree = ReferAFriend::where('email_address', $request->emailten)
                    ->where('user_id', Auth::user()->id)->count();

                if ($emailDatathree) {

                } else {
                    $data = [
                        'user_id' => Auth::user()->id,
                        'email_address' => $request->emailten
                    ];
                    $smsdata = [
                        'to' => $request->emailten,
                        'subject' => "Recommended for Ayojn",
                        'message' => "Dear - you have been referred by " . Auth::user()->email . " to sign up at Ayojn. <br/>Ayojn is a discovery platform for Sponsored Outreach.<br/> We look forward to see you at Ayojn.<br/><br/><strong>Outreach Team</strong> <br/><strong>Ayojn</strong><br/><strong><a href='https://www.ayojn.com'>www.ayojn.com</a></strong> ",
                    ];
                    $send_mail = send_mail($smsdata);
                    $referId = ReferAFriend::create($data);
                    $referCount++;
                    $edata = ReferAFriend::where('user_id', Auth::user()->id)->count();
                    if ($edata == 3) {
                        if ($userisApprovedOne->userstatus == 1 && $userisApprovedOne->email_verified == 1 && $userisApprovedOne->is_3refer != 1) {
                            $userisApprovedOne->is_3refer = 1;
                            $userisApprovedOne->disapprove_time = date("Y-m-d H:i:s", strtotime('+12 month'));
                            $userisApprovedOne->save();
                        } else {
                            $userisApprovedOne->is_3refer = 1;
                            $userisApprovedOne->save();
                        }
                        $smsdata = [
                            'to' => Auth::user()->email,
                            'subject' => "Ayojn access approved",
                            'message' => "Thank you for recommending Ayojn to your colleagues and feel free to do it more. For now your access to Sponsor has been approved for the next 12 months.<br/>See you at Ayojn. <br/><strong>Outreach Team</strong><br/><strong>Ayojn</strong>",
                        ];
                        //$send_mail = send_mail($smsdata);
                    }
                }
            }
            if ($referCount >= 3 && $userAsRefer == 0) {
                $userisApproved = User::where('id', Auth::user()->id)->first();
                if ($userisApproved->userstatus == 1 && $userisApproved->email_verified == 1 && $userisApproved->is_3refer != 1) {
                    $userisApproved->is_3refer = 1;
                    $userisApproved->disapprove_time = date("Y-m-d H:i:s", strtotime('+12 month'));
                    $userisApproved->save();
                } else {
                    $userisApproved->is_3refer = 1;
                    //$userisApproved->disapprove_time = date("Y-m-d H:i:s", strtotime('+12 month'));
                    $userisApproved->save();
                }
                $smsdata = [
                    'to' => Auth::user()->email,
                    'subject' => "Ayojn access approved",
                    'message' => "Thank you for recommending Ayojn to your colleagues and feel free to do it more. For now your access to Sponsor has been approved for the next 12 months.<br/>See you at Ayojn. <br/><strong>Outreach Team</strong><br/><strong>Ayojn</strong>",
                ];
                //$send_mail = send_mail($smsdata);
            }
            if ($referId) {
                $result = [
                    'msg' => 'Recommendations Recorded.',
                    'status' => '1'
                ];
            } else {
                $result = [
                    'status' => 0,
                    'msg' => "Recommended Email already exists! You can recommend someone else instead."

                ];
            }
        }

        return response()->json($result);
    }

    public function getReferFriends()
    {

        $referFriend = ReferAFriend::where('user_id', Auth::user()->id);
        $referList = $referFriend->get();
        $referCount = $referFriend->count();
        if ($referList) {
            $result = [
                'msg' => 'Record Found',
                'status' => '1',
                'referList' => $referList,
                'referCount' => $referCount
            ];
        } else {
            $result = [
                'status' => 0,
                'msg' => "No Record Found"

            ];
        }

        return response()->json($result);
    }

    public function getReferFriendsNew()
    {
        $userData = Auth::user();
        $existingReferedUsers = User::where('refer_by', $userData->id)->get();
        $html = view('refer-popup', compact('userData', 'existingReferedUsers'))->render();

        return response()->json(['html' => $html]);

    }

    public function redeemRequest(Request $request)
    {
        return view('redeem-request');
    }

    public function sentRedeemRequest(Request $request)
    {
        if (! empty($request->notes)) {
            $requestRedeem = [
                'user_id' => Auth::user()->id,
                'notes' => $request->notes
            ];
            RedeemRequest::create($requestRedeem);

            return redirect()->back()->with('sucess_message', 'Redeemption request successfully sent');
        } else {
            return redirect()->back()->with('message', 'Please enter bank detail');
        }

    }

    public function NotificationUpdate(Request $request)
    {
        $id = $request->id;
        $notification = SystemNotification::find($id);
        $notification->is_read = 1;
        $notification->save();
    }
}
