<?php $__env->startSection('content'); ?>

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
    
    <!-- banner section start -->
     <style>
        .btn-sm{padding: 5px 10px !important;}
     </style>
    <section>
        <div class="container" style="padding-top: 15px;">
            <div class="row">
                <h3 style="text-align: center;margin-top:110px; line-height:35px; display:none;">Any deal you pick from here will let you avail a discount of 30% on Ayojn’s next service fee.
                <br/>
                <span style="color:red;">Wallet Balance : <b><?php echo e(Auth::user()->wallet_balance); ?> points</b>(1 Point = 1 USD) </span>
                </h3>


                <h3 style="text-align: center;margin-top:110px; line-height:35px; display:none;">
                <span style="color:red;">Wallet Balance : <b><?php echo e(Auth::user()->wallet_balance); ?> points</b>(1 Point = 1 USD) </span>
                </h3>
                
            </div>

            
            <div class="row">
                <div class="col-md-12" style="padding-top: 15px;">
                    <?php if(session()->has('success')): ?>

                        <div class="alert alert-success alert-dismissible show" role="alert">
                        <strong>Success!</strong> <?php echo e(session()->get('success')); ?>

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>

                       
                    <?php endif; ?>

                    <?php if(session()->has('error')): ?>

                        <div class="alert alert-danger alert-dismissible show" role="alert">
                        <strong>Warning!</strong> <?php echo e(session()->get('error')); ?>

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>


                        <?php endif; ?>
                </div>
            </div>
            <br>
            <div class="row">
                <form name="search">
                    <div class="col-md-2">
                        <select class="form-control" name="f">
                            <option value="">Deal Amount</option>
                            <option value="Free" <?php if ($f == 'Free') { echo "selected"; }?>>Free</option>
                            <option value="Paid" <?php if ($f == 'Paid') { echo "selected"; }?>>Paid</option>
                            <option value="All">All</option>
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
                        <button type="submit" class="btn btn-primary"
                        >Search
                        </button> &nbsp;<a href="<?php echo e(url('offers')); ?>" title="refresh" class="btn btn-primary"
                        ><i class="fa fa-refresh" aria-hidden="true"></i></a></div>
                </form>
            </div>
            <br>
            <div class="row">
                <table id="table_id" class="display" style="width:100%">
                    <thead>
                    <tr>
                        <th>Deal Type</th>
                        <th>Suited For</th>
                        <th>Core Offer</th>
                        <!-- <th>Discount (%)</th> -->
                        <th>Deal Amount</th>
                        <!-- <th>Currency</th> -->
                        <th>Available In</th>
                        <th>Quantity</th>
                        <th>Weblink</th>

                        <th style="text-align:center;"></th>
                        <th style="text-align:center;"></th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php if(!empty($offers)): ?>
                        <?php $__currentLoopData = $offers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $offer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <?php
                        $usertype = Auth::user()->user_type;
                        $typename = \DB::table('user_type')->where('id',$usertype)->value('user_type');
                        $newtypename = "deal_amount_".$typename;
                        $free = '<span style="color:red; font-weight:bold;">Free</span>';
                        ?>
                    

                            <tr>
                                <td><?php echo e($offer->identity); ?></td>
                                <td><?php echo e((!empty($offer->industry) ? $offer->industry->name :'-')); ?></td>
                                <td><?php echo e($offer->title); ?></td>
                                <!-- <td><?php echo e($offer->discount); ?></td> -->
                                <td><?php echo (!empty($offer->$newtypename) ? config('sitevars.data.currency') .  $offer->$newtypename : $free); ?></td>

                                <!-- <td><?php echo e($offer->currency??''); ?></td> -->

                                <td><?php echo e($offer->country->country_name??''); ?></td>
                                <td><?php echo e($offer->qty??''); ?></td>
                                <td>
                                    <?php if(!empty($offer->weblink)): ?>

                                        <a class="btn" href="<?php echo e($offer->weblink); ?>" target="_blank"><i class="fa fa-link"></i> Open Link</a>

                                    <?php endif; ?>
                                </td>

                                <td><a href="<?php echo e(route('add_to_cart', $offer->id)); ?>" class="btn btn-success add-to-cart"><i class="fa fa-shopping-cart"></i> Add to Cart</a></td>
                                <td><a href="<?php echo e(route('add_to_wish', $offer->id)); ?>" class="btn btn-primary add-to-wish"><i class="fa fa-heart"></i> Add to Wishlist</a></td>

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
             $('#table_id').DataTable();
         });

         $(document).on('submit','form',function(){
                $(".loader").show();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>