@extends('layout')
@php  $buttons = $db_buttons['home_page'];  @endphp
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

    {{--    <div class="banner-text">--}}
    {{--        <h1>Thinking of Sponsorship for <br><span class="typed-strings"></span><span class="typed-cursor">|</span></h1>--}}
    {{--        <h2>Let's amplify your Sponsorship Opportunities. </h2>--}}
    {{--        @if(Auth::guest())--}}
    {{--        <p class="btn-group-banner">--}}
    {{--            <a href="javascript:void(0)" data-toggle="modal" data-target="#loginForm">Login</a>--}}
    {{--            <a href="javascript:void(0)" class="signup"  data-toggle="modal" data-target="#signupForm">Sign up</a>--}}
    {{--        </p>--}}
    {{--        @endif--}}
    {{--    </div>--}}
    <div class="flw main-body">
        <div class="inner-main-body">
            <div class="container">
                
                <div class="col-hf">
                    <div class="banner-text">
                        <h1><br>
                            <span class="typed-strings">specific content</span>
                            <span class="typed-cursor">|</span>
                        </h1>
                        <h2>Search, Create and Book Marketing Opportunities. </h2>
                        @if(Auth::guest())
                            <a href="javascript:void(0)" class="{!!$buttons[0]['css']!!} btn btn-primary-new" data-toggle="modal"
                               data-target="#signupForm" data-tooltip="true" data-placement="top"  title="{!!$buttons[0]['hover']!!}">{!!$buttons[0]['icon']!!} {!!$buttons[0]['name']!!}</a>
                        @endif

                    </div>
                </div>
                <div class="col-hf">
                    <img src="{{ asset('images/home.png') }}" class="img-responsive"/>

                </div>
            </div>
        </div>
    </div>

    
 

    <script src="{{ asset('js/jquery1.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/typed.min.js') }}"></script>
    <!-- <script src="{{ asset('js/slick.min.js') }}"></script> -->

    <script>

        $(document).ready(function () {
            @if($showmodal==true)
            $('#loginForm').modal('show');
            @endif
        }); 
         

        (function ($) {

            if ($.fn.typed) {
                var $typedStrings = $('.typed-strings');
                $typedStrings.typed({
                    strings: ['Launching a Product', 'Venturing Out', 'Preparing a Go-to-Market Strategy', 'Creating a New Campaign', 'Outlining the Marketing Plan', 'Hiring a PR Agency or more'],
                    // strings: ['an event', 'a campaign', 'a gig or', 'specific content ?'],
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
