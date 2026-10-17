<?php  $buttons = $db_buttons['recieve_opportunity'];  ?>
<?php 
  use App\Helpers\CommonHelper;
  ?>
<?php $__env->startSection('content'); ?>

<style>
	.badge-warning {
		background-color: #f0ad4e;
	}
	.badge-success {
		background-color: #5cb85c;
	}
	.badge-danger {
		background-color: #d9534f;
	}	
</style>
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <?php
	use Carbon\Carbon;

    $f = Request::get('f');
    $cn = Request::get('c');
    $b = Request::get('b');
    $i = Request::get('i');
	$re = Request::get('re');
    use App\Country;
    $countries = Country::all();
    use App\Industry;
    $industries = Industry::all();
    use App\SponsorrSpecify;
    use App\SponsorrSpecifyList;
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
			<div class="row"><h3 style="text-align: center;">Built Opportunities</h3>
			</div>
			<div class="row"><a href="<?php echo e(url('build-opportunity')); ?>" class="btn btn-primary float-left" data-tooltip="true" data-placement="top"  title="<?php echo $buttons[0]['hover']; ?>"

				> <?php echo $buttons[0]['icon']; ?> <?php echo $buttons[0]['name']; ?> </a>

			</div>
			<br>
			<div class="row">
				<form name="search">
					<div class="col-md-2">
						<input class="form-control" name="f" id="f" placeholder="Search Opportunities">
					</div>
					<div class="col-md-2">
						<select class="form-control" name="c" id="c">
							<option value="">Select Country</option>
							<?php if(!empty($countries)): ?>
								<?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									<option value="<?= $c->country_code?>" <?php if ($cn == $c->country_code) {
                                        echo "Selected";
                                    }?>><?= $c->country_name?></option>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							<?php endif; ?>
						</select>
					</div>
					<div class="col-md-2">
						<input class="form-control" name="b" id="b" placeholder="Search Vouches">
					</div>
					<div class="col-md-2">
						<select class="form-control" name="i" id="i">
							<option value="">Select Industry</option>
							<?php $__currentLoopData = $industries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $in): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>{
							<option value="<?php echo e($in->id); ?>" <?php if ($i == $in->id) {
                                echo "Selected";
                            }?>><?php echo e($in->name); ?></option>
							}
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</select>
					</div>
					<div class="col-md-2">
						<select class="form-control" name="re" id="re">
							<option value="">Select Reserved</option>
							<option value="1" <?php if($re==1){echo "Selected";}?>>Reserved</option>
							
						</select>
					</div>
					<div class="col-md-2">

						<button type="submit" class="btn btn-primary" data-tooltip="true" data-placement="top"  title="<?php echo $buttons[1]['hover']; ?>"
> <?php echo $buttons[1]['icon']; ?> <?php echo $buttons[1]['name']; ?> 

						</button> &nbsp;<a href="<?php echo e(url('opportunity')); ?>" title="refresh" class="btn btn-primary"
						data-tooltip="true" data-placement="top"  title="<?php echo $buttons[2]['hover']; ?>"
> <?php echo $buttons[2]['icon']; ?> <?php echo $buttons[2]['name']; ?> 
</a></div>
				</form>
			</div>
			<br>
			<div class="row">
				<table id="table_id" class="display">
					<thead>
					<tr>
						<th>ID</th>
						<th>Vouched</th>
						<th>Opportunity</th>
						<th>Country</th>
						<th>City</th>
						<th>Industry</th>
						<th>Reserved</th>
						<th>Renewal</th>
						<th>Status</th>
						<th>Added Date</th>
						<th>Share</th>
					</tr>
					</thead>
					<tbody>
					<?php if(!empty($bids->first())): ?>
						<?php $__currentLoopData = $bids; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bid): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<tr>
								<td><?php echo e($bid->id); ?></td>

								<td align="center"><a href="<?php echo e(url('vouches/')."/".$bid->id); ?>" data-tooltip="true" data-placement="top"  title="<?php echo $buttons[3]['hover']; ?>"
><span

											class="badge badge-danger"><?php echo e($bid->vouchesResponse->count()); ?></span></a>
								</td>
								<td><a href="javascript:void(0)" data-id="<?php echo e($bid->id); ?>" onclick="transaction(<?php echo e($bid->id); ?>)"><?php echo e($bid->hashtag); ?></a></td>
								<td><?php echo e((!empty($bid->country_name)) ? $bid->country_name->country_name : "-"); ?></td>
								<td><?php echo e((!empty($bid->opportunity_city)) ? $bid->city_name->name : "-"); ?></td>
								<td><?php echo e((!empty($bid->industry)) ? $bid->industry->name : '-'); ?></td>
								<td>
									<?php if($bid->is_reserved == 1): ?>
										<span class="badge badge-success">Yes</span>
										
									
									<?php endif; ?>	
								</td>
								<td>
								
									<?php if($bid->next_payment_date != null): ?>
										<?php echo e(Carbon::parse($bid->next_payment_date)->format('Y-m-d')); ?>

										<?php
											$nextPaymentDate = Carbon::parse($bid->next_payment_date);
											$daysRemaining = $nextPaymentDate->diffInDays(Carbon::now());
										?>
										<?php if($daysRemaining <= 30): ?>

											<a class='btn btn-primary btn-xs renew' data-id="<?php echo e($bid->id); ?>" data-tooltip="true" data-placement="top"  title="<?php echo $buttons[4]['hover']; ?>"
