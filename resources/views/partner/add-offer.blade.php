@extends('layout')
<style>

.input-group {
  display: table;
  border-collapse: collapse;
  width: 100%;
}

.input-group > * {
  display: table-cell;
  border: 1px solid #ddd;
  vertical-align: middle;
  background-color: #e4e4e4;
  width: 30%;
}

.input-group-icon {
  background-color: #eee;
  color: #777;
  padding: 0 12px;
}

.input-group-area {
  width: 100%;
}

.swal2-container{
    z-index: 99999 !important;
}

    </style>
@section('content')
    <style>
        .error {
            color: red;
        }
    </style>


    <?php
    use App\Industry;
    $industries = Industry::all();
    ?>
    <!-- banner section start -->
    <section id="feed" class="speakers">
        <div class="container" style="text-align: center;padding-top: 15px;">

            <div class="row"><br/>
                <h3 style="margin-top:100px">Create Offer</h3>
            </div>
            <!--<div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-7">
                    Let’s create offer for offer and receiver user
                </div>
            </div>-->

            <br>
            @php
            //$all = DB::table('offers')->where('partner_id',Auth::user()->id)->where('status','On')->get();
             $cnt = DB::table('offers')->where('partner_id',Auth::user()->id)->where('status','On')->count();
            // echo '<pre/>';
            // print_r($all); 
            @endphp



            @if($cnt < 5)
            <form name="add-offer" method="post" id="add-offer" action="{{ url('partner/store-offer') }}">
            @else
            <form name="add-offer" method="post" id="add-offer-pay" action="{{ url('partner/store-offer') }}">
            @endif   
                <div id="add-opporutnity-form">
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-7">
                            @if(session()->has('success'))
                                <div class="alert alert-success">
                                    {{ session()->get('success') }}
                                </div>
                            @endif
                            @if (!empty($errors->first()))
                                <div class="alert alert-danger">
                                    {{ $errors->first() }}

                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-3"></div>
                        <div class="col-md-7">
                            <select class="form-group form-control opportunity_country" name="offer_for"
                                    id="offer_for">
                                <option value="">Offer For</option>
                                <option value="Clients">Clients</option>
                                <option value="Agencies">Agencies</option>
                                <option value="Networks">Networks</option>
                                <option value="Freelancers">Freelancers</option>
                                <option value="Communities">Communities</option>
                                <option value="Both">Everyone
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-7" style="padding-bottom: 15px">
                            <input type="text" class="form-control" name="offer_by" id="offer_by"
                                   value="{{ Auth::user()->company_name }}"
                                   readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-7">
                            <select class="form-group form-control opportunity_country" name="partner_identity"
                                    id="partner_identity" required>
                                <option value="">Offer Type</option>
                                <option value="Tech">Tech</option>
                                <option value="Non-Tech">Non-Tech</option>

                            </select>
                        </div>
                    </div>
                     <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-7" style="padding-bottom: 15px">
                            <select class="form-control" name="refund">
                                <option value="0">Non-Refundable</option>
                                <option value="1">Refundable</option>
                            </select>
                           <!-- <input type="checkbox" name="refund" value="1"> If offer Refundable, then check -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-7">
                            <select class="form-group form-control opportunity_country" name="function" id="function" required>
                                <option value="">Suited For</option>
                                @foreach($industries as $in){
                                <option value="{{ $in->id}}">{{ $in->name}}</option>
                                }
                            @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-7">
                            <select class="form-group form-control available_in" name="available_in"
                                    id="available_in" required>
                                <option value="">Available in</option>
                                @if(!empty($countries))
                                    @foreach($countries as $c)
                                        <option value="{{$c->country_code}}">{{ $c->country_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-7" style="padding-bottom: 15px">
                            <input type="text" class="form-control" name="title" id="title"
                                   placeholder="Product/Service Headline">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-7" style="padding-bottom: 15px">
                            <input type="number" class="form-control" name="qty" id="qty"
                                   placeholder="Available Quantity of Deal" min="1" required>
                        </div>
                    </div>

                    <div class="row" style="display:none;">
                        <div class="col-md-3"></div>
                        <div class="col-md-7" style="padding-bottom: 15px">
                            <input type="text" class="form-control" name="discount" id="discount"
                                   placeholder="Discount (%) if any / Voucher Code">
                        </div>
                    </div>

                    <span style="color:red; font-size:12px; padding-bottom:10px; display:block;">Note:If Deal amount is blank, it considers as a free Deal for every user. </span>            
                    @php
                         $types = \DB::table('user_type')->get();      
                    @endphp
                    
                    @foreach($types as $type)
                    @php $sname = Str::lower($type->user_type); @endphp
                    <div class="row" >
                        <div class="col-md-3"></div>
                        <div class="col-md-7" style="padding-bottom: 15px">

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon3">{{Str::upper($type->user_type)}}</span>
                            </div>
                            <input type="text" class="form-control" name="deal_amount_{{$sname}}" id="deal_amount_{{$sname}}"
                            placeholder="Deal Amount">
                            </div>
                        </div>
                    </div>
                    @endforeach

                    <div class="row" style="display:none;">
                        <div class="col-md-3"></div>
                        <div class="col-md-7" style="padding-bottom: 15px">
                            <input type="text" class="form-control" name="incentive" id="incentive"
                                   placeholder="Ayojn's Incentive % if any(Only for our Reference)">
                        </div>
                    </div>
                    
                    <div class="row" style="display:none;">
                        <div class="col-md-3"></div>
                        <div class="col-md-7">
                            <select class="form-group form-control available_in" name="currency"
                                    id="currency" >
                                <option value="">Currency</option>
                                @if(!empty($Currency))
                                    @foreach($Currency as $cu)
                                        <option value="{{$cu->name}}">{{ $cu->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

            <div class="row" style="padding-bottom:15px;">
                <div class="col-md-3"></div>
                <div class="col-md-7">
                    <div class="input-group">
                         <div class="input-group-prepend">
                            <span class="input-group-text">https://www.</span>
                         </div>
 
                        <input type="text" class="form-control" name="weblink" id="weblink" placeholder="Weblink for the offer" >
                    </div>
                </div>
            </div>
                           
              

                    <div class="row" style="display:none;">
                        <div class="col-md-3"></div>
                        <div class="col-md-6" style="padding-bottom: 15px">
                            <input type="text" class="form-control" name="notification_email" id="notification_email" placeholder="Notification Email" >
                        </div>
                    </div>

                   

                </div>
                <div class="row">
                    <div class="col-md-12">
                    
                        <button type="submit" class="btn btn-primary">Submit</button> &nbsp;
                     
                    </div>
                </div>

            </form>
            <br>
        </div>
    </section>


    <div class="modal" id="paynow_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false" data-backdrop="static" style="z-index:9999;">
	<div class="modal-dialog modal-dialog-centered modal-sm" role="document" style="width:450px;">
		<div class="modal-content">
			<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
				<h4 class="modal-title" id="myModalLabel" style="text-align: center">Payment Details</h4>
			
			</div>
			<div class="modal-body">


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
                    <div class="input-group" style="position:absolute;     width: 50%;">
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


            <hr style="margin-top: 5px;  margin-bottom: 10px;"/>
				<div class="form-group autocomplete" style="margin-bottom: 0px; width: 100%; display: block; margin-left: auto; margin-right: 0;">
						<label>
							<strong>Final Paid Amount</strong>
						</label>
						<label class="pull-right">
							<span id="paidamt">
                               5
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

    <script src="{{ asset('js/jquery1.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/typed.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.min.js"></script>
    <script>
      
        // $.validator.addMethod("wordCount", function (value, element, wordCount) {
        //     return value.split(' ').length <= wordCount;
        // }, 'Exceeded word count');


        $("#add-offer").validate({
            rules: {
                offer_for: 'required',
                partner_identity: 'required',
                function: 'required',
                title: {
                    required: true,
                },
                available_in: 'required',
                weblink: 'required',
                currency: 'required',
                discount: {
                    required: true,
                    number: false,
                }, deal_amount_delta: {
                    required: false,
                    number: true,
                    min: 0
                }, deal_amount_sigma: {
                    required: false,
                    number: true,
                    min: 0
                },deal_amount_alpha: {
                    required: false,
                    number: true,
                    min: 0
                },deal_amount_omega: {
                    required: false,
                    number: true,
                    min: 0
                },incentive: {
                    required: true,
                    number: true,
                    min: 0,
                    max: 100
                }
            }, messages: {
                offer_for: 'Please select offer For ',
                partner_identity: 'Please select identity',
                function: 'Please select function',
                title: {
                    required: "Please enter title",
                },
                available_in: 'Please select country',
                currency: 'Please select currency',
                discount: {
                    required: 'Please enter discount'
                }, deal_amount_delta: {
                    number: 'Please enter number'
                },deal_amount_sigma: {
                    number: 'Please enter number'
                }, deal_amount_alpha: {
                    number: 'Please enter number'
                }, deal_amount_omega: {
                    number: 'Please enter number'
                }, incentive: {
                    required: 'Please enter incentive',
                    number: 'Please enter number'
                }
            }
        });



        $("#add-offer-pay").validate({
            rules: {
                offer_for: 'required',
                partner_identity: 'required',
                function: 'required',
                title: {
                    required: true,
                },
                weblink: 'required',
                available_in: 'required',
                currency: 'required',
                discount: {
                    required: true,
                    number: false,
                }, deal_amount_delta: {
                    required: false,
                    number: true,
                    min: 0
                }, deal_amount_sigma: {
                    required: false,
                    number: true,
                    min: 0
                },deal_amount_alpha: {
                    required: false,
                    number: true,
                    min: 0
                },deal_amount_omega: {
                    required: false,
                    number: true,
                    min: 0
                }, incentive: {
                    required: true,
                    number: true,
                    min: 0,
                    max: 100
                }
            }, messages: {
                offer_for: 'Please select offer For ',
                partner_identity: 'Please select identity',
                weblink:'Please enter weblink',
                function: 'Please select function',
                title: {
                    required: "Please enter title",
                    
                },
                available_in: 'Please select country',
                currency: 'Please select currency',
                discount: {
                    required: 'Please enter discount'
                }, deal_amount_delta: {
                    number: 'Please enter number'
                },deal_amount_sigma: {
                    number: 'Please enter number'
                }, deal_amount_alpha: {
                    number: 'Please enter number'
                }, deal_amount_omega: {
                    number: 'Please enter number'
                }, incentive: {
                    required: 'Please enter incentive',
                    number: 'Please enter number'
                }
            },
            submitHandler: function(form) {
                $("#paynow_modal").modal('show');
                $("#voucher_row #voucher_code, #voucher_row .voucher_button").prop("disabled", true);
                return false;
        }
        });
    </script>

    <script>
        
        
        $(".click_wallet").click(function(){
            

            check = $(".click_wallet").prop("checked");
            if(check) 
            {
              
                if({{Auth::user()->wallet_balance}} > 5)
                {
                    var chk = parseInt($("#paidamt").html());
                    var pamt = {{Auth::user()->wallet_balance}}-chk;
                    $("#wallet_balance").html(pamt);
                    $("#paidamt").html(0);
                    $("#wallet_use").val(pamt);
                    
                    var chkagain = parseInt($("#paidamt").html());
                    if(chkagain==0)
                    {
                        $("#voucher_row #voucher_code, #voucher_row .voucher_button").prop("disabled", true);
                        
                    }
                    
                    
                }
                else{
                    var chk = parseInt($("#paidamt").html());
                    var pamt = chk-{{Auth::user()->wallet_balance}};
                    $("#wallet_balance").html(0);
                    $("#paidamt").html(pamt);
                    $("#wallet_use").val({{Auth::user()->wallet_balance}});
        
                
                }
            }
            else
            {
                var chk = parseInt($("#paidamt").html());
                if({{Auth::user()->wallet_balance}} > 5)
                {
                    $("#paidamt").html(5);
                }
                else{
                    $("#paidamt").html(chk+{{Auth::user()->wallet_balance}});
                }
                $("#wallet_balance").html({{Auth::user()->wallet_balance}});
              
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
                    $("#paidamt").html(5);
                }
                
                $("#wallet_row :input").prop("disabled", false);
                $("#gateway_row :input").prop("disabled", false);
                var vamt = parseInt($("#vouch_amount").val(0));
            }
        });



        $(document).on('click', '.paynowfinal', function (e) {

            $(".loader ").show();
            var amt = $("#paidamt").html();
            var voucher_code = $("#voucher_code").val();
            var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
            if($(".click_wallet").prop("checked"))
            {
                var wallet = $("#wallet_use").val();
            }
            else{
                var wallet = 0;
            }
            var formData = $('#add-offer-pay').serializeArray();

            $.ajax({
                    url: "{{ route('paynow-partner-offer-create') }}",
                    type: 'POST',
                    data: {wallet:wallet, amt: amt, voucher_code: voucher_code, form_data:formData},
                    headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
                    success: function (data) {
                    console.log(data);
                    if(data['flag'] == 1)
                    {   
                        if(data['link'] !="")
                        {
                            window.location.replace(data['link']);
                        }else{

                            alert("offer created successfully");
                            location.reload();
                        }
                        
                    }
                    
                    },
                    error: function () {
                    alert("something went wrong!");
                    }
                });

            });
    </script>
@endsection
