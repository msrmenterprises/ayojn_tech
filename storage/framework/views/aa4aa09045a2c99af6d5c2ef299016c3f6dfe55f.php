<?php  $buttons = $db_buttons['offer_opportunity']; 
            use App\Helpers\CommonHelper;
?>
<?php $__env->startSection('content'); ?>
    <style>
        .modal{z-index: 9999 !important;}
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
            <div class="row"><h3 style="text-align: center;">Launched Opportunities</h3>
            </div>
            <div class="row">
                <h4 style="text-align: center" ></h4>
                <a href="<?php echo e(url('my-vouches')); ?>" class="btn btn-primary float-left" data-tooltip="true" data-placement="top"  title="<?php echo $buttons[0]['hover']; ?>"> <?php echo $buttons[0]['icon']; ?> <?php echo $buttons[0]['name']; ?> </a>

            </div>
            <br>
            <div class="row">
                <form name="search">
                    <div class="col-md-3">
                        <input class="form-control" name="f" id="f" placeholder="Search Opportunity">
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" name="c" id="lunch_opportunity_country" onchange="getCity(9)">
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
                        <select class="form-control" name="city" id="lunch_opportunity_city">
                            <option value="">Select City</option>

                        </select>
                    </div>
                    <div class="col-md-3">
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
                        <button type="submit" class="btn btn-primary" data-tooltip="true" data-placement="top"  title="<?php echo $buttons[1]['hover']; ?>"><?php echo $buttons[1]['icon']; ?> <?php echo $buttons[1]['name']; ?></button> 
                        &nbsp;<a href="<?php echo e(url('opportunity')); ?>" class="btn btn-primary" data-tooltip="true" data-placement="top"  title="<?php echo $buttons[2]['hover']; ?>"
                        ><?php echo $buttons[2]['icon']; ?> <?php echo $buttons[2]['name']; ?></a></div>
                </form>
            </div>
            <br>
            <div class="row">
                <table id="table_id" class="display">
                    <thead>
                    <tr>
                        
                        <th>Opportunity</th>
                        
                        <th>Industry</th>
                        <!--<th>Added Date</th> -->
                        <th width="30%">Action</th>
                        
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(!empty($bids->first())): ?>
                        <?php $__currentLoopData = $bids; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bid): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                               $check =\DB::table('vouches')->where('opportunity_id',$bid->id)->where('offer_id',Auth::user()->id)->value('opportunity_id'); 
                               $flag = \DB::table('flag_activity')->where('user_id',Auth::user()->id)->where('reference_id',$bid->id)->where('flag_type','opportunities')->value('reason');
                            ?>

                            <?php if($check != $bid->id): ?>
                               <tr>
                                
                                <td><?php echo e($bid->hashtag); ?></td>
                                
                                <td><?php echo e((!empty($bid->industry)) ? $bid->industry->name : '-'); ?></td>
                         
                                
                                 <td id="vouch_<?php echo e($bid->id); ?>">
                                  
                                    <span id="opportunity_<?php echo e($bid->id); ?>" class="share-course-filed"
                                          style="display: none"> <?php echo e(url('share-opportunity')."/" . $bid->share_id); ?></span>
                                    <a href="javascript:void(0)" class="btn btn-primary read-more-btn share-opp-btn"
                                        onclick="copyToClipboard('#opportunity_<?php echo e($bid->id); ?>')" data-tooltip="true" data-placement="top"  title="<?php echo $buttons[4]['hover']; ?>"><?php echo $buttons[4]['icon']; ?> <?php echo $buttons[4]['name']; ?> </a>

                                    <?php if(Auth::user()->free_vouch  < 3 ||  Auth::user()->is_paid == 1): ?>
                                        <a href="javascript:void(0)" onclick="getVouchForm(<?php echo e($bid->id); ?>,'free')"
                                           class="btn btn-primary" data-tooltip="true" data-placement="top"  title="<?php echo $buttons[3]['hover']; ?>"><?php echo $buttons[3]['icon']; ?> <?php echo $buttons[3]['name']; ?></a>
                                    <?php else: ?>

                                        <a href="javascript:void(0)" onclick="getVouchForm(<?php echo e($bid->id); ?>,'paid')"
                                           class="btn btn-primary" data-tooltip="true" data-placement="top"  title="<?php echo $buttons[3]['hover']; ?>"><?php echo $buttons[3]['icon']; ?> <?php echo $buttons[3]['name']; ?></a>
                                    <?php endif; ?>

                                    <?php if(!$flag): ?>
                                    <a href="javascript:void(0)" class="btn btn-primary" onclick="openReasonForm(<?php echo e($bid->id); ?>,'opportunities')" data-tooltip="true" data-placement="top"  title="<?php echo $buttons[6]['hover']; ?>"><?php echo $buttons[6]['icon']; ?> <?php echo $buttons[6]['name']; ?></a>
                                    <?php else: ?>
                                    <a href="javascript:void(0)" class="btn btn-info" onclick="getflag(`<?php echo e(addslashes($flag)); ?>`)" data-tooltip="true" data-placement="top"  title="<?php echo $buttons[7]['hover']; ?>"><?php echo $buttons[7]['icon']; ?> <?php echo $buttons[7]['name']; ?></a>
                                   
                                    <?php endif; ?>


                                </td>
                               

                            </tr>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
        <div id="append-form"></div>
