@extends('layout')
@php  $buttons = $db_buttons['offer_bid_response'];  @endphp
@section('content')

<style>
.swal-overlay--show-modal {
    z-index: 99999999999 !important;
}
.swal-modal {
    z-index: 99999 !important;
}
</style>

    <?php
    $f = Request::get('f');
    $cn = Request::get('c');
    $b = Request::get('b');
    $i = Request::get('i');

    ?>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
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
    }?>
    <!-- banner section start -->

    @php
    $bid = base64_decode(request()->segment(2));
    $rst = \DB::table('bids')->where('id',$bid)->first(['sponsor_budget','per']);
    @endphp



    <br><br><br><br>
    <section id="feed" class="speakers">
        <div class="container">
            <a href="{{url('bid')}}" class="btn btn-primary" data-tooltip="true" data-placement="top"  title="{!!$buttons[0]['hover']!!}">{!!$buttons[0]['icon']!!} {!!$buttons[0]['name']!!} </a>
            <!--a href="{{url('spam-bids') . '/'.$bidId}}" class="btn btn-primary" data-tooltip="true" data-placement="top"  title="{!!$buttons[1]['hover']!!}">{!!$buttons[1]['icon']!!} {!!$buttons[1]['name']!!} </a-->
            <h4 style="text-align: center"> Response for </h4>
            <h4 style="text-align: center"> Opportunity ID #{{ @$bidDetail->id }}</h4>
            <br>
            <div class="row">
                <form name="search">
                    <div class="col-md-3">
                        <select class="form-control" name="f">
                            <option value="">Select Indentity</option>
                            <option value="Freelancers" <?php if ($f == "Freelancers") {
                                echo "Selected";
                            }?>>Freelancers
                            </option>
                            <option value="Agencies" <?php if ($f == "Agencies") {
                                echo "Selected";
                            }?>>Agencies
                            </option>
                            <option value="Networks" <?php if ($f == "Networks") {
                                echo "Selected";
                            }?>>Networks
                            </option>
                            <option value="Communities" <?php if ($f == "Communities") {
                                echo "Selected";
                            }?>>Communities
                            </option>

                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" name="c" id="c-filter" onchange="getCity(5)">
                            <option value="">Select Country</option>
                            @if(!empty($countries))
                                @foreach($countries as $c)
                                    <option value="<?= $c->country_code?>" <?php if ($cn == $c->country_code) {
                                        echo "Selected";
                                    }?>><?= $c->country_name?></option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" name="city" id="city-filter">
                            <option value="">Select city</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" name="i" id="i">
                            <option value="">Select Any</option>
                            <option value="1">Read More</option>
                            <option value="2">Open For Negotiation</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary" data-tooltip="true" data-placement="top"  title="{!!$buttons[2]['hover']!!}"
                        >{!!$buttons[2]['icon']!!} {!!$buttons[2]['name']!!} 
                        </button> &nbsp;<a href="{{ url('bid') }}" title="refresh" class="btn btn-primary" data-tooltip="true" data-placement="top"  title="{!!$buttons[3]['hover']!!}"
                        >{!!$buttons[3]['icon']!!} {!!$buttons[3]['name']!!} </a></div>
                </form>
            </div>
            <br>
            <div class="row">
                <table id="table_id" class="display">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Email</th>
                        <th>Description</th>
                        <th>Link</th>
                        <th>Date</th>
                        <th>Action</th>
                        <!-- <th>Irrevelant</th> -->
                    </tr>
                    </thead>
                    <tbody>
                    @if(!empty($bidResponses->first()))
                        @foreach($bidResponses as $response)

                            <tr>
                                <td>{{ $response->id }}</td>
                                @if($response->is_accepted ==2)
                                    <td>{{ (!empty($response->userDetail)) ? $response->userDetail->email : ''  }}</td>
                                @else
                                    <td>XXXXXXgmail.com</td>
                            @endif
                                <td>{{ $response->description }}</td> 
                                <td>{{ $response->portfolio_link }}</td>
                                <td>{{ date('Y-m-d',strtotime($response->created_at)) }}</td>
                                <td>
                                    <a href="javascript:void(0)"
                                       title="Read More" onclick="readMore({{ $response->id }})" class="read-more"
                                       data-value="{{ $response->id }}">Read More&nbsp;<i class="fa fa-eye" aria-hidden="true"></i></a> 
                                     @if($response->is_accepted == 1)

                                     <a href="javascript:void(0)" class="btn btn-primary btn-sm" onclick="acceptBid({{ $response->id }},'2')"
                                           title="Open for Negotiation">Open for Negotiation&nbsp;<i class="fa fa-check"
                                                                                                     aria-hidden="true"></i></a>

                                    @elseif($response->is_accepted == 2)
                                    <a href="javascript:void(0)" class="btn btn-success btn-sm bookNegotiation" onclick="acceptBid({{ $response->id }},'3')">
                                        <i class="fa fa-hand-o-up"></i> Book Now</a>
                                    @elseif($response->is_accepted == 3)
                                    <!--a href="javascript:void(0)" class="btn btn-danger btn-sm paynow btn-xs" data-bid="{{ $response->id }}">Pay Now</a-->

                                    <a href='javascript:void(0)' class='btn btn-success button-success-color paynow_modal btn-xs' data-bid='{{ $response->id }}'> Pay Now</a>
                                       
                                    @elseif($response->is_accepted == 4)
                                        <span class="label label-success">Paid</span>       
                                    @endif
                                    &nbsp;
                                </td>
                                <!-- <td><a href="javascript:void(0)" onclick="openReasonForm({{ $response->id }})"
                                       title="Mark Irrevelant">Mark Irrevelant</a></td> -->


                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
            <div id="append-form"></div>
        </div>
        <div id="html"></div>


        <div id="ram_modal"></div>



