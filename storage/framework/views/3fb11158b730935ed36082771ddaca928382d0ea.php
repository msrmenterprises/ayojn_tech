<?php $__env->startSection('adminlte_css'); ?>
    <link rel="stylesheet"
          href="<?php echo e(asset('vendor/adminlte/dist/css/skins/skin-black-light' . '.min.css')); ?> ">
    <?php echo $__env->yieldPushContent('css'); ?>
    <?php echo $__env->yieldContent('css'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('body_class', 'skin-black-light' . ' sidebar-mini ' . (config('adminlte.layout') ? [
    'boxed' => 'layout-boxed',
    'fixed' => 'fixed',
    'top-nav' => 'layout-top-nav'
][config('adminlte.layout')] : '') . (config('adminlte.collapse_sidebar') ? ' sidebar-collapse ' : '')); ?>

<?php $__env->startSection('body'); ?>
    <div class="wrapper">

        <!-- Main Header -->
        <header class="main-header">
            <?php if(config('adminlte.layout') == 'top-nav'): ?>
            <nav class="navbar navbar-static-top">
                <div class="container">
                    <div class="navbar-header">
                        <a href="<?php echo e(url(config('adminlte.dashboard_url', 'home'))); ?>" class="navbar-brand">
                            <?php echo config('adminlte.logo', '<b>Admin</b>LTE'); ?>

                        </a>
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                            <i class="fa fa-bars"></i>
                        </button>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                        <ul class="nav navbar-nav">
                            <?php echo $__env->renderEach('adminlte::partials.menu-item-top-nav', $adminlte->menu(), 'item'); ?>
                        </ul>
                    </div>
                    <!-- /.navbar-collapse -->
            <?php else: ?>
            <!-- Logo -->
            <a href="<?php echo e(url(config('adminlte.dashboard_url', 'home'))); ?>" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><?php echo config('adminlte.logo_mini', '<b>A</b>LT'); ?></span>
                <!-- logo for regular state and mobile devices -->
                <!-- <span class="logo-lg"><?php echo config('adminlte.logo', '<b>Admin</b>LTE'); ?></span> -->
                <span class="logo-lg"><b>Ayojn</b></span>
            </a>

            <!-- Header Navbar -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only"><?php echo e(trans('adminlte::adminlte.toggle_navigation')); ?></span>
                </a>
            <?php endif; ?>
                <!-- Navbar Right Menu -->
                <div class="navbar-custom-menu">

                    <ul class="nav navbar-nav">
                        <li class="dropdown user user-menu">
                            <!-- Menu Toggle Button -->
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <!-- The user image in the navbar-->
                                <?php if(Auth::guard('controlpanel')->user()->profile_pic): ?>
                                <img src="<?php echo e(asset("/profile_image"."/".Auth::guard('controlpanel')->user()->profile_pic)); ?>" class="user-image" alt="User Image"/>
                                <?php else: ?>
                                <img src="<?php echo e(asset("/images/user2.jpg")); ?>" class="user-image" alt="User Image"/>
                                <?php endif; ?>
                                <!-- hidden-xs hides the username on small devices so only the image appears. -->
                                <span class="hidden-xs">  <?php if(Auth::guard('controlpanel')->check()): ?>
                                        <?php echo e(Auth::guard('controlpanel')->user()->name); ?>

                                    <?php elseif(Auth::guard('user')->check()): ?>
                                        <?php echo e(Auth::User()->name); ?>

                                    <?php endif; ?></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- The user image in the menu -->
                                <li class="user-header">
                                    <?php if(Auth::guard('controlpanel')->user()->profile_pic): ?>
                                        <img src="<?php echo e(asset("/profile_image"."/".Auth::guard('controlpanel')->user()->profile_pic)); ?>" class="img-circle" alt="User Image"/>
                                    <?php else: ?>
                                        <img src="<?php echo e(asset("/images/user2.jpg")); ?>" class="user-image" alt="User Image"/>
                                    <?php endif; ?>
                                    <p>
                                        <?php if(Auth::guard('controlpanel')->check()): ?>
                                            <a href="<?php echo e(route('editprofile')); ?>" title="Edit Profile"> <?php echo e(Auth::guard('controlpanel')->user()->name); ?></a>
                                        <?php elseif(Auth::guard('user')->check()): ?>
                                            <?php echo e(Auth::User()->name); ?>

                                        <?php endif; ?>
                                    </p>
                                </li>
                                <!-- Menu Body -->
                            
                            <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">

                                        <a href="<?php echo e(route('changepassword')); ?>" class="btn btn-default btn-flat">Change Password</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="#"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-default btn-flat">
                                            <i class="fa fa-fw fa-power-off"></i> <?php echo e(trans('adminlte::adminlte.log_out')); ?>

                                        </a>
                                        <?php if(Auth::guard('controlpanel')->check()): ?>
                                            <form id="logout-form" action="<?php echo e(url('/controlpanel/logout')); ?>" method="POST" style="display: none;">
                                                <?php else: ?>
                                                    <form id="logout-form" action="<?php echo e(url('/controlpanel/logout')); ?>" method="POST" style="display: none;">
                                                        <?php endif; ?>
                                                        <?php if(config('adminlte.logout_method')): ?>
                                                            <?php echo e(method_field(config('adminlte.logout_method'))); ?>

                                                        <?php endif; ?>
                                                        <?php echo e(csrf_field()); ?>

                                                    </form>

                                    </div>
                                </li>
                            </ul>
                        </li>
                        
                    </ul>
                </div>
                <?php if(config('adminlte.layout') == 'top-nav'): ?>
                </div>
                <?php endif; ?>
            </nav>
        </header>

    <?php if(config('adminlte.layout') != 'top-nav'): ?>
        <!-- Left side column. contains the logo and sidebar -->
        
        <?php echo $__env->make('vendor.adminlte.partials.sidebar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php endif; ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <?php if(config('adminlte.layout') == 'top-nav'): ?>
            <div class="container">
            <?php endif; ?>

            <!-- Content Header (Page header) -->
            <section class="content-header">
                <?php echo $__env->yieldContent('content_header'); ?>
            </section>

            <!-- Main content -->
            <section class="content">

                <?php echo $__env->yieldContent('content'); ?>

            </section>
            <!-- /.content -->
            <?php if(config('adminlte.layout') == 'top-nav'): ?>
            </div>
            <!-- /.container -->
            <?php endif; ?>
        </div>
        <!-- /.content-wrapper -->

    </div>
    <!-- ./wrapper -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('adminlte_js'); ?>
    <script src="<?php echo e(asset('vendor/adminlte/dist/js/adminlte.min.js')); ?>"></script>
    <?php echo $__env->yieldPushContent('js'); ?>
    <?php echo $__env->yieldContent('js'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>