@extends('layout')

@section('content')
<?php use App\Helpers\CommonHelper; ?>

<style>
    .overlay {
        position: absolute;
    background: rgb(0 0 0 / 57%);
    left: 0;
    right: 92px;
    line-height: 33px !important;
    text-align: center;
    color: #fff;
    font-weight: bold;
}

tr {
  position: relative;
}

</style>


    <!-- banner section start -->
    <section>
        <div class="container" style="padding-top: 15px;">
        @if($cart_details)        
        <form action="{{route('checkout')}}" method="post">
            <div class="row">
                <div class="col-md-12" style="padding-top: 15px;">
                    @if(session()->has('success'))
                    <div class="alert alert-success alert-dismissible show" role="alert">
                        <strong>Success!</strong> {{ session()->get('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                    @endif

                    @if(session()->has('error'))

                    <div class="alert alert-danger alert-dismissible show" role="alert">
                        <strong>Warning!</strong> {{ session()->get('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>

                       
                    @endif
                </div>
            </div>
         
            <div class="row"><h3 style="text-align: center;">Cart</h3>
            </div>
            <div class="row">
           
<table id="cart" class="table table-hover">
    <thead>
        <tr>
        <th>Deal Type</th>
        <th>Suited For</th>
        <th>Core Offer</th>
        <!-- <th>Discount (%)</th> -->
        <th>Deal Amount</th>
        <th>Available In</th>
        <th width="10%">Quantity</th>
        <th width="10%" class="text-center">Total</th>
        <th></th>
        </tr>
    </thead>
    <tbody>
  

        @php $total = 0; $free = '<span style="color:red; font-weight:bold;">Free</span>';  @endphp
        
            @foreach($cart_details as $id => $details)
            @php 
            if($details['deal_amount'] !=''){
                if($details['status'] == 1){

                $total += $details['deal_amount']*$details['quantity'];
                }
            }  
            
            @endphp
            <tr data-id="{{ $details['cart_id']}}">

                <td>{{ $details['identity'] }}</td>
                <td>{{ $details['industry'] }}</td>
                <td>{{ $details['title'] }}</td>
                <!-- <td>{{ $details['discount'] }}</td> -->
                <td>{!! (!empty($details['deal_amount']) ? $details['deal_amount'] : $free) !!}</td>
                <td>{{ $details['country_name'] }}</td>
                <td data-th="Quantity" >
                <input type="hidden" name="qty_type" value="" class="qty_type" />
                <div class="input-group">
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-default btn-number" data-type="minus" data-field="quant[{{ $details['quantity'] }}]">
                            <span class="glyphicon glyphicon-minus"></span>
                        </button>
                    </span>
                    <!-- <input type="text" name="quant[1]" class="form-control input-number" value="1" min="1" max="10"> -->
                    <input type="text" name="quant[{{ $details['quantity'] }}]" value="{{ $details['quantity'] }}" class="form-control quantity cart_update input-number" min="1" max="10"/>
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-default btn-number" data-type="plus" data-field="quant[{{ $details['quantity'] }}]">
                            <span class="glyphicon glyphicon-plus"></span>
                        </button>
                    </span>
                </div>

                    
                </td>
                <td class="text-center" style="font-size:18px; font-weight:bold; "> {{ $details['deal_amount'] * $details['quantity'] }}</td>
                <td class="actions" data-th="">
                    <button class="btn btn-danger cart_remove"><i class="fa fa-trash-o"></i></button>
                </td>
                @if($details['status'] ==0)
                <td class="overlay">This item is no longer available</td>
                @endif
            </tr>  
            @endforeach
      
    </tbody>
    <tfoot>
    <input type="hidden" name="pay_hidden" value="{{$total}}" />
    @if($total > 0)

       <tr>
            <td colspan="6" class="text-right" style="font-size:15px; font-weight:bold; line-height:35px;">Payble Amount</td>
            <td class="text-center" style="font-size:18px; font-weight:bold; line-height:35px;">
            
            {{$total}}
            </td>
            <td></td>
        </tr>

        <tr>
            @php
                $wallet_balance = \DB::table('users')->where('id',Auth::user()->id)->value('wallet_marketplace');
            @endphp
            <td colspan="6" class="text-right" style="font-size:15px; font-weight:bold; line-height:35px;">Pay via {{config('sitevars.data.ayojn_marketplace')}} 
            <input type="checkbox" value="<?=$wallet_balance  ?>" name="wallet_balance" class="walletcheck form-controle" style="height: 18px; width: 28px; margin-top: 10px; position: absolute;" />
            <input type="hidden" name="walletamt_hidden" id="walletamt_hidden" value="<?=$wallet_balance; ?>" />
            <input type="hidden" id="wallet_use" name="wallet_use" />

            </td>
            <td class="text-center" style="font-size:18px; font-weight:bold; line-height:35px;">
            <span id="walletamt">
              
                {{ $wallet_balance }}
            </span>
            </td>
            <td></td>
        </tr>


        <tr style="display: none;">
            <td colspan="6" class="text-right" style="line-height:35px;"> 
            <label>
                <span>Pay via Voucher</span>
                <input type="checkbox" name="list" value="" class="click_voucher" style="height: 18px; width: 28px; margin-top: 10px; position: absolute;"/>
                </label>
            </td>
            <td colspan="2" style="line-height:35px;">

            <div id="voucher_row" class="form-group autocomplete" style="width: 100%; margin-left: auto; margin-bottom:10px; margin-right: 0;">
              
                <label id="voucher_td" style="display: inline-block; float: right; width: 80%;">
                    <div class="input-group" style="position:absolute;">
                        <input type="search" class="form-control voucher_textbox" name="voucher_code" id="voucher_code" placeholder="Code" >
                        <span class="input-group-btn">
                        <button class="btn btn-primary voucher_button" type="button" >
                        </span> <i class='fa fa-thumbs-o-up'></i> 
                        </button>
                    
                    </div>
                    
                <button class="btn btn-success btn-sm"><i class='fa fa-thumbs-o-up'></i> Apply</button>    
            </label>
			</div>
                <input type="hidden" id="vouch_amount" value="0" />
                <input type="hidden" id="voucher_code_new" name="voucher_code_new" value="" />
            </td>
        </tr>
      
        <tr style="background-color:#e4e4e4;">
            <td colspan="6" class="text-right" style="font-size:15px; font-weight:bold; line-height:35px;">Total Amount</td>
            <td class="text-center" style="font-size:18px; font-weight:bold; line-height:35px;">
            <span id="paidamt"> {{$total}}</span>
            <!-- <input type="hidden" name="paidamt_hidden" value="{{$total}}" id="paidamt_hidden" /> -->
            </td>
            <td></td>
        </tr>

         <tr style="background-color:#e3e3e3;">
            <td colspan="6" class="text-right" style="font-size:15px; font-weight:bold; line-height:35px;">{{config('sitevars.data.service_charge')}} -  ({{config('sitevars.data.currency')}})</td>
            <td class="text-center" style="font-size:18px; font-weight:bold; line-height:35px;">
            <span id=""> {{CommonHelper::getConfig('marketplace_payment')['ayojn_service']}} </span>
            
            </td>
            <td></td>
        </tr>


         <tr style="background-color:#000; color:#fff;">
            <td colspan="6" class="text-right" style="font-size:15px; font-weight:bold; line-height:35px;">Paid Amount</td>
            <td class="text-center" style="font-size:18px; font-weight:bold; line-height:35px;">
            <span id="finalpaidamt"> {{$total + CommonHelper::getConfig('marketplace_payment')['ayojn_service']}} </span>
            <input type="hidden" name="paidamt_hidden" value="{{$total + CommonHelper::getConfig('marketplace_payment')['ayojn_service']}}" id="paidamt_hidden" />
            </td>
            <td></td>
        </tr>

        <tr class="pay_row">
            <td colspan="9" class="text-right">
                <br/>
                 <input type="hidden" name="btn_name" class="btn_name" value="pay" />
                <button class="btn btn-success"><i class="fa fa-money"></i> Pay Now</button>
            </td>
        </tr>
        

        @else

        <tr style="background-color:#e4e4e4;">
            <td colspan="6" class="text-right" style="font-size:15px; font-weight:bold; line-height:35px;">Paid Amount</td>
            <td class="text-center" style="font-size:18px; font-weight:bold; line-height:35px;">
            <span id="paidamt">{{$total}}</span>
            <input type="hidden" name="paidamt_hidden" value="{{$total}}" id="paidamt_hidden" />
            </td>
            <td></td>
        </tr>

        <tr class="free_row">
            <td colspan="9" class="text-right">
                <br/>
                <input type="hidden" name="btn_name" value="free" class="btn_name" />
                <button class="btn btn-primary"><i class="fa fa-money"></i> Pay Now</button>
            </td>
        </tr>

        @endif


       

        
    </tfoot>
</table>

</form>
@else

@if($rstorder > 0)
<div class="row text-center" >
    <h2 style="padding-top:140px; display: inline-block;">Your offers has been successfully redeem, check your <a href="{{url('marketplace-history')}}">history</a></h2>
</div>
@else
<div class="row text-center" >
    <h2 style="padding-top:140px; display: inline-block;">No items found, in Cart </h2>
</div>
@endif


@endif 
</div>

        </div>
    </section>
    <script src="{{ asset('js/jquery1.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/typed.min.js') }}"></script>
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.0/clipboard.min.js"></script>
    


<script type="text/javascript">


    $(document).ready(function() {
        $('#cart').DataTable({
            "order": [[ 0, "desc" ]],
            "columnDefs": [ {
                "targets": [ 0, 1, 2, 3, 4, 5, 6, 7 ],
                "orderable": false
            } ]
        });
    });

    $(document).ready(function(){
        $("#voucher_row :input").prop("disabled", true);
    });

  
    $(".cart_update").change(function (e) {
        e.preventDefault();
        var ele = $(this);
        console.log(ele);
  
        $.ajax({
            url: '{{ route('update_cart') }}',
            method: "patch",
            data: {
                _token: '{{ csrf_token() }}', 
                id: $(this).parents("tr").attr("data-id"), 
                quantity: $(this).parents("tr").find(".quantity").val(),
                type: $(this).parents("tr").find('.qty_type').val(),
            },
            success: function (response) {
                console.log(response);
                window.location.reload();
            }
        });
    });
  
    $(".cart_remove").click(function (e) {
        e.preventDefault();
  
        var ele = $(this);
  
        if(confirm("Do you really want to remove?")) {
            $.ajax({
                url: '{{ route('remove_from_cart') }}',
                method: "DELETE",
                data: {
                    _token: '{{ csrf_token() }}', 
                    id: ele.parents("tr").attr("data-id")
                },
                success: function (response) {
                    window.location.reload();
                }
            });
        }
    });


    $(".walletcheck").click(function(){

        check = $(".walletcheck").prop("checked");
        if(check) {
            var wallet_amt = parseFloat(<?php echo isset($wallet_balance)?$wallet_balance:0; ?>)
            //var paid_amt = parseFloat(<?php echo isset($total)?$total:0; ?>)

            var vcheck = $("#vouch_amount").val();
            if(vcheck > 0)
            {
                var p = parseFloat(<?php echo isset($total)?$total:0; ?>)
                var a = parseFloat($("#vouch_amount").val());
                var paid_amt = p-a;
            }
            else{
                var paid_amt = parseFloat(<?php echo isset($total)?$total:0; ?>)
            }

            if(wallet_amt > paid_amt)
            {
                var walletfinal = wallet_amt-paid_amt;
                var finalpaid = 0;
                var wallet_use = paid_amt;
            }
            else{
                var finalpaid = paid_amt-wallet_amt;
                var walletfinal = 0;
                var wallet_use = wallet_amt;
                
            }

            $("#wallet_use").val(wallet_use);
            $("#walletamt").html(walletfinal);
            $("#walletamt_hidden").val(walletfinal);
            $("#paidamt").html(finalpaid);
            $("#finalpaidamt").html(finalpaid + {{CommonHelper::getConfig('marketplace_payment')['ayojn_service']}});
            $("#paidamt_hidden").val(finalpaid + {{CommonHelper::getConfig('marketplace_payment')['ayojn_service']}});
            
            if($("#paidamt_hidden").val() == 0)
            {
                $(".btn_name").val('free');
            }
            else{
                $(".btn_name").val('pay');
            }
          


        } else {
            
            var wallet_amt = parseFloat(<?php echo isset($wallet_balance)?$wallet_balance:0; ?>)
            
            var check = $("#vouch_amount").val();
            if(check > 0)
            {
                var p = parseFloat(<?php echo isset($total)?$total:0; ?>)
                var a = parseFloat($("#vouch_amount").val());
                var paid_amt = p-a;
            }
            else{
                var paid_amt = parseFloat(<?php echo isset($total)?$total:0; ?>)
            }


            if(wallet_amt > paid_amt)
            {
                var walletfinal = wallet_amt;
                var finalpaid = <?php echo isset($total)?$total:0; ?>;
                var wallet_use = 0;
            }
            else{
                var finalpaid =  paid_amt;
                var walletfinal = wallet_amt;
                var wallet_use = 0;
            }

            $("#wallet_use").val(wallet_use);
            $("#walletamt").html(walletfinal);
            $("#walletamt_hidden").val(walletfinal);
            $("#paidamt").html(finalpaid);
            $("#finalpaidamt").html(finalpaid + {{CommonHelper::getConfig('marketplace_payment')['ayojn_service']}});
            $("#paidamt_hidden").val(finalpaid + {{CommonHelper::getConfig('marketplace_payment')['ayojn_service']}});
            $(".btn_name").val('pay');
        }
    });



    // $(".click_voucher").click(function(){

    //     var check = $(".click_voucher").prop("checked");
    //     if(check){
           
    //         $("#voucher_td :input").prop("disabled", false);
    //         $(".voucher_button").click(function(){
    //                     var code = $(".voucher_textbox").val();
    //                     if(code ==""){alert('please enter voucher code'); $(".voucher_textbox").focus(); return false;}
    //                     swal({
    //                     title: "Are you sure",
    //                     text: "You want to apply this code",
    //                     icon: "warning",
    //                     buttons: true,
    //                     }).then(
    //                         function (isConfirm) {
    //                             if (isConfirm) 
    //                             {
                                    
    //                                 $.ajax({
    //                                     url: "{{ route('check-vouch-code') }}",
    //                                     type: 'POST',
    //                                     data: {code: code},
    //                                     headers: {
    //                                     'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
    //                                     },
    //                                     success: function (data) {
    //                                         if(data > 0){
    //                                             $("#vouch_amount").val(data);
    //                                             $('#voucher_code_new').val(code);
    //                                             swal("Success", "Voucher has been applied", "success");
    //                                             var pamt = $("#paidamt").html();
    //                                             console.log(data);
    //                                             console.log(pamt);
    //                                             if(data < parseFloat(pamt)){
    //                                                 var fval = pamt-data;
    //                                             }
    //                                             else{
    //                                                 var fval = 0;
    //                                             }
                                                
    //                                             $("#paidamt").html(fval);
    //                                             $("#voucher_td :input").prop('disabled',true);
    //                                         }
    //                                         else{
    //                                             swal("Warning", "voucher not exist or used", "warning");
    //                                         }
                                            
    //                                     },
    //                                     error: function () {
    //                                         swal("error", "There's been an error", "error");
    //                                     }
    //                                 });


    //                             }
    //                         });

                    
    //                 });
    //     }
    //     else{
                 
    //                 var chk = parseFloat($("#paidamt").html());
    //                 var vamt = parseFloat($("#vouch_amount").val());
    //                 if(vamt > 0){var famt = chk+vamt;} 
    //                 else{var famt = chk;}
                    
    //                 $("#voucher_td :input").prop("disabled", false);
    //                 $(".voucher_textbox").val('');
    //                 $("#paidamt").html(famt);
    //                 $('#voucher_code_new').val('');
    //                 var vamt = parseFloat($("#vouch_amount").val(0));
    //     }
    // });
   
  
</script>


<script>
    $('.btn-number').click(function(e){
    e.preventDefault();
    
    fieldName = $(this).attr('data-field');
    type      = $(this).attr('data-type');
    var input = $("input[name='"+fieldName+"']");
    var currentVal = parseFloat(input.val());

    
    if (!isNaN(currentVal)) {
        if(type == 'minus') {

            $(this).parents("tr").find('.qty_type').val('minus');
            
            if(currentVal > input.attr('min')) {
                input.val(currentVal - 1).change();
            } 
            if(parseFloat(input.val()) == input.attr('min')) {
                $(this).attr('disabled', true);
            }

        } else if(type == 'plus') {

            $(this).parents("tr").find('.qty_type').val('plus');

            if(currentVal < input.attr('max')) {
                input.val(currentVal + 1).change();
            }
            if(parseFloat(input.val()) == input.attr('max')) {
                $(this).attr('disabled', true);
            }

        }
    } else {
        input.val(0);
    }
});
$('.input-number').focusin(function(){
   $(this).data('oldValue', $(this).val());
});
$('.input-number').change(function() {
    
    minValue =  parseFloat($(this).attr('min'));
    maxValue =  parseFloat($(this).attr('max'));
    valueCurrent = parseFloat($(this).val());
    
    name = $(this).attr('name');
    if(valueCurrent >= minValue) {
        $(".btn-number[data-type='minus'][data-field='"+name+"']").removeAttr('disabled')
    } else {
        alert('Sorry, the minimum value was reached');
        $(this).val($(this).data('oldValue'));
    }
    if(valueCurrent <= maxValue) {
        $(".btn-number[data-type='plus'][data-field='"+name+"']").removeAttr('disabled')
    } else {
        alert('Sorry, the maximum value was reached');
        $(this).val($(this).data('oldValue'));
    }
    
    
});
$(".input-number").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
             // Allow: Ctrl+A
            (e.keyCode == 65 && e.ctrlKey === true) || 
             // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
</script>

@endsection
