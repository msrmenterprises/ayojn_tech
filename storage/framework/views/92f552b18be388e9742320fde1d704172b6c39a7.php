<?php  $buttons = $db_buttons['recieve_bid'];  ?>
<?php 
  use App\Helpers\CommonHelper;
  ?>
<?php $__env->startSection('content'); ?>

<style>
/* Override SweetAlert styles */
.swal-overlay {
  z-index: 99999999 !important; /* Ensure it's above all other elements */
  top: 0 !important;
}

.swal-modal {
  margin-top: 20px !important; /* Pulls modal closer to the top */
}
</style>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <?php
    $f = Request::get('f');
    $cn = Request::get('c');
    $b = Request::get('b');
    $i = Request::get('i');
    $qa = Request::get('qa');

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
        <div class="container">
            <div class="row"><a href="<?php echo e(url('my-bids')); ?>" class="btn btn-primary float-left"
                data-tooltip="true" data-placement="top"  title="<?php echo $buttons[0]['hover']; ?>"> <?php echo $buttons[0]['icon']; ?> <?php echo $buttons[0]['name']; ?></a>
                <div class="row">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success">
                            <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>
                    <?php if(session('error')): ?>
                        <div class="alert alert-danger">
                            <?php echo e(session('error')); ?>

                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <br>
            <form name="search">
                <div class="row">
                    <div class="col-md-2">
                        <select class="form-control" name="f">
                            <option value="">Outreach Via</option>
                            <option value="Event" <?php if ($f == 'Event') {
                                echo "Selected";
                            }?>>Event
                            </option>
                            <option value="Campaign" <?php if ($f == 'Campaign') {
                                echo "Selected";
                            }?>>Campaign
                            </option>
                            <option value="Content" <?php if ($f == 'Content') {
                                echo "Selected";
                            }?>>Content
                            </option>
                            <option value="Sports Team" <?php if ($f == 'Sports Team') {
                                echo "Selected";
                            }?>>Sports Team
                            </option>
                            <option value="Venue" <?php if ($f == 'Venue') {
                                echo "Selected";
                            }?>>Venue
                            </option>
                            <option value="Not for Profit" <?php if ($f == 'Not for Profit') {
                                echo "Selected";
                            }?>>Not for Profit
                            </option>
                            <option value="Performing Arts" <?php if ($f == 'Performing Arts') {
                                echo "Selected";
                            }?>>Performing Arts
                            </option>
                            <option value="Think Tank" <?php if ($f == 'Think Tank') {
                                echo "Selected";
                            }?>>Think Tank
                            </option>
                            <option value="Knowledge Pool" <?php if ($f == 'Knowledge Pool') {
                                echo "Selected";
                            }?>>Knowledge Pool
                            </option>
                            <option value="Online Events" <?php if ($f == 'Online Events') {
                                echo "Selected";
                            }?>>Online Activities
                            </option>
                            <option value="Other" <?php if ($f == 'Other') {
                                echo "Selected";
                            }?>>Other
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" name="qa" id="qa">
                            <option value="">Available For</option>
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
                        <select class="form-control" name="c" id="filter_c" onchange="getCity(7)">
                            <option value="">Country</option>
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
                        <select class="form-control" name="f_c" id="f_c">
                            <option value="">Local Opportunities</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" name="b" id="b">
                            <option value="">Budget</option>
                            <option value="200-500" <?php echo e(($b == '200-500')?'selected':''); ?>>200-500</option>
                            <option value="500-2000" <?php echo e(($b == '500-2000')?'selected':''); ?>>500-2000</option>
                            <option value="2000-5000" <?php echo e(($b == '2000-5000')?'selected':''); ?>>2000-5000</option>
                            <option value="5000-10000" <?php echo e(($b == '5000-10000')?'selected':''); ?>>5000-10000</option>
                            <option value="10000-20000" <?php echo e(($b == '10000-20000')?'selected':''); ?>>10000-20000</option>
                            <option value="20000-30000" <?php echo e(($b == '20000-30000')?'selected':''); ?>>20000-30000</option>
                            <option value="30000-50000" <?php echo e(($b == '30000-50000')?'selected':''); ?>>30000-50000</option>
                            <option value="50000-100000" <?php echo e(($b == '50000-100000')?'selected':''); ?>>50000-100000
                            </option>
                            <option value="Above 100000" <?php echo e(($b == 'Above 100000')?'selected':''); ?>>Above 100000
                            </option>
                            <option value="In kind" <?php echo e(($b == 'In kind')?'selected':''); ?>>In kind</option>

                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" name="i" id="i">
                            <option value="">Industry</option>
                            <?php $__currentLoopData = $industries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $in): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>{
                            <option value="<?php echo e($in->id); ?>" <?php if ($i == $in->id) {
                                echo "Selected";
                            }?>><?php echo e($in->name); ?></option>
                            }
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="row" style="padding-top: 10px;">
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary" data-tooltip="true" data-placement="top"  title="<?php echo $buttons[1]['hover']; ?>"
                        > <?php echo $buttons[1]['icon']; ?> <?php echo $buttons[1]['name']; ?>

                        </button> &nbsp;<a href="<?php echo e(url('bid')); ?>" title="refresh" class="btn btn-primary" data-tooltip="true" data-placement="top"  title="<?php echo $buttons[2]['hover']; ?>"
                        ><?php echo $buttons[2]['icon']; ?> <?php echo $buttons[2]['name']; ?></a></div>
                </div>
            </form>
            <br>
            <div class="row">
                <table id="table_id" class="display">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Outreach Via</th>
                        <th>Specific <br>Opportunity</th>
                        <th>Objective</th>
                        <th>Geo Focus</th>
                        <th>Expected Budget <br/>(USD)</th>
                        <th>Industry</th>
                        <th>Action</th>
                        <th>Added Date</th>
                        <th width="24%">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(!empty($bids->first())): ?>
                        <?php $__currentLoopData = $bids; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bid): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                            $specification = '';
                            $specificationArray = [];
                            $flag = \DB::table('flag_activity')->where('user_id',Auth::user()->id)->where('reference_id',$bid->id)->where('flag_type','bid')->value('reason');

                            ?>
                            <?php if(!empty($bid->bidSpecify->first())): ?>
                                <?php $__currentLoopData = $bid->bidSpecify; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bidS): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $specificationArray[] = $bidS->specifyName->specify_name;
                                        $specification = implode(', ',$specificationArray);
                                    ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                            <tr>
                                <td><?php echo e($bid->id); ?></td>
                                <?php if($bid->sponsor_for=='Online Events'): ?>
                                    <td> Online Activities</td>
                                <?php else: ?>
                                    <td><?php echo e($bid->sponsor_for); ?></td>
                                <?php endif; ?>
                                <td><?php echo e($specification); ?></td>
                                <td><?php echo e($bid->likeSponsorr); ?></td>
                                <td><?php echo e((!empty($bid->country_name)) ? $bid->country_name->country_name : "-"); ?>

                                    / <?php echo e((!empty($bid->city) && !empty($bid->city_name) && !empty($bid->city_name->name)) ? $bid->city_name['name'] : "-"); ?></td>
                                <td><?php echo e($bid->sponsor_budget); ?></td>
                                <td><?php echo e((!empty($bid->industry)) ? $bid->industry->name : '-'); ?></td>
                                <td><?php if(!empty($bid->bidResponse->first() && $bid->bidResponse->first()->is_accepted == 2)): ?>
                                        <?php if(!empty($bid->bider) && $bid->bider->email != ''): ?>
                                            <a href="javascript:void(0)"
                                               onclick="displayEmail('<?php echo e($bid->bider->email); ?>')"><i
                                                    class="fa fa-envelope"
                                                    aria-hidden="true"></i></a>
                                        <?php endif; ?>
                                    <?php endif; ?> &nbsp;&nbsp;
                                    <?php if(!empty($bid->bidResponse->first())): ?>

                                        <?php if(!empty($bid->bidResponse->first()->is_accepted == 0)): ?>
                                            <a href="javascript:void(0)"><span
                                                    class="label label-warning " data-tooltip="true" data-placement="top"  title="<?php echo $buttons[4]['hover']; ?>"> <?php echo $buttons[4]['icon']; ?> <?php echo $buttons[4]['name']; ?> </span></a>
                                        <?php elseif(!empty($bid->bidResponse->first()->is_accepted == 1)): ?>

                                            <a href="javascript:void(0)"><span class="label label-primary" data-tooltip="true" data-placement="top"  title="<?php echo $buttons[5]['hover']; ?>"><?php echo $buttons[5]['icon']; ?> <?php echo $buttons[5]['name']; ?></span></a>
                                        <?php elseif(!empty($bid->bidResponse->first()->is_accepted == 2)): ?>
                                            <span class="label label-success" data-tooltip="true" data-placement="top"  title="<?php echo $buttons[6]['hover']; ?>"><?php echo $buttons[6]['icon']; ?> <?php echo $buttons[6]['name']; ?></span> 
                                        <?php elseif(!empty($bid->bidResponse->first()->is_accepted == 3)): ?>
                                            <span class="label label-success" data-tooltip="true" data-placement="top"  title="<?php echo $buttons[7]['hover']; ?>"><?php echo $buttons[7]['icon']; ?> <?php echo $buttons[7]['name']; ?></span>  
                                        <?php elseif(!empty($bid->bidResponse->first()->is_accepted == 4)): ?>
                                            <span class="label label-success" data-tooltip="true" data-placement="top"  title="<?php echo $buttons[8]['hover']; ?>"><?php echo $buttons[8]['icon']; ?> <?php echo $buttons[8]['name']; ?></span>                                      
                                        <?php else: ?>
                                           <!--  <a href="javascript:void(0)"><span
                                                    class="label label-success">Open for Negotiation</span></a> -->
                                        <?php endif; ?>
                                    <?php else: ?>

                                        <?php if($bid->city_bidder_from == 'Yes'): ?>
                                            <?php if($bid->city == Auth::user()->city): ?>
                                                <?php if($bid->identity == "All" || $bid->identity == "Everyone" || $bid->identity == Auth::user()->identity): ?>

                                                    <a href="javascript:void(0)"
                                                       onclick="OpenPopup('<?php echo e($bid->id); ?>')"><span
                                                            class="label label-info"><?php echo $buttons[9]['icon']; ?> <?php echo $buttons[9]['name']; ?></span></a>
                                                <?php else: ?>
                                                    <a href="javascript:void(0)"
                                                       onclick="displayErrorMessage()"><span
                                                            class="label label-info"><?php echo $buttons[9]['icon']; ?> <?php echo $buttons[9]['name']; ?></span></a>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <a href="javascript:void(0)" onclick="displayErrorMessage()"><span
                                                        class="label label-info"><?php echo $buttons[9]['icon']; ?> <?php echo $buttons[9]['name']; ?></span></a>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <?php if($bid->identity == "All" || $bid->identity == "Everyone" || $bid->identity == Auth::user()->identity): ?>
                                                <?php if(Auth::user()->free_response  < 3 ||  Auth::user()->is_paid == 1): ?>
                                                    <a href="javascript:void(0)"
                                                       onclick="OpenPopup('<?php echo e($bid->id); ?>')"><span
                                                            class="label label-info"><?php echo $buttons[9]['icon']; ?> <?php echo $buttons[9]['name']; ?></span></a>
                                                <?php else: ?>
                                                    <a href="javascript:void(0)"
                                                       onclick="paymentPopUp(<?php echo e($bid->id); ?>)"><span
                                                            class="label label-info"><?php echo $buttons[9]['icon']; ?> <?php echo $buttons[9]['name']; ?></span></a>

                                                <?php endif; ?>

                                            <?php else: ?>
                                                <a href="javascript:void(0)" onclick="displayErrorMessage()"><span
                                                        class="label label-info"><?php echo $buttons[9]['icon']; ?> <?php echo $buttons[9]['name']; ?></span></a>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                    <?php endif; ?>



                                </td>
                                <td><?php echo e(Date("Y-m-d",strtotime($bid->created_at))); ?></td>
                                <td><span id="opportunity_<?php echo e($bid->id); ?>" class="share-course-filed"
                                          style="display: none"> <?php echo e(url('share')."/" . $bid->share_id); ?></span> <a
                                        href="javascript:void(0)" class="btn btn-primary read-more-btn  share-opp-btn"
                                        onclick="copyToClipboard('#opportunity_<?php echo e($bid->id); ?>')" data-tooltip="true" data-placement="top"  title="<?php echo $buttons[3]['hover']; ?>"
