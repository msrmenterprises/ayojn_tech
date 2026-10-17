<?php

namespace App\Http\Controllers\UserAuth;

use App\Http\Controllers\Controller;
use App\SponsorrSpecify;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MapController extends Controller
{
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
        $userData = User::with('country')->where('country', $country_code)->where('sponsor_type', $sponsor_type)
            ->where('userstatus', 1)->get();


        $eventlesstwo = $eventabovetwo = $eventbtwfivetwo = $eventbtwtwofive = $eventUser = 0;
        $campaignabovetwo = $campaignbtwfivetwo = $campaignbtwtwofive = $campaignlesstwo = $campaignUser = 0;
        $contentabovetwo = $contentbtwfivetwo = $contentbtwtwofive = $contentlesstwo = $ContentUser = 0;
        $sportabovetwo = $sportbtwfivetwo = $sportlesstwo = $sportbtwtwofive = $sportTeamUser = 0;
        $otherlesstwo = $otherbtwfivetwo = $otherbtwtwofive = $otherabovetwo = $otherUser = 0;
        $venueUser = $venueabovetwo = $venuebtwfivetwo = $venuelesstwo = $venuebtwtwofive = 0;
        $profitUser = $profitabovetwo = $profitbtwfivetwo = $profitbtwtwofive = $profitlesstwo = 0;
        $performUser = $performabovetwo = $performbtwfivetwo = $performbtwtwofive = $performlesstwo = 0;
        $ThinkTankUser = $ThinkTankabovetwo = $ThinkTankbtwfivetwo = $ThinkTankbtwtwofive = $ThinkTanklesstwo = 0;
        $OnlineEventsUser = $OnlineEventsabovetwo = $OnlineEventsbtwfivetwo = $OnlineEventsbtwtwofive = $OnlineEventslesstwo = 0;
        $researchlesstwo = $researchabovetwo = $researchbtwfivetwo = $researchbtwtwofive = $researchUser = 0;
        $KnowledgePoolUser = $KnowledgePoolabovetwo = $KnowledgePoolbtwfivetwo = $KnowledgePoolbtwtwofive = $KnowledgePoollesstwo = 0;
        $conference = $music_festival = $tradeshow = $exhibition = $Online = $Offline = $SocialMedia = $Infleucer = $Blog = $Video = $Inforgraphics = $CaseStudies = $Whitpapers = $Articles = $Interviews = $Memes = $Football = $Regional = $AdventureSports = $Racetrack = $International = $Outdoor = $indoor = $Sports = $Entertainment = $Shopping = $Transport = $CSR = $Charity = $Aid = $Donation = $Campaign = $Lobbying = $Masterclass = $Seminar = $Delegation = $AwardsCompetitions = $ProductLaunch = $FestivalsParties = $Email = $Experiential = $Canvassing = $Cricket = $Boxing = $Golf = $Polo = $Skiing = $PowerBoat = $BaseBall = $PR = $OnlineGaming = $Theatre = $Dance = $Music = $Comedy = $StoryTelling = $Magic = $Circus = $Entertainment = $Puppetry = $Poetry = $VisualArts = $Regional = $Global = $LectureSeries = $Pedia = $Webinar = $Webcast = $LiveStream = $ChatGroups = $MediaForum = $FocusGroupBoard = $OnlineSports = $Prospecting = $MeetUp = $Roadshows = $Basketball = $commercialandscient = $scientific = $Affiliate = $GrowthMarketing = $Programmatic = $DOOH = $OOH = $Performance = $Lobbying1 = $SocialMediaGroups = $Commercial = $Scientific1 = $Recreational = $Experiences = $notDYet1 = $notDYet2 = $notDYet3 = $notDYet4 = $notDYet5 = $notDYet6 = $notDYet7 = $notDYet8 = $notDYet9 = $notDYet10 = $notDYet11 = 0;
        foreach ($userData as $user) {
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
            } else if ($user->sponsor_for == "Performing Arts") {
                if ($user->sponsor_budget == "Less than 2000") {
                    $performlesstwo = $performlesstwo + 1;
                } elseif ($user->sponsor_budget == "Between 2000-5000") {
                    $performbtwtwofive = $performbtwtwofive + 1;
                } elseif ($user->sponsor_budget == "Between 5000-20000") {
                    $performbtwfivetwo = $performbtwfivetwo + 1;
                } elseif ($user->sponsor_budget == "Above 20000") {
                    $performabovetwo = $performabovetwo + 1;
                }
                $performUser = $performUser + 1;
            } else if ($user->sponsor_for == "Think Tank") {
                if ($user->sponsor_budget == "Less than 2000") {
                    $ThinkTanklesstwo = $ThinkTanklesstwo + 1;
                } elseif ($user->sponsor_budget == "Between 2000-5000") {
                    $ThinkTankbtwtwofive = $ThinkTankbtwtwofive + 1;
                } elseif ($user->sponsor_budget == "Between 5000-20000") {
                    $ThinkTankbtwfivetwo = $ThinkTankbtwfivetwo + 1;
                } elseif ($user->sponsor_budget == "Above 20000") {
                    $ThinkTankabovetwo = $ThinkTankabovetwo + 1;
                }
                $ThinkTankUser = $ThinkTankUser + 1;
            } else if ($user->sponsor_for == "Knowledge Pool") {
                if ($user->sponsor_budget == "Less than 2000") {
                    $KnowledgePoollesstwo = $KnowledgePoollesstwo + 1;
                } elseif ($user->sponsor_budget == "Between 2000-5000") {
                    $KnowledgePoolbtwtwofive = $KnowledgePoolbtwtwofive + 1;
                } elseif ($user->sponsor_budget == "Between 5000-20000") {
                    $KnowledgePoolbtwfivetwo = $KnowledgePoolbtwfivetwo + 1;
                } elseif ($user->sponsor_budget == "Above 20000") {
                    $KnowledgePoolabovetwo = $KnowledgePoolabovetwo + 1;
                }
                $ThinkTankUser = $ThinkTankUser + 1;
            } else if ($user->sponsor_for == "Online Events") {
                if ($user->sponsor_budget == "Less than 2000") {
                    $OnlineEventslesstwo = $OnlineEventslesstwo + 1;
                } elseif ($user->sponsor_budget == "Between 2000-5000") {
                    $OnlineEventsbtwtwofive = $OnlineEventsbtwtwofive + 1;
                } elseif ($user->sponsor_budget == "Between 5000-20000") {
                    $OnlineEventsbtwfivetwo = $OnlineEventsbtwfivetwo + 1;
                } elseif ($user->sponsor_budget == "Above 20000") {
                    $OnlineEventsabovetwo = $OnlineEventsabovetwo + 1;
                }
                $OnlineEventsUser = $OnlineEventsUser + 1;
            } else if ($user->sponsor_for == "Research") {
                if ($user->sponsor_budget == "Less than 2000") {
                    $researchlesstwo = $researchlesstwo + 1;
                } elseif ($user->sponsor_budget == "Between 2000-5000") {
                    $researchbtwtwofive = $researchbtwtwofive + 1;
                } elseif ($user->sponsor_budget == "Between 5000-20000") {
                    $researchbtwfivetwo = $researchbtwfivetwo + 1;
                } elseif ($user->sponsor_budget == "Above 20000") {
                    $researchabovetwo = $researchabovetwo + 1;
                }
                $researchUser = $researchUser + 1;
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
                    } elseif ($userspecify->specify_name == 5) {
                        $Online = $Online + 1;
                    } elseif ($userspecify->specify_name == 6) {
                        $Offline = $Offline + 1;
                    } elseif ($userspecify->specify_name == 7) {
                        $SocialMedia = $SocialMedia + 1;
                    } elseif ($userspecify->specify_name == 8) {
                        $Infleucer = $Infleucer + 1;
                    } elseif ($userspecify->specify_name == 9) {
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
                    } elseif ($userspecify->specify_name == 17) {
                        $Football = $Football + 1;
                    } elseif ($userspecify->specify_name == 18) {
                        $Regional = $Regional + 1;
                    } elseif ($userspecify->specify_name == 19) {
                        $AdventureSports = $AdventureSports + 1;
                    } elseif ($userspecify->specify_name == 20) {
                        $Racetrack = $Racetrack + 1;
                    } elseif ($userspecify->specify_name == 21) {
                        $International = $International + 1;
                    } elseif ($userspecify->specify_name == 22) {
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
                    } elseif ($userspecify->specify_name == 40) {
                        $Email = $Email + 1;
                    } elseif ($userspecify->specify_name == 41) {
                        $Experiential = $Experiential + 1;
                    } elseif ($userspecify->specify_name == 42) {
                        $Canvassing = $Canvassing + 1;
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
                    } elseif ($userspecify->specify_name == 51) {
                        $PR = $PR + 1;
                    } elseif ($userspecify->specify_name == 52) {
                        $OnlineGaming = $OnlineGaming + 1;
                    } elseif ($userspecify->specify_name == 55) {
                        $Theatre = $Theatre + 1;
                    } elseif ($userspecify->specify_name == 56) {
                        $Dance = $Dance + 1;
                    } elseif ($userspecify->specify_name == 57) {
                        $Music = $Music + 1;
                    } elseif ($userspecify->specify_name == 58) {
                        $Comedy = $Comedy + 1;
                    } elseif ($userspecify->specify_name == 59) {
                        $StoryTelling = $StoryTelling + 1;
                    } elseif ($userspecify->specify_name == 60) {
                        $Magic = $Magic + 1;
                    } elseif ($userspecify->specify_name == 61) {
                        $Circus = $Circus + 1;
                    } elseif ($userspecify->specify_name == 62) {
                        $Entertainment = $Entertainment + 1;
                    } elseif ($userspecify->specify_name == 63) {
                        $Puppetry = $Puppetry + 1;
                    } elseif ($userspecify->specify_name == 64) {
                        $Poetry = $Poetry + 1;
                    } elseif ($userspecify->specify_name == 65) {
                        $VisualArts = $VisualArts + 1;
                    } elseif ($userspecify->specify_name == 66) {
                        $Regional = $Regional + 1;
                    } elseif ($userspecify->specify_name == 67) {
                        $Global = $Global + 1;
                    } elseif ($userspecify->specify_name == 68) {
                        $LectureSeries = $LectureSeries + 1;
                    } elseif ($userspecify->specify_name == 69) {
                        $Pedia = $Pedia + 1;
                    } elseif ($userspecify->specify_name == 70) {
                        $Webinar = $Webinar + 1;
                    } elseif ($userspecify->specify_name == 71) {
                        $Webcast = $Webcast + 1;
                    } elseif ($userspecify->specify_name == 72) {
                        $LiveStream = $LiveStream + 1;
                    } elseif ($userspecify->specify_name == 73) {
                        $ChatGroups = $ChatGroups + 1;
                    } elseif ($userspecify->specify_name == 74) {
                        $MediaForum = $MediaForum + 1;
                    } elseif ($userspecify->specify_name == 75) {
                        $FocusGroupBoard = $FocusGroupBoard + 1;
                    } elseif ($userspecify->specify_name == 76) {
                        $OnlineSports = $OnlineSports + 1;
                    } elseif ($userspecify->specify_name == 77) {
                        $Prospecting = $Prospecting + 1;
                    } elseif ($userspecify->specify_name == 78) {
                        $MeetUp = $MeetUp + 1;
                    } elseif ($userspecify->specify_name == 79) {
                        $Roadshows = $Roadshows + 1;
                    } elseif ($userspecify->specify_name == 80) {
                        $Basketball = $Basketball + 1;
                    } elseif ($userspecify->specify_name == 81) {
                        $commercialandscient = $commercialandscient + 1;
                    } elseif ($userspecify->specify_name == 82) {
                        $scientific = $scientific + 1;
                    } elseif ($userspecify->specify_name == 83) {
                        $Affiliate = $Affiliate + 1;
                    } elseif ($userspecify->specify_name == 84) {
                        $GrowthMarketing = $GrowthMarketing + 1;
                    } elseif ($userspecify->specify_name == 85) {
                        $Programmatic = $Programmatic + 1;
                    } elseif ($userspecify->specify_name == 86) {
                        $DOOH = $DOOH + 1;
                    } elseif ($userspecify->specify_name == 87) {
                        $OOH = $OOH + 1;
                    } elseif ($userspecify->specify_name == 88) {
                        $Performance = $Performance + 1;
                    } elseif ($userspecify->specify_name == 89) {
                        $Lobbying1 = $Lobbying1 + 1;
                    } elseif ($userspecify->specify_name == 90) {
                        $SocialMediaGroups = $SocialMediaGroups + 1;
                    } elseif ($userspecify->specify_name == 91) {
                        $Recreational = $Recreational + 1;
                    } elseif ($userspecify->specify_name == 92) {
                        $Experiences = $Experiences + 1;
                    } elseif ($userspecify->specify_name == 93) {
                        $notDYet1 = $notDYet1 + 1;
                    } elseif ($userspecify->specify_name == 94) {
                        $notDYet2 = $notDYet2 + 1;
                    } elseif ($userspecify->specify_name == 95) {
                        $notDYet3 = $notDYet3 + 1;
                    } elseif ($userspecify->specify_name == 96) {
                        $notDYet4 = $notDYet4 + 1;
                    } elseif ($userspecify->specify_name == 97) {
                        $notDYet5 = $notDYet5 + 1;
                    } elseif ($userspecify->specify_name == 98) {
                        $notDYet6 = $notDYet6 + 1;
                    } elseif ($userspecify->specify_name == 99) {
                        $notDYet7 = $notDYet7 + 1;
                    } elseif ($userspecify->specify_name == 100) {
                        $notDYet8 = $notDYet8 + 1;
                    } elseif ($userspecify->specify_name == 101) {
                        $notDYet9 = $notDYet9 + 1;
                    } elseif ($userspecify->specify_name == 102) {
                        $notDYet10 = $notDYet10 + 1;
                    } elseif ($userspecify->specify_name == 103) {
                        $notDYet11 = $notDYet11 + 1;
                    }

                }
            }
        }

        if ($eventlesstwo > $eventbtwtwofive && $eventlesstwo > $eventbtwfivetwo && $eventlesstwo > $eventabovetwo && $eventlesstwo > 0) {
            $finalArray['EventBudgetMessage'] = "Less than USD 2000 ";
        } elseif ($eventbtwtwofive > $eventlesstwo && $eventbtwtwofive > $eventbtwfivetwo && $eventbtwtwofive > $eventabovetwo && $eventbtwtwofive > 0) {
            $finalArray['EventBudgetMessage'] = "Between USD 2000-USD 5000 ";
        } elseif ($eventbtwfivetwo > $eventbtwtwofive && $eventbtwfivetwo > $eventlesstwo && $eventbtwfivetwo > $eventabovetwo && $eventbtwfivetwo > 0) {
            $finalArray['EventBudgetMessage'] = "Between USD 5000-USD 20000 ";
        } elseif ($eventabovetwo >= $eventbtwtwofive && $eventabovetwo >= $eventbtwfivetwo && $eventabovetwo >= $eventlesstwo && $eventabovetwo > 0) {
            $finalArray['EventBudgetMessage'] = "Above USD 20000 ";
        } else if ($eventabovetwo > 0 || $eventbtwtwofive > 0 || $eventbtwfivetwo > 0 || $eventlesstwo > 0) {
            $finalArray['EventBudgetMessage'] = 'Above USD 20000 ';
        } else {
            $finalArray['EventBudgetMessage'] = '';
        }

        if ($campaignlesstwo > $campaignbtwtwofive && $campaignlesstwo > $campaignbtwfivetwo && $campaignlesstwo > $campaignabovetwo && $campaignlesstwo > 0) {
            $finalArray['CampaingBudgetMessage'] = "Less than USD 2000 ";
        } elseif ($campaignbtwtwofive > $campaignlesstwo && $campaignbtwtwofive > $campaignbtwfivetwo && $campaignbtwtwofive > $campaignabovetwo && $campaignbtwtwofive > 0) {
            $finalArray['CampaingBudgetMessage'] = "Between USD 2000-USD 5000 ";
        } elseif ($campaignbtwfivetwo > $campaignbtwtwofive && $campaignbtwfivetwo > $campaignlesstwo && $campaignbtwfivetwo > $campaignabovetwo && $campaignbtwfivetwo > 0) {
            $finalArray['CampaingBudgetMessage'] = "Between USD 5000-USD 20000 ";
        } elseif ($campaignabovetwo > $campaignbtwtwofive && $campaignabovetwo > $campaignbtwfivetwo && $campaignabovetwo > $campaignlesstwo && $campaignabovetwo > 0) {
            $finalArray['CampaingBudgetMessage'] = "Above USD 20000 ";
        } else if ($campaignabovetwo > 0 || $campaignbtwtwofive > 0 || $campaignbtwfivetwo > 0 || $campaignlesstwo > 0) {
            $finalArray['CampaingBudgetMessage'] = 'Above USD 20000 ';
        } else {
            $finalArray['CampaingBudgetMessage'] = '';
        }
        if ($contentlesstwo > $contentbtwtwofive && $contentlesstwo > $contentbtwfivetwo && $contentlesstwo > $contentabovetwo && $contentlesstwo > 0) {
            $finalArray['ContentBudgetMessage'] = "Less than USD 2000 ";
        } elseif ($contentbtwtwofive > $contentlesstwo && $contentbtwtwofive > $contentbtwfivetwo && $contentbtwtwofive > $contentabovetwo && $contentbtwtwofive > 0) {
            $finalArray['ContentBudgetMessage'] = "Between USD 2000-USD 5000 ";
        } elseif ($contentbtwfivetwo > $contentbtwtwofive && $contentbtwfivetwo > $contentlesstwo && $contentbtwfivetwo > $contentabovetwo && $contentbtwfivetwo > 0) {
            $finalArray['ContentBudgetMessage'] = "Between USD 5000-USD 20000 ";
        } elseif ($contentabovetwo > $contentbtwtwofive && $contentabovetwo > $contentbtwfivetwo && $contentabovetwo > $contentlesstwo && $contentabovetwo > 0) {
            $finalArray['ContentBudgetMessage'] = "Above USD 20000 ";
        } else if ($contentabovetwo > 0 || $contentbtwtwofive > 0 || $contentbtwfivetwo > 0 || $contentlesstwo > 0) {
            $finalArray['ContentBudgetMessage'] = 'Above USD 20000 ';
        } else {
            $finalArray['ContentBudgetMessage'] = '';
        }
        if ($sportlesstwo > $sportbtwtwofive && $sportlesstwo > $sportbtwfivetwo && $sportlesstwo > $sportabovetwo && $sportlesstwo > 0) {
            $finalArray['SportBudgetMessage'] = "Less than USD 2000 ";
        } elseif ($sportbtwtwofive > $sportlesstwo && $sportbtwtwofive > $sportbtwfivetwo && $sportbtwtwofive > $sportabovetwo && $sportbtwtwofive > 0) {
            $finalArray['SportBudgetMessage'] = "Between USD 2000-USD 5000 ";
        } elseif ($sportbtwfivetwo > $sportbtwtwofive && $sportbtwfivetwo > $sportlesstwo && $sportbtwfivetwo > $sportabovetwo && $sportbtwfivetwo > 0) {
            $finalArray['SportBudgetMessage'] = "Between USD 5000-USD 20000";
        } elseif ($sportabovetwo > $sportbtwtwofive && $sportabovetwo > $sportbtwfivetwo && $sportabovetwo > $sportlesstwo && $sportabovetwo > 0) {
            $finalArray['SportBudgetMessage'] = "Above USD 20000 ";
        } else if ($sportabovetwo > 0 || $sportbtwtwofive > 0 || $sportbtwfivetwo > 0 || $sportlesstwo > 0) {
            $finalArray['SportBudgetMessage'] = 'Above USD 20000 ';
        } else {
            $finalArray['SportBudgetMessage'] = '';
        }
        if ($otherlesstwo > $otherbtwtwofive && $otherlesstwo > $otherbtwfivetwo && $otherlesstwo > $otherabovetwo && $otherlesstwo > 0) {
            $finalArray['OtherBudgetMessage'] = "Less than USD 2000 ";
        } elseif ($otherbtwtwofive > $otherlesstwo && $otherbtwtwofive > $otherbtwfivetwo && $otherbtwtwofive > $otherabovetwo && $otherbtwtwofive > 0) {
            $finalArray['OtherBudgetMessage'] = "Between USD 2000-USD 5000 ";
        } elseif ($otherbtwfivetwo > $otherbtwtwofive && $otherbtwfivetwo > $otherlesstwo && $otherbtwfivetwo > $otherabovetwo && $otherbtwfivetwo > 0) {
            $finalArray['OtherBudgetMessage'] = "Between USD 5000-USD 20000 ";
        } elseif ($otherabovetwo > $otherbtwtwofive && $otherabovetwo > $otherbtwfivetwo && $otherabovetwo > $otherlesstwo && $otherabovetwo > 0) {
            $finalArray['OtherBudgetMessage'] = "Above USD 20000";
        } else if ($otherabovetwo > 0 || $otherbtwtwofive > 0 || $otherbtwfivetwo > 0 || $otherlesstwo > 0) {
            $finalArray['OtherBudgetMessage'] = 'Above USD 20000 ';
        } else {
            $finalArray['OtherBudgetMessage'] = '';
        }
        if ($venuelesstwo > $venuebtwtwofive && $venuelesstwo > $venuebtwfivetwo && $venuelesstwo > $venueabovetwo && $venuelesstwo > 0) {
            $finalArray['VenueBudgetMessage'] = "Less than USD 2000 ";
        } elseif ($venuebtwtwofive > $venuelesstwo && $venuebtwtwofive > $venuebtwfivetwo && $venuebtwtwofive > $venueabovetwo && $venuebtwtwofive > 0) {
            $finalArray['VenueBudgetMessage'] = "Between USD 2000-USD 5000 ";
        } elseif ($venuebtwfivetwo > $venuebtwtwofive && $venuebtwfivetwo > $venuelesstwo && $venuebtwfivetwo > $venueabovetwo && $venuebtwfivetwo > 0) {
            $finalArray['VenueBudgetMessage'] = "Between USD 5000-USD 20000 ";
        } elseif ($venueabovetwo > $venuebtwtwofive && $venueabovetwo > $venuebtwfivetwo && $venueabovetwo > $venuelesstwo && $venueabovetwo > 0) {
            $finalArray['VenueBudgetMessage'] = "Above USD 20000 ";
        } else if ($venueabovetwo > 0 || $venuebtwtwofive > 0 || $venuebtwfivetwo > 0 || $venuelesstwo > 0) {
            $finalArray['VenueBudgetMessage'] = 'Above USD 20000 ';
        } else {
            $finalArray['VenueBudgetMessage'] = '';
        }
        if ($profitlesstwo > $profitbtwtwofive && $profitlesstwo > $profitbtwfivetwo && $profitlesstwo > $profitabovetwo && $profitlesstwo > 0) {
            $finalArray['ProfitBudgetMessage'] = "Less than USD 2000 ";
        } elseif ($profitbtwtwofive > $profitlesstwo && $profitbtwtwofive > $profitbtwfivetwo && $profitbtwtwofive > $profitabovetwo && $profitbtwtwofive > 0) {
            $finalArray['ProfitBudgetMessage'] = "Between USD 2000-USD 5000 ";
        } elseif ($profitbtwfivetwo > $profitbtwtwofive && $profitbtwfivetwo > $profitlesstwo && $profitbtwfivetwo > $profitabovetwo && $profitbtwfivetwo > 0) {
            $finalArray['ProfitBudgetMessage'] = "Between USD 5000-USD 20000 ";
        } elseif ($profitabovetwo > $profitbtwtwofive && $profitabovetwo > $profitbtwfivetwo && $profitabovetwo > $profitlesstwo && $profitabovetwo > 0) {
            $finalArray['ProfitBudgetMessage'] = "Above USD 20000 ";
        } else if ($profitabovetwo > 0 || $profitbtwtwofive > 0 || $profitbtwfivetwo > 0 || $profitlesstwo > 0) {
            $finalArray['ProfitBudgetMessage'] = 'Above USD 20000 ';
        } else {
            $finalArray['ProfitBudgetMessage'] = '';
        }
        if ($performlesstwo > $performbtwtwofive && $performlesstwo > $performbtwfivetwo && $performlesstwo > $performabovetwo && $performlesstwo > 0) {
            $finalArray['PerformBudgetMessage'] = "Less than USD 2000 ";
        } elseif ($performbtwtwofive > $performlesstwo && $performbtwtwofive > $performbtwfivetwo && $performbtwtwofive > $performabovetwo && $performbtwtwofive > 0) {
            $finalArray['PerformBudgetMessage'] = "Between USD 2000-USD 5000 ";
        } elseif ($performbtwfivetwo > $performbtwtwofive && $performbtwfivetwo > $performlesstwo && $performbtwfivetwo > $performabovetwo && $performbtwfivetwo > 0) {
            $finalArray['PerformBudgetMessage'] = "Between USD 5000-USD 20000 ";
        } elseif ($performabovetwo > $performbtwtwofive && $performabovetwo > $performbtwfivetwo && $performabovetwo > $performlesstwo && $performabovetwo > 0) {
            $finalArray['PerformBudgetMessage'] = "Above USD 20000 ";
        } else if ($performabovetwo > 0 || $performbtwtwofive > 0 || $performbtwfivetwo > 0 || $performlesstwo > 0) {
            $finalArray['PerformBudgetMessage'] = 'Above USD 20000 ';
        } else {
            $finalArray['PerformBudgetMessage'] = '';
        }
        if ($ThinkTanklesstwo > $ThinkTankbtwtwofive && $ThinkTanklesstwo > $ThinkTankbtwfivetwo && $ThinkTanklesstwo > $ThinkTankabovetwo && $ThinkTanklesstwo > 0) {
            $finalArray['ThinkTankBudgetMessage'] = "Less than USD 2000 ";
        } elseif ($ThinkTankbtwtwofive > $ThinkTanklesstwo && $ThinkTankbtwtwofive > $ThinkTankbtwfivetwo && $ThinkTankbtwtwofive > $ThinkTankabovetwo && $ThinkTankbtwtwofive > 0) {
            $finalArray['ThinkTankBudgetMessage'] = "Between USD 2000-USD 5000 ";
        } elseif ($ThinkTankbtwfivetwo > $ThinkTankbtwtwofive && $ThinkTankbtwfivetwo > $ThinkTanklesstwo && $ThinkTankbtwfivetwo > $ThinkTankabovetwo && $ThinkTankbtwfivetwo > 0) {
            $finalArray['ThinkTankBudgetMessage'] = "Between USD 5000-USD 20000 ";
        } elseif ($ThinkTankabovetwo > $ThinkTankbtwtwofive && $ThinkTankabovetwo > $ThinkTankbtwfivetwo && $ThinkTankabovetwo > $ThinkTanklesstwo && $ThinkTankabovetwo > 0) {
            $finalArray['ThinkTankBudgetMessage'] = "Above USD 20000 ";
        } else if ($ThinkTankabovetwo > 0 || $ThinkTankbtwtwofive > 0 || $ThinkTankbtwfivetwo > 0 || $ThinkTanklesstwo > 0) {
            $finalArray['ThinkTankBudgetMessage'] = 'Above USD 20000 ';
        } else {
            $finalArray['ThinkTankBudgetMessage'] = '';
        }
        if ($KnowledgePoollesstwo > $KnowledgePoolbtwtwofive && $KnowledgePoollesstwo > $KnowledgePoolbtwfivetwo && $KnowledgePoollesstwo > $KnowledgePoolabovetwo && $KnowledgePoollesstwo > 0) {
            $finalArray['KnowledgePoolBudgetMessage'] = "Less than USD 2000 ";
        } elseif ($KnowledgePoolbtwtwofive > $KnowledgePoollesstwo && $KnowledgePoolbtwtwofive > $KnowledgePoolbtwfivetwo && $KnowledgePoolbtwtwofive > $KnowledgePoolabovetwo && $KnowledgePoolbtwtwofive > 0) {
            $finalArray['KnowledgePoolBudgetMessage'] = "Between USD 2000-USD 5000 ";
        } elseif ($KnowledgePoolbtwfivetwo > $KnowledgePoolbtwtwofive && $KnowledgePoolbtwfivetwo > $KnowledgePoollesstwo && $KnowledgePoolbtwfivetwo > $KnowledgePoolabovetwo && $KnowledgePoolbtwfivetwo > 0) {
            $finalArray['KnowledgePoolBudgetMessage'] = "Between USD 5000-USD 20000 ";
        } elseif ($KnowledgePoolabovetwo > $KnowledgePoolbtwtwofive && $KnowledgePoolabovetwo > $KnowledgePoolbtwfivetwo && $KnowledgePoolabovetwo > $KnowledgePoollesstwo && $KnowledgePoolabovetwo > 0) {
            $finalArray['KnowledgePoolBudgetMessage'] = "Above USD 20000 ";
        } else if ($KnowledgePoolabovetwo > 0 || $KnowledgePoolbtwtwofive > 0 || $KnowledgePoolbtwfivetwo > 0 || $KnowledgePoollesstwo > 0) {
            $finalArray['KnowledgePoolBudgetMessage'] = 'Above USD 20000 ';
        } else {
            $finalArray['KnowledgePoolBudgetMessage'] = '';
        }
        if ($OnlineEventslesstwo > $OnlineEventsbtwtwofive && $OnlineEventslesstwo > $OnlineEventsbtwfivetwo && $OnlineEventslesstwo > $OnlineEventsabovetwo && $OnlineEventslesstwo > 0) {
            $finalArray['OnlineEventBudgetMessage'] = "Less than USD 2000 ";
        } elseif ($OnlineEventsbtwtwofive > $OnlineEventslesstwo && $OnlineEventsbtwtwofive > $OnlineEventsbtwfivetwo && $OnlineEventsbtwtwofive > $OnlineEventsabovetwo && $OnlineEventsbtwtwofive > 0) {
            $finalArray['OnlineEventBudgetMessage'] = "Between USD 2000-USD 5000 ";
        } elseif ($OnlineEventsbtwfivetwo > $OnlineEventsbtwtwofive && $OnlineEventsbtwfivetwo > $OnlineEventslesstwo && $OnlineEventsbtwfivetwo > $OnlineEventsabovetwo && $OnlineEventsbtwfivetwo > 0) {
            $finalArray['OnlineEventBudgetMessage'] = "Between USD 5000-USD 20000 ";
        } elseif ($OnlineEventsabovetwo > $OnlineEventsbtwtwofive && $OnlineEventsabovetwo > $OnlineEventsbtwfivetwo && $OnlineEventsabovetwo > $OnlineEventslesstwo && $OnlineEventsabovetwo > 0) {
            $finalArray['OnlineEventBudgetMessage'] = "Above USD 20000 ";
        } else if ($OnlineEventsabovetwo > 0 || $OnlineEventsbtwtwofive > 0 || $OnlineEventsbtwfivetwo > 0 || $OnlineEventslesstwo > 0) {
            $finalArray['OnlineEventBudgetMessage'] = 'Above USD 20000 ';
        } else {
            $finalArray['OnlineEventBudgetMessage'] = '';
        }

        if ($researchlesstwo > $researchbtwtwofive && $researchlesstwo > $researchbtwfivetwo && $researchlesstwo > $researchabovetwo && $researchlesstwo > 0) {
            $finalArray['ResearchBudgetMessage'] = "Less than USD 2000 ";
        } elseif ($researchbtwtwofive > $researchlesstwo && $researchbtwtwofive > $researchbtwfivetwo && $researchbtwtwofive > $researchabovetwo && $researchbtwtwofive > 0) {
            $finalArray['ResearchBudgetMessage'] = "Between USD 2000-USD 5000 ";
        } elseif ($researchbtwfivetwo > $researchbtwtwofive && $researchbtwfivetwo > $researchlesstwo && $researchbtwfivetwo > $researchabovetwo && $researchbtwfivetwo > 0) {
            $finalArray['ResearchBudgetMessage'] = "Between USD 5000-USD 20000 ";
        } elseif ($researchabovetwo > $researchbtwtwofive && $researchabovetwo > $researchbtwfivetwo && $researchabovetwo > $researchlesstwo && $researchabovetwo > 0) {
            $finalArray['ResearchBudgetMessage'] = "Above USD 20000 ";
        } else if ($researchabovetwo > 0 || $researchbtwtwofive > 0 || $researchbtwfivetwo > 0 || $researchlesstwo > 0) {
            $finalArray['ResearchBudgetMessage'] = 'Above USD 20000 ';
        } else {
            $finalArray['ResearchBudgetMessage'] = '';
        }

        $finalArray['notDYet1'] = $notDYet1;
        $finalArray['notDYet2'] = $notDYet2;
        $finalArray['notDYet3'] = $notDYet3;
        $finalArray['notDYet4'] = $notDYet4;
        $finalArray['notDYet5'] = $notDYet5;
        $finalArray['notDYet6'] = $notDYet6;
        $finalArray['notDYet7'] = $notDYet7;
        $finalArray['notDYet8'] = $notDYet8;
        $finalArray['notDYet9'] = $notDYet9;
        $finalArray['notDYet10'] = $notDYet10;
        $finalArray['notDYet11'] = $notDYet11;
        $finalArray['SocialMediaGroups'] = $SocialMediaGroups;
        $finalArray['Commercial'] = $Commercial;
        $finalArray['Scientific1'] = $Scientific1;
        $finalArray['Recreational'] = $Recreational;
        $finalArray['Experiences'] = $Experiences;
        $finalArray['Affiliate'] = $Affiliate;
        $finalArray['GrowthMarketing'] = $GrowthMarketing;
        $finalArray['Programmatic'] = $Programmatic;
        $finalArray['DOOH'] = $DOOH;
        $finalArray['OOH'] = $OOH;
        $finalArray['Performance'] = $Performance;
        $finalArray['Lobbying1'] = $Lobbying1;
        $finalArray['userCount'] = $userData->count();
        $finalArray['commercialandscient'] = $commercialandscient;
        $finalArray['scientific'] = $scientific;
        $finalArray['researchUser'] = $researchUser;
        $finalArray['researchUserPer'] = $researchUser * 100 / 1000;
        $finalArray['OnlineSports'] = $OnlineSports;
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
        $finalArray['ContentUser'] = $ContentUser;
        $finalArray['Video'] = $Video;
        $finalArray['Inforgraphics'] = $Inforgraphics;
        $finalArray['CaseStudies'] = $CaseStudies;
        $finalArray['Whitpapers'] = $Whitpapers;
        $finalArray['Articles'] = $Articles;
        $finalArray['Interviews'] = $Interviews;
        $finalArray['Memes'] = $Memes;
        $finalArray['ContentUserPer'] = $ContentUser * 100 / 1000;
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
        $finalArray['OtherUser'] = $otherUser;
        $finalArray['profitUser'] = $profitUser;
        $finalArray['CSR'] = $CSR;
        $finalArray['Aid'] = $Aid;
        $finalArray['Donation'] = $Donation;
        $finalArray['Campaign'] = $Campaign;
        $finalArray['Lobbying'] = $Lobbying;
        $finalArray['Charity'] = $Charity;
        $finalArray['ProfitPer'] = $profitUser * 100 / 1000;
        $finalArray['VenueUser'] = $venueUser;
        $finalArray['Outdoor'] = $Outdoor;
        $finalArray['indoor'] = $indoor;
        $finalArray['Entertainment'] = $Entertainment;
        $finalArray['Sports'] = $Sports;
        $finalArray['Shopping'] = $Shopping;
        $finalArray['Transport'] = $Transport;
        $finalArray['Theatre'] = $Theatre;
        $finalArray['Dance'] = $Dance;
        $finalArray['Music'] = $Music;
        $finalArray['Comedy'] = $Comedy;
        $finalArray['StoryTelling'] = $StoryTelling;
        $finalArray['Magic'] = $Magic;
        $finalArray['Circus'] = $Circus;
        $finalArray['Entertainment'] = $Entertainment;
        $finalArray['Puppetry'] = $Puppetry;
        $finalArray['Poetry'] = $Poetry;
        $finalArray['VisualArts'] = $VisualArts;
        $finalArray['Regional'] = $Regional;
        $finalArray['Global'] = $Global;
        $finalArray['LectureSeries'] = $LectureSeries;
        $finalArray['Pedia'] = $Pedia;
        $finalArray['Webinar'] = $Webinar;
        $finalArray['Webcast'] = $Webcast;
        $finalArray['LiveStream'] = $LiveStream;
        $finalArray['ChatGroups'] = $ChatGroups;
        $finalArray['MediaForum'] = $MediaForum;
        $finalArray['FocusGroupBoard'] = $FocusGroupBoard;
        $finalArray['Prospecting'] = $Prospecting;
        $finalArray['MeetUp'] = $MeetUp;
        $finalArray['Roadshows'] = $Roadshows;
        $finalArray['Basketball'] = $Basketball;
        $finalArray['VenuePer'] = $venueUser * 100 / 1000;
        $finalArray['OtherUserPer'] = $otherUser * 100 / 1000;
        $finalArray['PerformPer'] = $performUser * 100 / 1000;
        $finalArray['PerformMessage'] = "Total " . $performUser . " user " . $sponsorWord;
        $finalArray['KnowledgePoolPer'] = $KnowledgePoolUser * 100 / 1000;
        $finalArray['KnowledgePoolMessage'] = "Total " . $KnowledgePoolUser . " user " . $sponsorWord;
        $finalArray['OnlineEventsPer'] = $OnlineEventsUser * 100 / 1000;
        $finalArray['OnlineEventsMessage'] = "Total " . $OnlineEventsUser . " user " . $sponsorWord;
        $finalArray['ThinkTankPer'] = $ThinkTankUser * 100 / 1000;
        $finalArray['ThinkTankMessage'] = "Total " . $ThinkTankUser . " user " . $sponsorWord;
        $finalArray['VenueMessage'] = "Total " . $venueUser . " user " . $sponsorWord;
        $finalArray['ProfitMessage'] = "Total " . $profitUser . " user " . $sponsorWord;
        $finalArray['OtherMessage'] = "Total " . $otherUser . " user " . $sponsorWord;
        $finalArray['ContentMessage'] = "Total " . $ContentUser . " user " . $sponsorWord;
        $finalArray['EventMessage'] = "Total " . $eventUser . " user " . $sponsorWord;
        $finalArray['CampaignMessage'] = "Total " . $campaignUser . " user " . $sponsorWord;
        $finalArray['SportTeamMessage'] = "Total " . $sportTeamUser . " user " . $sponsorWord;
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
        $PerformTotalUsers = User::where('sponsor_for', 'Performing Arts')->where('sponsor_type', $sponsor_type)
            ->where('country', $country_code)->where('userstatus', 1)->get()->count();
        $ThinkTankTotalUsers = User::where('sponsor_for', 'ThinkTank')->where('sponsor_type', $sponsor_type)
            ->where('country', $country_code)->where('userstatus', 1)->get()->count();
        $KnowledgePoolTotalUsers = User::where('sponsor_for', 'Knowledge Pool')->where('sponsor_type', $sponsor_type)
            ->where('country', $country_code)->where('userstatus', 1)->get()->count();
        $OnlineEventsTotalUsers = User::where('sponsor_for', 'Online Events')->where('sponsor_type', $sponsor_type)
            ->where('country', $country_code)->where('userstatus', 1)->get()->count();
        $ReserachTotalUsers = User::where('sponsor_for', 'Research')->where('sponsor_type', $sponsor_type)
            ->where('country', $country_code)->where('userstatus', 1)->get()->count();
        $view = view('mappopup2', compact('userData', 'finalArray', 'sponsor_type', 'eventTotalUsers',
            'campaignTotalUsers', 'contentTotalUsers', 'SportsTotalUsers', 'OtherTotalUsers', 'VenueTotalUsers',
            'ProfitTotalUsers', 'PerformTotalUsers', 'ThinkTankTotalUsers', 'KnowledgePoolTotalUsers',
            'OnlineEventsTotalUsers', 'ReserachTotalUsers'))->render();

        return response()->json($view);

    }
}
