<?php  $buttons = $db_buttons['Partner'];  ?>
<?php $__env->startSection('content'); ?>
    <!-- banner section start -->
    <?php
    $f = Request::get('f');
    $cn = Request::get('c');
    $b = Request::get('b');
    $i = Request::get('i');
    $refund = Request::get('refund');
    $cdu = Request::get('cu');
    use App\Country;use App\Currency;use App\Industry;
    $countries = Country::all();
    $Currency = Currency::all();
    $industries = Industry::all();
    ?>
    <section>
        <div class="container" style="padding-top: 89px;">
            <div class="row"><h3 style="text-align: center;margin-top: 15px">Users at Ayojn will receive a 30%
                    discount on their next service fee from Ayojn, if they buy any of your offered options. </h3>
            </div>
            <div class="row">
          
                <a href="<?php echo e(url('partner/new-offer')); ?>" class="btn btn-primary float-left" data-tooltip="true" data-placement="top"  title="<?php echo $buttons[0]['hover']; ?>"
                ><?php echo $buttons[0]['icon']; ?> <?php echo $buttons[0]['name']; ?></a>
           
            </div>
            <div class="row">
                <div class="col-md-12" style="padding-top: 15px;">
                    <?php if(session()->has('success')): ?>
                        <div class="alert alert-success">
                            <?php echo e(session()->get('success')); ?>

                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <br>
            <div class="row">
                <form name="search">
                    <div class="col-md-2">
                        <select class="form-control" name="f">
                            <option value="">Offer For</option>
                            <option value="Clients" <?php if ($f == 'Clients') {
                                echo "selected";
                            }?>>Clients
                            </option>
                            <option
                                value="Services" <?php if ($f == 'Services') {
                                echo "selected";
                            }?>>Services
                            </option>
                            <option
                                value="Both" <?php if ($f == 'Both') {
                                echo "selected";
                            }?>>Both
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" name="c" id="c">
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
                        <select class="form-control" name="b" id="b">
                            <option value="">Deal Type</option>
                            <option value="Tech" <?php if ($b == 'Tech') {
                                echo "selected";
                            }?>>Tech
                            </option>
                            <option value="Non-Tech" <?php if ($b == 'Non-Tech') {
                                echo "selected";
                            }?>>Non-Tech
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" name="i" id="i">
                            <option value="">Suited For</option>

                            <?php $__currentLoopData = $industries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $in): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>{
                            <option
                                value="<?php echo e($in->id); ?>" <?php echo e((Auth::user()->sponsor_industry == $in->id)?'selected':''); ?>><?php echo e($in->name); ?></option>
                            }
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <!-- <option value="Sales" <?php if ($i == 'Sales') {
                            echo "selected";
                        }?>>Sales
                            </option>
                            <option value="Marketing" <?php if ($i == 'Marketing') {
                            echo "selected";
                        }?>>Marketing
                            </option>
                            <option value="Finance" <?php if ($i == 'Finance') {
                            echo "selected";
                        }?>>Finance
                            </option>
                            <option value="Legal" <?php if ($i == 'Legal') {
                            echo "selected";
                        }?>>Legal
                            </option>
                            <option value="Administration" <?php if ($i == 'Administration') {
                            echo "selected";
                        }?>>Administration
                            </option>
                            <option value="HR" <?php if ($i == 'HR') {
                            echo "selected";
                        }?>>HR
                            </option> <option value="Operations" <?php if ($i == 'Operations') {
                            echo "selected";
                        }?>>Operations
                            </option> <option value="Others" <?php if ($i == 'Others') {
                            echo "selected";
                        }?>>Others
                            </option> -->
                        </select>
                    </div>
                    <div class="col-md-2">
                        <!-- <select class="form-control" name="cu" id="cu">
                            <option value="">Currency</option>
                            <?php if(!empty($Currency)): ?>
                                <?php $__currentLoopData = $Currency; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($cu->name); ?>" <?php if ($cdu == $cu->name) {
                                        echo "Selected";
                                    }?>> <?php echo e($cu->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </select> -->

                        <select class="form-control" name="refund" id="refund">
                            <option value=''>All</option>
                            <option value="NULL" <?php if ($refund == 'NULL') {echo "Selected";} ?>>Non-Refundable</option>
                            <option value="1" <?php if ($refund == '1') {echo "Selected";} ?>>Refundable</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary" data-tooltip="true" data-placement="top"  title="<?php echo $buttons[1]['hover']; ?>"
                        ><?php echo $buttons[1]['icon']; ?> <?php echo $buttons[1]['name']; ?>

                        </button> &nbsp;<a href="<?php echo e(url('partner/home')); ?>" class="btn btn-primary" data-tooltip="true" data-placement="top"  title="<?php echo $buttons[2]['hover']; ?>"
                        ><?php echo $buttons[2]['icon']; ?> <?php echo $buttons[2]['name']; ?></a></div>
                </form>
            </div>
            <br>
            <div class="row">
                <table id="table_id" class="display">
                    <thead>
                    <tr>
                        <th>Offer For</th>
                        <th>Deal Type</th>
                        <th>Suited For</th>
                        <th>Core Offer</th>
                        <!-- <th>Discount (%)</th>
                       
                        <th>Currency</th>
                        <th>Ayojn's Incentive(%)</th> -->
                        <th>Available In</th>
                                    <th>Quantity</th>
                        <th>Status</th>
                        <th>Share</th>
                        <th>Weblink</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(!empty($offers)): ?>
                        <?php $__currentLoopData = $offers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $offer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                $cnt =  \DB::table('offers_order_item')->where('offer_id',$offer->id)->where('status',0)->count();
                                ?>
                            <tr>
                                <td><?php echo e($offer->offer_for); ?></td>
                                <td><?php echo e($offer->identity); ?></td>
                                <td><?php echo e((!empty($offer->industry) ? $offer->industry->name :'-')); ?></td>
                                <td><?php echo e($offer->title); ?></td>
                                <!-- <td><?php echo e($offer->discount); ?></td>
                                <td><?php echo e($offer->currency??''); ?></td>
                                <td><?php echo e($offer->incentive??''); ?></td> -->
                                <td><?php echo e($offer->country->country_name??''); ?></td>
                                <td><?php echo e($offer->qty); ?></td>
                                <td>
                                    <?php if($offer->admin_status == 0): ?>
                                        Pending

                                    <?php elseif($cnt > 0): ?>
                                        Active
                                    <?php else: ?>
                                        <input type="checkbox"
                                               onchange='changeOffer(<?php echo e($offer->id); ?>,"<?php echo e($offer->status); ?>")'
                                               name="toggle-event-bid" data-toggle="toggle" data-on="On"
                                               data-off="Off" <?php echo e(($offer->status == 'On')?'checked':''); ?>>
                                    <?php endif; ?>

                                </td>

                                <td><span id="offer_<?php echo e($offer->id); ?>" class="share-course-filed"
                                          style="display: none"> <?php echo e(url('offer-share')."/" . $offer->share_id); ?></span>
                                    <a href="javascript:void(0)" class="btn btn-primary btn-xs read-more-btn  share-opp-btn" data-tooltip="true" data-placement="top"  title="<?php echo $buttons[3]['hover']; ?>"
                                        onclick="copyToClipboard('#offer_<?php echo e($offer->id); ?>')"><?php echo $buttons[3]['icon']; ?> <?php echo $buttons[3]['name']; ?></a></td>
                                <td>
                                    <?php if(!empty($offer->weblink)): ?>
                                        <a class="btn btn-primary btn-xs" href="<?php echo e($offer->weblink); ?>" target="_blank" data-tooltip="true" data-placement="top"  title="<?php echo $buttons[4]['hover']; ?>"><?php echo $buttons[4]['icon']; ?> <?php echo $buttons[4]['name']; ?></a>
                                    <?php endif; ?>
                                </td>
                                <td><a href="<?php echo e(route('offer-details',$offer->id)); ?>" class="btn btn-success btn-xs" data-tooltip="true" data-placement="top"  title="<?php echo $buttons[5]['hover']; ?>"><?php echo $buttons[5]['icon']; ?> <?php echo $buttons[5]['name']; ?>


                                  <span style="background: #000; width: 20px; height: 20px; color: #fff;
    border-radius: 100%; font-size: 13px; display: block; text-align: center; line-height: 20px; position: absolute;  margin-top:-30px;  right: 20px;"><?php echo e($cnt); ?></span>

</a></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>




        


    </section>
    <script src="<?php echo e(asset('js/jquery1.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/bootstrap.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/typed.min.js')); ?>"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.0/clipboard.min.js"></script>
    <script>
        $(".share-opp-btn").on('click', function (e) {
            toastr.success('Web Link Copied', 'Success');
        });
  
  // var $jq = jQuery.noConflict();
  $(document).ready(function () {
    $('.your-toggle-class').bootstrapToggle();

  });
       

        function copyToClipboard(element) {
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val($(element).text()).select();
            document.execCommand("copy");
            $temp.remove();
        }

        function changeOffer(id, status) {
            $.ajax({
                type: 'POST',
                url: '<?php echo e(url('partner/change-offer-status')); ?>',
                headers: {"X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"},
                data: {
                    status: status,
                    id: id
                },
                success: function (response) {
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
        }

        $(document).ready(function () {

            $("#table_id").DataTable({
                "order": [[ 1, "desc" ]]
            });
        });

        


    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>