><?php echo $buttons[3]['icon']; ?> <?php echo $buttons[3]['name']; ?></a>

                             <?php if(!$flag): ?>
                                    <a href="javascript:void(0)" class="btn btn-primary" onclick="openReasonForm(<?php echo e($bid->id); ?>,'bid')" data-tooltip="true" data-placement="top"  title="<?php echo $buttons[11]['hover']; ?>"><?php echo $buttons[11]['icon']; ?> <?php echo $buttons[11]['name']; ?></a>
                                    <?php else: ?>
                                    <a href="javascript:void(0)" class="btn btn-info" onclick="getflag('<?php echo e($flag); ?>')" data-tooltip="true" data-placement="top"  title="<?php echo $buttons[12]['hover']; ?>"><?php echo $buttons[12]['icon']; ?> <?php echo $buttons[12]['name']; ?></a>
                                   
                                    <?php endif; ?>

</td>
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
                                    <strong>Write Your Pitch (In 200 characters)</strong>
                                </label>
                                <textarea id="description" name="description" class="form-control"
                                          placeholder="Let's talk about Oranges if the bid is about Oranges and NOT Apples. For e.g. tell a story about how you did it before and that could help the client for now. "
                                          maxlength="200"></textarea>
                                <input id="bid_input_id" name="bid_input_id" type="hidden">
                            </div>
                            <div class="form-group">
                                <label>
                                    <strong>Web Link <!--(Let’s build an event website for Free via our partner <a
                                            href="http://www.media101.tech" target="_blank">101 Media</a>)--> </strong>

                                </label>
                                <div class="input-group">
                                <span class="input-group-addon" id="basic-addon3">https://www.</span>
                                <input id="portfolio" name="portfolio" class="form-control"
                                placeholder="Website, Portfolio, Social Media Profile">
                                </div>
                               
                            </div>

                            <button type="submit" id="addBid" class="btn btn-default" data-tooltip="true" data-placement="top"  title="<?php echo $buttons[10]['hover']; ?>"
