<div class="modal" id="wallet_redreem" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
				<h4 class="modal-title" id="myModalLabel" style="text-align: center">Payment Details</h4>
				<span style="text-align: center; display: block; color: red;">Your will Get @if($bids['per']==0) 10% @else {{$bids['per']}}% @endif in your wallet </span>
				<span id="bid_id" style="text-align: center"></span>
			</div>
			<div class="modal-body">
				
			 

			<div class="form-group autocomplete" style="width: 100%; display: block; margin-left: auto; margin-bottom:10px; margin-right: 0;">
						<label>
							<strong>Sponsor Budget</strong>
						</label>
						<label class="pull-right">
						{{$bids['sponsor_budget']}}
						</label>

			</div>

				<div class="form-group autocomplete" style="margin-bottom:10px; width: 100%; display: block; margin-left: auto; margin-right: 0; line-height:30px; ">
						<label>
							<strong>Service Charges</strong>
						</label>
						<label class="pull-right">
                            <!--?php 
                            $full = explode("-", $bids['sponsor_budget']);
                            echo $full[1];
                             ?--> 
							 
						</label>
						<input type="text" name="" value="" class="form-control final" style="text-align:right; width: 40%; display: inline; float: right; height: 30px;" />
				</div>


				<div class="form-group autocomplete" style="width: 100%; display: block; margin-left: auto; margin-right: 0;">
						<label>
							<strong>Use Wallet</strong>
							<input type="hidden" id="wallet_use" name="wallet_use" value="0" />
							<input type="checkbox" value="<?= $bids->user['wallet_balance']; ?>" class="walletcheck form-controle" style="height: 15px; width: 28px; margin-top: 3px; position: absolute;" />
						</label>
						<label class="pull-right">
							<span id="walletamt"><?= $bids->user['wallet_balance']; ?></span> 
						</label>
						<hr style="margin-bottom: 0px;"/>
				</div>


				<div class="form-group autocomplete" style="margin-bottom: 0px; width: 100%; display: block; margin-left: auto; margin-right: 0;">
						<label>
							<strong>Paid Amount</strong>
						</label>
						<label class="pull-right">
							<span id="paidamt"><!--?php echo $full[1]; ?--></span> 
						</label>
						
				</div>

				<hr style="margin-top: 5px;"/>
				<div class="form-group autocomplete text-center">
				<a href="javascript:void(0)" class="btn btn-primary button-primary-color paynowfinal" data-bid="<?php echo $bids->bidResponse[0]['id']; ?>"><i class="fa fa-inr"></i> Pay Now</a>
                <input type="hidden" value="<?= $bids->user['id']; ?>" name="userid" id="userid" />
            </div>


			</div>
		</div>
	</div>
</div>




<script type="text/javascript">

	$(".walletcheck").click(function(){

		check = $(".walletcheck").prop("checked");
		if(check) {

			var wallet_amt = parseInt($("#walletamt").html());
			var paid_amt = parseInt($("#paidamt").html());
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

			
         	$("#walletamt").html(walletfinal);
			$("#paidamt").html(finalpaid);
			$(".final").attr('readonly','readonly');
			$("#wallet_use").val(wallet_use);

		} else {
			var finalpaid = $(".final").val();
         	$("#walletamt").html($(this).val());
			$("#paidamt").html(finalpaid);
			$(".final").removeAttr('readonly');
			$("#wallet_use").val(wallet_use);
		}
	});


	$(".final").on("keyup change", function(e) {
		var amt = $(this).val();
		$("#paidamt").html(amt);
	});

	//add by ram for paynow button 21 sep 2023
	$(document).on('click', '.paynowfinal', function (e) {

		if($(".final").val()=="")
		{
			alert('Service Charges is required!');
			$(".final").focus();
			return false;
			e.preventDefault();
		}
		else
		{

		var userid = $("#userid").val();
        var amt = $("#paidamt").html();
		var bid_close_amount = $(".final").val();
        var bidId = $(this).data('bid');
        var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
		if($(".walletcheck").prop("checked"))
        {
            var wallet = $("#wallet_use").val();
        }
        else{
            var wallet = 0;
        }
		$("#read-more-response").modal('hide');
		$("#wallet_redreem").modal('show');
        
        $.ajax({
                url: "{{ route('paynow-page-user') }}",
                type: 'POST',
                data: {_token: CSRF_TOKEN, bidId: bidId, wallet:wallet, userid: userid, amt: amt, bid_close_amount: bid_close_amount},
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
		}
	});



</script>
