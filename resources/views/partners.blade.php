@extends('layout')

@section('content')
    <!-- banner section start -->
    <link rel="stylesheet" href="{{ asset('css/slick-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/slick.css') }}">
    <style>
        .body-contianer.only-scroll-content {
            overflow: hidden;
            padding-bottom: 0;
        }
    </style>
    <div class="flw main-body">
        <div class="inner-main-body">
            <div class="container">

                <div class="row section">
                    <div class="col-lg-6 col-xs-12">
                        <div class="banner-text">
                            <h1>We are always keen to partner with tools & services providers who could enable users at
                                <br>
                                <span class="typed-strings">Ayojn for success.</span>
                                <span class="typed-cursor">|</span>
                            </h1>
                            <!-- <h2>Let's Calibrate your Outreach & the RoI. </h2> -->


                            <!-- <h2>Let's Calibrate your Outreach & the RoI. </h2> -->

                            <a href="{{ url('partner/login') }}" class="btn btn-primary-new">Join us</a>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xs-12">
                        <img src="images/partners.png" class="img-responsive"/>
                    </div>
                </div>


                <div class="row section">
                    <div class="col-lg-12 col-xs-12 ">
                        <div class="text-container">
                            <h2 class=" text-center">Partners we <i>trust</i> to deliver the Promise</h2>
                        </div>

                        <ul class="client-logos-new ">
                            <li><img src="images/logo/1.png"/></li>
                            <li><img src="images/logo/2.png"/></li>
                            <li><img src="images/logo/3.png"/></li>
                            <li><img src="images/logo/media.png"/></li>
                            <li><img src="images/logo/tbi.world_horizontal.png"/></li>
                        </ul>


                    </div>

                </div>
            </div>

        </div>
    </div>


    <script src="{{ asset('js/jquery1.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/typed.min.js') }}"></script>
    <script src="{{ asset('js/slick.min.js') }}"></script>
    <script>


        (function ($) {
            if ($.fn.typed) {
                var $typedStrings = $('.typed-strings');
                $typedStrings.typed({
                    strings: ['Ayojn for success.',],
                    typeSpeed: 130,
                    loop: true,
                    showCursor: false
                });
            } else {
                console.log('Animated typing: Plugin "typed" is not loaded.');
            }

//$(document).on('click','.nextOption',function(){
            //$(this).parent('.formClass').hide();
            //$(this).parent('.formClass').next('.formClass').show();

//})


            $(document).on('click', '.secondFormNext', function () {

            });

            //slider
            $('.slider-main .slider-inner').slick({
                infinite: true,
                slidesToShow: 1,
                slidesToScroll: 1,
                arrows: false,
                dots: false,
                autoplay: true,
                autoplaySpeed: 2000,
            });

            $('#wrapper').removeClass('body-contianer only-scroll-content');


        })(jQuery);


    </script>
@endsection