><?php echo $buttons[10]['icon']; ?> <?php echo $buttons[10]['name']; ?></button>
                        </form>

                    </div>

                </div>
            </div>
        </div>
    </section>


    <div class="modal" id="add-bid-pay" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                           <i class='fa fa-close'></i>
                        </button>
                        <h4 class="modal-title" id="myModalLabel" style="text-align: center">Response</h4>
                       
                    </div>
                    <div class="modal-body">
                        <form method="POST" onsubmit="return paymentmodal(event)">

                            <div class="form-group autocomplete">
                                <label>
                                    <strong>Write Your Pitch (In 200 characters)</strong>
                                </label>
                                <textarea id="description_new" name="" class="form-control"
                                          placeholder="Let's talk about Oranges if the bid is about Oranges and NOT Apples. For e.g. tell a story about how you did it before and that could help the client for now. "
                                          maxlength="200" required></textarea>
                                        <input id="bid_input_id_new" name="" type="hidden">
                            </div>
                            <div class="form-group">
                                <label>
                                    <strong>Web Link  </strong>

                                </label>
                                <div class="input-group">
                                <span class="input-group-addon" id="basic-addon3">https://www.</span>
                                <input id="portfolio_new" name="" class="form-control"
                                placeholder="Website, Portfolio, Social Media Profile" required>
                                </div>
                               
                            </div>

                            <button type="submit" class="btn btn-success">Submit & Pay</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>


    <div class="modal" id="paynow_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false" data-backdrop="static" style="z-index:9999;">
	<div class="modal-dialog modal-sm" role="document" style="width:450px;">
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
							<strong><?php echo e(config('sitevars.data.service_charge')); ?> (<?php echo e(config('sitevars.data.currency')); ?>)</strong>
						</label>
						<label class="pull-right">
							<span id="paidamt"><?php echo e(CommonHelper::getConfig('pay_bid')['ayojn_service']); ?></span>
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