<div class="modal" id="paynow_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-sm" role="document" style="width:450px;">
		<div class="modal-content">
			<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
				<h4 class="modal-title" id="myModalLabel" style="text-align: center">Payment Details</h4>
				<span style="text-align: center; display: block; color: red;">
                Your will Get @if($rst->per==0) 10% @else {{$rst->per}}% @endif in your wallet
                </span>
			</div>
			<div class="modal-body">
			
            <div class="form-group autocomplete" style="margin:0px; width: 100%; display: block; margin-left: auto; margin-right: 0;">
                <label>
                    <strong>Sponsor Budget</strong>
                </label>
                <label class="pull-right">
                {{$rst->sponsor_budget}}
                </label>
						
			</div>
            <hr style="margin-bottom: 20px; margin-top: 3px;" />


            <div class="form-group autocomplete" style="margin:0px; width: 100%; display: block; margin-left: auto; margin-right: 0;">
                <label>
                    <strong>Service Charges</strong>
                </label>
                <label class="pull-right">
                    <input type="text" class="form-control final" style="margin-top:-13px; width:150px;" />
                </label>
						
			</div>
            <hr style="margin-bottom: 20px; margin-top: 3px;" />
			 

			<div id="wallet_row" class="form-group autocomplete" style="width: 100%; display: block; margin-left: auto; margin-bottom:10px; margin-right: 0;">
                <label>
                
                <input type="checkbox" name="list" value="" class="click_wallet form-control" style="height: 16px; width: 16px; margin-top: 3px; position: absolute;" />
                <span style="margin-left: 25px;">{{config('sitevars.data.ayojn_reward')}}</span>
                <input type="hidden" id="wallet_use" name="wallet_use" value="0" />
                </label>
                <label class="pull-right" id="wallet_balance">
                
                {{auth::user()->wallet_balance}}
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
							<strong>Final Paid Amount</strong>
						</label>
						<label class="pull-right">
							<span id="paidamt">
                               0
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




    </section>

    <script>

        $(document).ready(function(){
            $("#voucher_td :input").prop("disabled", true);
        });

        function openReasonForm(responseId) {
            $.ajax({
                url: "{{ url('/') }}" + '/add-reason/' + responseId,
                type: "get",
                async: false,
                headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
                success: function (data) {
                    if (data.html != '') {
                        $("#append-form").append(data.html);
                        $("#add-reason").modal('show');
                    }
                },
                error: function (data) {
                },
            })
        }

        function acceptBid(responseId,status) {
            $.ajax({
                type: 'POST',
                headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
                url: '{{ url('bid-accept') }}/' + responseId,
                data: 'status=' + status ,
                cache: false,
                processData: false,
                success: function (response) {
                    swal("Bid has been booked", {
                        icon: "success",
                    });
                    setTimeout(function () {
                       location.reload();
                    }, 1000);
                },
                error: function (response) {
                    toastr.error(response.responseJSON.msg, "Error");
                    $(':input[type="submit"]').prop('disabled', false);
                },
            });
            {{--swal({--}}
            {{--  title: "Are you sure?",--}}
            {{--  text: "You want to accept this bid?",--}}
            {{--  icon: "warning",--}}
            {{--  buttons: true,--}}
            {{--  dangerMode: true,--}}
            {{--})--}}
            {{--  .then((willDelete) => {--}}
            {{--    if (willDelete) {--}}

            {{--      $.ajax({--}}
            {{--        type: 'GET',--}}
            {{--        headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},--}}
            {{--        url: '{{ url('bid-accept') }}/' + responseId,--}}

            {{--        cache: false,--}}
            {{--        processData: false,--}}
            {{--        success: function (response) {--}}
            {{--          swal(response.message, {--}}
            {{--            icon: "success",--}}
            {{--          });--}}
            {{--        },--}}
            {{--        error: function (response) {--}}
            {{--          toastr.error(response.responseJSON.msg, "Error");--}}
            {{--          $(':input[type="submit"]').prop('disabled', false);--}}
            {{--        },--}}
            {{--      });--}}
            {{--    } else {--}}

            {{--    }--}}
            {{--  });--}}
        }

        function readMore(response_id) {
            //  response_id = $(this).data("value");
            $.ajax({
                type: 'GET',
                headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
                url: '{{ url('get-bid-response') }}/' + response_id,

                cache: false,
                processData: false,
                success: function (response) {
                    $("#html").html(response.html);
                    $("#read-more-response").modal('show');
                },
                error: function (response) {
                    toastr.error(response.responseJSON.msg, "Error");
                    $(':input[type="submit"]').prop('disabled', false);
                },
            });
        }

        $("#addBid").click(function () {
            var bid_id = $("#bid_input_id").val();
            var description = $("#description").val();
            var portfolio = $("#portfolio").val();
            $.ajax({
                type: 'POST',
                url: '{{ url('add-bid-response') }}',
                headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
                data: {
                    bid_id: bid_id,
                    description: description,
                    portfolio_link: portfolio,
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


                    // link to page on clicking the notification

                    //}

                },
                error: function (response) {
                    toastr.error(response.responseJSON.msg, "Error");
                    $(':input[type="submit"]').prop('disabled', false);
                },
            });

        });


  
        //add by ram for paynow button 07 jan 2024------------------------        
        $("body").on("click", ".paynow_modal", function() {
            $("#paynow_modal").modal('show');
            var rspid = $(this).attr('data-bid');
            $('.paynowfinal').attr('data-bid',rspid);

        }); 
        
        $(".final").on("keyup change", function(e) {
            var amt = $(this).val();
            $("#paidamt").html(amt);
        });   



        $(document).on('click', '.paynowfinal', function (e) {

        $(".loader ").show();

        if($(".final").val()=="")
		{
			alert('Service Charges is required!');
			$(".final").focus();
			return false;
			
		}   

        // var fields = $("input[name='list']").serializeArray(); 
        // if (fields.length === 0) 
        // { 
        //     alert('Select any one way to pay'); 
        //     return false;
        // } 


        var bidId = $(this).data('bid');
        var amt = $("#paidamt").html();
        var bid_close_amount = $(".final").val();
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
                url: "{{ route('paynow-page-user') }}",
                type: 'POST',
                data: {_token: CSRF_TOKEN, bidId: bidId, wallet:wallet, amt: amt, bid_close_amount: bid_close_amount, voucher_code: voucher_code},
                headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
                success: function (data) {
                console.log(data);
                if(data['flag'] == 1)
                {   
                    if(data['link'] !="")
                    {
                        //alert("Payment has been done!");
                        //location.reload();
                        //window.open(data['link']);
                        window.location.replace(data['link']);
                    }else{

                        alert("Opportunity has been vouched!");
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
            
            if($(".final").val()=="")
            {
                alert('Service Charges is required!');
                $(".final").focus();
                return false;
                
            }   


            check = $(".click_wallet").prop("checked");
            if(check) 
            {
                var closeamt = parseInt($(".final").val());
                if({{auth::user()->wallet_balance}} > closeamt)
                {
                    var chk = parseInt($("#paidamt").html());
                    var pamt = {{auth::user()->wallet_balance}}-chk;
                    $("#wallet_balance").html(pamt);
                    $("#paidamt").html(0);
                    $("#wallet_use").val(pamt);
                    
                    var chkagain = parseInt($("#paidamt").html());
                    if(chkagain==0)
                    {
                        $("#voucher_row :input").prop("disabled", true);
                        $("#gateway_row :input").prop("disabled", true);
                    }
                    
                    
                }
                else{
                    var chk = parseInt($("#paidamt").html());
                    var pamt = chk-{{auth::user()->wallet_balance}};
                    $("#wallet_balance").html(0);
                    $("#paidamt").html(pamt);
                    $("#wallet_use").val({{auth::user()->wallet_balance}});
        
                
                }
            }
            else
            {
                var chk = parseInt($(".final").val());
                if({{auth::user()->wallet_balance}} > chk)
                {
                    $("#paidamt").html(chk);
                }
                else{
                    $("#paidamt").html(chk+{{auth::user()->wallet_balance}});
                }
        
                $("#wallet_balance").html({{auth::user()->wallet_balance}});
                $("#voucher_row :input").prop("disabled", false);
                $("#gateway_row :input").prop("disabled", false);
                $("#wallet_use").val(0);
            }
                
        });



        $(".click_voucher").click(function(){

            if($(".final").val()=="")
            {
                alert('Service Charges is required!');
                $(".final").focus();
                return false;
                
            }   
        
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
                                                swal("Warning", "voucher not exist or used or expired", "warning");
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

                    var chk = parseInt($(".final").val());
                    var vamt = parseInt($("#vouch_amount").val());
                    var famt = chk;
                    $("#voucher_td :input").prop("disabled", true);
                    $(".voucher_textbox").val('');
                    $("#paidamt").html(famt);
                    $("#wallet_row :input").prop("disabled", false);
                    $("#gateway_row :input").prop("disabled", false);
                    var vamt = parseInt($("#vouch_amount").val(0));

                }
        });










        //add by ram for paynow button 07 jan 2024----------------------


        //add by ram for paynow button 21 sep 2023
	$(document).on('click', '.paynow', function (e) {
        var bidId = $(this).data('bid');
        var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
		
		$("#read-more-response").modal('hide');
		
		$.ajax({
                type: 'GET',
                headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
                url: '{{ url('get-bid') }}/' + bidId,
                cache: false,
                processData: false,
                success: function (response) {
                    $("#ram_modal").html(response.html);
                    $("#wallet_redreem").modal('show');
                },
                error: function (response) {
                    toastr.error(response.responseJSON.msg, "Error");
                    
                },
            });

	});



    </script>
    <script src="{{ asset('js/jquery1.min.js') }}"></script>

    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/typed.min.js') }}"></script>
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script>

        $(document).ready(function () {

            $('#table_id').DataTable();
        });
    </script>
@endsection