> <?php echo $buttons[4]['icon']; ?> <?php echo $buttons[4]['name']; ?></a>
									
									
										<?php endif; ?>
								
									<?php endif; ?>
																	
								</td>
								<td>
									<?php if($bid->status == 0): ?>
										<span class="badge badge-warning">Pending</span>
									<?php else: ?>
										<span class="badge badge-success">Approved</span>
									<?php endif; ?>
								</td>
								<td><?php echo e(Date("Y-m-d",strtotime($bid->created_at))); ?></td>
								<td><span id="opportunity_<?php echo e($bid->id); ?>" class="share-course-filed"
										  style="display: none"> <?php echo e(url('share-opportunity')."/" . $bid->share_id); ?></span> <a
										href="javascript:void(0)" class="btn btn-primary read-more-btn  share-opp-btn"
										onclick="copyToClipboard('#opportunity_<?php echo e($bid->id); ?>',<?php echo e($bid->status); ?>)" data-tooltip="true" data-placement="top"  title="<?php echo $buttons[5]['hover']; ?>"
> <?php echo $buttons[5]['icon']; ?> <?php echo $buttons[5]['name']; ?></a></td>
							</tr>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					<?php endif; ?>
					</tbody>
				</table>
			</div>

		</div>
		<div class="modal" id="add-bid" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
						<h4 class="modal-title" id="myModalLabel" style="text-align: center">Response</h4>
						<h4 id="bid_id" style="text-align: center"></h4>
					</div>
					<div class="modal-body">
						<form class="addBidForm" id="addBidForm" method="post" onsubmit="return false"
							  name="addBidForm">

							<div class="form-group autocomplete">
								<label>
									<strong>Write Your Pitch (In 500 characters)</strong>
								</label>
								<textarea id="description" name="description" class="form-control"
										  placeholder="Let's talk about Oranges if the bid is about Oranges and NOT Apples. For e.g. tell a story about how you did it before and that could help the client for now. "
										  maxlength="500"></textarea>
								<input id="bid_input_id" name="bid_input_id" type="hidden">
							</div>
							<div class="form-group">
								<label>
									<strong>Web Link</strong>

								</label>
								<input id="portfolio" name="portfolio" class="form-control"
									   placeholder="Website, Portfolio, Social Media Profile" data-validation="url">
							</div>

							<button type="submit" id="addBid" class="btn btn-default">Submit</button>
						</form>

					</div>

				</div>
			</div>
		</div>
	</section>

	<div class="modal fade" id="transactionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Transaction</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive" id="transactionModalBody">

                <table class="table table-bordered table-striped last-child-sm" width="100%" data-page-length='100'>
                  <thead>
                  <tr>
                    <th width="3%">Id</th>
                    <th>Transaction Date</th>
                    <th>Transaction Amount</th>
                    <th>Transaction Mode</th>
                    <th>Refrence No.</th>
                    <th>Reserved Years</th>
                    <th>Renewal Date</th>
                 
                  </tr>
                  </thead>
                  <tbody id="transactionModalBodyContent">
                  </tbody>
                </table>

              </div>
            </div>
          </div>
        </div>  
  </div>
  </div>
  </div>



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
			<input type="hidden" id="opportunity_id" name="opportunity_id" value="" />

		  <select class="form-control reserved_year" name="reserved_year" required="required">
                <option value="">--Renewal Year--</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
              </select>
			  <br/>

          <div id="wallet_row" class="form-group autocomplete" style="width: 100%; display: block; margin-left: auto; margin-bottom:10px; margin-right: 0;">
                    <label>
                    
                    <input type="checkbox" name="list" value="" class="click_wallet form-control" style="height: 16px; width: 16px; margin-top: 3px; position: absolute;" />
                    <span style="margin-left: 25px;"><?php echo e(config('sitevars.data.ayojn_reward')); ?></span>
                    <input type="hidden" id="wallet_use" name="wallet_use" value="0" />
                    </label>
                    <label class="pull-right" id="wallet_balance">
                    
                    <?php echo e(Auth::user()->wallet_balance); ?>

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
                  <strong><?php echo e(config('sitevars.data.service_charge')); ?> (<?php echo e(config('sitevars.data.currency')); ?>)</strong>
                </label>
                <label class="pull-right">
                  <span id="paidamt"><?php echo e(CommonHelper::getConfig('build_hashtag_renew')['ayojn_service']); ?></span> 
                </label>
                
            </div>

            <hr style="margin-top: 5px;"/>
            <div class="form-group autocomplete text-center">

            <a href="javascript:void(0)" class="btn btn-primary button-primary-color paynowfinal" data-tooltip="true" data-placement="top"  title="<?php echo $buttons[6]['hover']; ?>"
