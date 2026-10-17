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
				<div class="col-hf">
					<div class="banner-text">
						<h1>At Ayojn we work with  <br>
							<span class="typed-strings">Governments</span>
							<span class="typed-cursor">|</span>
						</h1>
						<h2>To help them discover Opportunities via Sponsored Outreach. </h2>

						<a href="javascript:void(0)" data-toggle="modal" data-target="#loginForm" class="btn btn-primary-new">Discover More</a>


					</div>
				</div>
				<div class="col-hf">

					<img src="images/expert.png" class="img-responsive"/>

					<!-- <div class="slider-main">
						<h1 class="heading-right">Patrons we <img style="display:inline;" src="images/love.png"/> at Ayojn</h1>
						 <div class="slider-inner">
							 <div>
								 <ul class="client-logos">
									 <li><img src="images/hc1.png"/></li>
									 <li><img src="images/hc2.png"/></li>
									 <li><img src="images/hc3.png"/></li>
									 <li><img src="images/hc4.png"/></li>
									 <li><img src="images/hc5.png"/></li>
									 <li><img src="images/hc6.png"/></li>
								 </ul>
							 </div>
							 <div>
								 <ul class="client-logos">
								 <li><img src="images/hc7.png"/></li>
								 <li><img src="images/hc8.png"/></li>
								 <li><img src="images/hc9.png"/></li>
								 <li><img src="images/hc10.png"/></li>
								 <li><img src="images/hc11.png"/></li>
								 <li><img src="images/hc12.png"/></li>
								 </ul>
							 </div>
						 </div>
					</div>
					-->
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
            strings: ['Governments', 'Businesses', 'Nonprofits', 'Individuals'],
            typeSpeed: 130,
            loop: true,
            showCursor: false
          });
        }

		else {
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
