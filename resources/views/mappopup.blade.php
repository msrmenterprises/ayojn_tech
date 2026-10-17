<style>
    .forgot_button{
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
                            <a href="javascript:void(0)" id="a1" onClick="changeContent(1,2)" data-toggle="collapse" data-target="#demo">View More</a>
                            <a href="javascript:void(0)" id="a2" onClick="changeContent(2,1)" data-toggle="collapse" data-target="#demo">View Less</a>

                            <div id="demo" class="collapse">
                                <button href="javascript:void(0)" class="forgot_button" >Conference ({{ $finalArray['conference']}})</button>
                                <button href="javascript:void(0)" class="forgot_button">Work Shop ({{ $finalArray['music_festival']}})</button>
                                <button href="javascript:void(0)" class="forgot_button">Tradeshow ({{ $finalArray['tradeshow']}})</button>
                                <button href="javascript:void(0)" class="forgot_button">Exhibition ({{ $finalArray['exhibition']}})</button>
                                <button href="javascript:void(0)" class="forgot_button">Masterclass ({{ $finalArray['Masterclass']}})</button><button href="javascript:void(0)" class="forgot_button">Seminar ({{ $finalArray['Seminar']}})</button><button href="javascript:void(0)" class="forgot_button">Delegation ({{ $finalArray['Delegation']}})</button><button href="javascript:void(0)" style="width: 215px" class="forgot_button">Awards & Competitions ({{ $finalArray['AwardsCompetitions']}})</button><button href="javascript:void(0)" class="forgot_button" style="width: 215px">Festivals & Parties ({{ $finalArray['FestivalsParties']}})</button><button href="javascript:void(0)" class="forgot_button" style="width: 160px">Product Launch ({{ $finalArray['ProductLaunch']}})</button>
                            </div><br>
                            <div class="progress" >
                                <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="{{$finalArray['EventPer']}}" aria-valuemin="0" aria-valuemax="100" style="width:{{$finalArray['EventPer']}}%">

                                </div>
                            </div><br>
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
                            <a href="javascript:void(0)" id="a3" onClick="changeContent(3,4)" data-toggle="collapse" data-target="#campaign">View More</a>
                            <a href="javascript:void(0)" id="a4" onClick="changeContent(4,3)" data-toggle="collapse" data-target="#campaign">View Less</a>

                            <div id="campaign" class="collapse">
                                <button href="javascript:void(0)" class="forgot_button" >Online ({{ $finalArray['Online'] }})</button>
                                <button href="javascript:void(0)" class="forgot_button">Offline ({{ $finalArray['Offline'] }})</button>
                                <button href="javascript:void(0)" class="forgot_button">Social Media ({{ $finalArray['SocialMedia'] }})</button>
                                <button href="javascript:void(0)" class="forgot_button">Influencer ({{ $finalArray['Infleucer'] }})</button><button href="javascript:void(0)" class="forgot_button">Email ({{ $finalArray['Email'] }})</button><button href="javascript:void(0)" class="forgot_button">Experiential ({{ $finalArray['Experiential'] }})</button><button href="javascript:void(0)" class="forgot_button">Canvassing ({{ $finalArray['Canvassing'] }})</button>
                                <button href="javascript:void(0)" class="forgot_button">PR ({{ $finalArray['PR'] }})</button>
                            </div><br>
                            <div class="progress" >
                                <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="{{ $finalArray['CampaignPer']}}" aria-valuemin="0" aria-valuemax="100" style="width:{{ $finalArray['CampaignPer']}}%">

                                </div>
                            </div><br>
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
                            <a href="javascript:void(0)" id="a5" onClick="changeContent(5,6)" data-toggle="collapse" data-target="#Content">View More</a>
                            <a href="javascript:void(0)" id="a6" onClick="changeContent(6,5)" data-toggle="collapse" data-target="#Content">View Less</a>

                            <div id="Content" class="collapse">
                                <button href="javascript:void(0)" class="forgot_button">Blog ({{ $finalArray['Blog'] }})</button>
                                <button href="javascript:void(0)" class="forgot_button">Video ({{ $finalArray['Video'] }})</button>
                                <button href="javascript:void(0)" class="forgot_button">Infographics ({{ $finalArray['Inforgraphics'] }})</button>
                                <button href="javascript:void(0)" class="forgot_button">Case Studies ({{ $finalArray['CaseStudies'] }})</button>
                                <button href="javascript:void(0)" class="forgot_button">Whitepapers ({{ $finalArray['Whitpapers'] }})</button>
                                <button href="javascript:void(0)" class="forgot_button">Articles ({{ $finalArray['Articles'] }})</button>
                                <button href="javascript:void(0)" class="forgot_button">Interviews ({{ $finalArray['Interviews'] }})</button>
                                <button href="javascript:void(0)" class="forgot_button">
                                    Memes/ GIFs ({{ $finalArray['Memes'] }})</button>
                            </div><br>
                            <div class="progress" >
                                <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" aria-valuenow="{{ $finalArray['ContentUserPer']}}" aria-valuemin="0" aria-valuemax="100" style="width:{{ $finalArray['ContentUserPer']}}%">

                                </div>
                            </div>
                            <p class="text-muted font-13 mb-1">
                                <i class="mdi mdi-checkbox-blank-circle mr-2 text-success"></i>
                                @if($sponsor_type == 1)
                                    Average Sponsorship Offered for Sports Team
                                @else
                                    Average Price of Sports Team Sponsorship
                                @endif
                            </p>
                            <p class="text-muted font-13 mb-1">
                                <span class="float-right"> {{ $finalArray['SportBudgetMessage']}} (Based on {{$SportsTotalUsers}} inputs )</span>
                            </p>
                            <a href="javascript:void(0)" id="a7" onClick="changeContent(7,8)" data-toggle="collapse" data-target="#SportTeam">View More</a>
                            <a href="javascript:void(0)" id="a8" onClick="changeContent(8,7)" data-toggle="collapse" data-target="#SportTeam">View Less</a>

                            <div id="SportTeam" class="collapse">
                                <button href="javascript:void(0)" class="forgot_button" >Football ({{ $finalArray['Football'] }})</button>
                                <button href="javascript:void(0)" class="forgot_button">Regional ({{ $finalArray['Regional'] }})</button>
                                <button href="javascript:void(0)" class="forgot_button">Adventure Sports ({{ $finalArray['AdventureSports'] }})</button>
                                <button href="javascript:void(0)" class="forgot_button">Racetrack ({{ $finalArray['Racetrack'] }})</button>
                                <button href="javascript:void(0)" class="forgot_button">International ({{ $finalArray['International'] }})</button><button href="javascript:void(0)" class="forgot_button">Cricket ({{ $finalArray['Cricket'] }})</button><button href="javascript:void(0)" class="forgot_button">Boxing ({{ $finalArray['Boxing'] }})</button><button href="javascript:void(0)" class="forgot_button">Golf ({{ $finalArray['Golf'] }})</button><button href="javascript:void(0)" class="forgot_button">Polo ({{ $finalArray['Polo'] }})</button><button href="javascript:void(0)" class="forgot_button">Skiing ({{ $finalArray['Skiing'] }})</button><button href="javascript:void(0)" class="forgot_button">Power Boat ({{ $finalArray['PowerBoat'] }})</button><button href="javascript:void(0)" class="forgot_button">Base Ball ({{ $finalArray['BaseBall'] }})</button>
                                <button href="javascript:void(0)" style="width: 150px"  class="forgot_button">Online Gaming ({{ $finalArray['OnlineGaming'] }})</button>
                            </div>
                            <div class="progress" >
                                <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="{{ $finalArray['SportTeamUserPer']}}" aria-valuemin="0" aria-valuemax="100" style="width:{{ $finalArray['SportTeamUserPer']}}%">

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
                            <a href="javascript:void(0)" id="a11" onClick="changeContent(11,12)" data-toggle="collapse" data-target="#Outdoor">View More</a>
                            <a href="javascript:void(0)" id="a12" onClick="changeContent(12,11)" data-toggle="collapse" data-target="#Outdoor">View Less</a>

                            <div id="Outdoor" class="collapse">
                                <button href="javascript:void(0)" class="forgot_button" >Outdoor ({{ $finalArray['Outdoor']}})</button>
                                <button href="javascript:void(0)" class="forgot_button">Indoor ({{ $finalArray['indoor']}})</button>
                                <button href="javascript:void(0)" class="forgot_button">Sports ({{ $finalArray['Sports']}})</button>
                                <button href="javascript:void(0)" class="forgot_button">Shopping ({{ $finalArray['Shopping']}})</button>
                                <button href="javascript:void(0)" class="forgot_button">Entertainment ({{ $finalArray['Entertainment']}})</button> <button href="javascript:void(0)" class="forgot_button">Transport ({{ $finalArray['Transport']}})</button>
                            </div><br>
                            <div class="progress" >
                                <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="{{$finalArray['VenuePer']}}" aria-valuemin="0" aria-valuemax="100" style="width:{{$finalArray['VenuePer']}}%">

                                </div>
                            </div><br>

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
                            <a href="javascript:void(0)" id="a13" onClick="changeContent(13,14)" data-toggle="collapse" data-target="#CSR">View More</a>
                            <a href="javascript:void(0)" id="a14" onClick="changeContent(14,13)" data-toggle="collapse" data-target="#CSR">View Less</a>

                            <div id="CSR" class="collapse">
                                <button href="javascript:void(0)" class="forgot_button" >CSR ({{ $finalArray['CSR']}})</button>
                                <button href="javascript:void(0)" class="forgot_button">Aid ({{ $finalArray['Aid']}})</button>
                                <button href="javascript:void(0)" class="forgot_button">Donation ({{ $finalArray['Donation']}})</button>
                                <button href="javascript:void(0)" class="forgot_button">Campaign ({{ $finalArray['Campaign']}})</button>
                                <button href="javascript:void(0)" class="forgot_button">Lobbying ({{ $finalArray['Lobbying']}})</button>
                                <button href="javascript:void(0)" class="forgot_button">Charity ({{ $finalArray['Charity']}})</button>
                            </div><br>
                            <div class="progress" >
                                <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="{{$finalArray['ProfitPer']}}" aria-valuemin="0" aria-valuemax="100" style="width:{{$finalArray['ProfitPer']}}%">

                                </div>
                            </div><br>

                            {{--sdfsdfsdf--}}
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
                            <div class="progress" >
                                <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" aria-valuenow="{{ $finalArray['OtherUserPer']}}" aria-valuemin="0" aria-valuemax="100" style="width:{{ $finalArray['OtherUserPer']}}%">

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
        $( document ).ready(function() {
    $('#a2,#a4,#a6,#a8,#a10,#a12,#a14').hide();
});
     function changeContent(currentId,newId){
        $('#a'+currentId).hide();
         $('#a'+newId).show();
     }
    </script>