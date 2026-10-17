@extends('layout')

@section('content')
    <!-- banner section start -->


        <br><br><br><br>
        <section id="feed" class="speakers" >
  <div class="container">
    <div class="row">
		{{--<a href="javascript:void(0)" onclick="openreferafriend()">Refer A Friend</a>--}}
		<br>
    	{{--<h2 class="text-center">Thank you for the inputs please wait as you're bring directed to the finish page.</h2>--}}
		<!-- <h2 class="text-center">Thank you for sharing your inputs.<br> While we validate the details how about you have a read on how we are building a momentum for Ayojn.</h2> -->
{{--    <h2 class="text-center">Thank you for sharing your inputs.<br> While we validate the details how about you recommend Ayojn to a few colleagues/peers who could use it and you may access Ayojn for the next 12 months.</h2>--}}

		<h2 class="text-center"><br/><br/><br/>Thank you for signing up. <br/><br/>Please check your inbox, we just sent you an email to verify your email address. </h2>
    	<hr>
      {{--<h2 class="block-title text-center"><strong>Live</strong> Feed</h2>

	  <div class="col-md-12 col-sm-12 col-xs-12" style="height:1500px;overflow: scroll;">
        <a class="twitter-timeline" href="https://twitter.com/TeamSponsorr"></a> <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>

      </div> --}}
    </div>

  </div>
</section>


<script src="{{ asset('js/jquery1.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/typed.min.js') }}"></script>

@endsection
