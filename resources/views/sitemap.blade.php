@extends('layout')

@section('content')
    <!-- banner section start -->

    <div class="banner-text">
        <!-- <h2><a href="#" data-toggle="modal" data-target="#sponsorrText"><b>Ayojn</b></a></h2> -->
         <h2><a href="#" data-toggle="modal" data-target="#ConnectText"><b>Connect</b></a></h2>
          <h2><a href="javascript:void(0)" data-toggle="modal" data-target="#loginForm"><b>Ayojn Login</b></a></h2>
           <h2><a href="javascript:void(0)" data-toggle="modal" data-target="#signupForm" class="signup"><b>Ayojn Register</b></a></h2>
           <h2><a href="#" data-toggle="modal" data-target="#TermsText"><b>Terms And Condition</b></a></h2>
            <h2><a href="#" data-toggle="modal" data-target="#privacyText"><b>Privacy and Policy</b></a></h2>

    </div>


<script src="{{ asset('js/jquery1.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/typed.min.js') }}"></script>

@endsection
