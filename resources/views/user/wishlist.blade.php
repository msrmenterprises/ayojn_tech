@extends('layout')

@section('content')

<style>
    .overlay {
        position: absolute;
    background: rgb(0 0 0 / 57%);
    left: 0;
    right: 0;
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

            <div class="row"><h3 style="text-align: center;">Wishlist</h3>
            </div>

            <div class="row">
           
<table id="cart" class="table table-hover table-bordered">
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
                $total += $details['deal_amount'];
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
                <td data-th="Quantity">
                {{ $details['quantity'] }}
                    <!-- <input type="number" value="{{ $details['quantity'] }}" class="form-control quantity cart_update" min="1" /> -->
                </td>
                <td class="text-center" style="font-size:18px; font-weight:bold; "> {{ $details['deal_amount'] * $details['quantity'] }}</td>
                <td class="actions" data-th="">
                    <button class="btn btn-danger wishlist_remove"><i class="fa fa-trash-o"></i></button>
                    <a href="{{ route('add_to_cart_wishlist', $details['id']) }}" class="btn btn-success"><i class="fa fa-shopping-cart"></i></a>
                </td>
                @if($details['status'] ==0)
                <td class="overlay">No longer available this item</td>
                @endif
            </tr>  
            @endforeach
      
    </tbody>
    <tfoot>
    <input type="hidden" name="pay_hidden" value="{{$total}}" />


       

        
    </tfoot>
</table>

</form>
@else


<div class="row text-center" >
    <h2 style="padding-top:140px; display: inline-block;">No items found, in wishlist </h2>
</div>



@endif            </div>

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

    

    $(".wishlist_remove").click(function (e) {
        e.preventDefault();
  
        var ele = $(this);
  
        if(confirm("Do you really want to remove?")) {
            $.ajax({
                url: '{{ route('remove_from_wishlist') }}',
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
            var wallet_amt = parseInt(<?php echo isset($wallet_balance)?$wallet_balance:0; ?>)
            //var paid_amt = parseInt(<?php echo isset($total)?$total:0; ?>)

            var vcheck = $("#vouch_amount").val();
            if(vcheck > 0)
            {
                var p = parseInt(<?php echo isset($total)?$total:0; ?>)
                var a = parseInt($("#vouch_amount").val());
                var paid_amt = p-a;
            }
            else{
                var paid_amt = parseInt(<?php echo isset($total)?$total:0; ?>)
            }

            if(wallet_amt>paid_amt)
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
            $("#paidamt_hidden").val(finalpaid);
            $(".btn_name").val('free');


        } else {
            
            var wallet_amt = parseInt(<?php echo isset($wallet_balance)?$wallet_balance:0; ?>)
            
            var check = $("#vouch_amount").val();
            if(check > 0)
            {
                var p = parseInt(<?php echo isset($total)?$total:0; ?>)
                var a = parseInt($("#vouch_amount").val());
                var paid_amt = p-a;
            }
            else{
                var paid_amt = parseInt(<?php echo isset($total)?$total:0; ?>)
            }


            if(wallet_amt>paid_amt)
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
            $("#paidamt_hidden").val(finalpaid);
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
    //                                             if(data < parseInt(pamt)){
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
                 
    //                 var chk = parseInt($("#paidamt").html());
    //                 var vamt = parseInt($("#vouch_amount").val());
    //                 if(vamt > 0){var famt = chk+vamt;} 
    //                 else{var famt = chk;}
                    
    //                 $("#voucher_td :input").prop("disabled", false);
    //                 $(".voucher_textbox").val('');
    //                 $("#paidamt").html(famt);
    //                 $('#voucher_code_new').val('');
    //                 var vamt = parseInt($("#vouch_amount").val(0));
    //     }
    // });
   
  
</script>

@endsection
