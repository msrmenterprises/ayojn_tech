<style>
    .forgot_button {
        height: 45px;
        width: 135px;
        text-align: center;
        line-height: 45px;
        margin-right: 0;
        border-radius: 40px;
        background: #52616D;
        color: #fff;
    }
</style>
<div class="modal" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <!--   <div class="modal-header">
                   <h4 class="modal-title">Modal Heading</h4>
                   <button type="button" class="close" data-dismiss="modal">&times;</button>
               </div>-->

            <!-- Modal body -->
            <div class="modal-body">

                <div class="row">
                    @if(!empty($finalArray))
                        <div class="col-md-12 col-lg-12 align-self-center">
                            <p class="text-muted font-13 mb-1">
                                <i class="mdi mdi-checkbox-blank-circle mr-2 text-success"></i>
                                @if($sponsor_type == 1)
                                    Average Sponsorship Offered for Events
                                @else
                                    Average Price of Event Sponsorship
                                @endif

                            </p>
                            <p class="text-muted font-13 mb-1">
                                <span class="float-right"> {{ $finalArray['EventBudgetMessage']}} (Based on {{$eventTotalUsers }} inputs )</span>
                            </p>
                            <a href="javascript:void(0)" id="a1" onClick="changeContent(1,2)" data-toggle="collapse"
                               data-target="#demo">View More</a>
                            <a href="javascript:void(0)" id="a2" onClick="changeContent(2,1)" data-toggle="collapse"
                               data-target="#demo">View Less</a>

                            <div id="demo" class="collapse">
                                <button href="javascript:void(0)" class="forgot_button">Conference
                                    ({{ $finalArray['conference']}})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Work Shop
                                    ({{ $finalArray['music_festival']}})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Tradeshow
                                    ({{ $finalArray['tradeshow']}})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Exhibition
                                    ({{ $finalArray['exhibition']}})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Masterclass
                                    ({{ $finalArray['Masterclass']}})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Seminar
                                    ({{ $finalArray['Seminar']}})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Delegation
                                    ({{ $finalArray['Delegation']}})
                                </button>
                                <button href="javascript:void(0)" style="width: 215px" class="forgot_button">Awards &
                                    Competitions ({{ $finalArray['AwardsCompetitions']}})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button" style="width: 215px">Festivals &
                                    Parties ({{ $finalArray['FestivalsParties']}})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button" style="width: 160px">Product
                                    Launch ({{ $finalArray['ProductLaunch']}})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">MeetUp
                                    ({{ $finalArray['MeetUp']}})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Roadshows
                                    ({{ $finalArray['Roadshows']}})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Recreational
                                    ({{ $finalArray['Recreational']}})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Experiences
                                    ({{ $finalArray['Experiences']}})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button" style="width: 160px;">Not Decided yet
                                    ({{ $finalArray['notDYet1']}})
                                </button>
                            </div>
                            <br>
                            <div class="progress">
                                <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar"
                                     aria-valuenow="{{$finalArray['EventPer']}}" aria-valuemin="0" aria-valuemax="100"
                                     style="width:{{$finalArray['EventPer']}}%">

                                </div>
                            </div>
                            <br>
                            <p class="text-muted font-13 mb-1">
                                <i class="mdi mdi-checkbox-blank-circle mr-2 text-success"></i>
                                @if($sponsor_type == 1)
                                    Average Sponsorship Offered for Campaign
                                @else
                                    Average Price of Campaign Sponsorship
                                @endif
                            </p>
                            <p class="text-muted font-13 mb-1">
                                <span class="float-right"> {{ $finalArray['CampaingBudgetMessage']}} (Based on {{ $campaignTotalUsers }} inputs )</span>
                            </p>
                            <a href="javascript:void(0)" id="a3" onClick="changeContent(3,4)" data-toggle="collapse"
                               data-target="#campaign">View More</a>
                            <a href="javascript:void(0)" id="a4" onClick="changeContent(4,3)" data-toggle="collapse"
                               data-target="#campaign">View Less</a>

                            <div id="campaign" class="collapse">
                                <button href="javascript:void(0)" class="forgot_button">Online
                                    ({{ $finalArray['Online'] }})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Offline
                                    ({{ $finalArray['Offline'] }})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Social Media
                                    ({{ $finalArray['SocialMedia'] }})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Influencer
                                    ({{ $finalArray['Infleucer'] }})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Email
                                    ({{ $finalArray['Email'] }})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Experiential
                                    ({{ $finalArray['Experiential'] }})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Canvassing
                                    ({{ $finalArray['Canvassing'] }})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">PR ({{ $finalArray['PR'] }})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Prospecting
                                    ({{ $finalArray['Prospecting'] }})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Affiliate
                                    ({{ $finalArray['Affiliate'] }})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">GrowthMarketing
                                    ({{ $finalArray['GrowthMarketing'] }})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Programmatic
                                    ({{ $finalArray['Programmatic'] }})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">DOOH ({{ $finalArray['DOOH'] }}
                                    )
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">OOH ({{ $finalArray['OOH'] }})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Performance
                                    ({{ $finalArray['Performance'] }})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Lobbying
                                    ({{ $finalArray['Lobbying1'] }})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button" style="width: 160px;">Not Decided yet
                                    ({{ $finalArray['notDYet2']}})
                                </button>
                            </div>
                            <br>
                            <div class="progress">
                                <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar"
                                     aria-valuenow="{{ $finalArray['CampaignPer']}}" aria-valuemin="0"
                                     aria-valuemax="100" style="width:{{ $finalArray['CampaignPer']}}%">

                                </div>
                            </div>
                            <br>
                            <p class="text-muted font-13 mb-1">
                                <i class="mdi mdi-checkbox-blank-circle mr-2 text-success"></i>
                                @if($sponsor_type == 1)
                                    Average Sponsorship Offered for Content
                                @else
                                    Average Price of Content Sponsorship
                                @endif
                            </p>
                            <p class="text-muted font-13 mb-1">
                                <span class="float-right"> {{ $finalArray['ContentBudgetMessage']}} (Based on {{ $contentTotalUsers }} inputs )</span>
                            </p>
                            <a href="javascript:void(0)" id="a5" onClick="changeContent(5,6)" data-toggle="collapse"
                               data-target="#Content">View More</a>
                            <a href="javascript:void(0)" id="a6" onClick="changeContent(6,5)" data-toggle="collapse"
                               data-target="#Content">View Less</a>

                            <div id="Content" class="collapse">
                                <button href="javascript:void(0)" class="forgot_button">Blog ({{ $finalArray['Blog'] }}
                                    )
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Video
                                    ({{ $finalArray['Video'] }})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Infographics
                                    ({{ $finalArray['Inforgraphics'] }})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Case Studies
                                    ({{ $finalArray['CaseStudies'] }})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Whitepapers
                                    ({{ $finalArray['Whitpapers'] }})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Articles
                                    ({{ $finalArray['Articles'] }})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Interviews
                                    ({{ $finalArray['Interviews'] }})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">
                                    Memes/ GIFs ({{ $finalArray['Memes'] }})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button" style="width: 160px;">Not Decided yet
                                    ({{ $finalArray['notDYet3']}})
                                </button>
                            </div>
                            <br>
                            <div class="progress">
                                <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar"
                                     aria-valuenow="{{ $finalArray['ContentUserPer']}}" aria-valuemin="0"
                                     aria-valuemax="100" style="width:{{ $finalArray['ContentUserPer']}}%">

                                </div>
                            </div>
                            <p class="text-muted font-13 mb-1">
                                <i class="mdi mdi-checkbox-blank-circle mr-2 text-success"></i>
                                @if($sponsor_type == 1)
                                    Average Sponsorship Offered for Sports
                                @else
                                    Average Price of Sports Sponsorship
                                @endif
                            </p>
                            <p class="text-muted font-13 mb-1">
                                <span class="float-right"> {{ $finalArray['SportBudgetMessage']}} (Based on {{$SportsTotalUsers}} inputs )</span>
                            </p>
                            <a href="javascript:void(0)" id="a7" onClick="changeContent(7,8)" data-toggle="collapse"
                               data-target="#SportTeam">View More</a>
                            <a href="javascript:void(0)" id="a8" onClick="changeContent(8,7)" data-toggle="collapse"
                               data-target="#SportTeam">View Less</a>

                            <div id="SportTeam" class="collapse">
                                <button href="javascript:void(0)" class="forgot_button">Football
                                    ({{ $finalArray['Football'] }})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Online Sports
                                    ({{ $finalArray['OnlineSports'] }})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Regional
                                    ({{ $finalArray['Regional'] }})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Adventure Sports
                                    ({{ $finalArray['AdventureSports'] }})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Racetrack
                                    ({{ $finalArray['Racetrack'] }})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">International
                                    ({{ $finalArray['International'] }})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Cricket
                                    ({{ $finalArray['Cricket'] }})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Boxing
                                    ({{ $finalArray['Boxing'] }})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Golf ({{ $finalArray['Golf'] }}
                                    )
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Polo ({{ $finalArray['Polo'] }}
                                    )
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Skiing
                                    ({{ $finalArray['Skiing'] }})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Power Boat
                                    ({{ $finalArray['PowerBoat'] }})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Base Ball
                                    ({{ $finalArray['BaseBall'] }})
                                </button>
                                <button href="javascript:void(0)" style="width: 150px" class="forgot_button">Online
                                    Gaming ({{ $finalArray['OnlineGaming'] }})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">
                                    Basketball({{ $finalArray['Basketball'] }})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button" style="width: 160px;">Not Decided yet
                                    ({{ $finalArray['notDYet4']}})
                                </button>
                            </div>
                            <div class="progress">
                                <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar"
                                     aria-valuenow="{{ $finalArray['SportTeamUserPer']}}" aria-valuemin="0"
                                     aria-valuemax="100" style="width:{{ $finalArray['SportTeamUserPer']}}%">

                                </div>
                            </div>

                            {{--sdfsdfsdf--}}
                            <p class="text-muted font-13 mb-1">
                                <i class="mdi mdi-checkbox-blank-circle mr-2 text-success"></i>
                                @if($sponsor_type == 1)
                                    Average Sponsorship Offered for Venue
                                @else
                                    Average Price of Venue Sponsorship
                                @endif

                            </p>
                            <p class="text-muted font-13 mb-1">
                                <span class="float-right"> {{ $finalArray['VenueBudgetMessage']}} (Based on {{$VenueTotalUsers }} inputs )</span>
                            </p>
                            <a href="javascript:void(0)" id="a11" onClick="changeContent(11,12)" data-toggle="collapse"
                               data-target="#Outdoor">View More</a>
                            <a href="javascript:void(0)" id="a12" onClick="changeContent(12,11)" data-toggle="collapse"
                               data-target="#Outdoor">View Less</a>

                            <div id="Outdoor" class="collapse">
                                <button href="javascript:void(0)" class="forgot_button">Outdoor
                                    ({{ $finalArray['Outdoor']}})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Indoor
                                    ({{ $finalArray['indoor']}})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Sports
                                    ({{ $finalArray['Sports']}})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Shopping
                                    ({{ $finalArray['Shopping']}})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Entertainment
                                    ({{ $finalArray['Entertainment']}})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Transport
                                    ({{ $finalArray['Transport']}})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button" style="width: 160px;">Not Decided yet
                                    ({{ $finalArray['notDYet5']}})
                                </button>
                            </div>
                            <br>
                            <div class="progress">
                                <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar"
                                     aria-valuenow="{{$finalArray['VenuePer']}}" aria-valuemin="0" aria-valuemax="100"
                                     style="width:{{$finalArray['VenuePer']}}%">

                                </div>
                            </div>
                            <br>

                            {{--sdfsdfsdf--}}

                            {{--sdfsdfsdf--}}
                            <p class="text-muted font-13 mb-1">
                                <i class="mdi mdi-checkbox-blank-circle mr-2 text-success"></i>
                                @if($sponsor_type == 1)
                                    Average Sponsorship Offered for Not for Profit
                                @else
                                    Average Price of Not for Profit Sponsorship
                                @endif

                            </p>
                            <p class="text-muted font-13 mb-1">
                                <span class="float-right"> {{ $finalArray['ProfitBudgetMessage']}} (Based on {{$ProfitTotalUsers }} inputs )</span>
                            </p>
                            <a href="javascript:void(0)" id="a13" onClick="changeContent(13,14)" data-toggle="collapse"
                               data-target="#CSR">View More</a>
                            <a href="javascript:void(0)" id="a14" onClick="changeContent(14,13)" data-toggle="collapse"
                               data-target="#CSR">View Less</a>

                            <div id="CSR" class="collapse">
                                <button href="javascript:void(0)" class="forgot_button">CSR ({{ $finalArray['CSR']}})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Aid ({{ $finalArray['Aid']}})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Donation
                                    ({{ $finalArray['Donation']}})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Campaign
                                    ({{ $finalArray['Campaign']}})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Lobbying
                                    ({{ $finalArray['Lobbying']}})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Charity
                                    ({{ $finalArray['Charity']}})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button" style="width: 160px;">Not Decided yet
                                    ({{ $finalArray['notDYet6']}})
                                </button>
                            </div>
                            <br>
                            <div class="progress">
                                <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar"
                                     aria-valuenow="{{$finalArray['ProfitPer']}}" aria-valuemin="0" aria-valuemax="100"
                                     style="width:{{$finalArray['ProfitPer']}}%">

                                </div>
                            </div>
                            <br>
                            {{--sdfsdfsdf--}}
                            <p class="text-muted font-13 mb-1">
                                <i class="mdi mdi-checkbox-blank-circle mr-2 text-success"></i>
                                @if($sponsor_type == 1)
                                    Average Sponsorship Offered for Performing Art
                                @else
                                    Average Price of Performing Art Sponsorship
                                @endif

                            </p>
                            <p class="text-muted font-13 mb-1">
                                <span class="float-right"> {{ $finalArray['PerformBudgetMessage']}} (Based on {{$PerformTotalUsers }} inputs )</span>
                            </p>
                            <a href="javascript:void(0)" id="a15" onClick="changeContent(15,16)" data-toggle="collapse"
                               data-target="#Theatre">View More</a>
                            <a href="javascript:void(0)" id="a16" onClick="changeContent(16,15)" data-toggle="collapse"
                               data-target="#Theatre">View Less</a>

                            <div id="Theatre" class="collapse">
                                <button href="javascript:void(0)" class="forgot_button">Theatre
                                    ({{ $finalArray['Theatre']}})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Dance ({{ $finalArray['Dance']}}
                                    )
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Music ({{ $finalArray['Music']}}
                                    )
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Comedy
                                    ({{ $finalArray['Comedy']}})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Story Telling
                                    ({{ $finalArray['StoryTelling']}})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Magic ({{ $finalArray['Magic']}}
                                    )
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Circus
                                    ({{ $finalArray['Circus']}})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Entertainment
                                    ({{ $finalArray['Entertainment']}})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Puppetry
                                    ({{ $finalArray['Puppetry']}})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Poetry
                                    ({{ $finalArray['Poetry']}})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Visual Arts
                                    ({{ $finalArray['VisualArts']}})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button" style="width: 160px;">Not Decided yet
                                    ({{ $finalArray['notDYet7']}})
                                </button>
                            </div>
                            <br>
                            <div class="progress">
                                <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar"
                                     aria-valuenow="{{$finalArray['PerformPer']}}" aria-valuemin="0" aria-valuemax="100"
                                     style="width:{{$finalArray['PerformPer']}}%">

                                </div>
                            </div>
                            <br>
                            {{--sdfsdfsdf--}}
                            <p class="text-muted font-13 mb-1">
                                <i class="mdi mdi-checkbox-blank-circle mr-2 text-success"></i>
                                @if($sponsor_type == 1)
                                    Average Sponsorship Offered for Think Tank
                                @else
                                    Average Price of Think Tank Sponsorship
                                @endif

                            </p>
                            <p class="text-muted font-13 mb-1">
                                <span class="float-right"> {{ $finalArray['ThinkTankBudgetMessage']}} (Based on {{$ThinkTankTotalUsers }} inputs )</span>
                            </p>
                            <a href="javascript:void(0)" id="a17" onClick="changeContent(17,18)" data-toggle="collapse"
                               data-target="#Regional">View More</a>
                            <a href="javascript:void(0)" id="a18" onClick="changeContent(18,17)" data-toggle="collapse"
                               data-target="#Regional">View Less</a>

                            <div id="Regional" class="collapse">
                                <button href="javascript:void(0)" class="forgot_button">Regional
                                    ({{ $finalArray['Regional']}})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Global
                                    ({{ $finalArray['Global']}})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button" style="width: 160px;">Not Decided yet
                                    ({{ $finalArray['notDYet8']}})
                                </button>
                            </div>
                            <br>
                            <div class="progress">
                                <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar"
                                     aria-valuenow="{{$finalArray['ThinkTankPer']}}" aria-valuemin="0"
                                     aria-valuemax="100" style="width:{{$finalArray['ThinkTankPer']}}%">

                                </div>
                            </div>
                            <br>
                            {{--sdfsdfsdf--}}
                            <p class="text-muted font-13 mb-1">
                                <i class="mdi mdi-checkbox-blank-circle mr-2 text-success"></i>
                                @if($sponsor_type == 1)
                                    Average Sponsorship Offered for Knowledge Pool
                                @else
                                    Average Price of Knowledge Pool Sponsorship
                                @endif

                            </p>
                            <p class="text-muted font-13 mb-1">
                                <span class="float-right"> {{ $finalArray['KnowledgePoolBudgetMessage']}} (Based on {{$KnowledgePoolTotalUsers }} inputs )</span>
                            </p>
                            <a href="javascript:void(0)" id="a19" onClick="changeContent(19,20)" data-toggle="collapse"
                               data-target="#Pedia">View More</a>
                            <a href="javascript:void(0)" id="a20" onClick="changeContent(20,19)" data-toggle="collapse"
                               data-target="#Pedia">View Less</a>

                            <div id="Pedia" class="collapse">
                                <button href="javascript:void(0)" class="forgot_button">Lecture Series
                                    ({{ $finalArray['LectureSeries']}})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Pedia ({{ $finalArray['Pedia']}}
                                    )
                                </button>
                                <button href="javascript:void(0)" class="forgot_button" style="width: 160px;">Not Decided yet
                                    ({{ $finalArray['notDYet9']}})
                                </button>

                            </div>
                            <br>
                            <div class="progress">
                                <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar"
                                     aria-valuenow="{{$finalArray['KnowledgePoolPer']}}" aria-valuemin="0"
                                     aria-valuemax="100" style="width:{{$finalArray['KnowledgePoolPer']}}%">

                                </div>
                            </div>
                            <br>
                            <p class="text-muted font-13 mb-1">
                                <i class="mdi mdi-checkbox-blank-circle mr-2 text-success"></i>
                                @if($sponsor_type == 1)
                                    Average Sponsorship Offered for Online Activities
                                @else
                                    Average Price of Online Activities  Sponsorship
                                @endif

                            </p>
                            <p class="text-muted font-13 mb-1">
                                <span class="float-right"> {{ $finalArray['OnlineEventBudgetMessage']}} (Based on {{$OnlineEventsTotalUsers }} inputs )</span>
                            </p>
                            <a href="javascript:void(0)" id="a21" onClick="changeContent(21,22)" data-toggle="collapse"
                               data-target="#Regional">View More</a>
                            <a href="javascript:void(0)" id="a22" onClick="changeContent(22,21)" data-toggle="collapse"
                               data-target="#Regional">View Less</a>

                            <div id="Regional" class="collapse">
                                <button href="javascript:void(0)" class="forgot_button">Webinar
                                    ({{ $finalArray['Webinar']}})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Webcast
                                    ({{ $finalArray['Webcast']}})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Live Stream
                                    ({{ $finalArray['LiveStream']}})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Chat Groups
                                    ({{ $finalArray['ChatGroups']}})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button">Media Forum
                                    ({{ $finalArray['MediaForum']}})
                                </button>
                                <button href="javascript:void(0)" style="width: 200px" class="forgot_button">Focus Group
                                    Board ({{ $finalArray['FocusGroupBoard']}})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button" style="width: 160px;">Not Decided yet
                                    ({{ $finalArray['notDYet10']}})
                                </button>
                            </div>
                            <br>
                            <div class="progress">
                                <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar"
                                     aria-valuenow="{{$finalArray['OnlineEventsPer']}}" aria-valuemin="0"
                                     aria-valuemax="100" style="width:{{$finalArray['OnlineEventsPer']}}%">

                                </div>
                            </div>
                            <br>
                            {{--sdfsdfsdf--}}
                            <p class="text-muted font-13 mb-1">
                                <i class="mdi mdi-checkbox-blank-circle mr-2 text-success"></i>
                                @if($sponsor_type == 1)
                                    Average Sponsorship Offered for Research
                                @else
                                    Average Price of Research Sponsorship
                                @endif

                            </p>
                            <p class="text-muted font-13 mb-1">
                                <span class="float-right"> {{ $finalArray['ResearchBudgetMessage']}} (Based on {{$ReserachTotalUsers }} inputs )</span>
                            </p>
                            <a href="javascript:void(0)" id="a29" onClick="changeContent(29,30)" data-toggle="collapse"
                               data-target="#Research">View More</a>
                            <a href="javascript:void(0)" id="a30" onClick="changeContent(30,29)" data-toggle="collapse"
                               data-target="#Research">View Less</a>

                            <div id="Research" class="collapse">
                                <button href="javascript:void(0)" class="forgot_button" style="width: 200px">
                                    Commercial({{ $finalArray['commercialandscient']}})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button" style="width: 200px"> Scientific
                                    ({{ $finalArray['scientific']}})
                                </button>
                                <button href="javascript:void(0)" class="forgot_button" style="width: 160px;">Not Decided yet
                                    ({{ $finalArray['notDYet11']}})
                                </button>
                            </div>
                            <br>
                            <div class="progress">
                                <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar"
                                     aria-valuenow="{{$finalArray['researchUserPer']}}" aria-valuemin="0"
                                     aria-valuemax="100" style="width:{{$finalArray['researchUserPer']}}%">

                                </div>
                            </div>
                            <br>


                            <p class="text-muted font-13 mb-1">
                                <i class="mdi mdi-checkbox-blank-circle mr-2 text-success"></i> @if($sponsor_type == 1)
                                    Average Sponsorship Offered for Other
                                @else
                                    Average Price of Other Sponsorship
                                @endif
                            </p>
                            <p class="text-muted font-13 mb-1">
                                <span class="float-right"> {{ $finalArray['OtherBudgetMessage']}} (Based on {{$OtherTotalUsers }} inputs )</span>
                            </p>
                            <div class="progress">
                                <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar"
                                     aria-valuenow="{{ $finalArray['OtherUserPer']}}" aria-valuemin="0"
                                     aria-valuemax="100" style="width:{{ $finalArray['OtherUserPer']}}%">

                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-md-12 col-lg-12 align-self-center">
                            No data Found For this Country
                        </div>
                @endif
                <!--<div id="bardiv"></div>-->
                </div>
                <p>*Calculation based on 100 users per section</p>
            </div>


            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" onclick="closePopup()" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#a2,#a4,#a6,#a8,#a10,#a12,#a14,#a16,#a18,#a20,#a22,#a30').hide();
    });

    function changeContent(currentId, newId) {
        $('#a' + currentId).hide();
        $('#a' + newId).show();
    }
</script>
