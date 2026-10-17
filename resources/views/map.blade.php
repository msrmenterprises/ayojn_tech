@extends('layout')

@section('content')
    <?php
    use App\Country;
    $countries  = Country::all();
    use App\Industry;
    $industries  = Industry::all();
    use App\SponsorrSpecify;
    use App\SponsorrSpecifyList;
    $sponsorrlist = SponsorrSpecifyList::where('sponsorr_type',Auth::user()->sponsor_for)->get();
    $userwisesponsor=SponsorrSpecify::where('user_id',Auth::user()->id)->get();
    $userwisesponsorarray=[];
    if(count($userwisesponsor) > 0){
        foreach ($userwisesponsor as $usersponsorr)
            {
                $userwisesponsorarray[]=$usersponsorr->specify_name;
            }
    }
    ?>
    <style>

        #chartdiv{
            height:calc(100vh - 135px);
        }
        #chartdiv {
            width: 100%;
            //height: 500px;
        }

        #bardiv {
            width: 100%;
            height: 300px;
        }
        .modal {
            text-align: center;
            padding: 0!important;
        }

        .modal:before {
            content: '';
            display: inline-block;
            height: 100%;
            vertical-align: middle;
            margin-right: -4px;
        }

        .modal-dialog {
            display: inline-block;
            text-align: left;
            vertical-align: middle;
        }
        .amcharts-chart-div a {display:none !important;}
    </style>
    <!-- Resources -->
    <div id="chartdiv" style="margin-top: 75px"></div>

    <!-- Modal -->
    <div id="popup"></div>
    <div class="modal" id="edituserform" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 45.56 45.559">
                            <path id="Union_1" data-name="Union 1" class="cls-1" d="M22.426,22.426,0,44.852,22.426,22.426,0,44.852,22.426,22.426,0,0,22.426,22.426,0,0,22.426,22.426,44.852,0,22.426,22.426,44.852,0,22.426,22.426,44.852,44.852,22.426,22.426,44.852,44.852ZM22.426,22.426Z" transform="translate(0.354 0.354)"/>
                        </svg>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Edit {{ (Auth::user()->sponsor_type == 1)?'Offer ':'Manage or Receive' }} Profile</h4>
                </div>
                <div class="modal-body">
                    <form class="edituserform" id="edituserform" method="post" onsubmit="return false"  name="edituserform">
                        <div class="form-group">
                            <h3>
                                Let's gather the relevant details so that we can match your information requirements
                            </h3>
                        </div>
                        <div class="form-group">
                            <label>
                                <strong>What do you want to get Sponsored</strong>
                            </label>
                        </div>

                        <div class="form-group">
                            <select class="form-control" name="sponsorType_edit" id="sponsorType_edit" onchange="displaySpecifyEdit(1)">
                                <option value="select">Select Any</option>
                                <option value="Event" {{ (Auth::user()->sponsor_for == 'Event')?'selected':'' }}>Event</option>
                                <option value="Campaign" {{ (Auth::user()->sponsor_for == 'Campaign')?'selected':'' }}>Campaign</option>
                                <option value="Content" {{ (Auth::user()->sponsor_for == 'Content')?'selected':'' }}>Content</option>
                                <option value="Sports Team" {{ (Auth::user()->sponsor_for == 'Sports Team')?'selected':'' }}>Sports Team</option>
                                <option value="Venue" {{ (Auth::user()->sponsor_for == 'Venue')?'selected':'' }}>Venue</option>
                                <option value="Not for Profit" {{ (Auth::user()->sponsor_for == 'Not for Profit')?'selected':'' }}>Not for Profit</option>
                                <option value="Performing Arts" {{ (Auth::user()->sponsor_for == 'Performing Arts')?'selected':'' }}>Performing Arts</option>
                            <option value="Think Tank" {{ (Auth::user()->sponsor_for == 'Think Tank')?'selected':'' }}>Think Tank</option>
                            <option value="Knowledge Pool" {{ (Auth::user()->sponsor_for == 'Knowledge Pool')?'selected':'' }}>Knowledge Pool</option>
                            <option value="Online Events" {{ (Auth::user()->sponsor_for == 'Online Events')?'selected':'' }}>Online Events</option>
                                <option value="Other" {{ (Auth::user()->sponsor_for == 'Other')?'selected':'' }}>Other</option>
                            </select>

                        </div>
                        <div id="sponsorOtherSpecifys" style="display: show !important;">
                            <div class="form-group autocomplete">
                                <label>
                                    <strong>Please Specify</strong>
                                </label>

                                {{--<input type="text" class="form-control" placeholder="e.g Conference, Music Festival, Tradeshow, Exhibition etc" id="sponsorOtherSpecifyValue">--}}
                                <select class="form-control" name="sponsorOtherSpecifyValue_edit[]" id="sponsorOtherSpecifyValue_edit" multiple>
                                    @if(count($sponsorrlist) > 0)
                                        @foreach($sponsorrlist as $sponsor_list)
                                            <option value="{{ $sponsor_list->id }}" {{ (in_array($sponsor_list->id,$userwisesponsorarray))?'selected':'' }}>{{ $sponsor_list->specify_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>
                                <strong>Where would you like this to be sponsored</strong>
                            </label>
                        </div>
                        <div class="form-group">
                            <select class="form-control" name="sponsorCountry_edit" id="sponsorCountry_edit">
                                <option value="">Select Country</option>
                                @if(!empty($countries))
                                    @foreach($countries as $c)
                                        <option value="<?= $c->country_code?>" {{ ($c->country_code == Auth::user()->country)?'selected':'' }}><?= $c->country_name?></option>
                                    @endforeach
                                @endif
                            </select>
                        </div>



                        <div class="form-group">
                            <label>
                                <strong>Whats your ideal budget(AED)</strong>
                            </label>
                        </div>

                        <div class="form-group">
                            <select class="form-control" name="sponsorBudget_edit" id="sponsorBudget_edit">
                                <option value="">Select Any</option>
                                <option value="Less than 2000" {{ (Auth::user()->sponsor_budget == 'Less than 2000')?'selected':'' }}>Less than $2000</option>
                                <option value="Between 2000-5000" {{ (Auth::user()->sponsor_budget == 'Between 2000-5000')?'selected':'' }}>Between $2000-$5000</option>
                                <option value="Between 5000-20000" {{ (Auth::user()->sponsor_budget == 'Between 5000-20000')?'selected':'' }}>Between $5000-$20000</option>
                                <option value="Above 20000" {{ (Auth::user()->sponsor_budget == 'Above 20000')?'selected':'' }}>Above $20000</option>

                            </select>
                        </div>
                        <div class="form-group">
                            <label>
                                <strong>Select Industry </strong>
                            </label>
                        </div>
                        <div class="form-group">
                            <select class="form-control" name="sponsorIndustry_edit" id="sponsorIndustry_edit">
                                <option value="test">Select your Industry </option>
                                @foreach($industries as $in){
                                <option value="{{ $in->id}}" {{ (Auth::user()->sponsor_industry == $in->id)?'selected':'' }}>{{ $in->name}}</option>
                                }
                                @endforeach
                            </select>

                        </div>
                        {{--<button type="button" class="btn btn-default" onclick="secondPopupPrevious()">Previous</button>--}}
                        <button type="submit" id="editformsubmit" onclick="UpdateUserForm()" class="btn btn-default">Submit</button>
                    </form>

                </div>

            </div>
        </div>
    </div>
    <!-- top country popup start -->
    <!-- Modal -->
    <div id="topCountriesForms" class="modal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Modal Header</h4>
                </div>
                <div class="modal-body">
                    <p>Some text in the modal.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
    <!-- top country popup end -->
    <!-- banner section end -->

    <script>
        (function($){

            if ($.fn.typed){
                var $typedStrings = $('.typed-strings');
                $typedStrings.typed({
                    strings: ['an event', 'a campaign', 'a sports team','a gig','specific content'],
                    typeSpeed: 130,
                    loop: true,
                    showCursor: false
                });
            }
            else{
                console.log('Animated typing: Plugin "typed" is not loaded.');
            }




        })(jQuery);
        function UpdateUserForm(){
          var sponsorType_edit = $("#sponsorType_edit").val();
          var sponsorOtherSpecifyValue_edit = $("#sponsorOtherSpecifyValue_edit").val();
          var sponsorCountry_edit = $("#sponsorCountry_edit").val();
          var sponsorBudget_edit = $("#sponsorBudget_edit").val();
          var sponsorIndustry_edit = $("#sponsorIndustry_edit").val();


          // $('#create_post').prop('disabled', true);
          $.ajax({
            type: 'POST',
            url: '{{ route('edituserprofile') }}',
            headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
            data: {sponsorType_edit:sponsorType_edit,sponsorOtherSpecifyValue_edit:sponsorOtherSpecifyValue_edit,sponsorCountry_edit:sponsorCountry_edit,sponsorBudget_edit:sponsorBudget_edit,sponsorIndustry_edit:sponsorIndustry_edit},
            //cache: false,
            //contentType: false,
            //processData: false,
            success: function (response) {
              //console.log(response.status);
              var data = response;

                toastr.success(response.msg, "Success");
                  /*notification = new Notification('New post alert!', {
                   body: 'this is test', // content for the alert
                   icon: "https://pusher.com/static_logos/320x320.png" // optional image url
                   });*/

                // link to page on clicking the notification
                 window.location.reload();
                //}

            },
            error: function (response) {
              toastr.error(response.responseJSON.msg, "Error");
              $(':input[type="submit"]').prop('disabled', false);
            },
          });

}
    </script>


    <!-- Chart code -->
    <script>
        var map = AmCharts.makeChart("chartdiv", {

            "type": "map",
            "theme": "light",
            "zoomOnDoubleClick": false,
            "dataProvider": {
                "map": "worldLow",
                "getAreasFromMap": true,
                "mapColor":"#fff",
                "color":"#fff"
            },
            "areasSettings": {
                "autoZoom": false,
                "selectedColor": "rgb(253, 227, 231)",
                "rollOverColor": "rgb(253, 227, 231)",
                "mapColor":"#fff",
                "color":"rgb(184, 228, 216)",
                "selectable": true
            },
            // "smallMap": {}
        });

        map.addListener("clickMapObject", function(event) {
            openpopup(event);

        });
        function openpopvup(event){
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
          type: 'POST',
          url: "{{ route('mapdata') }}",
          data: {_token: CSRF_TOKEN},
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function (response) {
            var data = JSON.parse(response);
             $('#myModal').modal('show');
          },
          error: function(jqXHR, textStatus, errorThrown) {
            toastr.error("Something went wrong", "Error");
          },
        });

        }


        function openpopup(event) {
        $.ajax({
            type: 'POST',
            url: '{{ route('mapdata') }}',
            headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
            data: {countryId:event.mapObject.id},
            success: function (data) {
             $('#popup').html(data);

                 $('#myModal').modal('show');
            },
        });
        return false;
        }
       function closePopup(){
        $(".modal-backdrop").removeClass('modal-backdrop');
        var map = AmCharts.makeChart("chartdiv", {

                "type": "map",
                "theme": "light",
                "zoomOnDoubleClick": false,
                "dataProvider": {
                    "map": "worldLow",
                    "getAreasFromMap": true,
                    "mapColor":"#fff",
                    "color":"#fff"
                },
                "areasSettings": {
                    "autoZoom": false,
                    "selectedColor": "rgb(253, 227, 231)",
                    "rollOverColor": "rgb(253, 227, 231)",
                    "mapColor":"#fff",
                    "color":"rgb(184, 228, 216)",
                    "selectable": true
                },
                // "smallMap": {}
            });
            map.addListener("clickMapObject", function(event) {

                openpopup(event);

            });
       }
        $('#myModal').on('hidden.bs.modal', function () {
          console.log("test")
        })
        function submitEditForm() {

        }
    </script>
@endsection
