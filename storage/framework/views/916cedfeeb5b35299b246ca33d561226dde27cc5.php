<!-- footer section start-->
<footer
    class="flw footer-main <?php echo e((request()->path() == '/' || request()->path() == 'expertise' || request()->path() == 'connect' || ! empty(Auth::user()) ) ? 'fixed-footer' : ''); ?>">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="pull-left">


                    <!--a href="#" data-toggle="modal" data-target="#sponsorrText">&copy; Ayojn </a-->
                    <a href="javascript:void(0)">&copy; Ayojn </a>
                    <a href="#" data-toggle="modal" data-target="#TermsText">Terms</a>
                    <a href="#" data-toggle="modal" data-target="#privacyText">Privacy</a>
                    <a href="<?php echo e(url('sitemap')); ?>">Sitemap</a>
                    <!--<a href="javascript:void(0)">Sponsorr is a discovery platform for Sponsorship Opportunities</a> -->


                    <!-- </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"> -->
                    <!-- <a href="https://twitter.com/TeamSponsorr" target="_blank">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 611.999 497.119">
                                <path id="Path_11" data-name="Path 11" class="cls-1" d="M612,116.258a250.714,250.714,0,0,1-72.088,19.772A126.067,126.067,0,0,0,595.1,66.619,253.1,253.1,0,0,1,515.321,97.1,125.643,125.643,0,0,0,301.391,211.56,356.431,356.431,0,0,1,42.641,80.386,125.692,125.692,0,0,0,81.5,247.966,125.556,125.556,0,0,1,24.629,232.21v1.568a125.671,125.671,0,0,0,100.693,123.1,127.165,127.165,0,0,1-33.08,4.4,120.5,120.5,0,0,1-23.634-2.333,125.612,125.612,0,0,0,117.253,87.194A251.888,251.888,0,0,1,29.945,499.8,266.812,266.812,0,0,1,0,498.075,354.876,354.876,0,0,0,192.439,554.56c230.948,0,357.188-191.291,357.188-357.188l-.421-16.253A250.7,250.7,0,0,0,612,116.258Z" transform="translate(-0.001 -57.441)"/>
                            </svg>
                        </a> -->
                    <div class="smofooter">
                        <a href="https://x.com/ayojnconnect" target="_blank">
                            <i class="fa  fa-twitter"></i></a>
                        <a href="https://www.linkedin.com/company/ayojn/" target="_blank">
                            <i class="fa  fa-linkedin"></i></a>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- footer section end-->
<script>
    $(document).ready(function () {
        $('.site-header .navbar-toggle').click(function () {
            $('.mobile-nav-final').slideToggle();
            $('.overflowmob').slideToggle();
        });

        $('.main-nav li.menu-hover').click(function () {
            $(this).children('ul').slideToggle();
        });

    });
</script>
