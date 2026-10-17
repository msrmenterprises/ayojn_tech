@if(!empty(Auth::user()))
@if(Auth::user()->userstatus==0)
<script type="text/javascript">
   $(document).ready(function(){

        var host = window.location.pathname.split("/")[1];
        var url = base_url + '/pending';
        if(host == 'pending'){
            return false;
        }else{
            window.location.href = url;
        }
       
   });
       
</script>
@endif
@endif

@php
    $buttons = $db_buttons['header'];  
   // print_r($buttons);  die;

@endphp

<style>
    .close{
        font-size: 30px;
    font-weight: 500;
    }
 .modal-open .tnc.modal, .modal-open .prvp.modal{
    overflow-x: hidden;
    overflow-y: inherit;
    }
    #TermsText .cms-content, #privacyText .cms-content{
        overflow: auto;
        height: 70vh;
    }
.modal {
        top: 0px !important;
    }
.main-nav li a {
         margin: 23px 10px 0px !important;
    }
</style>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<div class="overflowmob"></div>
<header class="flw site-header">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <a href="{{ url('/')}}" class="pull-left logo">
                    <img src="{{ asset('images/n-logo.png') }}"  />

                </a>
                <div class="navbar-default">
                    <button type="button" class="navbar-toggle">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="mobile-nav-final">
                    <a class="closed navbar-toggle"><img src="{{ asset('images/close.png') }}"></a>

                    @if(Auth::guest())

                        <ul class="pull-right main-nav">

                     


                            <li class="{{ (request()->path() == '/') ? 'active' : '' }}"><a href="{{ url('/') }}" class="{!!$buttons[0]['css']!!}" data-tooltip="true" data-placement="top"  title="{!!$buttons[0]['hover']!!}">{!! $buttons[0]['icon'] !!} {!! $buttons[0]['name'] !!}</a></li>
                            <!-- <li class="{{ (request()->path() == 'expertise') ? 'active' : '' }}"><a
                                    href="{{ url('expertise') }}"> Expertise</a></li>
                            <li class="{{ (request()->path() == 'focus') ? 'active' : '' }}"><a
                                    href="{{ url('focus') }}"> Focus</a></li>
                            <li class="{{ (request()->path() == 'solutions') ? 'active' : '' }}"><a
                                    href="{{ url('solutions') }}"> Solutions</a></li>
                            <li class="{{ (request()->path() == 'partners') ? 'active' : '' }}"><a
                                    href="{{ url('partners') }}"> Partners</a></li>
                            <li class="{{ (request()->path() == 'stories') ? 'active' : '' }}"><a
                                    href="{{ url('stories') }}"> Success Stories</a></li> -->
                        <!--<li class="{{ (request()->path() == 'connect') ? 'active' : '' }}"><a
                                        href="{{ url('events') }}">Events</a></li> -->
                            <!-- <li class="{{ (request()->path() == 'connect') ? 'active' : '' }}"><a
                                    href="{{ url('connect') }}"> Connect</a></li> -->
                            <li><a style="margin-top: 8px !important;" href="javascript:void(0)" class="{!!$buttons[1]['css']!!}" data-toggle="modal"
                                   data-target="#signupForm" data-tooltip="true" data-placement="top"  title="{!!$buttons[1]['hover']!!}">{!!$buttons[1]['icon']!!} {!!$buttons[1]['name']!!}</a></li>
                            <li><a href="javascript:void(0)" data-toggle="modal" data-target="#loginForm" class="{!!$buttons[2]['css']!!}" data-tooltip="true" data-placement="top"  title="{!!$buttons[2]['hover']!!}"> {!!$buttons[2]['icon']!!} {!!$buttons[2]['name']!!}</a>
                            </li>


                        </ul>

                    @else
                    
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                           class="pull-right login-button">
                            Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>

                        <ul class="pull-right main-nav">

                            @if(Auth::user()->userstatus == 1 && Auth::user()->is_suspend != 1)
                                @if((Auth::user()->sponsor_type == 1 || Auth::user()->sponsor_type == 2 || Auth::user()->sponsor_type == 3))
                            
                                    <?php
                                    $userData = Auth::user();

                                    $notifications = Test::where('in_app', 1)->where('is_read', 0)
                                    ->where('user_id', Auth::user()->id)->limit(15)
                                    ->orderBy('id', 'desc')->get();

                                    $cart_items = DB::table('cart')->where('user_id', Auth::user()->id)->get();
                                    $cart_count = count($cart_items);

                                    $heart_items = DB::table('wishlist')->where('user_id', Auth::user()->id)->get();
                                    $heart_count = count($heart_items)??0;

                                    ?>
                                   
                                    @if(Auth::user()->sponsor_type == 1)
                                        <li class="menu-hover">
                                            <a href="#">INDEX</a>
                                            <ul>
                                                <li><a href="{{ route('map') }}">Map</a></li>
                                                @if (!empty($userData->sponsor_for) || !empty($userData->likeSponsorr) || !empty($userData->sponsor_budget) || !empty($userData->sponsor_industry) || !empty($userData->sponsor_country))
                                                    <li><a href="javascript:void(0)" onclick="opentop_countries()">Emerging
                                                            From</a>
                                                    </li>
                                                    <li><a href="javascript:void(0)" onclick="opentop_destination()">Directed
                                                            To</a>
                                                    </li>
                                                    <li><a href="javascript:void(0)" onclick="opentop_industries()">Biggest
                                                            Spenders</a>
                                                    </li>
                                                    <li><a href="javascript:void(0)" onclick="opentop_recipients()">Top
                                                            Recipients</a></li>
                                                @endif
                                            </ul>
                                        </li>
                                        <li class="menu-hover">
                                            <a href="#"> OPPORTUNITIES</a>
                                            <ul>
                                                <li><a href="{{ route('opportunity')}}">Browse Opportunities <br/><br/>(Limited
                                                        Use)</a></li>
                                                <li><a href="{{ route('bid') }}">Create Opportunities<br/><br/>
                                                        (Payable)
                                                    </a></li>

                                            </ul>
                                        </li>

                                        <li><a href="{{ route('poffers') }}">MARKETPLACE </a></li>
                                        <li><a href="javascript:void(0)" onclick="openreferafriend()">RECOMMEND </a>
                                        <li class="menu-hover">
                                            <a href="#">ACCOUNT</a>
                                            <ul>
                                                <li><a href="javascript:void(0)"
                                                    onclick="openEditForm({{ Auth::user()->id }})">PROFILE</a></li>
                                                    <li><a href="javascript:void(0)"
                                                    onclick="openBankform({{ Auth::user()->id }})">My Bank Details</a></li>
                                                <li><a href="{{ route('notification-settings') }}">Notification Settings</a></li>
                                                <!--add by ram 29 sep -->
                                                <li><a href="{{ route('marketplace-history') }}">Marketplace History</a></li>
                                                <li><a href="{{ route('wallet-history') }}">Wallet History</a></li>
                                                <li><a href="{{ route('directpay') }}">Direct Payment History</a></li>
                                                <li><a href="{{route('ticket')}}">Manage Tickets</a></li>
                                            </ul>
                                        </li>
                                        <!-- <li><a href="{{ url('/offer/faq') }}">FAQs </a></li> -->

                                        <li class="dropdown">
                                        <a href="{{ route('wishlist') }}" style="position: relative;"> <i class="fa fa-heart" style="font-size:20px; color:red;"></i> <span
                                        class="nober">{{ $heart_count }}</span></a>                                      
                                        </li>

                                        <li class="dropdown">
                                        <a href="{{ route('cart') }}" style="position: relative;"> <i class="fa fa-shopping-cart" style="font-size:20px; color:green;"></i> <span
                                        class="nober">{{ $cart_count }}</span></a>                                      
                                        </li>


                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                   aria-haspopup="true" aria-expanded="false"> <i class="fa fa-bell" style="font-size:20px; color:orange;"></i> <span
                                        class="nober">{{ $notifications->count() }}</span></a>
                                <ul class="dropdown-menu notify-drop">
                                    <div class="notify-drop-title">
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6 col-xs-6">Notifications
                                                (<b>{{ $notifications->count() }}</b>)
                                            </div>

                                        </div>
                                    </div>
                                    <!-- end notify title -->
                                    <!-- notify content -->
                                    <div class="drop-content">
                                        @if(!empty($notifications->first()))
                                            @foreach($notifications as $notification)
                                                <li>

                                                    <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12 ">
                                                        <p>{{ strip_tags($notification->message) }}
                                                            <br>
                                                            @if(Auth::user()->userstatus == 1)<a
                                                                href="{{ $notification->link }}"
                                                                onclick="updateStatusNotification({{ $notification->id }})">view</a>@endif
                                                        </p>

                                                    </div>
                                                </li>
                                            @endforeach
                                        @endif
                                    </div>

                                    <div class="notify-drop-footer text-center" >
                                    <a href="{{route('usernotification')}}" style="margin-top:9px !important;"><i class="fa fa-eye"></i> View All</a>
                                    </div>
                                  
                                   
                                  
                                </ul>
                            </li>

                                    @elseif(Auth::user()->sponsor_type == 2 )
                                        <li class="menu-hover">
                                            <a href="#">INDEX</a>
                                            <ul>
                                                <li><a href="{{ route('map') }}">Map</a></li>
                                                @if (!empty($userData->sponsor_for) || !empty($userData->sponsor_budget) || !empty($userData->sponsor_country))
                                                    <li><a href="javascript:void(0)" onclick="opentop_countries()">Emerging
                                                            From</a>
                                                    </li>
                                                    <li><a href="javascript:void(0)" onclick="opentop_destination()">Directed
                                                            To</a>
                                                    </li>
                                                    <li><a href="javascript:void(0)" onclick="opentop_industries()">Biggest
                                                            Spenders</a>
                                                    </li>
                                                    <li><a href="javascript:void(0)" onclick="opentop_recipients()">Top
                                                            Recipients</a></li>
                                                @endif
                                            </ul>
                                        </li>
                                        <li class="menu-hover">
                                            <a href="#"> OPPORTUNITIES</a>
                                            <ul>
                                                <li><a href="{{ route('bid') }}">Bid Opportunities <br/><br/>(Limited
                                                        use)</a></li>
                                                <li><a href="{{ route('opportunity')}}">Build Opportunities <br/><br/>(Payable)
                                                    </a>
                                                </li>
                                                <li><a href="{{ route('collaborate') }}">Collaborate<br/><br/>(Free to use)
                                                    </a></li>
                                            </ul>
                                        </li>
                                        <li><a href="{{ route('poffers') }}">MARKETPLACE </a></li>
                                        <li><a href="javascript:void(0)" onclick="openreferafriend()">RECOMMEND </a></li>
                                        <li class="menu-hover">
                                            <a href="#">ACCOUNT</a>
                                            <ul>
                                                <li><a href="javascript:void(0)"
                                                    onclick="openEditForm({{ Auth::user()->id }})">PROFILE</a></li>
                                                    <li><a href="javascript:void(0)"
                                                    onclick="openBankform({{ Auth::user()->id }})">My Bank Details</a></li>
                                                <li><a href="{{ route('notification-settings') }}">Notification Settings</a></li>
                                                <!--add by ram 29 sep -->
                                                <li><a href="{{ route('marketplace-history') }}">Marketplace History</a></li>
                                                <li><a href="{{ route('wallet-history') }}">Wallet Log</a></li>
                                                <li><a href="{{ route('directpay') }}">Direct Payment History</a></li>
                                                <li><a href="{{route('ticket')}}">Manage Tickets</a></li>
                                            </ul>
                                        </li>
                                        <!-- <li><a href="{{ url('receive/faq') }}">FAQs </a></li> -->

                                        <li class="dropdown">
                                        <a href="{{ route('wishlist') }}" style="position: relative;"> <i class="fa fa-heart" style="font-size:20px; color:red;"></i> <span
                                        class="nober">{{ $heart_count }}</span></a>                                      
                                        </li>

                                        <li class="dropdown">
                                <a href="{{ route('cart') }}" style="position: relative;"> <i class="fa fa-shopping-cart" style="font-size:20px; color:green;"></i> <span
                                        class="nober">{{ $cart_count }}</span></a>
                            
                            </li>


                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                   aria-haspopup="true" aria-expanded="false"> <i class="fa fa-bell" style="font-size:20px; color:orange;"></i> <span
                                        class="nober">{{ $notifications->count() }}</span></a>
                                <ul class="dropdown-menu notify-drop">
                                    <div class="notify-drop-title">
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6 col-xs-6">Notifications
                                                (<b>{{ $notifications->count() }}</b>)
                                            </div>

                                        </div>
                                    </div>
                                    <!-- end notify title -->
                                    <!-- notify content -->
                                    <div class="drop-content">
                                        @if(!empty($notifications->first()))
                                            @foreach($notifications as $notification)
                                                <li>

                                                    <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12 ">
                                                        <p>{{ strip_tags($notification->message) }}
                                                            <br>
                                                            @if(Auth::user()->userstatus == 1)<a
                                                                href="{{ $notification->link }}"
                                                                onclick="updateStatusNotification({{ $notification->id }})">view</a>@endif
                                                        </p>

                                                    </div>
                                                </li>
                                            @endforeach
                                        @endif

                                    </div>
                                    <div class="notify-drop-footer text-center" >
                                    <a href="{{route('usernotification')}}" style="margin-top:9px !important;"><i class="fa fa-eye"></i> View All</a>
                                    </div>
                                    
                                </ul>
                            </li>

                                        @elseif(Auth::user()->sponsor_type == 3)
                                        <li><a href="{{ url('partner/home') }}">MARKETPLACE</a></li>
                                        <li><a href="javascript:void(0)" onclick="openreferafriend()">RECOMMEND </a>
                                        <li class="menu-hover">
                                            <a href="#">ACCOUNT</a>
                                            <ul>
                                                <li><a href="javascript:void(0)"
                                                    onclick="openEditForm({{ Auth::user()->id }})">PROFILE</a></li>
                                                <li><a href="javascript:void(0)"
                                                    onclick="openBankform({{ Auth::user()->id }})">My Bank Details</a></li>
                                                <li><a href="{{ route('notification-settings') }}">Notification Settings</a></li>
                                                <!--add by ram 29 sep -->
                                                <li><a href="{{ route('marketplace-history') }}">Marketplace History</a></li>
                                                <li><a href="{{ route('wallet-history') }}">Wallet Log</a></li>
                                                <li><a href="{{ route('directpay') }}">Direct Payment History</a></li>
                                                <li><a href="{{route('ticket')}}">Manage Tickets</a></li>
                                            </ul>
                                        </li>
                                           
                                            <!-- <li><a href="{{ url('partner-faqs') }}">FAQs </a></li> -->

                                           

                            <!-- <li class="dropdown">
                                <a href="{{ route('cart') }}" style="position: relative;"> <i class="fa fa-shopping-cart"></i> <span
                                        class="nober">{{ $cart_count }}</span></a>
                            
                            </li> -->


                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                   aria-haspopup="true" aria-expanded="false" > <i class="fa fa-bell" style="font-size:20px; color:blue;"></i> <span
                                        class="nober">{{ $notifications->count() }}</span></a>
                                <ul class="dropdown-menu notify-drop">
                                    <div class="notify-drop-title">
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6 col-xs-6">Notifications
                                                (<b>{{ $notifications->count() }}</b>)
                                            </div>

                                        </div>
                                    </div>
                                    <!-- end notify title -->
                                    <!-- notify content -->
                                    <div class="drop-content">
                                        @if(!empty($notifications->first()))
                                            @foreach($notifications as $notification)
                                                <li>

                                                    <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12 ">
                                                        <p>{{ strip_tags($notification->message) }}
                                                            <br>
                                                            @if(Auth::user()->userstatus == 1)<a
                                                                href="{{ $notification->link }}"
                                                                onclick="updateStatusNotification({{ $notification->id }})">view</a>@endif
                                                        </p>

                                                    </div>
                                                </li>
                                            @endforeach
                                        @endif

                                    </div>
                                  <div class="notify-drop-footer text-center" >
                                    <a href="{{route('usernotification')}}" style="margin-top:9px !important;"><i class="fa fa-eye"></i> View All</a>
                                    </div>
                                </ul>
                            </li>

                                        @endif
                                @endif

                                @else
                                <li><a href="javascript:void(0)" onclick="openreferafriend()">RECOMMEND </a></li>
                                <li class="menu-hover">
                                            <a href="#">ACCOUNT</a>
                                            <ul>
                                                <li><a href="javascript:void(0)"
                                                    onclick="openEditForm({{ Auth::user()->id }})">PROFILE</a></li>
                                            
                                            </ul>
                                        </li>

                            @endif
                             
                            </li>
                           
                         

                           
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</header>
<script>
    $(function () {
        $('[data-tooltip="tooltip"]').tooltip();
        $('[data-tooltip="true"]').tooltip();
    });

    // $(".dropdown-toggle").click(function () {
    //     $(".notify-drop").toggle();
    // });

     $(document).click(function (event) {
         if (!$(event.target).closest(".notify-drop, .dropdown-toggle").length) {
            $(".notify-drop").hide();
        }
    });

    $(".dropdown-toggle").click(function (event) {
        event.stopPropagation();
        $(".notify-drop").toggle();
    });

    function updateStatusNotification(id) {
        $.ajax({
            type: 'POST',
            url: '{{ route('update-status-notification') }}',
            headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
            data: {
                id: id
            },
            success: function (response) {
            },
            error: function (response) {
                toastr.error(response.responseJSON.msg, "Error");
                $(':input[type="submit"]').prop('disabled', false);
            },
        });
    }
</script>