<div id="append-form-reason"></div>


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
			
            <div class="form-group autocomplete" style="margin:0px; width: 100%; display: none; margin-left: auto; margin-right: 0;">
						<label>
							<strong><?php echo e(config('sitevars.data.service_charge')); ?> (<?php echo e(config('sitevars.data.currency')); ?>)</strong>
						</label>
						<label class="pull-right">
                         <?php echo e(CommonHelper::getConfig('hashtag_vouch')['ayojn_service']); ?>

						</label>
						
				</div>
               

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


            <div id="voucher_row" class="form-group autocomplete" style="width: 100%; display: none; margin-left: auto; margin-bottom:25px; margin-right: 0;">
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

            <div id="gateway_row" class="form-group autocomplete" style="display:none; width: 100%; margin-left: auto; margin-bottom:10px; margin-right: 0;">
                <label>
                <input type="checkbox" name="list" value="" class="form-control" style="height: 16px; width: 16px; margin-top: 3px; position: absolute;" />
                <span style="margin-left: 25px;">Pay via Gateway</span>
                </label>
                
			</div>


            <hr style="margin-top: 5px;  margin-bottom: 10px;"/>
				<div class="form-group autocomplete" style="margin-bottom: 0px; width: 100%; display: block; margin-left: auto; margin-right: 0;">
						<label>
							<strong><?php echo e(config('sitevars.data.service_charge')); ?> (<?php echo e(config('sitevars.data.currency')); ?>)</strong>
						</label>
						<label class="pull-right">
							<span id="paidamt">
                               <?php echo e(CommonHelper::getConfig('hashtag_vouch')['ayojn_service']); ?>

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




    </section>

    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>

    <script>
        $(".share-opp-btn").on('click', function (e) {
            toastr.success('Web Link Copied', 'Success');
        });

        function openReasonForm(id,form_type) {
            $.ajax({
                url: "<?php echo e(url('/')); ?>" + '/add-reason-common/',
                data: {id: id, form_type: form_type},
                type: "post",
                async: false,
                headers: {"X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"},
                success: function (data) {
                    if (data.html != '') {
                        $("#append-form-reason").append(data.html);
                        $("#add-reason").modal('show');
                    }
                },
                error: function (data) {
                },
            })
        }


        function paymentPopUp(id) {
            const el = document.createElement('div')
            const url = "<?php echo e(url('vouch-payment/')); ?>" + "/" + id;
            el.innerHTML = "<a href='javascript:void(0)' class='btn btn-success button-primary-color paynow_modal' data-bid='"+id+"' data-tooltip='true' data-placement='top' > <?=$buttons[5]['name'] ?>  <?=CommonHelper::getConfig('hashtag_vouch')['ayojn_service'] ?></a>";
            //el.innerHTML = "<a class='swal-button swal-button--confirm' href='" + url + "'>Pay $5</a>";
            //swal("Good news ! Client would like to negotiate and discuss the proposal. At this stage we highly recommend that you use PandaDoc create winning proposals and send it directly to the client at: ", email);
            swal({
                title: "Please pay to Vouch this bid",
                content: el,
                buttons:false,
            })
        }


        function copyToClipboard(element) {
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val($(element).text()).select();
            document.execCommand("copy");
            $temp.remove();
            toastr.success('Web Link Copied', 'Success');
        }

        function changebid(id, status) {
            $.ajax({
                type: 'POST',
                url: '<?php echo e(url('change-opportunity-status')); ?>',
                headers: {"X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"},
                data: {
                    status: status,
                    id: id
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
        }

        function getVouchForm(vouchId,type) {
        
                 $.ajax({
                    url: "<?php echo e(url('/')); ?>" + '/add-vouch/',
                    type: "post",
                    data: {type: type,vouchId: vouchId},
                    async: false,
                    headers: {"X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"},
                    success: function (data) {
                        if (data.html != '') {
                            $("#append-form").html(data.html);
                            $("#add-bid").modal('show');
                        }
                    },
                    error: function (data) {
                    },
                })
            
           
        }

        function displayEmail(email) {
            swal("You may communicate directly with Opportunity creator via: \n" + email);
        }
    </script>
    <script src="<?php echo e(asset('js/jquery1.min.js')); ?>"></script>
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <script src="<?php echo e(asset('js/bootstrap.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/typed.min.js')); ?>"></script>
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script>

        $(document).ready(function () {

            $('#table_id').DataTable();
            $("#voucher_td :input").prop("disabled", true);
        });
    </script>




<script>
     $("body").on("click", ".paynow_modal", function() {
      $("#paynow_modal").modal('show');
      var rspid = $(this).attr('data-bid');
     $('.paynowfinal').attr('data-bid',rspid);
      swal.close();
    });

    $(".click_wallet").click(function(){
        
        check = $(".click_wallet").prop("checked");
        if(check) {
    
            if(<?php echo e(Auth::user()->wallet_balance); ?> > <?php echo e(CommonHelper::getConfig('hashtag_vouch')['ayojn_service']); ?>)
            {
                var chk = parseInt($("#paidamt").html());
                var pamt = <?php echo e(Auth::user()->wallet_balance); ?>-chk;
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
                var pamt = chk-<?php echo e(Auth::user()->wallet_balance); ?>;
                $("#wallet_balance").html(0);
                $("#paidamt").html(pamt);
                $("#wallet_use").val(<?php echo e(Auth::user()->wallet_balance); ?>);

            }
        }
        else
        {
            var chk = parseInt($("#paidamt").html());
            if(<?php echo e(Auth::user()->wallet_balance); ?> > <?php echo e(CommonHelper::getConfig('hashtag_vouch')['ayojn_service']); ?>)
            {
                $("#paidamt").html(<?php echo e(CommonHelper::getConfig('hashtag_vouch')['ayojn_service']); ?>);
            }
            else{
                $("#paidamt").html(chk+<?php echo e(Auth::user()->wallet_balance); ?>);
            }
    
            $("#wallet_balance").html(<?php echo e(Auth::user()->wallet_balance); ?>);
            $("#voucher_row :input").prop("disabled", false);
            $("#gateway_row :input").prop("disabled", false);
            $("#wallet_use").val(0);
        }
            
    });
    

    function getflag(flag)
    {
        swal({
        title: 'Flag Reason',
        text: flag,
        icon: "",
        buttons: false,
        }).show();
    }
    
    
    
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

            $(".loader ").show();
    
            //var fields = $("input[name='list']").serializeArray(); 
            //if (fields.length === 0) 
            //{ 
            //    alert('Select any one way to pay'); 
            //    return false;
            //} 
           
    
    
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
                    url: "<?php echo e(route('paynow-vouch')); ?>",
                    type: 'POST',
                    data: {_token: CSRF_TOKEN, opid: opid, vid: vid, wallet:wallet, amt: amt, voucher_code: voucher_code},
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
                                $(".loader ").hide();
                               
                               
                                
                            }else{

                                //alert("Opportunity has been vouched!");
                                $(".loader ").hide();
                                $("#paynow_modal").modal('hide');
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