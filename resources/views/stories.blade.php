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
    <div class="flw main-body stories">
        <div class="inner-main-body">
            <div class="container">
                <div class="row section">
                    <div class="col-lg-6 col-xs-12">
                        <div class="banner-text">
                            {{--							<h1 class="black-color m-0">--}}
                            {{--                                Team at Ayojn delivered success for hundreds of users and everytime it was a Tale to share. Talk to us and we will be glad to share more with you. <br>--}}

                            {{--							</h1>--}}
                            <h1 class="black-color m-0" style="font-weight: 300 !important; ">
                                Users at Ayojn experience success measured at various levels, most of the time it’s overwhelming. We would be glad to share such stories with you during the <span style="color:#5576C3">Discovery</span> call once you are in.
                            </h1>
                            <!-- <h2>Let's Calibrate your Outreach & the RoI. </h2> -->

                            <a href="javascript:void(0)" class="btn btn-primary-new" data-toggle="modal"
                               data-target="#loginForm">Join us</a>


                        </div>
                    </div>
                    <div class="col-lg-6 col-xs-12">
                        <img src="images/stories.png" class="img-responsive"/>
                    </div>
                </div>

                <div class="row section">
                    <div class="col-lg-12 col-xs-12 ">
                        <div class="text-container">
                            <h2 class="text-center">Users we <i>admire</i> at Ayojn</h2>
                        </div>

                        <ul class="client-logos-new ">
                            <li><img src="images/hc1.png"/></li>
                            <li><img src="images/hc2.png"/></li>
                            <li><img src="images/hc3.png"/></li>
                            <li><img src="images/hc4.png"/></li>
                            <li><img src="images/hc5.png"/></li>
                            <li><img src="images/hc6.png"/></li>
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
                    strings: ['connect at ayojn.com', ' You can also follow us on ', 'Twitter, LinkedIn, Quora', 'and Medium '],
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
//slider
            $('.client-logos-new').slick({
                infinite: true,
                slidesToShow: 4,
                slidesToScroll: 1,
                arrows: true,
                dots: false,
                autoplay: false,
                autoplaySpeed: 2000,
                responsive: [
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }
                ]


            });

        })(jQuery);

    </script>
@endsection
