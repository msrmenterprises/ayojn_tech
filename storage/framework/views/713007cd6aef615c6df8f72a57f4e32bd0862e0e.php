<!--Add title-->
<?php $__env->startSection('title',  'Ayojn'); ?>
<!--Main Body content-->
<?php $__env->startSection('content'); ?>
    <?php
    use App\User;
    //$incompleteCount = User::whereNull('sponsor_for')->where('id', '!=', Auth::user()->id)->get()->count();
    $incompleteCount = User::where('userstatus',0)->where('id', '!=', Auth::user()->id)->get()->count();
    $inCompleteofferCount = User::whereNull('sponsor_for')->where('sponsor_type', '=', '1')
        ->where('id', '!=', Auth::user()->id)->get()->count();
    $inCompleteSponsorCount = User::whereNull('sponsor_for')->where('sponsor_type', '=', '2')
        ->where('id', '!=', Auth::user()->id)->get()->count();
    $userCount = User::where('sponsor_type', '!=', '3')->where('is_edited', 1)->get()->count();
    $partnerUserCount = User::where('sponsor_type', '=', '3')->where('is_edited', 1)->get()->count();

    $beneficiary = DB::table('beneficiary')->get()->count();

    $payment = DB::table('direct_payment')->get()->sum('transaction_amount');

    

    $opportunity_flag = DB::table('flag_activity')->where('flag_type','opportunities')->get()->count();;
    $bid_flag = DB::table('flag_activity')->where('flag_type','bid')->get()->count();
    $ayojn_earning = DB::table('direct_payment')->where('status_desc','SUCCESS')->get()->sum('ayojn_earn');
    ?>
    <!-- //banner-top -->
    <!-- banner -->
    <!-- Content Header (Page header) -->
    <style type="text/css">
        .mb20 {
            margin-bottom: 20px;
        }

        .checkbox {
            margin-top: 0px;
        }
    </style>
    <!-- Main content -->
    <section class="content">
        <div class="row">

        <?php if(Auth::guard('controlpanel')->user()->role_id == 1): ?>
            <div class="col-md-4 col-sm-6 col-xs-12">
                <a href="<?php echo e(url('/controlpanel/user-list?type=offer')); ?>">
                    <div class="info-box">
                        <span class="info-box-icon bg-aqua"><i class="ion ion-ios-people-outline"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Total Offer</span>
                            <!-- <span class="info-box-number"><?php echo e($inCompleteofferCount); ?>/<?php echo e($offercount); ?></span> -->
                            <span class="info-box-number"><?php echo e($offercount); ?></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </a>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <!-- /.col -->

            <!-- fix for small devices only -->
            <div class="clearfix visible-sm-block"></div>

            <div class="col-md-4 col-sm-6 col-xs-12">
                <a href="<?php echo e(url('/controlpanel/user-list?type=manage')); ?>">
                    <div class="info-box">
                        <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Total Manage </span>
                            <!-- <span class="info-box-number"><?php echo e($inCompleteSponsorCount); ?>/<?php echo e($managecount); ?></span> -->
                            <span class="info-box-number"><?php echo e($managecount); ?></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </a>
                <!-- /.info-box -->
            </div>

            <div class="clearfix visible-sm-block"></div>

            <div class="col-md-4 col-sm-6 col-xs-12">
                <a href="<?php echo e(url('/controlpanel/user-list?type=incomplete')); ?>">
                    <div class="info-box">
                        <span class="info-box-icon bg-red"><i class="ion ion-ios-people-outline"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Total Incomplete </span>
                            <span class="info-box-number"><?php echo e($incompleteCount); ?></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </a>
                <!-- /.info-box -->
            </div>

            <div class="clearfix visible-sm-block"></div>

            <div class="col-md-4 col-sm-6 col-xs-12">
                <a href="<?php echo e(url('controlpanel/review-list')); ?>">
                    <div class="info-box">
                        <span class="info-box-icon bg-aqua"><i class="ion ion-ios-people-outline"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Total User Updates </span>
                            <span class="info-box-number"><?php echo e($userCount); ?></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </a>
                <!-- /.info-box -->
            </div>
            <div class="clearfix visible-sm-block"></div>

            <div class="col-md-4 col-sm-6 col-xs-12">
                <a href="<?php echo e(url('controlpanel/partner-review-list')); ?>">
                    <div class="info-box">
                        <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Total Partner Updates </span>
                            <span class="info-box-number"><?php echo e($partnerUserCount); ?></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </a>
                <!-- /.info-box -->
            </div>
            <?php endif; ?>

            <?php if(Auth::guard('controlpanel')->user()->role_id == 2): ?>
            <div class="col-md-4 col-sm-6 col-xs-12">
                <a href="<?php echo e(url('controlpanel/all-beneficiary')); ?>">
                    <div class="info-box">
                        <span class="info-box-icon bg-blue"><i class="ion ion-ios-people-outline"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Total Beneficiary </span>
                            <span class="info-box-number"><?php echo e($beneficiary); ?></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </a>
                <!-- /.info-box -->
            </div>


            <div class="col-md-4 col-sm-6 col-xs-12">
                <a href="<?php echo e(route('controlpanel.all-beneficiary-payment', 0)); ?>">
                    <div class="info-box">
                        <span class="info-box-icon bg-green"><i class="fa fa-usd"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Total Payment </span>
                            <span class="info-box-number"><?php echo e($payment); ?></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </a>
                <!-- /.info-box -->
            </div>



            <div class="col-md-4 col-sm-6 col-xs-12">
                <a href="<?php echo e(url('controlpanel/opportunity-list')); ?>">
                    <div class="info-box">
                        <span class="info-box-icon bg-red"><i class="fa fa-flag"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Opportunity Flag </span>
                            <span class="info-box-number"><?php echo e($opportunity_flag); ?></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </a>
                <!-- /.info-box -->
            </div>



            <div class="col-md-4 col-sm-6 col-xs-12">
                <a href="<?php echo e(url('controlpanel/bid-list')); ?>">
                    <div class="info-box">
                        <span class="info-box-icon bg-red"><i class="fa fa-flag"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Bids Flag </span>
                            <span class="info-box-number"><?php echo e($bid_flag); ?></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </a>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->


            <div class="col-md-4 col-sm-6 col-xs-12">
                <a href="#">
                    <div class="info-box">
                        <span class="info-box-icon bg-red"><i class="fa fa-flag"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Ayojn Earning </span>
                            <span class="info-box-number"><?php echo e($ayojn_earning); ?></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </a>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
       <?php endif; ?>
        <!-- /.col -->
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>