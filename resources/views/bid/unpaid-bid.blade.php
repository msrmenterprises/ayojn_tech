@extends('layout')
@php  $buttons = $db_buttons['unpaid_bid'];  @endphp
@section('content')

<?php
use App\Country;use App\Industry;use App\SponsorrSpecify;use App\SponsorrSpecifyList;
 $countries = Country::all();
    $industries = Industry::all();
    $sponsorrlist = SponsorrSpecifyList::where('sponsorr_type', Auth::user()->sponsor_for)->get();
    $userwisesponsor = SponsorrSpecify::where('user_id', Auth::user()->id)->get();
    $userwisesponsorarray = [];
    if (count($userwisesponsor) > 0) {
        foreach ($userwisesponsor as $usersponsorr) {
            $userwisesponsorarray[] = $usersponsorr->specify_name;
        }
    }
?>
    <style>
    .swal-overlay--show-modal {
        z-index: 99999999999 !important;
    }
    .swal-modal {
        z-index: 99999 !important;
    }
    </style>

    <br><br><br><br>
    <section id="feed" class="speakers">
        <div class="container">
            <div class="row">
                <a href="{{ url('bid') }}" class="btn btn-primary float-left"
                >Opportunities </a>

            </div>
            <br>
            <br>
            <div class="row">
                <table id="table_id" class="display">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Wish to Sponsor</th>
                        <th>Specific <br>Opportunity</th>
                        <th>Why this Opportunity</th>
                        <th>Where to Sponsor</th>
                        <th>Identity</th>
                        <th>Expected Budget <br>(USD $)</th>
                        <th>Industry</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(!empty($bids->first()))
                        @foreach($bids as $bid)
                            <?php
                            $specification = '';
                            $specificationArray = [];
                            ?>
                            @if (!empty($bid->bidSpecify->first()))
                                @foreach ($bid->bidSpecify as $bidS)
                                    @php
                                        $specificationArray[] = $bidS->specifyName->specify_name;
                                        $specification = implode(', ',$specificationArray);
                                    @endphp
                                @endforeach
                            @endif

                            <tr>
                                <td>{{ $bid->id }}</td>
                                @if($bid->sponsor_for=='Online Events')
                                    <td> Online Activities</td>
                                @else
                                    <td>{{ $bid->sponsor_for }}</td>
                                @endif
                                <td>{{ $specification }}</td>
                                <td>{{  $bid->likeSponsorr }}</td>
                                <td>{{  (!empty($bid->country_name)) ? $bid->country_name->country_name : "-" }}
                                    / {{ (!empty($bid->city) && !empty($bid->city_name) && !empty($bid->city_name->name)) ? $bid->city_name['name'] : "-" }}</td>
                            <!--<td>{{ $bid->contacted_by }}</td> -->
                                <td>{{ $bid->identity }}</td>
                                <td>{{ $bid->sponsor_budget }}</td>
                                <td>{{ (!empty($bid->industry)) ? $bid->industry->name : '-'}}</td>

                                <td>
                                    @if($bid->admin_status == 0)
                                        Pending
                                    @else
                                        <!-- <a href="{{ url('pay-now/').'/'.$bid->id }}">
                                            <button class="btn btn-primary">Via Paypal</button>
                                        </a>  
                                        <a data-toggle="modal" data-target="#add-vouch-code">
                                            <input type="hidden" name="bid_id" id="bid_id" value="{{ $bid->id }}">
                                            @if(Auth::user()->refer_by == '' || (Auth::user()->refer_by != '' && Auth::user()->is_bonus_used == 1))
                                                <button class="btn btn-primary">Use a Voucher Code</button>
                                            @endif
                                        </a> -->
                                        @if($bid->is_paid==0)
                                        <a data-toggle="modal" class="paynow_modal" data-id="{{ $bid->id }}" data-sponsor="{{$bid->sponsor_budget}}" data-per="{{$bid->per}}" data-tooltip="true" data-placement="top"  title="{!!$buttons[0]['hover']!!}">  
                                            <button class="btn btn-success btn-sm">{!!$buttons[0]['icon']!!} {!!$buttons[0]['name']!!} </button>
                                        </a>
                                        <a href="javascript:void(0)" class="btn btn-primary btn-sm edit-bid" onclick="editBid({{ $bid->id }})"><i class='fa fa-pencil'></i> Edit</a>
                                        <a href="#" class="btn btn-danger btn-sm delete-bid" data-id="{{$bid->id}}"><i class='fa fa-trash'></i> Delete</a>
                                        @endif

                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>

        </div>

    </section>
    <div class="modal" id="add-vouch-code" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 45.56 45.559">
                            <path id="Union_1" data-name="Union 1" class="cls-1"
                                  d="M22.426,22.426,0,44.852,22.426,22.426,0,44.852,22.426,22.426,0,0,22.426,22.426,0,0,22.426,22.426,44.852,0,22.426,22.426,44.852,0,22.426,22.426,44.852,44.852,22.426,22.426,44.852,44.852ZM22.426,22.426Z"
                                  transform="translate(0.354 0.354)"/>
                        </svg>
                    </button>
                    <div style="text-align: center">Use a Voucher Code</div>
                </div>
                <div class="modal-body">

                    <form class="addVouchForm" id="addVouchForm" method="post" onsubmit="return false"
                          name="addVouchForm">

                        <div id="add-opporutnity-form">
                            <div class="row" style="padding-bottom: 8px;">
                                <div class="col-md-3">Vouch Code</div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="vouch_code" id="vouch_code"
                                           placeholder="Use a Voucher Code">
                                </div>
                            </div>

                        </div>
                        <button type="submit" id="addBid" class="btn btn-default" onclick="submitFormVouch()"
                                style="text-align: center">Submit
                        </button>
                    </form>

                </div>

            </div>
        </div>
    </div>



    <div class="modal" id="payment_proof" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
                    <div style="text-align: center">Upload Payment Proof</div>
                   
                </div>
                <div class="modal-body">
                    <span id="proof_attachment"></span>
                   
                <form id="payment_proof_form" enctype="multipart/form-data">
                {!! csrf_field() !!}
                        <input type="hidden" name="proof_bid" id="proof_bid" />
                        <div id="add-opporutnity-form">
                            <div class="row" style="padding-bottom: 8px;">
                                <div class="col-md-3">Select file</div>
                                <div class="col-md-6">
                                    <input type="file" class="form-control" name="payment_proof_img" id="payment_proof_img" required>
                                </div>
                               
                            </div>
                            <span id="error_msg" style="text-align: center; display: block; color: red;"></span>
                        </div>
                        <button type="submit"  class="btn btn-primary">Upload & Pay</button>
                        <a class="btn btn-success continue" style="display:none;">Continue to Pay</a>
                    </form>

                </div>

            </div>
        </div>
    </div>




    <div class="modal" id="paynow_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-sm" role="document" style="width:450px;">
		<div class="modal-content">
			<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
				<h4 class="modal-title" id="myModalLabel" style="text-align: center">Payment Details</h4>
				<span style="text-align: center; display: none; color: red;">
                Your will Get <span id="put_per"></span> in your wallet
                </span>
			</div>
			<div class="modal-body">
			
            <div class="form-group autocomplete" style="margin:0px; width: 100%; display: none; margin-left: auto; margin-right: 0;">
                <label>
                    <strong>Sponsor Budget</strong>
                </label>
                <label class="pull-right" id="put_sponsor_budget">
                </label>
                <hr style="margin-bottom: 10px; margin-top: 3px;" />			
			</div>
           


            <div class="form-group autocomplete" style="margin:0px; width: 100%; display: none; margin-left: auto; margin-right: 0;">
                <label>
                    <strong>Service Charges</strong>
                </label>
                <label class="pull-right">
                    100
                </label>
                <hr style="margin-bottom: 20px; margin-top: 3px;" />
			</div>
   
            <?php 
            use App\Helpers\CommonHelper;
            ?>

			<div id="wallet_row" class="form-group autocomplete" style="width: 100%; display: block; margin-left: auto; margin-bottom:10px; margin-right: 0;">
                <label>
                
                <input type="checkbox" name="list" value="" class="click_wallet form-control" style="height: 16px; width: 16px; margin-top: 3px; position: absolute;" />
                <span style="margin-left: 25px;">{{config('sitevars.data.ayojn_reward')}}</span>
                <input type="hidden" id="wallet_use" name="wallet_use" value="0" />
                </label>
                <label class="pull-right" id="wallet_balance">
                
                {{Auth::user()->wallet_balance}}
                </label>
			</div>


            <div id="voucher_row" class="form-group autocomplete" style="width: 100%; display: none; margin-left: auto; margin-bottom:30px; margin-right: 0;">
                <label>
                <input type="checkbox" name="list" value="" class="click_voucher form-control" style="height: 16px; width: 16px; margin-top: 3px; position: absolute;" />
                <span style="margin-left: 25px;">Pay via Voucher</span>
                </label>
                <label id="voucher_td" style="display: inline-block; float: right; width: 50%;">
                    <div class="input-group" style="position:absolute;">
                    <input type="search" class="form-control voucher_textbox" id="voucher_code" placeholder="Voucher code" >
                    <span class="input-group-btn">
                    <input type="hidden" id="vouch_amount" />
                    <button class="btn btn-primary voucher_button" type="button" >
                    </span> <i class='fa fa-thumbs-o-up'></i> Apply
                    </button>
                    
                    </div>

                
                <button class="btn btn-success btn-sm"><i class='fa fa-thumbs-o-up'></i> Apply</button>    
            </label>
			</div>

            <div id="gateway_row" class="form-group autocomplete" style="width: 100%; display: none; margin-left: auto; margin-bottom:10px; margin-right: 0;">
                <label>
                <input type="checkbox" name="list" value="" class="form-control" style="height: 16px; width: 16px; margin-top: 3px; position: absolute;" />
                <span style="margin-left: 25px;">Pay via Gateway</span>
                </label>
                
			</div>

				



            <hr style="margin-top: 5px;  margin-bottom: 10px;"/>
				<div class="form-group autocomplete" style="margin-bottom: 0px; width: 100%; display: block; margin-left: auto; margin-right: 0;">
						<label>
							<strong>{{config('sitevars.data.service_charge')}} ({{config('sitevars.data.currency')}})</strong>
						</label>
						<label class="pull-right">
							<span id="paidamt">
                               {{CommonHelper::getConfig('unpaid_opportunity')['ayojn_service']}}
                            </span> 
						</label>
						
				</div>

				<hr style="margin-top: 5px;"/>
				<div class="form-group autocomplete text-center">
				<a href="javascript:void(0)" class="btn btn-primary button-primary-color paynowfinal" data-bid=""> Pay Now</a>
                
            </div>


			</div>
		</div>
	</div>
