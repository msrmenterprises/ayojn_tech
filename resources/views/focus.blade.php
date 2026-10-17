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
						<div class="text-container">
							<h1>Stories</h1>
							<h2>Stories which are Actionable & Results oriented.</h2>
							<a href="#" class="btn btn-primary-new" data-toggle="modal" data-target="#loginForm">Share your Story</a>
						</div>
					</div>
					<div class="col-lg-6 col-xs-12">
						<img src="images/focus.png" class="img-responsive"/>
					</div>
				</div>

				<div class="row section rtl">
					<div class="col-lg-6 col-xs-12">
						<div class="text-container">
							<h1>Storyteller</h1>
							<h2>Storytellers who are Competent & Trustworthy.</h2>
							<a href="#" class="btn btn-primary-new" data-toggle="modal" data-target="#loginForm">Meet a StoryTeller</a>
						</div>
					</div>
					<div class="col-lg-6 col-xs-12">
						<img src="images/focus2.png" class="img-responsive"/>
					</div>
				</div>
				<div class="row section">
					<div class="col-lg-6 col-xs-12">
						<div class="text-container">
							<h1>Medium</h1>
							<h2>Medium which is Effective
								& Scalable.
							</h2>
                            <a href="#" class="btn btn-primary-new" data-toggle="modal" data-target="#loginForm">Test a New Medium </a>
						</div>
					</div>
					<div class="col-lg-6 col-xs-12">
						<img src="images/foucs3.png" class="img-responsive"/>
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
            strings: ['an event', 'a campaign', 'a gig or', 'specific content ?'],
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
          dots:false,
          autoplay: true,
          autoplaySpeed: 2000,
        });

        $('#wrapper').removeClass('body-contianer only-scroll-content');


      })(jQuery);

	</script>
@endsection