<div id="append-form-reason"></div>



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

    function getflag(flag)
    {
        swal({
        title: 'Flag Reason',
        text: flag,
        icon: "",
        buttons: false,
        }).show();
    }

        function copyToClipboard(element) {
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val($(element).text()).select();
            document.execCommand("copy");
            $temp.remove();
            toastr.success('Web Link Copied', 'Success');
        }

        $("#addBidForm").validate({
            rules: {
                portfolio: {
                    required: true,
                }, description: {
                    required: true,
                    maxlength: 200
                }
            },
            submitHandler: function (form) {
                //  console.log(form)
                addBid()
            }
        });

        function displayEmail(email) {
            const el = document.createElement('div')
           // el.innerHTML = "<strong>At this stage we highly recommend that you use <a href='https://www.pandadoc.com' target='_blank'>PandaDoc </a> create winning proposals and send it directly to the client at: " + email
            el.innerHTML = "<strong>Send it directly to the client at: " + email
            //swal("Good news ! Client would like to negotiate and discuss the proposal. At this stage we highly recommend that you use PandaDoc create winning proposals and send it directly to the client at: ", email);

            swal({
                title: "Good news ! Client would like to negotiate and discuss the proposal.",
                content: el,
            })
        }

        function paymentPopUp(id)
        {
            $("#add-bid-pay").modal('show');
            $("#bid_input_id_new").val(id);
        }

        function paymentmodal()
        {

             $("#paynow_modal").modal('show');
             $("#voucher_row #voucher_code, #voucher_row .voucher_button").prop("disabled", true);
             $("#add-bid-pay").modal('hide');
             return false;
        }



        // function paymentPopUp(id) {
        //     const el = document.createElement('div')
        //     const url = "<?php echo e(url('bid-response-payment/')); ?>" + "/" + id;
        //     el.innerHTML = "<a class='swal-button swal-button--confirm' href='" + url + "'>Pay $5</a>";
        //     //swal("Good news ! Client would like to negotiate and discuss the proposal. At this stage we highly recommend that you use PandaDoc create winning proposals and send it directly to the client at: ", email);
        //     swal({
        //         title: "Please pay to bid this opportunity",
        //         content: el,
        //     })
        // }

        function displayErrorMessage() {
            toastr.error('Your Identity / Location is a mismatch for this bid as per the client specification. ', "Error");
        }

        function OpenPopup(bidId) {
            $("#bid_id").text("#" + bidId);
            $("#bid_input_id").val(bidId);
            $("#add-bid").modal('show');
        }

        function addBid() {
            var bid_id = $("#bid_input_id").val();
            var description = $("#description").val();
            var portfolio = 'https://www.'+$("#portfolio").val();
            $.ajax({
                type: 'POST',
                url: '<?php echo e(url('add-bid-response')); ?>',
                headers: {"X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"},
                data: {
                    bid_id: bid_id,
                    description: description,
                    portfolio_link: portfolio,
                },
                success: function (response) {
                    //console.log(response.status);
                    var data = response;
                    if (data.status) {
                        $("#add-bid").modal('hide');
                        const el = document.createElement('div')
                        el.innerHTML = "<a class='swal-button swal-button--confirm' href='<?php echo e(url('my-bids')); ?>'>View my bids</a>"
                        //el.innerHTML = "<strong>In the meanwhile let's help you prepare the Proposal using <a href='https://www.pandadoc.com' target='_blank'>PandaDoc</a>. At <a href='https://www.pandadoc.com' target='_blank'>PandaDoc</a> you can streamline all of your sales documents.</strong><br><br><a class='swal-button swal-button--confirm' href='<?php echo e(url('my-bids')); ?>'>View my bids</a>"
                        // swal('', data.message, 'success');
                        swal({
                            title: "Great you made a Bid. Let's wait for the client to Read or Open the Bid for Negotiation. We will notify you once they do.",
                            content: el,
                        })
                        // toastr.success(response.message, "Success");
                        setTimeout(function () {
                            window.location.reload();
                        }, 1500);
                        //window.location.reload();
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
    </script>
    <script src="<?php echo e(asset('js/jquery1.min.js')); ?>"></script>

    <script src="<?php echo e(asset('js/bootstrap.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/typed.min.js')); ?>"></script>
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#table_id').DataTable();
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

                $(".loader ").show();

                var bidId = $("#bid_input_id_new").val();
                var description = $("#description_new").val();
                var portfolio = 'https://www.'+$("#portfolio_new").val();
                
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

                $.ajax({
                        url: "<?php echo e(route('paynow-bid-receiver')); ?>",
                        type: 'POST',
                        data: {_token: CSRF_TOKEN, bidId: bidId, wallet:wallet, amt: amt, voucher_code: voucher_code, description: description, portfolio: portfolio},
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
    
    
    
    
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>