</div>



    <div class="modal" id="update-bid" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 45.56 45.559">
                                <path id="Union_1" data-name="Union 1" class="cls-1"
                                      d="M22.426,22.426,0,44.852,22.426,22.426,0,44.852,22.426,22.426,0,0,22.426,22.426,0,0,22.426,22.426,44.852,0,22.426,22.426,44.852,0,22.426,22.426,44.852,44.852,22.426,22.426,44.852,44.852ZM22.426,22.426Z"
                                      transform="translate(0.354 0.354)"/>
                            </svg>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">Update Bid</h4>
                    </div>
                    <div class="modal-body">
                        <form class="updateBidForm" id="updateBidForm" method="post" onsubmit="return false"
                              name="updateBidForm">
                            <input type="hidden" id="bidId" name="bidId" value="">
                            <div class="form-group">
                                <!-- <h3>
                                    Let's gather the relevant details so that we can match your information requirements
                                </h3> -->
                            </div>
                            <div class="form-group autocomplete">
                                <label>
                                    <strong>Status Of Bid</strong>
                                </label>

                                {{--<input type="text" class="form-control" placeholder="e.g Conference, Music Festival, Tradeshow, Exhibition etc" id="sponsorOtherSpecifyValue">--}}
                                <select class="form-control" name="status_edit" id="status_edit">
                                    <option value="On">On</option>
                                    <option value="Off">Off</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>
                                    <strong>What do you want to get Sponsored</strong>
                                </label>
                            </div>

                            <div class="form-group">
                                <select class="form-control" name="sponsorr_type_bid_edit" id="sponsorr_type_bid_edit"
                                        onchange="getSpecifyBidEdit()">
                                    <option value="">Select Any</option>
                                    <option value="Event">Event</option>
                                    <option value="Campaign">Campaign</option>
                                    <option value="Content">Content</option>
                                    <option value="Sports Team">Sports Team</option>
                                    <option value="Venue">Venue</option>
                                    <option value="Not for Profit">Not for Profit</option>
                                    <option value="Performing Arts">Performing Arts</option>
                                    <option value="Think Tank">Think Tank</option>
                                    <option value="Knowledge Pool">Knowledge Pool</option>
                                    <option value="Online Events">Online Events</option>
                                    <option value="Other">Other</option>
                                </select>

                            </div>
                            <div id="sponsorOtherSpecifys" style="display: show !important;">
                                <div class="form-group autocomplete">
                                    <label>
                                        <strong>Please Specify</strong>
                                    </label>

                                    {{--<input type="text" class="form-control" placeholder="e.g Conference, Music Festival, Tradeshow, Exhibition etc" id="sponsorOtherSpecifyValue">--}}
                                    <select class="form-control" name="specify_bid_edit" id="specify_bid_edit" multiple>
                                        @if(count($sponsorrlist) > 0)
                                            @foreach($sponsorrlist as $sponsor_list)
                                                <option
                                                    value="{{ $sponsor_list->id }}">{{ $sponsor_list->specify_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>
                                    <strong>Objective you’re trying to achieve?</strong>
                                </label>
                            </div>
                            <div class="form-group ">
                                <select class="form-control" name="likeSponsorr[]" id="likeSponsorr" multiple>
                                    <option value="Message">Message</option>
                                    <option value="Leads">Leads</option>
                                    <option value="Branding">Branding</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>
                                    <strong>Geographically! Where exactly do you wish to focus?</strong>
                                </label>
                            </div>
                            <div class="form-group">
                                <select class="form-control" name="country_bid_edit" id="country_bid_edit" onchange="getCity(4)">
                                    <option value="">Select Country</option>
                                    @if(!empty($countries))
                                        @foreach($countries as $c)
                                            <option value="<?= $c->country_code?>"><?= $c->country_name?></option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group">
                                <label>
                                    <strong>Any Specific City/Across the Country</strong>
                                </label>
                            </div>
                            <div class="form-group">
                                <select class="form-control" name="city_bid_edit" id="city_bid_edit">
                                    <option value="Anywhere">Anywhere</option>
                                </select>
                            </div>

                              <div class="form-group">
                                <label>
                                    <strong>Do you wish this opportunity to be available only for local bidders from the selected city ? </strong>
                                </label>
                            </div>
                            <div class="form-group">
                                <select class="form-control" name="city_bidder_from" id="city_bidder_from">
                                    <option value="">Select Type</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>
                                    <strong>How do you wish to be contacted by the bidders?</strong>
                                </label>
                            </div>
                            <div class="form-group">
                                <select class="form-control" name="contacted_by_edit" id="contacted_by_edit">
                                    <option value="">Select Contact type</option>
                                    <option value="Email">Email</option>
                                    <option value="Mobile">Mobile</option>
                                    <option value="Both">Both</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>
                                    <strong>Opportunity available for</strong>
                                </label>
                            </div>
                            <div class="form-group">
                                <select class="form-control" name="identity_bid_edit" id="identity_bid_edit">
                                    <option value="">Select Contact type</option>
                                    <option value="Freelancers">Freelancers</option>
                                    <option value="Agencies">Agencies</option>
                                    <option value="Networks">Networks</option>
                                    <option value="Everyone">Everyone</option>
                                    <option value="Communities">Communities</option>
                                </select>
                            </div>


                            <div class="form-group">
                                <label>
                                    <strong>Approximate Budget (USD)</strong>
                                </label>
                            </div>

                            <div class="form-group">

                             <select class="form-control" name="budget_bid_edit" id="budget_bid_edit">
                                    <option value="">Select Any</option>
                                    <option value="200-500">200-500</option>
                                    <option value="500-2000">500-2000</option>
                                    <option value="2000-5000">2000-5000</option>
                                    <option value="5000-10000">5000-10000</option>
                                    <option value="10000-20000">10000-20000</option>
                                    <option value="20000-30000">20000-30000</option>
                                    <option value="30000-50000">30000-50000</option>
                                    <option value="50000-100000">50000-100000</option>
                                    <option value="Above 100000">Above 100000</option>
                                    <option value="In kind">In kind</option>

                                </select>
                               
                            </div>
                            <div class="form-group">
                                <label>
                                    <strong>Select Industry </strong>
                                </label>
                            </div>
                            <div class="form-group">
                                <select class="form-control" name="industry_bid_edit" id="industry_bid_edit">
                                    <option value="">Select your Industry</option>
                                    @foreach($industries as $in){
                                    <option value="{{ $in->id}}">{{ $in->name}}</option>
                                    }
                                    @endforeach
                                </select>

                            </div>
                            {{--<button type="button" class="btn btn-default" onclick="secondPopupPrevious()">Previous</button>--}}
                            <button type="submit" id="updateBid" onclick="updateBid()" class="btn btn-default">Submit
                            </button>
                        </form>

                    </div>

                </div>

                </div>
        </div>




    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        function submitFormVouch() {
            var vouch_code = $("#vouch_code").val();
            var bid_id = $("#bid_id").val();
// if (bid_id != '' && vouch_code != '') {
            $.ajax({
                url: "{{ url('/') }}" + '/add-new-vouch-code',
                type: "post",
                async: false,
                data: {
                    vouch_code: vouch_code,
                    bid_id: bid_id,
                },
                headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
                success: function (data) {
                    if (data.status) {
                        $("#add-vouch-code").modal('hide');
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                        swal(data.message);
                        setTimeout(function () {
                            location.href = "{{ url('/bid') }}";
                        }, 1000);
                    } else {
                        $("#add-vouch-code").modal('toggle');
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                        swal(data.message);
                    }
                },
                error: function (data) {
                },
            })
// } else {
//     $("#add-vouch-code").modal('toggle');
//     $('body').removeClass('modal-open');
//     $('.modal-backdrop').remove();
//     swal("Please enter valid Vouch code");
// }


        }
    </script>
    <script src="{{ asset('js/jquery1.min.js') }}"></script>
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/typed.min.js') }}"></script>
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script>


        $(document).ready(function () {
            $('#table_id').DataTable({
                "fnDrawCallback": function () {
// $('.my_switch').bootstrapToggle();
                    $('.my_switch').bootstrapToggle({})
                }
            });
        });
    </script>






<script>
    $(document).ready(function(){
        $("#voucher_td :input").prop("disabled", true);
    });


$('#payment_proof_form').on('submit', function(event){

    event.preventDefault();
    $.ajax({
        url: "{{ route('paynow-proof') }}",            
        method:'post',
        data:new FormData(this),
        dataType:'json',
        contentType: false,
        processData: false,
        success: function(result)
        {
            if(result.status){
                console.log(result.status);
                $("#paynow_modal").modal('show');
                $("#payment_proof").modal('hide');
            }
            else{
                console.log(result.status);
                $("#error_msg").html('unsupported file, please select pdf,doc,jpg,png')
            }
        },
        error: function(data)
        {
            //console.log(data);
        }
    });
});


    $(".continue").click(function(){

        $("#payment_proof").modal('hide');
        $("#paynow_modal").modal('show');
           
           var sponsor_budget = $(this).attr('data-sponsor');
           var per = $(this).attr('data-per');
           if(per>0){ var fper = per;} else{var fper = '10%';}
           var rspid = $(this).attr('data-id');
           $('.paynowfinal').attr('data-bid',rspid);
           $("#put_per").html(fper);
           $("#put_sponsor_budget").html(sponsor_budget);
    });




    //add by ram for paynow button 07 jan 2024------------------------        
    $("body").on("click", ".paynow_modal", function() {
         
        $("#paynow_modal").modal('show');
        var sponsor_budget = $(this).attr('data-sponsor');
           var per = $(this).attr('data-per');
           if(per>0){ var fper = per;} else{var fper = '10%';}
           var rspid = $(this).attr('data-id');
           $('.paynowfinal').attr('data-bid',rspid);
           $("#put_per").html(fper);
           $("#put_sponsor_budget").html(sponsor_budget);
           
        }); 
        
        $(".final").on("keyup change", function(e) {
            var amt = $(this).val();
            $("#paidamt").html(amt);
        });   



        $(document).on('click', '.paynowfinal', function (e) {

        $(".loader ").show();

        // var fields = $("input[name='list']").serializeArray(); 
        // if (fields.length === 0) 
        // { 
        //     alert('Select any one way to pay'); 
        //     return false;
        // } 

        var bidId = $(this).data('bid');
        var amt = $("#paidamt").html();
        var vid = $(this).data('bid');
        var voucher_code = $("#voucher_code").val();
        var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
        if($(".click_wallet").prop("checked"))
        {
            var wallet = $("#wallet_use").val();
        }
        else{
            var wallet = 0;
        }

        

        $.ajax({
                url: "{{ route('paynow-oportunity') }}",
                type: 'POST',
                data: {_token: CSRF_TOKEN, bidId: bidId, wallet:wallet, amt: amt, voucher_code: voucher_code},
                headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
                success: function (data) {
                console.log(data);
                if(data['flag'] == 1)
                {   
                    if(data['link'] !="")
                    {
                        window.location.replace(data['link']);
                    }else{

                        alert("Bid has been Paid!");
                        location.reload();
                    }
                    
                }
                
                },
                error: function () {
                alert("something went wrong!");
                }
            });

        });



        $(".click_wallet").click(function(){
            

            check = $(".click_wallet").prop("checked");
            if(check) 
            {
              
                if({{Auth::user()->wallet_balance}} >  {{CommonHelper::getConfig('unpaid_opportunity')['ayojn_service']}})
                {
                    var chk = parseInt($("#paidamt").html());
                    var pamt = {{Auth::user()->wallet_balance}}-chk;
                    $("#wallet_balance").html(pamt);
                    $("#paidamt").html(0);
                    $("#wallet_use").val(chk);
                    
                    var chkagain = parseInt($("#paidamt").html());
                    if(chkagain==0)
                    {
                        $("#voucher_row :input").prop("disabled", true);
                        $("#gateway_row :input").prop("disabled", true);
                    }
                    
                    
                }
                else{
                    var chk = parseInt($("#paidamt").html());
                    var pamt = chk-{{Auth::user()->wallet_balance}};
                    $("#wallet_balance").html(0);
                    $("#paidamt").html(pamt);
                    $("#wallet_use").val(chk);
        
                
                }
            }
            else
            {
                var chk = parseInt($("#paidamt").html());
                if({{Auth::user()->wallet_balance}} >  {{CommonHelper::getConfig('unpaid_opportunity')['ayojn_service']}})
                {
                    $("#paidamt").html({{CommonHelper::getConfig('unpaid_opportunity')['ayojn_service']}});
                }
                else{
                    $("#paidamt").html(chk+{{Auth::user()->wallet_balance}});
                }
        
                $("#wallet_balance").html({{Auth::user()->wallet_balance}});
                $("#voucher_row :input").prop("disabled", false);
                $("#gateway_row :input").prop("disabled", false);
                $("#wallet_use").val(0);
            }
                
        });



        $(".click_voucher").click(function(){

                check = $(".click_voucher").prop("checked");
                if(check) {
                    $("#voucher_td :input").prop("disabled", false);
                    $(".voucher_button").click(function(){
                        var code = $(".voucher_textbox").val();
                        if(code ==""){alert('please enter voucher code'); $(".voucher_textbox").focus(); return false;}
                        swal({
                        title: "Are you sure",
                        text: "You want to apply this code",
                        icon: "warning",
                        buttons: true,
                        }).then(
                            function (isConfirm) {
                                if (isConfirm) 
                                {
                                    
                                    $.ajax({
                                        url: "{{ route('check-vouch-code') }}",
                                        type: 'POST',
                                        data: {code: code},
                                        headers: {
                                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                                        },
                                        success: function (data) {
                                            if(data > 0){
                                                $("#vouch_amount").val(data);
                                                swal("Success", "Voucher has been applied", "success");
                                                var pamt = $("#paidamt").html();
                                                console.log(data);
                                                console.log(pamt);
                                                if(data < parseInt(pamt)){
                                                    var fval = pamt-data;
                                                }
                                                else{
                                                    var fval = 0;
                                                }
                                                
                                                $("#paidamt").html(fval);
                                                if(pamt==0)
                                                {
                                                    $("#wallet_row :input").prop("disabled", true);
                                                    $("#gateway_row :input").prop("disabled", true);
                                                }
                                                $("#voucher_td :input").prop('disabled',true);
                                            }
                                            else{
                                                swal("Warning", "voucher not exist or used", "warning");
                                            }
                                            
                                        },
                                        error: function () {
                                            swal("error", "There's been an error", "error");
                                        }
                                    });


                                }
                            });

                    
                    });
                }
                else{
                    var chk = parseInt($("#paidamt").html());
                    var vamt = parseInt($("#vouch_amount").val());
                    $("#voucher_td :input").prop("disabled", true);
                    $(".voucher_textbox").val('');
                    if($(".click_wallet").is(':checked')) 
                    {
                        var plusvalue = chk+vamt;
                        var finalvalue = plusvalue-{{Auth::user()->wallet_balance}}
                        $("#paidamt").html(finalvalue);
                    }
                    if($(".click_voucher").is(':checked')) 
                    {
                        $("#paidamt").html(chk+vamt);
                    }
                    else{
                        $("#paidamt").html(100);
                    }
                    
                    $("#wallet_row :input").prop("disabled", false);
                    $("#gateway_row :input").prop("disabled", false);
                    var vamt = parseInt($("#vouch_amount").val(0));
                }
        });

        //add by ram for paynow button 07 jan 2024----------------------

</script>

<script>
$(document).on('click', '.delete-bid', function(e) {
    e.preventDefault();
    var bidId = $(this).data('id');
    var row = $(this).closest('tr');
    swal({
        title: "Are you sure?",
        text: "This bid will be deleted permanently!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then(function(isConfirm) {
        if (isConfirm) {
            $.ajax({
                url: "{{ route('unpaid-bid-delete') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: bidId
                },
                success: function(response) {
                    if(response.status) {
                        swal("Deleted!", response.message, "success");
                        row.remove();
                    } else {
                        swal("Error!", response.message || "Could not delete.", "error");
                    }
                },
                error: function() {
                    swal("Error!", "Something went wrong.", "error");
                }
            });
        }
    });
});


function editBid(id) {
            $.ajax({
                type: 'POST',
                url: '{{ url('edit-bid') }}',
                headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
                data: {
                    id: id,
                },
                success: function (response) {
                    var data = response;
                    console.log(data.bid);
                    if (data.status) {
                        $('#bidId').val(data.bid.id);
                        $('#status_edit').find('option[value="' + data.bid.status + '"]').attr('selected', 'selected');
                        $('#sponsorr_type_bid_edit').find('option[value="' + data.bid.sponsor_for + '"]').attr('selected', 'selected');
                        $('#country_bid_edit').find('option[value="' + data.bid.sponsor_country + '"]').attr('selected', 'selected');
                        $('#budget_bid_edit').find('option[value="' + data.bid.sponsor_budget + '"]').attr('selected', 'selected');
                        $('#industry_bid_edit').find('option[value="' + data.bid.sponsor_industry + '"]').attr('selected', 'selected');
                        getSpecifyBidEdit();
                        getCity(4);
                        $('#identity_bid_edit').find('option[value="' + data.bid.identity + '"]').attr('selected', 'selected');
                        $('#contacted_by_edit').find('option[value="' + data.bid.contacted_by + '"]').attr('selected', 'selected');
                        $('#likeSponsorr').find('option[value="' + data.bid.likeSponsorr + '"]').attr('selected', 'selected');
                        $('#city_bidder_from').find('option[value="' + data.bid.city_bidder_from + '"]').attr('selected', 'selected');
                        setTimeout(function () {
                            $('#city_bid_edit').find('option[value="' + data.bid.city + '"]').attr('selected', 'selected');
                        }, 3500);
                        $.each(data.bid.bid_specify, function (key, value) {
                            console.log(value['specify_name']);
                            $('#specify_bid_edit').find('option[value="' + value['specify_name'] + '"]').attr('selected', 'selected');
                        })
                        $('#update-bid').modal("toggle");
                        //window.location.reload();
                    } else {
                        toastr.error(response.errors, "Error");
                    }
                },
                error: function (response) {
                    toastr.error(response.responseJSON.msg, "Error");
                    $(':input[type="submit"]').prop('disabled', false);
                },
            });
        }


        $("#updateBid").click(function () {
            var bidid = $("#bidId").val();
            var status = $("#status_edit").val();
            var country_bid = $("#country_bid_edit").val();
            var specify_bid = $("#specify_bid_edit").val();
            var sponsorr_type_bid = $("#sponsorr_type_bid_edit").val();
            var industry_bid = $("#industry_bid_edit").val();
            var budget_bid = $("#budget_bid_edit").val();
            var contacted_by = $("#contacted_by_edit").val();
            var identity_bid = $("#identity_bid_edit").val();
            var city_bid = $("#city_bid_edit").val();
            var city_bidder_from = $("#city_bidder_from").val();
            var likeSponsorr = $("#likeSponsorr").val();
            // $('#create_post').prop('disabled', true);
            $.ajax({
                type: 'POST',
                url: '{{ url('update-bid') }}',
                headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
                data: {
                    bidid: bidid,
                    status: status,
                    country_bid: country_bid,
                    specify_bid: specify_bid,
                    sponsorr_type_bid: sponsorr_type_bid,
                    industry_bid: industry_bid,
                    contacted_by: contacted_by,
                    identity_bid: identity_bid,
                    city_bid: city_bid,
                    budget_bid: budget_bid,
                    city_bidder_from: city_bidder_from,
                    likeSponsorr: likeSponsorr,
                },
                success: function (response) {
                    //console.log(response.status);
                    var data = response;
                    if (data.status) {
                        toastr.success(response.message, "Success");
                        window.location.reload();
                    } else {
                        toastr.error(response.errors, "Error");
                    }
                },
                error: function (response) {
                    toastr.error(response.responseJSON.msg, "Error");
                    $(':input[type="submit"]').prop('disabled', false);
                },
            });

        });


</script>
@endsection
