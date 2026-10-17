@extends('layout')

@section('content')

<style>
.swal-overlay--show-modal {
    z-index: 99999999999 !important;
}
.swal-modal {
    z-index: 99999 !important;
}
</style>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <?php
    $f = Request::get('f');
    $cn = Request::get('c');
    $b = Request::get('b');
    $i = Request::get('i');

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


    <br><br><br><br>
    <section id="feed" class="speakers">
        <div class="container" style="padding-top: 15px;">
            <div class="row"><h3 style="text-align: center;">Vouch List For {{ $opportunityData->hashtag }}</h3></div>
            <div class="row">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
            </div>
            <div class="row">
               <!-- @if($opportunityData->is_paid == 1)
                    <a href="{{ url('export-vouch/') .'/'.$opportunityData->id }}"
                       class="btn btn-primary float-left">Export Vouches</a>
                @else
                    <a href="javascript:void(0)"
                       class="btn btn-primary float-left" onclick="payment()">Export Vouches</a>
                @endif -->
                @if(!empty($vouchLists->first()))
                <a href="{{ url('export-vouch/') .'/'.$opportunityData->id }}"
                class="btn btn-primary float-left">Export Vouches</a>
                @endif

            </div>

            <br>
            <div class="row">
                <table id="table_id" class="display">
                    <thead>
                    <tr>
                        <th>Vouch ID</th>
                        <th>Budget</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(!empty($vouchLists->first()))
                        @foreach($vouchLists as $vouchList)
                            @if($vouchList->vouch_identity == $identity || $vouchList->vouch_identity == "All")
                                <tr>
                                    <td>#{{ $vouchList->id }}</td>
                                    <td>{{  $vouchList->vouch_value }}</td>
                                    <td>@if(!empty($vouchList->VouchUser) && $vouchList->VouchUser->status == 1)

                                            <a href="{{url('update-vouch/')."/".$vouchList->VouchUser->id}}"
                                               class="btn btn-primary">Open
                                                for Negotiation </a>
                                        @else
                                            @if($opportunityData->is_paid == 1)
                                                @if(!empty($vouchList->userDetail))
                                                    <?php
                                                    $email = '';
                                                    if ($vouchList->vouch_contacted == 'Mobile') {
                                                        $email = $email . $vouchList->userDetail->phone_no . ", ";
                                                    } else if ($vouchList->vouch_contacted == 'Both') {
                                                        $email = $email . $vouchList->userDetail->email . "-" . $vouchList->userDetail->phone_no . ", ";
                                                    } else {
                                                        $email = $email . $vouchList->userDetail->email . ", ";
                                                    }
                                                    ?>
                                                    <a href="javascript:void(0)" style="font-size: 25px"
                                                       onclick="displayEmail('{{ $email }}')"><i
                                                            class="fa fa-envelope"></i></a>
                                                @endif
                                            @else
                                                    
                                            @if($opportunityData->status == 1)
                                            <a href="javascript:void(0)" style="font-size: 25px"
                                                       onclick="displayEmail('{{ $vouchList->userDetail->email}}')"><i
                                                            class="fa fa-envelope"></i></a>
                                                <!-- <a href="javascript:void(0)" style="font-size: 25px"
                                                   onclick="payment()"><i
                                                        class="fa fa-envelope"></i></a> -->
                                            @else
                                            <span>Paid</span>
                                            @endif

                                            @endif

                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>

        </div>

    </section>

   
<div class="modal" id="paynow_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-sm" role="document" style="width:450px;">
		<div class="modal-content">
			<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
				<h4 class="modal-title" id="myModalLabel" style="text-align: center">Payment Details</h4>
				
			</div>
			<div class="modal-body">
			
            <div class="form-group autocomplete" style="margin:0px; width: 100%; display: block; margin-left: auto; margin-right: 0;">
						<label>
							<strong>Payble Amount</strong>
						</label>
						<label class="pull-right">
                         100
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


            <div id="voucher_row" class="form-group autocomplete" style="width: 100%; display: none; margin-left: auto; margin-bottom:10px; margin-right: 0;">
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

            <div id="gateway_row" class="form-group autocomplete" style="width: 100%; display: block; margin-left: auto; margin-bottom:10px; margin-right: 0;">
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
                               100
                            </span> 
						</label>
						
				</div>

				<hr style="margin-top: 5px;"/>
				<div class="form-group autocomplete text-center">
				<a href="javascript:void(0)" class="btn btn-primary button-primary-color paynowfinal" data-bid="<?php echo request()->segment(2); ?>"> Pay Now</a>
                
            </div>


			</div>
		</div>
	</div>
</div>


    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
    @if(session()->has('success_messagesss'))
        <script>
            swal("This vouch has been closed");
        </script>
    @endif
    <script>

       //$("#paynow_modal").modal('show');
        $(document).ready(function(){
            $("#voucher_td :input").prop("disabled", true);
        });



        function payment() {
            url = "{{ url('receive-payment/')."/".$opportunityData->id }}";
            const el = document.createElement('div');
            el.innerHTML = "In order to access the contact details of clients for this Vouchlist, please pay the premium fees of AED 100. If you're unaware of the premium fees then please refer the FAQs. " +
                "<br/><br/><a href='javascript:void(0)' class='btn btn-success button-primary-color paynow_modal' data-bid=''> Pay Now</a>";
            swal({
                title: "",
                content: el,
            });
        }

        function displayEmail(email) {
            const el = document.createElement('div')
            //el.innerHTML = "Brilliant ! One of the clients reachable at " + email + " vouched you for this opportunity.";
            el.innerHTML = "Client is reachable at " + email;
            swal({
                title: "",
                content: el,
            });
        }

        $("body").on("click", ".paynow_modal", function() {
      
                $("#paynow_modal").modal('show');
                swal.close();
        });
    </script>
    <script src="{{ asset('js/jquery1.min.js') }}"></script>

    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/typed.min.js') }}"></script>
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script>

        $(document).ready(function () {

            $('#table_id').DataTable({

                "oLanguage": {
                    "sEmptyTable": "It seems like the vouch is Not for you, perhaps a mismatch of Identity/ Location as per the client specification."
                }
            });
        });
    </script>


<script>
$(".click_wallet").click(function(){
        
    check = $(".click_wallet").prop("checked");
	if(check) {

        if({{auth::user()->wallet_balance}} > 100)
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
        var chk = parseInt($("#paidamt").html());
        if({{auth::user()->wallet_balance}} > 100)
        {
            $("#paidamt").html(100);
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
            $("#paidamt").html(chk+vamt);
            $("#wallet_row :input").prop("disabled", false);
            $("#gateway_row :input").prop("disabled", false);
            var vamt = parseInt($("#vouch_amount").val(0));
        }
});



    //add by ram for paynow button 31 dec 2023
    $(document).on('click', '.paynowfinal', function (e) {

        var fields = $("input[name='list']").serializeArray(); 
        if (fields.length === 0) 
        { 
            alert('Select any one way to pay'); 
            return false;
        } 
       


        var opid = $(this).data('bid');
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
                url: "{{ route('paynow-vouch') }}",
                type: 'POST',
                data: {_token: CSRF_TOKEN, opid: opid, vid: vid, wallet:wallet, amt: amt, voucher_code: voucher_code},
                headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
                success: function (data) {
                console.log(data);
                if(data==1)
                {
                    alert("Payment has been done!");
                    location.reload();
                }
                
                
                },
                error: function () {
                alert("something went wrong!");
                }
            });
        
    });


</script>
@endsection
