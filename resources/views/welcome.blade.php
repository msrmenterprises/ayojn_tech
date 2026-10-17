@extends('layout')

@section('content')
	<!-- banner section start -->

	{{--    <div class="banner-text">--}}
	{{--        <h1>Thinking of sponsoring <span class="typed-strings"></span><span class="typed-cursor">|</span></h1>--}}
	{{--        <h2>Let's do a bit of market check before you Ayojn. </h2>--}}
	{{--        <p class="btn-group-banner">--}}
	{{--            <a href="javascript:void(0)" data-toggle="modal" data-target="#loginForm">Login</a>--}}
	{{--            <a href="javascript:void(0)" class="signup"  data-toggle="modal" data-target="#signupForm">Sign up</a>--}}
	{{--        </p>--}}
	{{--    </div>--}}
	<div class="flw main-body">
		<div class="inner-main-body">
			<div class="col-hf">
				<div class="banner-text">
					<h1>Thinkings of sponsoring <br>
						<span class="typed-strings">specific content</span>
						<span class="typed-cursor">|</span>
					</h1>
					<h2>Let's Calibrate your Outreach & the RoI1.</h2>
					<p class="btn-group-banner">
						<a href="javascript:void(0)" data-toggle="modal" data-target="#loginForm">Login</a>
						<a href="javascript:void(0)" class="signup" data-toggle="modal" data-target="#signupForm">Sign
							up</a>
					</p>bid
				</div>
			</div>
			<div class="col-hf">
				<ul class="client-logos">
					<li><img src="{{ asset('images/client-logo1.png') }}"/></li>
					<li><img src="{{ asset('images/client-logo2.png') }}"/></li>
					<li><img src="{{ asset('images/client-logo3.png') }}"/></li>
					<li><img src="{{ asset('images/client-logo4.png') }}"/></li>
					<li><img src="{{ asset('images/client-logo1.png') }}"/></li>
					<li><img src="{{ asset('images/client-logo2.png') }}"/></li>


				</ul>
			</div>
		</div>
	</div>


	<script src="{{ asset('js/jquery1.min.js') }}"></script>
	<script src="{{ asset('js/bootstrap.min.js') }}"></script>
	<script src="{{ asset('js/typed.min.js') }}"></script>
	<script>


      (function ($) {

        if ($.fn.typed) {
          var $typedStrings = $('.typed-strings');
          $typedStrings.typed({
            strings: ['an event', 'a campaign', 'a sports team', 'a gig', 'specific content'],
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

        })

      })(jQuery);

	</script>
@endsection