>  <?php echo $buttons[6]['icon']; ?> <?php echo $buttons[6]['name']; ?> </a>
                    
                    
                </div>

          </div>
        </div>
      </div>
    </div>

	<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
	<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>

	<script>
      $(".share-opp-btn").on('click', function (e) {

      });
      function copyToClipboard(element,status) {
        if(status != 0){
          var $temp = $("<input>");
          $("body").append($temp);
          $temp.val($(element).text()).select();
          document.execCommand("copy");
          $temp.remove();
          toastr.success('Web Link Copied','Success');
		}else{
          toastr.error('We are preparing this opportunity for a launch, please wait while we are working on this.','Warning');
		}

      }
      // $("#addBidForm").validate({
      //   rules: {
      //     portfolio: {
      //       required: true,
      //       url: true
      //     }, description: {
      //       required: true,
      //       maxlength: 500
      //     }
      //   },
      //   submitHandler: function (form) {
      //     addBid()
      //   }
      // });

      function displayEmail(email) {
        swal(email);
      }
	</script>
	<script src="<?php echo e(asset('js/jquery1.min.js')); ?>"></script>

	<script src="<?php echo e(asset('js/bootstrap.min.js')); ?>"></script>
	<script src="<?php echo e(asset('js/typed.min.js')); ?>"></script>
	<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
	<script>

      $(document).ready(function () {

        $('#table_id').DataTable({
			"order": [[0, 'desc']],
		});
      });


	  function transaction(id){
        var url = "<?php echo e(route('opp-transaction')); ?>";
        $.ajax({
          type: 'POST',
          url: url,
          data: {'id': id},
          dataType: 'html',
          headers: {"X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"},
          success: function (response) {
            //var data = JSON.parse(response.opportunity);
            console.log(response);
            $('#transactionModal').modal('toggle');
            $('#transactionModalBodyContent').html(response);
          },
        })
      }

	</script>

	<script>

		$(document).on('click', '.renew', function () {
			var id = $(this).data('id');
			$("#opportunity_id").val(id);
			$("#paynow_modal").modal('show');
			$("#voucher_row #voucher_code, #voucher_row .voucher_button").prop("disabled", true);
		});


		$(".click_wallet").click(function(){
            check = $(".click_wallet").prop("checked");
            if(check) 
            {
              
                if(<?php echo e(Auth::user()->wallet_balance); ?> > 5)
                {
                    var chk = parseInt($("#paidamt").html());
                    var pamt = <?php echo e(Auth::user()->wallet_balance); ?>-chk;
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
                    var pamt = chk-<?php echo e(Auth::user()->wallet_balance); ?>;
                    $("#wallet_balance").html(0);
                    $("#paidamt").html(pamt);
                    $("#wallet_use").val(<?php echo e(Auth::user()->wallet_balance); ?>);
        
                
                }
            }
            else
            {
                var chk = parseInt($("#paidamt").html());
                if(<?php echo e(Auth::user()->wallet_balance); ?> > 5)
                {
                    $("#paidamt").html(5);
                }
                else{
                    $("#paidamt").html(chk+<?php echo e(Auth::user()->wallet_balance); ?>);
                }
                $("#wallet_balance").html(<?php echo e(Auth::user()->wallet_balance); ?>);
              
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
									url: "<?php echo e(route('check-vouch-code')); ?>",
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
					var finalvalue = plusvalue-<?php echo e(Auth::user()->wallet_balance); ?>

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
			var yrs = $(".reserved_year").val();
			if(yrs == ""){
				alert("Please select renewal year");
				$(".reserved_year").focus();
				return false;
			}

			$(".loader ").show();
			var id = $("#opportunity_id").val();
			var amt = $("#paidamt").html();
			var voucher_code = $("#voucher_code").val();
			var reserved_year = $(".reserved_year").val();
			if($(".click_wallet").prop("checked")){
				var wallet = $("#wallet_use").val();
			}else{
				var wallet = 0;
			}

			$.ajax({
					url: "<?php echo e(route('renew-opportunity-paynow')); ?>",
					type: 'POST',
					data: {id:id, wallet:wallet, amount: amt, voucher_code: voucher_code, reserved_year:reserved_year},
					headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
					success: function (data) {
					console.log(data);
					if(data['flag'] == 1)
					{   
						if(data['link'] !="")
						{
							window.location.replace(data['link']);
						}else{

							alert("Opportunities renewed successfully");
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>