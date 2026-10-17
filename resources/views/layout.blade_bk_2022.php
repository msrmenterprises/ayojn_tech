<?php
use App\Country;use App\Industry;use App\SponsorrSpecify;use App\SponsorrSpecifyList;
$countries = Country::all();
$industries = Industry::all();

if (Auth::check()) {
    $sponsorrlist = SponsorrSpecifyList::where('sponsorr_type', Auth::user()->sponsor_for)->get();
    $userwisesponsor = SponsorrSpecify::where('user_id', Auth::user()->id)->get();
} else {
    $sponsorrlist = [];
    $userwisesponsor = [];
}


$userwisesponsorarray = [];
if (count($userwisesponsor) > 0) {
    foreach ($userwisesponsor as $usersponsorr) {
        $userwisesponsorarray[] = $usersponsorr->specify_name;
    }
}
?>

    <!doctype html>
<html lang="en" class="no-js">
<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords"
          content="sponsored, content, sponsor, event, marketing, sponsorship, mega, event, campaign, advertisement, international trade, business, influencer, social media, marketing, venue, sports, Trade, Delegation, Conference, Exhibition, Seminar, Tradeshow, Workshop, Offer, Receive, Deal, Sale, Festival, Interview, Infographics, Adventure, Football, Entertainment, Transport.">

    <meta name="description"
          content="Ayojn is a B2B discovery platform for Sponsored Outreach opportunities. For Events , Business Conference, Sports Team Sponsorship . Find campaign Sponsors from ayojn and we also provide event sponsorship Opportunities & Sponsorship Agreements Sports Teams. ">

    <meta name="google-site-verification" content="hr0sYvxRzuiBtjySCW2HZW3QWPl2kF8DAC3IBt0ve3Q"/>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet"/>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('css/style.css?param=4') }}"/>
    <link rel="stylesheet" href="{{ asset('css/style1.css?param=4') }}"/>
    <link rel="stylesheet" href="{{ asset('css/custom.css?param=3') }}"/>
    <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}"/>
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}"/>
    <link rel="stylesheet" href="{{ asset('css/jquery.dataTables.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}"/>
    <script src="{{ asset('js/jquery.min.js?param=2') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js?param=2') }}"></script>
    <script src="{{ asset('js/toastr.min.js') }}"></script>
    <script src="{{ asset('js/amcharts.js') }}"></script>
    <script src="{{ asset('js/serial.js') }}"></script>
    <script src="{{ asset('js/ammap.js') }}"></script>
    <script src="{{ asset('js/worldLow.js') }}"></script>
    <script src="{{ asset('js/light.js') }}"></script>

    <script src="{{ asset('js/export.min.js') }}"></script>
    <script src="{{ asset('js/none.js') }}"></script>
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>

    <script src="{{ asset('js/popper.min.js') }}"></script>


    <link rel="stylesheet" href="{{ asset('css/export.css') }}" type="text/css" media="all"/>
    <!-- Default Theme -->
    <!-- Resourcedddddd style -->
    <title>Find Campaign, Events , Business Conference, Sports Team Sponsorship opportunities near me</title>
    <meta property="og:title" content="Planning an Outreach | Outreach Discovery Platform"/>
    <meta property="og:description" content="Outreach Discovery Platform"/>
    <meta property="og:url" content="https://ayojn.com/"/>

    <meta name="_token" content="{{ csrf_token() }}">
    <script>
        var base_url = "{{ url('/')}}";
    </script>

    <!-- Sponsay_Privacy Policy
    <a href="https://www.iubenda.com/terms-and-conditions/56788298" class="iubenda-white no-brand iubenda-embed" title="Terms and Conditions ">Terms and Conditions</a><script type="text/javascript">(function (w,d) {var loader = function () {var s = d.createElement("script"), tag = d.getElementsByTagName("script")[0]; s.src="https://cdn.iubenda.com/iubenda.js"; tag.parentNode.insertBefore(s,tag);}; if(w.addEventListener){w.addEventListener("load", loader, false);}else if(w.attachEvent){w.attachEvent("onload", loader);}else{w.onload = loader;}})(window, document);</script> -->

    <!--  Sponsay_Cookie Solution
    <script type="text/javascript">
        var _iub = _iub || [];
        _iub.csConfiguration = {"consentOnContinuedBrowsing":false,"lang":"en","siteId":2140621,"cookiePolicyId":56788298, "banner":{ "acceptButtonDisplay":true,"customizeButtonDisplay":true,"acceptButtonColor":"#0073CE","acceptButtonCaptionColor":"white","customizeButtonColor":"#DADADA","customizeButtonCaptionColor":"#4D4D4D","rejectButtonDisplay":true,"rejectButtonColor":"#0073CE","rejectButtonCaptionColor":"white","position":"float-top-center","textColor":"black","backgroundColor":"white" }};
    </script>
    <script type="text/javascript" src="//cdn.iubenda.com/cs/iubenda_cs.js" charset="UTF-8" async></script>
-->

    <style>
        .moveleft{margin-left:30px}
        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5);
        }

        .custom-padding {
            padding-top: 5px;
        }

        .autocomplete {
            /*the container must be positioned relative:*/
            position: relative;
            display: inline-block;
            width: 100%;
        }

        input {
            border: 1px solid transparent;

            padding: 10px;
            font-size: 16px;
        }

        input[type=text] {

            width: 100%;
        }


        .autocomplete-items {
            position: absolute;
            border: 1px solid #d4d4d4;
            border-bottom: none;
            border-top: none;
            z-index: 99;
            /*position the autocomplete items to be the same width as the container:*/
            top: 100%;
            left: 0;
            right: 0;
        }

        .autocomplete-items div {
            padding: 10px;
            cursor: pointer;
            background-color: #fff;
            border-bottom: 1px solid #d4d4d4;
        }

        .autocomplete-items div:hover {
            /*when hovering an item:*/
            background-color: #e9e9e9;
        }

        .autocomplete-active {
            /*when navigating through the items using the arrow keys:*/
            background-color: DodgerBlue !important;
            color: #ffffff;
        }

        .loader {
            display: none;
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 999999999;
            background: url('https://media.giphy.com/media/JPm3smpf87jSzw6gZ1/giphy.gif') 50% 50% no-repeat rgb(249, 249, 249);
        }
    </style>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-127610237-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', 'UA-127610237-1');
    </script>
</head>
<body>
<div class="loader"></div>
<div class="body-contianer only-scroll-content" id="wrapper">
@include('includes.header')


<!-- banner section start -->
    {{--	<div class="content-wrapper">--}}
    <div id="clickDiv"></div>
@yield('content')
{{--	</div>--}}


<!-- banner section end -->
</div>


@include('includes.footer')

<!-- siignup popup start-->
<!-- Modal -->
@if (Auth::check())
    @if(Auth::user()->sponsor_type != 3)
        <div class="modal" id="edituserform" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 45.56 45.559">
                                <path id="Union_1" data-name="Union 1" class="cls-1"
                                      d="M22.426,22.426,0,44.852,22.426,22.426,0,44.852,22.426,22.426,0,0,22.426,22.426,0,0,22.426,22.426,44.852,0,22.426,22.426,44.852,0,22.426,22.426,44.852,44.852,22.426,22.426,44.852,44.852ZM22.426,22.426Z"
                                      transform="translate(0.354 0.354)"/>
                            </svg>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">
                           <!-- Edit {{ (Auth::user()->sponsor_type == 1)?'Offer ':'Manage or Receive' }} Profile -->                       Update your Profile. Once you click submit it will be locked for a review.

                        </h4>
                    </div>
                    <div class="modal-body">
                        <form class="edituserform" id="edituserform" method="post" onsubmit="return false"
                              name="edituserform">
                            <div class="form-group">
                                <label>
                                    <strong>Entity</strong>
                                </label>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Entity" name="entity_edit"
                                       id="entity_edit" value="{{ Auth::user()->entity }}">
                            </div>
                            @if(Auth::user()->sponsor_type == 2)
                                <div class="form-group">
                                    <label>
                                        <strong>Identify Yourself </strong>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" value="{{ Auth::user()->identity }}"
                                           readonly>

                                </div>
                            @endif
                            <div class="form-group">
                                <label>
                                    <strong>Email Id</strong>
                                </label>
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control" placeholder="Workplace Email" name="email_edit"
                                       id="email_edit" value="{{ Auth::user()->email }}" onblur="validateEmail(this);">
                                <p id="emailNew" style="color:red;">Please sign up using your work email address since
                                    we
                                    will
                                    conduct a validation.</p>
                            </div>
                            <div class="form-group">
                                <label>
                                    <strong>Mobile No</strong>
                                </label>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control"
                                       placeholder="Mobile Number (with Country code +) "
                                       name="phone_no_edit"
                                       id="phone_no_edit" value="{{ Auth::user()->phone_no }}">
                            </div>

                            <div class="form-group">
                                <label>
                                    <strong>Country</strong>
                                </label>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Country" name="country"
                                       id="country" value="{{ Auth::user()->country_name->country_name }}" disabled>
                            </div>
                            <div class="form-group">
                                <label>
                                    <strong>City</strong>
                                </label>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="City" name="city"
                                       id="city"
                                       value="{{ (!empty(Auth::user()->city_name)) ? Auth::user()->city_name->name : ''}}"
                                       disabled>
                            </div>
                            <div class="form-group">
                                <label>
                                    <strong>Hint incase you forget Username or Password</strong>
                                </label>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Nick Name, First Job, Favourite Colour or anything"
                                       name="sq1_edit"
                                       id="sq1_edit" value="{{ Auth::user()->sq1 }}">
                            </div>
                            {{--<button type="button" class="btn btn-default" onclick="secondPopupPrevious()">Previous</button>--}}
                            <button type="submit" id="editformsubmit" onclick="UpdateUserForm()"
                                    class="btn btn-default">
                                Submit
                            </button>
                        </form>

                    </div>

                </div>
            </div>
        </div>
        <script>
            function UpdateUserForm() {
                var useremail_edit = $("#email_edit").val();
                var userentity_edit = $("#entity_edit").val();
                var identity_edit = $("#identity_edit").val();
                var phone_no_edit = $("#phone_no_edit").val();
                var sq1_edit = $("#sq1_edit").val();
                // $('#create_post').prop('disabled', true);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('edituserprofile') }}',
                    headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
                    data: {
                        identity_edit: identity_edit,
                        phone_no_edit: phone_no_edit,
                        sq1_edit: sq1_edit,
                        useremail_edit: useremail_edit,
                        userentity_edit: userentity_edit,
                    },
                    //cache: false,
                    //contentType: false,
                    //processData: false,
                    success: function (response) {
                        //console.log(response.status);
                        var data = response;
                        if (response.status) {
                            toastr.success(response.msg, "Success");
                            location.href = "{{ url('/review') }}";
                        } else {
                            toastr.error(response.msg, "Error");
                        }
                    },
                    error: function (response) {
                        toastr.error(response.responseJSON.msg, "Error");
                        $(':input[type="submit"]').prop('disabled', false);
                    },
                });

            }
        </script>
    @else
        <div class="modal" id="edituserform" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 45.56 45.559">
                                <path id="Union_1" data-name="Union 1" class="cls-1"
                                      d="M22.426,22.426,0,44.852,22.426,22.426,0,44.852,22.426,22.426,0,0,22.426,22.426,0,0,22.426,22.426,44.852,0,22.426,22.426,44.852,0,22.426,22.426,44.852,44.852,22.426,22.426,44.852,44.852ZM22.426,22.426Z"
                                      transform="translate(0.354 0.354)"/>
                            </svg>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">
                            Edit Partner Profile</h4>
                    </div>
                    <div class="modal-body">
                        <form class="edituserform" id="edituserform" method="post" onsubmit="return false"
                              name="edituserform">

                            <div class="form-group">
                                <label>
                                    <strong>Company Name </strong>
                                </label>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Company Name"
                                       value="{{ Auth::user()->company_name }}"
                                       name="edit_company_name" id="edit_company_name">

                            </div>
                            <div class="form-group">
                                <label>
                                    <strong>Contact Person</strong>
                                </label>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Contact Person"
                                       name="contact_person"
                                       id="contact_person" value="{{ Auth::user()->contact_person }}">
                            </div>
                            <div class="form-group">
                                <label>
                                    <strong>HQ</strong>
                                </label>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="HQ"
                                       name="HQ_edit"
                                       id="HQ_edit" value="{{ Auth::user()->HQ }}" readonly>
                            </div>
                            <div class="form-group">
                                <label>
                                    <strong>Email Id</strong>
                                </label>
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control" placeholder="Workplace Email" name="email_edit"
                                       id="email_edit" value="{{ Auth::user()->email }}" onblur="validateEmail(this);">
                                <p id="emailNew" style="color:red;">Please sign up using your work email address since
                                    we
                                    will
                                    conduct a validation.</p>
                            </div>
                            <div class="form-group">
                                <label>
                                    <strong>Mobile No</strong>
                                </label>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control"
                                       placeholder="Mobile Number (with Country code +) "
                                       name="phone_no_edit"
                                       id="phone_no_edit" value="{{ Auth::user()->phone_no }}">
                            </div>
                            <button type="submit" id="editformsubmit" onclick="UpdateUserForm()"
                                    class="btn btn-default">
                                Submit
                            </button>
                        </form>

                    </div>

                </div>
            </div>
        </div>
        <script>
            function UpdateUserForm() {
                var useremail_edit = $("#email_edit").val();
                var contact_person = $("#contact_person").val();
                var edit_company_name = $("#edit_company_name").val();
                var phone_no_edit = $("#phone_no_edit").val();
                // $('#create_post').prop('disabled', true);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('updateuserprofile') }}',
                    headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
                    data: {
                        edit_company_name: edit_company_name,
                        phone_no_edit: phone_no_edit,
                        contact_person: contact_person,
                        useremail_edit: useremail_edit,
                    },
                    success: function (response) {
                        var data = response;
                        if (response.status) {
                            toastr.success(response.msg, "Success");
                            location.href = "{{ url('/review') }}";
                        } else {
                            toastr.error(response.msg, "Error");
                        }

                    },
                    error: function (response) {
                        toastr.error(response.responseJSON.msg, "Error");
                        $(':input[type="submit"]').prop('disabled', false);
                    },
                });

            }
        </script>
    @endif
@endif
<div class="modal fade formElement" id="signupForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 45.56 45.559">
                        <path id="Union_1" data-name="Union 1" class="cls-1"
                              d="M22.426,22.426,0,44.852,22.426,22.426,0,44.852,22.426,22.426,0,0,22.426,22.426,0,0,22.426,22.426,44.852,0,22.426,22.426,44.852,0,22.426,22.426,44.852,44.852,22.426,22.426,44.852,44.852ZM22.426,22.426Z"
                              transform="translate(0.354 0.354)"/>
                    </svg>
                </button>
                <h4 class="modal-title" id="myModalLabel">Create your account</h4>
                <h5 class="modal-title custom-heading" id="myModalLabel">Get started for free. No credit card
                    required.</h5>
            </div>
            <div class="modal-body">
                <form class="formClass firstForm" style="display:block">
                    <div class="form-group">
                        <div class="row alert alert-warning" id="offerOptionMessage">
                            <strong>Warning!</strong> Please Select any One
                        </div>
                        <div class="row">
{{--                            <div class="col-md-2">--}}
{{--                                <label class="custom-padding">--}}
{{--                                    <b>Want to</b>--}}
{{--                                </label>--}}
{{--                            </div>--}}
                            <div class="col-md-4">
                                <select class="form-control" id="offerOption">
                                    <option value="">Select Any</option>
                                    <option value="1">Clients / Brands</option>
                                    <option value="2">Networks, Agencies, Freelancers</option>
                                </select>
                            </div>
{{--                            <div class="col-md-4">--}}
{{--                                <label class="custom-padding"><b>Outreach</b></label>--}}
{{--                            </div>--}}
                        </div>
                        <div class="row">

                            <input type="checkbox" class="form-control" style="width: 22px;float: left"
                                   name="firstCheckBox" id="firstCheckBox"
                                   value="1"><span style="line-height: 3;padding-left: 5px">By signing up, you agree to the <a
                                    href="javascript: popuponclick()">Terms
										of Service</a> and <a
                                    href="javascript: privacyWindow()">Privacy Policy</a>.</span>

                        </div>

                    </div>
                    <button type="button" id="firstFormNext" class="btn btn-default nextOption firstFormNext"
                            onclick="checkFirstPopupValidation()">Next
                    </button>
                    <br>
                    <span style="line-height: 3;padding-left: 5px"><a
                            href="{{ url('partner/login') }}">Want to signup as partner?</a></span>
                </form>

                <form class="formClass thirdForm" autocomplete="off" onsubmit="return false;">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Entity (Name of Organisation)"
                               name="OfferEntity"
                               id="OfferEntity">
                    </div>

                    <div class="form-group">
                        <input type="email" class="form-control" placeholder="Workplace Email" name="OfferEmail"
                               id="OfferEmail"
                               onblur="validateEmail(this);">
                        <p id="emailNew1" style="color:red;display: none">Please sign up using your work email address
                            since we will
                            conduct a validation.</p>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Mobile Number (with Country code +) "
                               name="OfferPhoneNo"
                               id="OfferPhoneNo">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Password" name="OfferPassword"
                               id="OfferPassword">
                    </div>
                    <div class="form-group">
                        <label>
                            <strong>Country</strong>
                        </label>
                    </div>
                    <div class="form-group">
                        <select class="form-control" name="OfferCountry" id="OfferCountry" onchange="getCity(1)">
                            <option value="">Select Country</option>
                            @if(!empty($countries))
                                @foreach($countries as $c)
                                    <option value="<?= $c->country_code?>"><?= $c->country_name?></option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label>
                            <strong>City</strong>
                        </label>
                    </div>
                    <div class="form-group">
                        <select class="form-control" name="OfferCity" id="OfferCity">
                            <option value="">Select City</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>
                            <strong>Hint incase you forget Username or Password</strong>
                        </label>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Nick Name, First Job, Favourite Colour or anything" name="OfferQuestion1"
                               id="OfferQuestion1">
                    </div>
                    <button type="button" class="btn btn-default" onclick="firstPopupPrevious()">Previous</button>
                    <button type="button" id="thirdFormId" class="btn btn-default" onclick="submitForm()">Submit
                    </button>
                </form>
                <form class="formClass forthForm" autocomplete="off" onsubmit="return false;">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Entity (Name of Organisation)"
                               name="ReceiveEntity"
                               id="ReceiveEntity">
                    </div>
                    <div class="form-group">
                        <label>
                            <strong>Identify Yourself </strong>
                        </label>
                    </div>
                    <div class="form-group">
                        <select class="form-control" name="ReceiveIdentity" id="ReceiveIdentity">
                            <option value="Freelancers">Freelancers</option>
                            <option value="Agencies">Agencies</option>
                            <option value="Networks">Networks</option>
                            <option value="Communities">Communities</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control" placeholder="Workplace Email" name="ReceivceEmail"
                               id="ReceivceEmail"
                               onblur="validateEmail(this);">
                        <p id="emailNew2" style="color:red;display: none">Please sign up using your work email address
                            since we will
                            conduct a validation.</p>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Mobile Number (with Country code +) "
                               name="ReceivePhoneNo"
                               id="ReceivePhoneNo">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Password" name="ReceivePassword"
                               id="ReceivePassword">
                    </div>
                    <div class="form-group">
                        <label>
                            <strong>Country</strong>
                        </label>
                    </div>
                    <div class="form-group">
                        <select class="form-control" name="ReceiverCountry" id="ReceiverCountry" onchange="getCity(2)">
                            <option value="">Select Country</option>
                            @if(!empty($countries))
                                @foreach($countries as $c)
                                    <option value="<?= $c->country_code?>"><?= $c->country_name?></option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label>
                            <strong>City</strong>
                        </label>
                    </div>
                    <div class="form-group">
                        <select class="form-control" name="ReceiveCity" id="ReceiveCity">
                            <option value="">Select City</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>
                            <strong>Hint incase you forget Username or Password</strong>
                        </label>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Nick Name, First Job, Favourite Colour or anything"
                               name="ReceiveQuestion1"
                               id="ReceiveQuestion1">
                    </div>
                    <button type="button" class="btn btn-default" onclick="firstPopupPrevious()">Previous</button>
                    <button type="button" id="forthFormId" class="btn btn-default" onclick="submitForm()">Submit
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
<!-- siignup popup END-->

<!-- login popup start -->
<div class="modal fade formElement" id="loginForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 45.56 45.559">
                        <path id="Union_1" data-name="Union 1" class="cls-1"
                              d="M22.426,22.426,0,44.852,22.426,22.426,0,44.852,22.426,22.426,0,0,22.426,22.426,0,0,22.426,22.426,44.852,0,22.426,22.426,44.852,0,22.426,22.426,44.852,44.852,22.426,22.426,44.852,44.852ZM22.426,22.426Z"
                              transform="translate(0.354 0.354)"/>
                    </svg>
                </button>
                <h4 class="modal-title" id="myModalLabel">Login to your account</h4>
            </div>
            <div class="modal-body">
                <form method="post">

                    <div class="form-group">
                        <input type="email" class="form-control" placeholder="Email" id="loginEmail">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Password" id="loginPassword">
                    </div>
                    <div class="form-group">
                        <button type="button" id="loginButtonId" class="btn btn-default" onclick="loginForm()">Submit
                        </button>
                    </div>
                    <div class="form-group">
                        <a href="{{ url('/password/reset') }}">Forgot password?</a> &nbsp;&nbsp; <a
                            href="{{ url('/username/reset') }}">Forgot username?</a>
                    </div>

                    <div class="form-group">
                        Don't have an account?<a href="javascript:void(0)" onclick="displayRegister()"> Register
                            Now</a>
                    </div>
                    <div class="form-group">
                        <a href="{{ url('partner/login') }}"> Want to login as Partner?</a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
<div id="sponsorrText" class="modal " role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">

            <div class="modal-body">
{{--                <p>At Ayojn we help businesses discover Opportunities via Sponsored Outreach. </p>--}}
                <p>Ayojn Ltd is a company registered in England and Wales (Company No. 12666870). The registered address is Kemp House 160 City Road, London, EC1V 2NX. For any enquiries please write us at connect(at)ayojn.com. </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
<div id="ConnectText" class="modal " role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">

            <div class="modal-body">
                <p>
                    Connect with us at Live Chat or alternatively write us at connect(at)Ayojn.com. You can also
                    schedule a call with us via : <a href="https://calendly.com/ayojn" target="_blank">https://calendly.com/ayojn</a>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
<div id="PricingText" class="modal " role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body">
                <p>
                    Users at Ayojn explore various features & services at different price levels. We urge you to
                    experience it by yourself and decide if it's worth paying.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
<div id="TermsText" class="modal " role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">

            <div class="modal-body">
                <!--<h2>Ayojn Terms of Service</h2> -->
                <div style="padding-left: 10px;padding-right: 10px;">
                    <h3>We don't offer Terms & Conditions rather it's Trust we wish to build. </h3>

                    <h5>What's in these terms?</h5>
                    <p>These terms tell you the rules for using our website <a href="www.ayojn.com" target="_blank">www.ayojn.com</a> (our site)</p>

                    <h5>Who we are and how to contact us</h5>
                    <p>www.ayojn.com is a site operated by Ayojn Limited ("We"). We are registered in England and Wales under company number 12666870 and have our registered office at Kemp House 160 City Road, London, EC1V 2NX<br/>
                        We are a limited company.<br/>
                        To contact us, please email connect@ayojn.com.
                    </p>

                    <h5>By using our site you accept these terms</h5>
                    <p>By using our site, you confirm that you accept these terms of use and that you agree to comply with them.<br/>
                        If you do not agree to these terms, you must not use our site.<br/>
                        We recommend that you print a copy of these terms for future reference.<br/>
                        If you purchase services from our site, our Terms and conditions of supply will apply to the sales.

                    </p>

                    <h5>We may make changes to these terms
                    </h5>
                    <p>We amend these terms from time to time. Every time you wish to use our site, please check these terms to ensure you understand the terms that apply at that time.<br/>
                        We may make changes to our site.<br/>
                        We may update and change our site from time to time without providing you with notice.
                    </p>

                    <h5>We may suspend or withdraw our site
                    </h5>
                    <p>Our site is made available free of charge.
                        We do not guarantee that our site, or any content on it, will always be available or be uninterrupted. We may suspend or withdraw or restrict the availability of all or any part of our site for business and operational reasons. We will try to give you reasonable notice of any suspension or withdrawal.<br/>
                        You are also responsible for ensuring that all persons who access our site through your internet connection are aware of these terms of use and other applicable terms and conditions, and that they comply with them.
                    </p>


                    <h5>You must keep your account details safe</h5>
                    <p>If you choose, or you are provided with, a user identification code, password or any other piece of information as part of our security procedures, you must treat such information as confidential. You must not disclose it to any third party.<br/>
                        We have the right to disable any user identification code or password, whether chosen by you or allocated by us, at any time, if in our reasonable opinion you have failed to comply with any of the provisions of these terms of use.<br/>
                        If you know or suspect that anyone other than you knows your user identification code or password, you must promptly notify us at connect@ayojn.com.
                    </p>

                    <h5>How you may use material on our site</h5>
                    <p>We are the owner or the licensee of all intellectual property rights in our site, and in the material published on it.  Those works are protected by copyright laws and treaties around the world. All such rights are reserved.<br/>
                        You may print off one copy, and may download extracts, of any page(s) from our site for your personal use and you may draw the attention of others within your organisation to content posted on our site.<br/>
                        You must not modify the paper or digital copies of any materials you have printed off or downloaded in any way, and you must not use any illustrations, photographs, video or audio sequences or any graphics separately from any accompanying text.<br/>
                        Our status (and that of any identified contributors) as the authors of content on our site must always be acknowledged.<br/>
                        You must not use any part of the content on our site for commercial purposes without obtaining a licence to do so from us or our licensors.<br/>
                        If you print off, copy or download any part of our site in breach of these terms of use, your right to use our site will cease immediately and you must, at our option, return or destroy any copies of the materials you have made.
                    </p>

                    <h5>Do not rely on information on this site</h5>
                    <p>The content on our site is provided for general information only. It is not intended to amount to advice on which you should rely. You must obtain professional or specialist advice before taking, or refraining from, any action on the basis of the content on our site.
                        Although we make reasonable efforts to update the information on our site, we make no representations, warranties or guarantees, whether express or implied, that the content on our site is accurate, complete or up to date.
                    </p>

                    <h5>We are not responsible for websites we link to</h5>
                    <p>Where our site contains links to other sites and resources provided by third parties, these links are provided for your information only. Such links should not be interpreted as approval by us of those linked websites or information you may obtain from them.
                        We have no control over the contents of those sites or resources.
                    </p>


                    <h5>User-generated content is not approved by us</h5>
                    <p>This website may include information and materials uploaded by other users of the site, including reviews, bulletin boards and chat rooms. This information and these materials have not been verified or approved by us. The views expressed by other users on our site do not represent our views or values.
                        If you wish to complain about information and materials uploaded by other users please contact us on connect@ayojn.com.
                    </p>

                    <h5>Our responsibility for loss or damage suffered by you</h5>
                    <p>Our website is intended solely for business-to-business use.  If you are a consumer, we cannot prevent you from browsing our website but if you choose to do so, our limitation of liability as outlined below applies to you equally as if you were a business user.<br/>
                        We do not exclude or limit in any way our liability to you where it would be unlawful to do so. This includes liability for death or personal injury caused by our negligence or the negligence of our employees, agents or subcontractors and for fraud or fraudulent misrepresentation.<br/>
                        Different limitations and exclusions of liability will apply to liability arising as a result of the supply of any products to you, which will be set out in our Terms and conditions of supply.
                        We exclude all implied conditions, warranties, representations or other terms that may apply to our site or any content on it.<br/>

                    <ul class="moveleft">We will not be liable to you for any loss or damage, whether in contract, tort (including negligence), breach of statutory duty, or otherwise, even if foreseeable, arising under or in connection with:
                        <li>use of, or inability to use, our site; or</li>
                        <li>use of or reliance on any content displayed on our site.</li>
                    </ul>
                    <ul class="moveleft">
                        In particular, we will not be liable for:
                        <li> loss of profits, sales, business, or revenue;</li>
                        <li> business interruption;</li>
                        <li> loss of anticipated savings;</li>
                        <li> loss of business opportunity, goodwill or reputation; or</li>
                        <li> any indirect or consequential loss or damage.</li>
                    </ul>
                    </p>

                    <h5>How we may use your personal information
                    </h5>
                    <p>We will only use your personal information as set out in our Privacy Policy.
                    </p>

                    <h5>Uploading content to our site
                    </h5>
                    <p>Whenever you make use of a feature that allows you to upload content to our site, or to make contact with other users of our site, you must comply with the content standards set out in our Acceptable Use Policy.<br/>
                        You warrant that any such contribution does comply with those standards, and you will be liable to us and indemnify us for any breach of that warranty. This means you will be responsible for any loss or damage we suffer as a result of your breach of warranty.<br/>
                        Any content you upload to our site will be considered non-confidential and non-proprietary. You retain all of your ownership rights in your content, but you are required to grant us and other users of our site a limited licence to use, store and copy that content and to distribute and make it available to third parties. The rights you license to us are described in Rights you are giving us to use material you upload.<br/>
                        We also have the right to disclose your identity to any third party who is claiming that any content posted or uploaded by you to our site constitutes a violation of their intellectual property rights, or of their right to privacy.<br/>
                        We have the right to publish your logo, testimonials and remarks about our services rendered to you.<br/>
                        We have the right to remove any posting you make on our site if, in our opinion, your post does not comply with the content standards set out in our Acceptable Use Policy.<br/>
                        You are solely responsible for securing and backing up your content.
                    </p>
                    <h5>Rights you are giving us to use material you upload</h5>
                    <P>When you upload or post content to our site, you grant us the following rights to use that content:</P>
                    <ul class="moveleft">
                        <LI><P>A perpetual, worldwide, non-exclusive, royalty-free, transferable
                                licence that allows us to use, reproduce, distribute, prepare
                                derivative works of, display, and perform that user-generated
                                content in connection with the service provided by our website and
                                across different media. We may also use the content to promote the
                                website or our service.</P>
                        <LI><P>A perpetual, worldwide, non-exclusive, royalty-free, transferable
                                licence that allows third parties (for example, other users,
                                partners or advertisers) to use the material which you upload for
                                their purposes or in accordance with the functionality of the site.</P>
                    </UL>
                    <h5>We are not responsible for viruses and you must not introduce them</h5>
                    <P>We do not guarantee that our site will be secure or free from bugs or
                        viruses.</P>
                    <P>You are responsible for configuring your information technology, computer
                        programmes and platform to access our site. You should use your own
                        virus protection software.</P>
                    <P>You must not misuse our site by knowingly introducing viruses, trojans,
                        worms, logic bombs or other material that is malicious or
                        technologically harmful. You must not attempt to gain unauthorised
                        access to our site, the server on which our site is stored or any
                        server, computer or database connected to our site. You must not
                        attack our site via a denial-of-service attack or a distributed
                        denial-of service attack. By breaching this provision, you would
                        commit a criminal offence under the Computer Misuse Act 1990. We will
                        report any such breach to the relevant law enforcement authorities
                        and we will co-operate with those authorities by disclosing your
                        identity to them. In the event of such a breach, your right to use
                        our site will cease immediately.</P>
                    <h5>Rules about linking to our site</h5>
                    <P>You may link to our home page, provided you do so in a way that is fair
                        and legal and does not damage our reputation or take advantage of it.</P>
                    <P>You must not establish a link in such a way as to suggest any form of
                        association, approval or endorsement on our part where none exists.</P>
                    <P>You must not establish a link to our site in any website that is not
                        owned by you.</P>
                    <P>Our site must not be framed on any other site, nor may you create a link
                        to any part of our site other than the home page.</P>
                    <P>We reserve the right to withdraw linking permission without notice.</P>
                    <P>The website in which you are linking must comply in all respects with the
                        content standards set out in our Acceptable Use Policy.</P>
                    <P>If you wish to link to or make any use of content on our site other than
                        that set out above, please contact connect@ayojn.com.</P>

                    <h5>Which country's laws apply to any disputes?</h5>
                    <P>If you are a consumer, please note that these terms of use, their
                        subject matter and their formation, are governed by English law. You
                        and we both agree that the courts of England and Wales will have
                        exclusive jurisdiction except that if you are a resident of Northern
                        Ireland you may also bring proceedings in Northern Ireland, and if
                        you are resident of Scotland, you may also bring proceedings in
                        Scotland.</P>
                    <P>If you are a business, these terms of use, their subject matter and
                        their formation (and any non-contractual disputes or claims) are
                        governed by English law. We both agree to the exclusive jurisdiction
                        of the courts of England and Wales.</P>

                    <p>Intellectual Property in Trademarks<br/>
                        We own the intellectual property rights in the trademarks “Ayojn”
                        and other logo components such the icon layout. These are registered
                        in the United Kingdom under classes 35 and 37. You are not permitted
                        to use them without our approval, unless they are part of material
                        you are using as permitted under How you may use material on our
                        site.</P>

                    <h5>2. WEBSITE ACCEPTABLE USE POLICY</h5>
                    <p><strong>What's in these terms?</strong></p>
                    <P>This acceptable use policy sets out the content standards that apply when
                        you upload content to our site, make contact with other users on our
                        site, link to our site, or interact with our site in any other way,</P>
                    <p><strong>Who we are and how to contact us</strong></p>
                    <P>
                        <A HREF="http://www.yourstandbuilder.com/" target="_blank">www.ayojn.com</A>
                        is a site operated by Ayojn Limited (&quot;We&quot;). We are
                        registered in England and Wales under company number 12666870 and
                        have our registered office at Kemp House 160 City Road, London, EC1V
                        2NX</P>
                    <P>
                        We are a limited company.<br/>
                        To contact us, please email connect@ayojn.com<br/>
                        By using our site you accept these terms<br/>
                        By using our site, you confirm that you accept the terms of this policy and that you agree to comply with them.<br/>
                        If you do not agree to these terms, you must not use our site.<br/>
                        We recommend that you print a copy of these terms for future reference.</P>

                    <h5>We may make changes to the terms of this policy</h5>
                    <P>We amend these terms from time to time. Every time you wish to use our
                        site, please check these terms to ensure you understand the terms
                        that apply at that time.</P>
                    <h5>Prohibited uses</h5>
                    <P>You may use our site only for lawful purposes.&nbsp; You may not use our
                        site:</P>
                    <UL>
                        <LI><P>In any way that breaches any applicable local, national or
                                international law or regulation.</LI></P>
                        <LI><P>In any way that is unlawful or fraudulent, or has any unlawful or
                                fraudulent purpose or effect.</LI></P>
                        <LI><P>For the purpose of harming or attempting to harm minors in any way.</LI></P>
                        <LI><P>To send, knowingly receive, upload, download, use or re-use any
                                material which does not comply with our content standards.</LI></P>
                        <LI><P>To transmit, or procure the sending of, any unsolicited or unauthorised
                                advertising or promotional material or any other form of similar
                                solicitation (spam).</LI></P>
                        <LI><P>To knowingly transmit any data, send or upload any material that
                                contains viruses, Trojan horses, worms, time-bombs, keystroke
                                loggers, spyware, adware or any other harmful programs or similar
                                computer code designed to adversely affect the operation of any
                                computer software or hardware.</LI></P>
                    </UL>
                    <h5>You also agree:</h5>
                    <UL>
                        <LI><P>Not to reproduce, duplicate, copy or re-sell any part of our site in
                                contravention of the provisions of our terms of website use.</LI></P>
                        <LI><P>Not to access without authority, interfere with, damage or disrupt:</LI></P>
                        <LI><P>any part of our site;</LI></P>
                        <LI><P>any equipment or network on which our site is stored;</LI></P>
                        <LI><P>any software used in the provision of our site; or</LI></P>
                        <LI><P>any equipment or network or software owned or used by any third party.</P></LI>
                    </UL>
                    <h5>Interactive services<h5>
                            <P>We may from time to time provide interactive services on our site,
                                including, without limitation:</P>
                            <UL>
                                <LI><P>Chat rooms.</LI></P>
                                <LI><P>Bulletin boards.</LI></P>
                                <LI><P>Review facilities.</LI></P>
                            </UL>
                            <P>Where we do provide any interactive service, we will provide clear
                                information to you about the kind of service offered, if it is
                                moderated and what form of moderation is used (including whether it
                                is human or technical).</P>
                            <P>We will do our best to assess any possible risks for users (and in
                                particular, for children) from third parties when they use any
                                interactive service provided on our site, and we will decide in each
                                case whether it is appropriate to use moderation of the relevant
                                service (including what kind of moderation to use) in the light of
                                those risks. However, we are under no obligation to oversee, monitor
                                or moderate any interactive service we provide on our site, and we
                                expressly exclude our liability for any loss or damage arising from
                                the use of any interactive service by a user in contravention of our
                                content standards, whether the service is moderated or not.</P>
                            <P>The use of any of our interactive services by a minor is subject to the
                                consent of their parent or guardian. We advise parents who permit
                                their children to use an interactive service that it is important
                                that they communicate with their children about their safety online,
                                as moderation is not foolproof. Minors who are using any interactive
                                service should be made aware of the potential risks to them.</P>
                            <P>Where we do moderate an interactive service, we will normally provide you
                                with a means of contacting the moderator, should a concern or
                                difficulty arise.</P>
                            <h5>Content standards</h5>
                            <P>These content standards apply to any and all material which you contribute
                                to our site (Contribution), and to any interactive services
                                associated with it.</P>
                            <P>The
                                Content Standards must be complied with in spirit as well as to the
                                letter. The standards apply to each part of any Contribution as well
                                as to its whole.</P>
                            <P>Ayojn
                                Limited will determine, in its discretion, whether a Contribution
                                breaches the Content Standards.</P>
                            <P>A Contribution must:</P>
                            <UL>
                                <LI><P>Be
                                        accurate (where it states facts).</LI></P>
                                <LI><P>Be
                                        genuinely held (where it states opinions).</LI></P>
                                <LI><P>
                                        Comply with the law applicable in England and Wales and in any country from which it is posted.</LI></P>
                            </UL>
                            <P>A Contribution must not:</P>
                            <UL>
                                <LI><P>
                                        Be
                                        defamatory of any person.</LI></P>
                                <LI><P>
                                        Be
                                        obscene, offensive, hateful or inflammatory.</LI></P>
                                <LI><P>
                                        Promote
                                        sexually explicit material.</LI></P>
                                <LI><P>
                                        Promote
                                        violence.</LI></P>
                                <LI><P>
                                        Promote
                                        discrimination based on race, sex, religion, nationality,
                                        disability, sexual orientation or age.</LI></P>
                                <LI><P>
                                        Infringe
                                        any copyright, database right or trademark of any other person.</LI></P>
                                <LI><P>
                                        Be
                                        likely to deceive any person.</LI></P>
                                <LI><P>
                                        Breach
                                        any legal duty owed to a third party, such as a contractual duty or
                                        a duty of confidence.</LI></P>
                                <LI><P>
                                        Promote
                                        any illegal activity.</LI></P>
                                <LI><P>
                                        Be
                                        in contempt of court.</LI></P>
                                <LI><P>
                                        Be
                                        threatening, abuse or invade another's privacy, or cause annoyance,
                                        inconvenience or needless anxiety.</LI></P>
                                <LI><P>
                                        Be
                                        likely to harass, upset, embarrass, alarm or annoy any other person.</LI></P>
                                <LI><P>
                                        Impersonate
                                        any person, or misrepresent your identity or affiliation with any
                                        person.</LI></P>
                                <LI><P>
                                        Give
                                        the impression that the Contribution emanates from Ayojn Limited,
                                        if this is not the case.</LI></P>
                                <LI><P>
                                        Advocate,
                                        promote, incite any party to commit, or assist any unlawful or
                                        criminal act such as (by way of example only) copyright infringement
                                        or computer misuse.</LI></P>
                                <LI><P>
                                        Contain
                                        a statement which you know or believe, or have reasonable grounds
                                        for believing, that members of the public to whom the statement is,
                                        or is to be, published are likely to understand as a direct or
                                        indirect encouragement or other inducement to the commission,
                                        preparation or instigation of acts of terrorism.</LI></P>
                                <LI><P>
                                        Contain
                                        any advertising or promote any services or web links to other sites.</LI></P>
                            </UL>
                            <h5>Breach
                                of this policy</h5>
                            <P>
                                When
                                we consider that a breach of this acceptable use policy has occurred,
                                we may take such action as we deem appropriate.&nbsp;</P>
                            <P>
                                Failure
                                to comply with this acceptable use policy constitutes a material
                                breach of the terms of use upon which you are permitted to use our
                                site, and may result in our taking all or any of the following
                                actions:</P>
                            <UL>
                                <LI><P>
                                        Immediate,
                                        temporary or permanent withdrawal of your right to use our site.</LI></P>
                                <LI><P>
                                        Immediate,
                                        temporary or permanent removal of any Contribution uploaded by you
                                        to our site.</LI></P>
                                <LI><P>
                                        Issue
                                        of a warning to you.</LI></P>
                                <LI><P>
                                        Legal
                                        proceedings against you for reimbursement of all costs on an
                                        indemnity basis (including, but not limited to, reasonable
                                        administrative and legal costs) resulting from the breach.</LI></P>
                                <LI><P>
                                        Further
                                        legal action against you.</LI></P>
                                <LI><P>
                                        Disclosure
                                        of such information to law enforcement authorities as we reasonably
                                        feel is necessary or as required by law.</LI></P>
                            </UL>
                            <P>We exclude our liability for all action we may take in response to
                                breaches of this acceptable use policy. The actions we may take are
                                not limited to those described above, and we may take any other
                                action we reasonably deem appropriate.</P>
                            <h5>Which country's laws apply to any disputes?</h5>
                            <P>If you are a consumer, please note that the terms of this policy, its
                                subject matter and its formation are governed by English law. You and
                                we both agree that the courts of England and Wales will have
                                exclusive jurisdiction except that if you are a resident of Northern
                                Ireland you may also bring proceedings in Northern Ireland, and if
                                you are resident of Scotland, you may also bring proceedings in
                                Scotland.</P>
                            <P>If you are a business, the terms of this policy, its subject matter and
                                its formation (and any non-contractual disputes or claims) are
                                governed by English law. We both agree to the exclusive jurisdiction
                                of the courts of England and Wales.</P>

                            <h4>3. SUPPLY OF SERVICE - AGREED TERMS</h3>
                                <OL>
                                    <LI><P>
                                            About
                                            us<BR><BR>1.1 Company details. Ayojn Limited (company number
                                            12666870) (we and us) is a company registered in England and Wales
                                            and our registered office is at Kemp House 160 City Road, London,
                                            EC1V 2NX. We operate the website www.ayojn.com. <BR><BR>1.2
                                            Background<BR><BR>(a) These terms and conditions apply to any
                                            individual, sole trader, firm, company or other legal entity who
                                            registers as a User of this website (a Registered User).<BR><BR>(b)
                                            Ayojn Limited is an independent company which aims to facilitate
                                            connections between Clients/ Brands Registered Users (an CRU) who
                                            require the provision of Sponsored Outreach/ or services and
                                            Agencies (an ARU) who may be able to offer such provision. There are
                                            also Partner companies (PRU) which offer deals at the marketplace
                                            offered by Ayojn Limited. <BR><BR>(c) This facilitation of
                                            connections and the means of facilitation provided by us are
                                            hereinafter known as the Website Services.<BR><BR>(d) Registration
                                            is free of charge for all users.<BR><BR>(e) ARUs &amp; CRUs agree to
                                            pay a fee for the discovery or data sharing and connecting with the
                                            potential leads. <BR>(f) PRUs may/may not pay a commission to
                                            Ayojn. The deals run by PRUs are independent and don't have any
                                            connection or influence with Ayojn. Ayojn holds no liability
                                            incase of any dispute or damage incurred by ARUs/ CRUs or PRUs which
                                            may occur during their transactions.&nbsp;<BR><BR>1.3
                                            Contacting us. To contact us email our customer service team:
                                            connect@ayojn.com. How to give us formal notice of any matter under
                                            the Contract is set out in clause 2.<BR>&nbsp;</P>
                                </OL>
                                <OL START=2>
                                    <LI><P>
                                            Introduction<BR><BR>2.1
                                            If you become a Registered User you agree to be legally bound by
                                            this contract.<BR><BR>2.2 You may only become a Registered User on
                                            our site for business reasons.<BR><BR>2.3 This contract is only
                                            available in English. No other languages will apply to this
                                            contract<BR><BR>2.4 When becoming a Registered User you also agree
                                            to be legally bound by:<BR><BR>(a) Our Privacy
                                            Policy.<BR><BR>(b)
                                            Our Acceptable
                                            Use Policy,
                                            which sets out the permitted uses and prohibited uses of our site.
                                            When using our site, you must comply with this Acceptable Use
                                            Policy.<BR><BR>(c) Our Website
                                            Terms and Conditions of Use Policy,
                                            which includes information about us, our rights to modify or
                                            withdraw the website and information about our Content Standards
                                            which includes material published on it or linked to from it.<BR><BR>(d)
                                            Our Cookie
                                            Policy,
                                            which sets out information about the cookies on our site.<BR><BR>(e)
                                            Our Terms
                                            and conditions of Supply
                                            as contained in this document (the Contract).</P>
                                </OL>

                                <P>   All these documents form part of this Contract as though set out in full
                                    here.</P>
                                <OL START=3>
                                    <LI><P>Our
                                            contract with you<BR><BR>3.1 Our contract. These terms and
                                            conditions (Terms) apply to the registration by you and supply of
                                            Website Services by us to you (Contract). They apply to the
                                            exclusion of any other terms that you seek to impose or incorporate,
                                            or which are implied by trade, custom, practice or course of
                                            dealing.<BR><BR>3.2 Entire agreement. The Contract is the entire
                                            agreement between you and us in relation to its subject matter. You
                                            acknowledge that you have not relied on any statement, promise or
                                            representation or assurance or warranty that is not set out in the
                                            Contract.<BR><BR>3.3 These Terms and the Contract are made only in
                                            the English language.<BR><BR>3.4 Your copy. You should print a copy
                                            of these Terms or save them to your computer for future reference.
                                        </P></li>
                                </OL>
                                <OL START=4>
                                    <LI><P>How
                                            it Works<BR><BR>4.1 Access to full website: to access the full
                                            www.ayojn.com website (the Site), users must register for an
                                            account, provide accurate and complete information, and keep their
                                            account information updated. Both CRUs and ARUs undergo the same
                                            account registration process except that CRUs may register free of
                                            charge but ARUs are required to become paid subscribers following
                                            the expiry of a 1 month free trial period.<BR><BR>4.2 Submissions:
                                            CRUs will follow the onscreen prompts to list their requirements for
                                            Outreach Services. This is known as a Project Listing.&nbsp; Project
                                            Listings will appear on the Registered User section of the website
                                            and all users may pCRUse the Project Listings.&nbsp; ARUs may
                                            respond to Project Listings by following the onscreen prompts to
                                            make a bid for the Project Listing (known as a Bid).&nbsp; The ARU
                                            will be required to list its terms and conditions for transacting
                                            any Booking as part of the Bid process.&nbsp; Project Listings and
                                            Bids are known together as Registered User Submissions (RU
                                            Submissions)<BR><BR>4.3 Moderation: RU Submissions may be moderated
                                            by us to ensure that their terms meet with our Acceptable Use
                                            Policy, Website Terms and Conditions and/ or to ensure that they are
                                            not in breach of the Contract or any applicable statutory or
                                            regulatory requirements. There may be a delay in RU Submissions
                                            appearing on the Registered User section of the website whilst such
                                            moderation takes place.&nbsp; However for the avoidance of doubt, we
                                            do not undertake to routinely oversee, monitor or moderate the
                                            content of RU Submissions.<BR><BR>4.4 Creation of Bookings: If a Bid
                                            is made by an ARU, the CRU will consider the terms of that Bid,
                                            undertake its own due diligence including in particular the terms of
                                            any contractual commitment required from it and assess whether or
                                            not to accept that Bid. If the CRU decides to accept a Bid from an
                                            ARU, it will follow the direct communication between both RUs.&nbsp;</P></li>
                                </OL>
                                <OL START=5>
                                    <LI><P>
                                            Registering
                                            as a User<BR><BR>5.1 Registration. Please follow the onscreen
                                            prompts to become a Registered User. You may only become a
                                            Registered User using the methods set out on the site.&nbsp; Once
                                            you follow the onscreen prompts and become a Registered User you
                                            have agreed to any conditions imposed on or limitations which apply
                                            to the Website Services specified in the registration process and
                                            subject to these Terms.<BR><BR>5.2 Correcting input errors. Our
                                            subscription process allows you to check and amend any errors before
                                            submitting your registration to us. Please check the registration
                                            carefully before confirming it. You are responsible for ensuring
                                            that your registration and any specification submitted by you in
                                            that registration is complete and accurate.<BR><BR>5.3 Acknowledging
                                            receipt of your registration. After you place your registration, you
                                            will receive an email from us acknowledging that we have received
                                            it, but please note that this does not mean that your registration
                                            has been accepted. Our acceptance of your registration will take
                                            place as described in clause 4.<BR><BR>5.4 Accepting your
                                            registration. Our acceptance of your registration takes place when
                                            we send an email to you to accept it (Registration Confirmation), at
                                            which point and on which date (Commencement Date) the Contract
                                            between you and us will come into existence. The Contract will
                                            relate only to those Website Services confirmed in the Registration
                                            Confirmation.<BR><BR>5.5 If we cannot accept your registration. If
                                            we are unable to supply you with the Website Services for any
                                            reason, we will inform you of this by email and we will not process
                                            your registration. If you have already paid for the Website
                                            Services, we will refund you the full amount.
                                        </P></li>
                                </OL>
                                <OL START=6>
                                    <LI><P>
                                            Our
                                            services<BR><BR>6.1 Descriptions and illustrations. Any descriptions
                                            or illustrations on our site are published for the sole purpose of
                                            giving an approximate idea of the services we provide. They will not
                                            form part of the Contract or have any contractual force.<BR><BR>6.2
                                            Compliance with specification. Subject to our right to amend the
                                            specification (see clause 3) we will supply the Website Services to
                                            you in accordance with the specification for the Website Services
                                            appearing on our website at the date of your registration in all
                                            material respects.<BR><BR>6.3 Changes to specification. We reserve
                                            the right to amend the specification of the Website Services if
                                            required by any applicable statutory or regulatory requirement
                                            within the UK or if the amendment will not materially affect the
                                            nature or quality of the Website Services, and we will notify you in
                                            advance of any such event.<BR><BR>6.4 Reasonable care and skill. We
                                            warrant to you that the Website Services will be provided using
                                            reasonable care and skill.<BR><BR>6.5 Time for performance. We will
                                            use all reasonable endeavours to meet any performance dates
                                            specified in the Registration Confirmation, but any such dates are
                                            estimates only and failure to perform the Website Services by such
                                            dates will not give you the right to terminate the Contract.
                                        </P></li>
                                </ol>
                                <OL START=7>
                                    <LI><P>
                                            Our
                                            Role in the Booking<BR><BR>7.1 Facilitation of connections. Our role
                                            is only to facilitate connections between CRUs who require Outreach
                                            Services and ARUs who may be able to offer Outreach Services. For
                                            the avoidance of doubt, we do not offer any Outreach Services
                                            whatsoever.&nbsp; Our role is to provide the Website Services
                                            through an online platform where CRUs and ARUs can find each
                                            other.<BR><BR>7.2 Responsibility for due diligence. We do not
                                            undertake or agree to and in fact will not undertake any due
                                            diligence as to the identity, creditworthiness or veracity of any
                                            information provided to us.&nbsp; It is the responsibility of the
                                            CRUs/ ARUs to select each other based on their own assessment of the
                                            information contained in the Project Listing and/or the Bid which
                                            due diligence should include, as a minimum, an assessment of the
                                            Outreach Services each requires or is offering and the contractual
                                            terms and rates offered by the other party<BR><BR>7.3 Further
                                            Information. If, after carrying out its own due diligence on the
                                            Project Listing or the Bid, an CRU or ARU considers that they
                                            require further information from the other party before entering
                                            into a Booking, it is the responsibility of the party who requires
                                            that information to obtain it.<BR><BR>7.4 Booking Contracts between
                                            Registered Users. Should you, as an CRU or ARU, make a Booking with
                                            another Registered User, the Booking Contract entered into will be
                                            on terms negotiated between you directly and you undertake to
                                            procure and pay for your own legal advice and assistance on the
                                            terms and conditions of that contract and the advisability or
                                            otherwise of entering into it.<BR><BR>7.5 No authority. We shall
                                            have no authority to:<BR><BR>(a) obtain Bookings from Registered
                                            Users; or<BR><BR>(b) enter into or conclude contracts with any
                                            person or company, including the Registered Users, for Bookings of
                                            the Stand Services.
                                        </P></li>
                                </OL>
                                <OL START=8>
                                    <LI><P>
                                            Obtaining
                                            a refund<BR><BR>NO refunds will be offered once the payment is made.
                                    </li></P>
                                </OL>
                                <OL START=9>
                                    <LI><P>
                                            Warranties
                                        </P></li>
                                </OL>
                                <OL START=10>
                                    <LI><P>
                                            We enter into this Contract based on the following assumptions and
                                            you undertake and warrant that they are accurate:<BR><BR>(a) that
                                            where you are an individual or sole trader Registered User that you
                                            are at least 18 years old;<BR><BR>(b) that where you are registering
                                            on behalf of a firm, company or other legal entity, that you have
                                            authority to enter into legal agreements on behalf of that
                                            organisation and to bind that organisation;<BR><BR>(c) that in
                                            entering into this agreement you understand and agree that the
                                            Website Services are intended to facilitate introductions between
                                            CRUs and ARUs only and that the terms and conditions of any Booking
                                            will be on separate terms agreed between those parties which we will
                                            not be a party to;<BR><BR>(d) that you warrant and represent to us
                                            that you are who you have held yourself out to be in the
                                            registration process and that you have the full right, power and
                                            authority to enter into, deliver and perform this agreement and any
                                            associated Booking;<BR><BR>(e) that in entering into this agreement
                                            you understand and agree that we do not undertake or agree to and in
                                            fact will not undertake any due diligence as to the identity,
                                            creditworthiness or veracity of any information provided to us in
                                            the specifications provided by Registered Users at registration and
                                            we expressly reliance on the warranty in Clause 1(d) and that you
                                            will act in accordance with your obligation in Clause 12.1(a);<BR><BR>(f)
                                            that you will provide us with all information required by or under
                                            the Electronic Commerce (EC Directive) Regulations 2002
                                            and or the Provision
                                            of Services Regulations 2009
                                            and agree that we are authorised to and will disclose such
                                            information when the Booking is confirmed;<BR><BR>(g) that you will
                                            act in good faith in connection with the Website Services, the
                                            Project Listing and/ or Bid and the provision of the Outreach
                                            Services and that you will enter into and perform any Booking
                                            Contract in good faith;<BR><BR>(h) that, where you are a ARU, you
                                            will during this Contract and in connection with the Outreach
                                            Services maintain in force with reputable underwriters or insurance
                                            companies, in commercially prudent amounts, policies of insurance
                                            against the risks legally required in the jurisdiction where the
                                            Booking Contract is to be performed and/ or customarily covered by
                                            companies providing similar services (which should as a minimum
                                            cover public and third party liability, business interruption and
                                            other appropriate risks) and that if requested to do so by the CRU,
                                            you shall provide them with copies of relevant policy certificates
                                            and details of the cover provided;<BR><BR>(i) that, in line with the
                                            Financial Action Task Force (FATF) recommendations on anti-money
                                            laundering and combating the financing of terrorism (AML/CFT), you
                                            are not domiciled in a high-risk, non-cooperative jurisdiction or
                                            monitored jurisdiction;<BR><BR>(j) that you are not economically
                                            barred /limited under the sanctions rules of any country worldwide
                                            and if your circumstances change such that you are included in such
                                            lists, you will cease using the website immediately;<BR><BR>(k) that
                                            Registered Users will not attempt to circumvent our processes and
                                            attempt to or actually exchange contact information for the purpose
                                            of soliciting Outreach Services or sales outside of this Contract
                                            and that if you are found to do so you will become liable for the
                                            Confirmation Fee as if the transaction had been processed
                                            appropriately under these Terms.<BR><BR>10.2 We enter into the
                                            Contract to express reliance on the assumptions in Clause 1.
                                        </P></li>
                                </OL>
                                <OL START=11>
                                    <LI><P>
                                            Indemnity<BR><BR>11.1
                                            You agree and undertake to indemnify us against all liabilities,
                                            costs, expenses, damages and losses (including but not limited to
                                            any direct, indirect or consequential losses, loss of profit, loss
                                            of reputation and all interest, penalties and legal costs
                                            (calculated on a full indemnity basis) and all other professional
                                            costs and expenses suffered or incurred by us arising out of or in
                                            connection with:<BR><BR>(a) any breach of the warranties contained
                                            in Clause 1;<BR><BR>(b) your breach or negligent performance or
                                            non-performance of this agreement;<BR><BR>(c) any claim made against
                                            us for actual or alleged infringement of a third party's
                                            intellectual property rights arising out of or in connection with
                                            the RU Submissions or the Outreach Services;<BR><BR>(d) any claim
                                            made against us by a third party arising out of or in connection
                                            with the provision of the Outreach Services, to the extent that such
                                            claim arises out of the breach, negligent performance or failure or
                                            delay in performance of this agreement by you, your employees,
                                            agents or subcontractors;<BR><BR>(e) any claim made against us by a
                                            third party for death, personal injury or damage to property arising
                                            out of or in connection with defective Goods, to the extent that the
                                            defect in the Goods is attributable to your acts or omissions, or
                                            those of your employees, agents or subcontractors.<BR><BR>11.2 This
                                            indemnity shall apply whether or not you have been negligent or at
                                            fault.<BR><BR>11.3 If a payment due from you under this clause is
                                            subject to tax (whether by way of direct assessment or withholding
                                            at its source), we shall be entitled to receive from you such
                                            amounts as shall ensure that the net receipt, after tax, to us in
                                            respect of the payment is the same as it would have been were the
                                            payment not subject to tax.
                                    </li></P>
                                </OL>
                                <OL START=12>
                                    <LI><P>
                                            Your
                                            obligations<BR><BR>12.1 It is your responsibility to ensure
                                            that:<BR><BR>(a) the terms of your registration are complete and
                                            accurate;<BR><BR>(b) you cooperate with us in all matters relating
                                            to the Website Services;<BR><BR>(c) you provide us with such
                                            information as we may reasonably require at registration to supply
                                            the Website Services, and ensure that such information is complete
                                            and accurate in all material respects;<BR><BR>(d) the performance of
                                            the Outreach Services listed in the Project Listing and/ or Bid is
                                            legally permitted in the jurisdiction where those Outreach Services
                                            are to be performed;<BR><BR>(e) if you are a CRU, that you are
                                            acting in good faith and you intend to proceed with any Booking
                                            which results from the supply of the Website Services;<BR><BR>(f) if
                                            you are a ARU, that you are acting in good faith and intend to and
                                            are able to fulfil the terms of any Booking which results from the
                                            supply of the Website Services;<BR><BR>(g) you obtain and maintain
                                            all necessary licences, permissions and consents which may be
                                            required in your jurisdiction for the Website Services or the
                                            Booking before the date on which the Website Services and/ or the
                                            Booking are to start;<BR><BR>(h) you comply with all applicable
                                            laws, relevant to the provision of the Website Services and the
                                            Booking;<BR><BR>(i) you read, understand and comply with our Website
                                            Terms &amp; Conditions, Acceptable Use Policy, Privacy Notice and
                                            Cookies Policy;<BR><BR>(j) you do not register for more than one
                                            account;<BR><BR>(k) the information that you supply in the public
                                            profile of your account complies with our Website Terms of
                                            Use.<BR><BR>12.2 If our ability to perform the Website Services is
                                            prevented or delayed by any failure by you to fulfil any obligation
                                            listed in clause 1 (Your Default):<BR><BR>(a) we will be entitled to
                                            suspend performance of the Website Services until you remedy Your
                                            Default, and to rely on Your Default to relieve us from the
                                            performance of the Website Services, in each case to the extent Your
                                            Default prevents or delays performance of the Website Services. In
                                            certain circumstances Your Default may entitle us to terminate the
                                            contract under clause 20 (Termination);<BR><BR>(b) we will not be
                                            responsible for any costs or losses you sustain or incur arising
                                            directly or indirectly from our failure or delay to perform the
                                            Website Services; and<BR><BR>(c) it will be your responsibility to
                                            reimburse us on written demand for any costs or losses we sustain or
                                            incur arising directly or indirectly from Your Default.</li></P>
                                </OL>
                                <OL START=13>
                                    <LI><P>
                                            Charges<BR><BR>13.1
                                            In consideration of us providing the Website Services you must pay
                                            our charges (Charges) in accordance with this clause 13.<BR><BR>13.2
                                            The Charges are the prices quoted on our site at the time you submit
                                            your registration.<BR><BR>13.3 If you wish to change the scope of
                                            the Website Services after we accept your registration, and we agree
                                            to such change, we will modify the Charges accordingly.<BR><BR>13.4
                                            We use our best efforts to ensure that the prices stated for the
                                            Website Services are correct at the time when the relevant
                                            information was entered into the system. However, please see clause
                                            7 for what happens if we discover an error in the price of the
                                            Website Services you registered.<BR><BR>13.5 We reserve the right to
                                            increase the Charges from time-to-time and we will notify you
                                            usually by email if we do so. You undertake and agree to accept such
                                            notification by email and that any such notice sent in this manner
                                            constitutes a valid notice of variation in accordance with the
                                            Contract.<BR><BR>13. 6 Our Charges are exclusive of VAT calculated
                                            at the prevailing rate in the UK as amended from time-to-time.<BR><BR>13.
                                            7 It is always possible that, despite our reasonable efforts, some
                                            of the Website Services on our site may be incorrectly priced. If
                                            the correct price for the Website Services is higher than the price
                                            stated on our site, we will contact you as soon as possible to
                                            inform you of this error and we will give you the option of
                                            continuing to purchase the Website Services at the correct price or
                                            cancelling your registration. We will not process your registration
                                            until we have your instructions. If we are unable to contact you
                                            using the contact details you provided during the registration
                                            process, we will treat the registration as cancelled and notify you
                                            in writing. However, if we mistakenly accept and process your
                                            registration where a pricing error is obvious and unmistakable and
                                            could reasonably have been recognised by you as a mispricing, we may
                                            cancel supply of the Website Services and refund you any sums you
                                            have paid.
                                        </P></li>
                                </OL>
                                <OL START=14>
                                    <LI><P>
                                            How
                                            to pay<BR><BR>14.1 Payment for the Website Services is in advance
                                            using a payment system powered by Paypal (www.paypal.com) who are
                                            responsible for the security of monies paid. You should familiarise
                                            yourself with the Stripe Terms and Conditions which can be reviewed
                                            by clicking on the following link
                                            <A HREF="https://www.paypal.com/uk/webapps/mpp/ua/privacy-full" target="_blank">https://www.paypal.com/uk/webapps/mpp/ua/privacy-full</A>.<BR><BR>14.2
                                            All of our subscription charges are based on United States Dollars
                                            (USD). If you bank in a different currency, your bank will be
                                            responsible for setting the exchange rate and converting our USD
                                            prices into your currency. Confirmation Fee charges can be made as
                                            Pounds Sterling, Euro or United States Dollars as specified in the
                                            corresponding Project Listing and as chosen by the CRU who published
                                            the Project Listing.<BR><BR>14.3 Payment for the Website Services is
                                            by direct deduction using the credit or debit card you used when you
                                            registered. This credit or debit card will be charged automatically
                                            each month - we accept the following cards:&nbsp; Visa, MasterCard,
                                            and American Express. The Confirmation Fee becomes payable when the
                                            Booking is confirmed by the RU after following the relevant onscreen
                                            prompts. You can pay for the Confirmation Fee via bank transfer
                                            using the bank account details which appear on the invoice you
                                            receive from us.
                                        </P></li>
                                </OL>
                                <OL START=15>
                                    <LI><P>
                                            Complaints<BR><BR>If a problem arises or you are dissatisfied with the Website Services,
                                            please notify us at connect@ayojn.com.&nbsp;
                                            It will assist us in achieving a timely and efficient resolution if
                                            you provide as much detail as possible about your issue when you
                                            notify us of your complaint.</P></li>
                                </OL>
                                <OL START=16>
                                    <LI><P>
                                            Intellectual
                                            property rights<BR><BR>16.1 All intellectual property rights in or
                                            arising out of or in connection with the Website Services (other
                                            than intellectual property rights in any materials provided by you)
                                            will be owned by us.<BR><BR>16.2 We agree to grant you a licence for
                                            the purpose of receiving and using the Website Services and such
                                            deliverables in your business. You may not sub-license, assign or
                                            otherwise transfer the rights granted in this clause 16.2.<BR><BR>16.3
                                            You agree to grant us a fully paid-up, non-exclusive, royalty-free,
                                            non-transferable licence to copy and modify any materials provided
                                            by you to us for the term of the Contract for the purpose of
                                            providing the Website Services to you.</li>
                                    </P>
                                </OL>
                                <OL START=17>
                                    <LI><P>
                                            How
                                            we may use your personal information<BR><BR>17.1 We will use any
                                            personal information you provide to us to:<BR><BR>(a) provide the
                                            Website Services;<BR><BR>(b) process your payment for the Website
                                            Services; and<BR><BR>(c) inform you about similar services that we
                                            provide, but you may stop receiving these at any time by contacting
                                            us.<BR><BR>17.2 Further details of how we will process personal
                                            information are set out in our Privacy Policy.</li>
                                    </P>
                                </OL>
                                <OL START=18>
                                    <LI><P>
                                            Limitation
                                            of liability: <BR><br>18.1 We have obtained insurance cover in respect
                                            of our own legal liability for individual claims not exceeding
                                            £100,000per claim. The limits and exclusions in this clause reflect
                                            the insurance cover we have been able to arrange and you are
                                            responsible for making your own arrangements for the insurance of
                                            any excess loss.<BR><BR>18.2 Nothing in the Contract limits any
                                            liability which cannot legally be limited, including liability
                                            for:<BR><BR>(a) death or personal injury caused by negligence;<BR><BR>(b)
                                            fraud or fraudulent misrepresentation; and<BR><BR>(c) breach of the
                                            terms implied by section 2 of the Supply of Goods and Services Act
                                            1982 (title and quiet possession).<BR><BR>18.3 Subject to clause 2,
                                            we will not be liable to you, whether in contract, tort (including
                                            negligence), for breach of statutory duty, or otherwise, arising
                                            under or in connection with the Contract for:<BR><BR>(a) loss of
                                            profits;<BR><BR>(b) loss of sales or business;<BR><BR>(c) loss of
                                            agreements or contracts;<BR><BR>(d) loss of anticipated
                                            savings;<BR><BR>(e) loss of use or corruption of software, data or
                                            information;<BR><BR>(f) loss of or damage to goodwill; and<BR><BR>(g)
                                            any indirect or consequential loss.<BR><BR>18.4 Subject to clause 2,
                                            our total liability to you arising under or in connection with the
                                            Contract, whether in contract, tort (including negligence), breach
                                            of statutory duty, or otherwise, will be limited to the total
                                            Charges paid under the Contract.<BR><BR>18.5 We have given
                                            commitments as to compliance of the Website Services with the
                                            relevant specification in clause 2. In view of these commitments,
                                            the terms implied by sections 3, 4 and 5 of the Supply of Goods and
                                            Services Act 1982 are, to the fullest extent permitted by law,
                                            excluded from the Contract.<BR><BR>18.6 Unless you notify us that
                                            you intend to make a claim in respect of an event within the notice
                                            period, we shall have no liability for that event. The notice period
                                            for an event shall start on the day on which you became, or ought
                                            reasonably to have become, aware of the event having occurred and
                                            shall expire 12 months from that date. The notice must be in writing
                                            and must identify the event and the grounds for the claim in
                                            reasonable detail.<BR><BR>18.7 Nothing in these Terms limits or
                                            affects the exclusions and limitations set out in our Terms and
                                            Conditions of Use.<BR><BR>18.8 This clause 18 will survive
                                            termination of the Contract.</li>
                                    </P>
                                </OL>
                                <OL START=19>
                                    <LI><P>
                                            Confidentiality<BR><BR>19.1
                                            We each undertake that we will not at any time disclose to any
                                            person any confidential information concerning one another's
                                            business, affairs, customers, clients or suppliers, except as
                                            permitted by clause 19.2.<BR><BR>19.2 We each may disclose the
                                            other's confidential information:<BR><BR>(a) to such of our
                                            respective employees, officers, representatives, subcontractors or
                                            advisers who need to know such information for the purposes of
                                            carrying out our respective obligations under the Contract. We will
                                            each ensure that such employees, officers, representatives,
                                            subcontractors or advisers comply with this clause 19; and<BR><BR>(b)
                                            as may be required by law, a court of competent jurisdiction or any
                                            governmental or regulatory authority.<BR><BR>(c) Each of us may only
                                            use the other's confidential information for the purpose of
                                            fulfilling our respective obligations under the Contract.
                                        </P></li>
                                </OL>
                                <OL START=20>
                                    <LI><P>
                                            Status<BR><BR>20.1
                                            Nothing in this agreement is intended to, or shall be deemed to,
                                            establish any partnership or joint venture between any of the
                                            parties, constitute any party the agent of another party, or
                                            authorise any party to make or enter into any commitments for or on
                                            behalf of any other party.<BR><BR>20.2 Each party confirms it is
                                            acting on its own behalf and not for the benefit of any other
                                            person.
                                        </P></li>
                                </OL>
                                <OL START=21>
                                    <LI><P>
                                            Dispute
                                            resolution procedure<BR><BR>21.1 If a dispute arises out of or in
                                            connection with this agreement or the performance, validity or
                                            enforceability of it (Dispute), then the parties shall follow the
                                            procedure set out in this clause.<BR><BR>21.2 Either party shall
                                            give to the other notice of the Dispute by email, setting out its
                                            nature and full particulars (Dispute Notice), together with relevant
                                            supporting documents. On service of the Dispute Notice, the parties
                                            shall attempt in good faith to resolve the Dispute.<BR><BR>21.3 No
                                            party may commence any arbitration proceedings under clause 25
                                            (Arbitration) in relation to the whole or part of the Dispute until
                                            180 days after service of the ADR notice, provided that the right to
                                            issue proceedings is not prejudiced by a delay.<BR><BR>21.4 If the
                                            dispute is not settled by mediation within 180 days of service of
                                            the ADR notice or within such further period as the parties may
                                            agree in writing, either party may issue arbitration or court
                                            proceedings in accordance with clause 25 (Arbitration) in this
                                            Agreement.
                                        </P></li>
                                </OL>
                                <OL START=22>
                                    <LI><P>
                                            Termination<BR><BR>22.1
                                            Without limiting any of our other rights, we may suspend the
                                            performance of the Website Services, or terminate the Contract with
                                            immediate effect by giving written notice to you if:<BR><BR>(a) you
                                            commit a material breach of any term of the Contract and (if such a
                                            breach is remediable) fail to remedy that breach within 14 days of
                                            you being notified in writing to do so;<BR><BR>(b) you fail to pay
                                            any amount due under the Contract on the due date for payment;<BR><BR>(c)
                                            you take any step or action in connection with you entering
                                            administration, provisional liquidation or any composition or
                                            arrangement with your creditors (other than in relation to a solvent
                                            restructuring), being wound up (whether voluntarily or by
                                            registration of the court, unless for the purpose of a solvent
                                            restructuring), having a receiver appointed to any of your assets or
                                            ceasing to carry on business or, if the step or action is taken in
                                            another jurisdiction, in connection with any analogous procedure in
                                            the relevant jurisdiction;<BR><BR>(d) you suspend, threaten to
                                            suspend, cease or threaten to cease to carry on all or a substantial
                                            part of your business; or<BR><BR>(e) your financial position
                                            deteriorates to such an extent that in our opinion your capability
                                            to adequately fulfil your obligations under the Contract has been
                                            placed in jeopardy.<BR><BR><BR>22.2 Termination of the Contract will
                                            not affect your or our rights and remedies that have accrued as at
                                            termination.<BR><BR>22.3 Any provision of the Contract that
                                            expressly or by implication is intended to come into or continue in
                                            force on or after termination will remain in full force and
                                            effect.
                                        </P></li>
                                </OL>
                                <OL START=23>
                                    <LI><P>
                                            Events
                                            outside our control<BR><BR>23.1 We will not be liable or responsible
                                            for any failure to perform, or delay in performance of, any of our
                                            obligations under the Contract that is caused by any act or event
                                            beyond our reasonable control (Event Outside Our Control).<BR><BR>23.2
                                            If an Event Outside Our Control takes place that affects the
                                            performance of our obligations under the Contract:<BR><BR>(a) we
                                            will contact you as soon as reasonably possible to notify you;
                                            and<BR><BR>(b) our obligations under the Contract will be suspended
                                            and the time for performance of our obligations will be extended for
                                            the duration of the Event Outside Our Control. We will arrange a new
                                            date for performance of the Website Services with you after the
                                            Event Outside Our Control is over.<BR><BR>23.3 You may cancel the
                                            Contract affected by an Event Outside Our Control which has
                                            continued for more than 30 days. To cancel please contact us. If you
                                            opt to cancel we will refund the price you have paid, less the
                                            charges reasonably and actually incurred us by performing the
                                            Website Services up to the date of the occurrence of the Event
                                            Outside Our Control.
                                        </P></li>
                                </OL>
                                <OL START=24>
                                    <LI><P>
                                            Communications
                                            between us<BR><BR>24.1 When we refer to &quot;in writing&quot; in
                                            these Terms, this includes email.<BR><BR>24.2 Any notice or other
                                            communication given under or in connection with the Contract must be
                                            in writing and be delivered personally, sent by pre-paid first class
                                            post or other next working day delivery service, or email.<BR><BR>24.3
                                            A notice or other communication is deemed to have been
                                            received:<BR><BR>(a) if delivered personally, on signature of a
                                            delivery receipt;<BR><BR>(b) if sent by pre-paid first class post or
                                            other next working day delivery service, at 9.00 am on the second
                                            working day after posting; or<BR><BR>(c) if sent by email, at 9.00
                                            am the next working day after transmission.<BR><BR>24.4 In proving
                                            the service of any notice, it will be sufficient to prove, in the
                                            case of a letter, that such letter was properly addressed, stamped
                                            and placed in the post and, in the case of an email, that such email
                                            was sent to the specified email address of the addressee.<BR><BR>24.5
                                            The provisions of this clause will not apply to the service of any
                                            proceedings or other documents in any legal action.
                                        </P></li>
                                </OL>
                                <OL START=25>
                                    <LI><P>
                                            Arbitration<BR><BR>25.1
                                            Any dispute arising out of or in connection with this contract,
                                            including any question regarding its existence, validity or
                                            termination, shall be referred to and finally resolved by
                                            arbitration under the LCIA Rules, which Rules are deemed to be
                                            incorporated by reference into this clause.<BR><BR>25.2 The number
                                            of arbitrators shall be one.<BR><BR>25.3 The seat, or legal place,
                                            of arbitration shall be London, United Kingdom.<BR><BR>25.4 The
                                            language to be used in the arbitral proceedings shall be
                                            English.<BR><BR>25.5 The governing law of the contract shall be the
                                            substantive law of England &amp; Wales.
                                        </P></li>
                                </OL>
                                <OL START=26>
                                    <LI><P>
                                            General<BR><BR>26.1
                                            Assignment and transfer.<BR><BR>(a) We may assign or transfer our
                                            rights and obligations under the Contract to another entity but will
                                            always notify you by posting on this webpage if this happens.<BR><BR>(b)
                                            You may only assign or transfer your rights or your obligations
                                            under the Contract to another person if we agree in writing.<BR><BR>26.2
                                            Variation. Any variation of the Contract only has effect if it is in
                                            writing and signed by you and us (or our respective authorised
                                            representatives).<BR><BR>26.3 Waiver If we do not insist that you
                                            perform any of your obligations under the Contract, or if we do not
                                            enforce our rights against you, or if we delay in doing so, that
                                            will not mean that we have waived our rights against you or that you
                                            do not have to comply with those obligations. If we do waive any
                                            rights, we will only do so in writing, and that will not mean that
                                            we will automatically waive any right related to any later default
                                            by you.<BR><BR>26.4 Severance. Each paragraph of these Terms
                                            operates separately. If any court or relevant authority decides that
                                            any of them is unlawful or unenforceable, the remaining paragraphs
                                            will remain in full force and effect.<BR><BR>26.5 Third party
                                            rights. The Contract is between you and us. No other person has any
                                            rights to enforce any of its terms.</P></li>
                                </OL>

                                <P STYLE="background: #34495e; text-align:center;">
                                    <FONT COLOR="#ffffff"><B>Ayojn
                                            Limited</B><br/>
                                        Copyright © Ayojn
                                        Limited 2020&nbsp;</FONT></style></P>


                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
<div id="privacyText" class="modal " role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">

            <div class="modal-body">
                <h2>Privacy Policy</h2>
                <div style="padding-left: 10px;padding-right: 10px;">
                    <p>The policy: This privacy policy notice is served by Ayojn Limited, Kemp House, 160 City Road, London, EC1V 2NX under the website; www.ayojn.com. The purpose of this policy is to explain to you how we control, process, handle and protect your personal information through the business and while you browse or use this website. If you do not agree to the following policy you may wish to cease viewing / using this website, and or refrain from submitting your personal data to us.
                    </p>
                    <h5>Policy key definitions:</h5>
                    <p>
                        <ul class="moveleft">
                            <li>"I", "our", "us", or "we" refer to the business, Ayojn Limited.</li>
                            <li>"you", "the user" refer to the person(s) using this website.</li>
                             <li>GDPR means General Data Protection Act.</li>
                                <li>   PECR means Privacy & Electronic Communications Regulation.</li>
                                <li>    ICO means Information Commissioner's Office.</li>
                                <li>    Cookies mean small files stored on a users’ computer or device.</li>

                        </ul>
                    </p>
                    <h5>Key principles of GDPR:</h5>
                    <p>Our privacy policy embodies the following key priciples; (a) Lawfullness, fairness and transparency, (b) Purpose limitation, (c) Data minimisation, (d) Accuracy, (e) Storage limitation, (f) Integrity and confidence, (g) Accountability.</p>
                    <h5>Processing of your personal data</h5>
                    <p>Under the GDPR (General Data Protection Regulation) we control and / or process any personal information about you electronically using the following lawful bases.</p>
                    <p>
                        <ul class="moveleft">
                            <li>We are exempt from registration in the ICO Data Protection Register because:
                            </li>
                            <li>We only process information necessary to establish or maintain membership or support.                   </li>
                        <li>We only process information necessary to provide or administer activities for people who are members of the organisation or have regular contact with it; </li>
                        <li>We only hold information about individuals whose data you need to process for this exempt purpose
                        </li>
                        <li>The personal data we process is restricted to personal information that is necessary for this exempt purpose
                        </li>
                        </ul>
                    </p>
                    <h5>In this section we have set out:</h5>
                    <p>
<ul class="moveleft">
<li>the general categories of personal data that we may process;
</li>
<li>the purposes for which we may process personal data; and
</li>
<li>the legal basis of the processing.
</li>

</ul>
                    </p>
                    <p>We may process data about your use of our website and services ("usage data"). The usage data may include your IP address, approximate geographical location, browser type and version, operating system, referral source, length of visit, page views and website navigation paths, as well as information about the timing, frequency and pattern of your service use. The source of the usage data is our analytics tracking system, Google Analytics, and Google Adwords. This usage data may be processed for the purposes of analysing the use of the website and services. The legal basis for this processing is our legitimate interests, namely monitoring and improving our website and services.</p>
                    <p>We may process your account data ("account data"). The account data may include - but is not limited to - your name, email address, company, locations. The source of the account data is you. The account data may be processed for the purposes of operating our website, providing our services to you, ensuring the security of our website and services, maintaining back-ups of our databases and communicating with you. The legal basis for this processing is our legitimate interests, namely the proper administration of our website and business.
                    </p>
                    <p>This policy is effective as of 1 June 2020.We may process information contained in any listing you submit to us regarding Sponsored Outreach services ("listing data"). The enquiry data may include - but is not limited to - details of where you require it, the dates you require the service, the scale of the service. The source of the service data is you. If you do not accept and finalise a service from us we will store a record of the unfinished listing in your Dashboard for your own future reference. The service data may be processed for the purposes of operating our website, enabling any purchased/finalised projects to happen, ensuring the security of our website and services, maintaining back-ups of our databases and communicating with you. The legal basis for this processing is our legitimate interests.
                    </p>
                    <p>
                        We may process your personal data that are provided in the course of the use of our services ("service data"). The service data may include - but is not limited to - details of where you require the service, the dates you require the service, the scale of the service. The source of the service data is you. The service data may be processed for the purposes of operating our website, enabling any purchased/finalised projects to happen, ensuring the security of our website and services, maintaining back-ups of our databases and communicating with you. The legal basis for this processing is our legitimate interests.

                    </p>
                    <p>
                        We may process information relating to transactions, including purchases of goods and services, that you enter into with us and/or through our website ("transaction data"). The transaction data may include your contact details, and the transaction details. We never store card details. The transaction data may be processed for the purpose of supplying the purchased goods and services and keeping proper records of those transactions. The legal basis for this processing is our legitimate interests, namely our interest in the proper administration of our website and business.

                    </p>
                    <p>
                        We may process information that you provide to us for the purpose of unsubscribing to our email notifications and/or newsletters ("notification data"). The notification data may be processed for the purposes of sending you relevant notifications and/or newsletters. The legal basis for this processing is our legitimate interests, namely providing customers, who have expressed an interest in buying our services, up to date with our latest features, offers, and news. You can opt out of emails by click the unsubscribe link on any of our emails.
                    </p>
                    <p>
                        We may process information contained in or relating to any communication that you send to us ("correspondence data"). The correspondence data may include the communication content and metadata associated with the communication. We also record phone call content and the times of calls. Our website will generate the metadata associated with communications made using the website contact forms. The correspondence data may be processed for the purposes of communicating with you and record-keeping. The legal basis for this processing is our legitimate interests, namely the proper administration of our website and business and communications with users.
                    </p>
                    <p>
                        We may process any of your personal data identified in this policy where necessary for the establishment, exercise or defence of legal claims, whether in court proceedings or in an administrative or out-of-court procedure. The legal basis for this processing is our legitimate interests, namely the protection and assertion of our legal rights, your legal rights and the legal rights of others.

                    </p>
                    <p>
                        We may process any of your personal data identified in this policy where necessary for the purposes of obtaining or maintaining insurance coverage, managing risks, or obtaining professional advice. The legal basis for this processing is our legitimate interests, namely the proper protection of our business against risks.
                    </p>
                    <h5>Providing your personal data to others
                    </h5>
                    <p>
                        We may disclose company name, address, contact names, phone numbers, and listing details to suppliers insofar as reasonably necessary for the purposes of providing sponsored outreach services you have requested. The services providers are only allowed to use and retain your data to allow the service you have requested to complete.

                    </p>
                    <p>Financial transactions relating to our website and services are handled by our payment services provider, Paypal. We will share transaction data with our payment services providers only to the extent necessary for the purposes of processing your payments, refunding such payments and dealing with complaints and queries relating to such payments and refunds. You can find information about the payment services provider’s privacy policies and practices at www.paypal.com.
                    </p>
                    <p>
                        In addition to the specific disclosures of personal data set out in this Section 4, we may disclose your personal data where such disclosure is necessary for compliance with a legal obligation to which we are subject, or in order to protect your vital interests or the vital interests of another natural person. We may also disclose your personal data where such disclosure is necessary for the establishment, exercise or defence of legal claims, whether in court proceedings or in an administrative or out-of-court procedure.

                    </p>
                    <h5>Providing Outreach services providers data to you
                    </h5>
                    <p>
                        We may disclose Outreach services providers contact data (name, phone number, company details) to you insofar as reasonably necessary for the purposes of providing the services you have requested. You are only allowed to use and retain this data to allow the service you have requested to complete.


                    </p>
                    <h5>Retaining and deleting personal data
                    </h5>
                    <p>This Section sets out our data retention policies and procedures, which are designed to help ensure that we comply with our legal obligations in relation to the retention and deletion of personal data.<br/>
                        Personal data that we process for any purpose or purposes shall not be kept for longer than is necessary for that purpose or those purposes.
                    </p>
                    <h5>We will retain your personal data as follows:
                    </h5>
                    <p>
                        Customer name, emails, listing information (including dates, listing specifics, and locations), service reviews. We will retain this information on your behalf for 6 years, to allow you to access your historical account details and, if eligible, any discounts or account credit that you may have received or earned as part of any incentives, offers, or promotions.<br/>
                        Notwithstanding the other provisions of this section, we may retain your personal data where such retention is necessary for compliance with a legal obligation to which we are subject, or in order to protect your vital interests or the vital interests of another natural person.

                    </p>
                    <h5>Amendments
                    </h5>
                    <p>
                        We may update this policy from time to time by publishing a new version on our website.<br/>
                        You should check this page occasionally to ensure you are happy with any changes to this policy.<br/>
                        We may notify you of changes to this policy by email.<br/>
                        If, as determined by us, the lawful basis upon which we process your personal information changes, we will notify you about the change and any new lawful basis to be used if required. We shall stop processing your personal information if the lawful basis used is no longer relevant.

                    </p>
                    <h5>Your individual rights</h5>
                    <p>
                        Under the GDPR your rights are as follows. You can read more about your rights in details here;
<ul class="moveleft">
                        <li>the right to be informed; </li>
                        <li> the right of access; </li>
                        <li> the right to rectification; </li>
                        <li> the right to erasure; </li>
                        <li> the right to restrict processing; </li>
                        <li> the right to data portability; </li>
                        <li> the right to object; and </li>
                        <li>  the right not to be subject to automated decision-making including profiling. </li>


                    </ul>
<br/>
                    You also have the right to complain to the ICO (www.ico.org.uk) if you feel there is a problem with the way we are handling your data.
<br/>
                    We handle subject access requests in accordance with the GDPR.

                    </p>

                    <h5>Internet cookies</h5>
                    <p>We use cookies on this website to provide you with a better user experience. We do this by placing a small text file on your device / computer hard drive to track how you use the website, to record or log whether you have seen particular messages that we display, to keep you logged into the website where applicable, to display relevant adverts or content, referred you to a third party website.</p>
                    <p>Some cookies are required to enjoy and use the full functionality of this website.
                    </p>
                    <p>We use a cookie control system which allows you to accept the use of cookies, and control which cookies are saved to your device / computer. Some cookies will be saved for specific time periods, where others may last indefinitely. Your web browser should provide you with the controls to manage and delete cookies from your device, please see your web browser options.
                    </p>
                    <p>Further information can be requested by emailing connect@ayojn.com
                    </p>
                    <h5>Data security and protection
                    </h5>
                    <p>We ensure the security of any personal information we hold by using secure data storage technologies and precise procedures in how we store, access and manage that information. Our methods meet the GDPR compliance requirement.
                    </p>
                    <h5>Fair & Transparent Privacy Explained
                    </h5>
                    <p>
                        We have provided some further explanations about user privacy and the way we use this website to help promote a transparent and honest user privacy methodology.

                    </p>
                    <h5>Email marketing messages & subscription
                    </h5>
                    <p>

                        Under the GDPR we use the consent lawful basis for anyone subscribing to our newsletter or marketing mailing list. We only collect certain data about you, as detailed in the "Processing of your personal data" above. Any email marketing messages we send are done so through an EMS, email marketing service provider. An EMS is a third-party service provider of software / applications that allows marketers to send out email marketing campaigns to a list of users.<br/>
                        Email marketing messages that we send may contain tracking beacons / tracked clickable links or similar server technologies in order to track subscriber activity within email marketing messages. Where used, such marketing messages may record a range of data such as; times, dates, I.P addresses, opens, clicks, forwards, geographic and demographic data. Such data, within its limitations will show the activity each subscriber made for that email campaign.<br/>
                        Any email marketing messages we send are in accordance with the GDPR and the PECR. We provide you with an easy method to withdraw your consent (unsubscribe) or manage your preferences / the information we hold about you at any time. See any marketing messages for instructions on how to unsubscribe or manage your preferences, otherwise contact the EMS provider.<br/>
                        Our EMS provider is; Google. We hold the following information about you within our EMS system;<br/>
                       <ul class="moveleft"> <li>Email address</li>
                        <li>I.P address</li>
                        <li>Subscription time & date</li>
                    </ul>
                        <ul class="moveleft">Resources & further information
                        <li>Overview of the GDPR - General Data Protection Regulation</li>
                        <li>Data Protection Act 2018</li>
                        <li>Privacy and Electronic Communications Regulations 2003</li>
                        <li> The Guide to the PECR 2003</li>
                    </ul>

                    </p>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div id="howtouseText" class="modal " role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <!-- The data reflected at Ayojn is calibrated from the public domain access plus based on the inputs from the Users (Sponsors and Sponsorship Receivers/ Managers).-->
            <div class="modal-body">
                <h3 style="text-align: center"><b>How it Works!</b></h3>
                <p>1. Select if you wish to Offer/ Receive Sponsorship </p>
                <br>
                <p>2. Share what you wish to Sponsor or Get Sponsored </p>
                <br>
                <p>3. We Validate your Profile </p>
                <br>
                <p>4. Go Live - Spot Trends, Gain Insights & Discover Sponsorship Opportunities </p>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<div id="refer-user"></div>
<!-- login popup END -->
<div id="popupcountry"></div>
<div id="popupdestination"></div>
<div id="popupindustries"></div>
@if (Session::has('error_message'))
    <script>
        setTimeout(function () {
            toastr.error("{!! Session::get('error_message') !!}", 'Error');
        }, 1000);
    </script>
@endif
@if (Session::has('success_message'))
    <script>
        setTimeout(function () {
            toastr.success("{!! Session::get('success_message') !!}", 'Success');
        }, 1000);
    </script>
@endif
<!-- <script src="{{ asset('js/jquery1.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script> -->
<script src="{{ asset('js/typed.min.js') }}"></script>
<script src="{{ asset('js/custom-jquery-new.js') }}"></script>

<script src="{{ asset('js/jquery.bs.gdpr.cookies.js') }}"></script>
<script type="text/javascript">

    function displayRegister() {
        $("#loginForm").modal('hide');
        $("#signupForm").modal('show');
    }

    //comment by Akash
    var settings = {
        message: 'We use tools, such as cookies, to enable essential services and functionality on our site and to collect data on how visitors interact with our site, products and services. By clicking Accept, you agree to our use of these tools for analytics and support.',
        messageMaxHeightPercent: 30,
        delay: 1000,
        allowAdvancedOptions: false,
        acceptButtonLabel: 'Got it',
        OnAccept: function () {

            var preferences = $.fn.bsgdprcookies.GetUserPreferences();
            console.log(preferences);

        }
    }

    $(document).ready(function () {
        $('body').bsgdprcookies(settings);
        $("#bs-gdpr-cookies-modal-more-link").hide();
        $('#cookiesBtn').on('click', function () {
            $('body').bsgdprcookies(settings, 'reinit');
        });
    });

    function loginForm() {
        if ($("#loginEmail").val() == '' || $("#loginPassword").val() == '') {
            toastr.info('All Fileds are mandatory');

        } else {
            $('.loader').show();
            $.ajax({
                type: 'POST',
                url: base_url + '/login',
                headers: {'X-CSRF-Token': $('meta[name="_token"]').attr('content')},
                data: {email: $("#loginEmail").val(), password: $("#loginPassword").val()},
                success: function (data) {
                    $('.loader').hide();
                    if (data.status == 0) {
                        toastr.info('Please verify your email');
                    }
                    if (data.status == 2) {
                        toastr.info('You have not permission to login here');
                    } else {
                        console.log(data);
                        if (data.auth) {
                            window.location.href = data.intended;
                        } else {
                            toastr.info('Invalid credential');
                        }
                    }


                    //
                }
            })
        }
    }

    // function referFriendForm() {
    //     $("#referafriend_button").attr('disabled', true);
    //     if ($("#emailone").val() == '' && $("#emailtwo").val() == '' && $("#emailthree").val() == '' && $("#emailfour").val() == '' && $("#emailfive").val() == '' && $("#emailsix").val() == '' && $("#emailseven").val() == '' && $("#emaileight").val() == '' && $("#emailnine").val() == '' && $("#emailten").val() == '') {
    //         toastr.info('Enter Atleast One Email Address');
    //     } else {
    //         //  var refer_data = $("#referafriend_form").serialize();
    //         $('#referafriend_button').prop('disabled', true);
    //         //$('#referafriend_button').attr("disabled", "disabled");
    //         $('.loader').show();
    //         $.ajax({
    //             type: 'POST',
    //             url: base_url + '/referAFriend',
    //             headers: {'X-CSRF-Token': $('meta[name="_token"]').attr('content')},
    //             data: {
    //                 emailone: $("#emailone").val(),
    //                 emailtwo: $("#emailtwo").val(),
    //                 emailthree: $("#emailthree").val(),
    //                 emailfour: $("#emailfour").val(),
    //                 emailfive: $("#emailfive").val(),
    //                 emailsix: $("#emailsix").val(),
    //                 emailseven: $("#emailseven").val(),
    //                 emaileight: $("#emaileight").val(),
    //                 emailnine: $("#emailnine").val(),
    //                 emailten: $("#emailten").val()
    //             },
    //             success: function (data) {
    //                 $('.loader').hide();
    //                 $("#referafriend_button").attr('disabled', false);
    //                 if (data.status) {
    //                     toastr.success(data.msg);
    //                     //window.location.href = base_url+data.intended;
    //                 } else {
    //                     toastr.info(data.msg);
    //                 }
    //                 $('#referafriend').modal('hide');
    //                 $('#referafriend_button').prop('disabled', false);
    //                 //
    //             }
    //         })
    //     }
    // }
</script>
{{--<script type="text/javascript" src="https://cdn.ywxi.net/js/1.js" async></script>--}}
<!--Start of Tawk.to Script-->
<script type="text/javascript">
    // var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
    // (function () {
    //     var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
    //     s1.async = true;
    //     s1.src = 'https://embed.tawk.to/5bc6fd1661d0b770925179bb/default';
    //     s1.charset = 'UTF-8';
    //     s1.setAttribute('crossorigin', '*');
    //     s0.parentNode.insertBefore(s1, s0);
    // })();
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
        var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
        s1.async=true;
        s1.src='https://embed.tawk.to/5feff70bdf060f156a933aae/1er0lq1qa';
        s1.charset='UTF-8';
        s1.setAttribute('crossorigin','*');
        s0.parentNode.insertBefore(s1,s0);
    })();
    var newwindow;

    function popuponclick() {
        my_window = window.open("",
            "mywindow", "status=1,width=350,height=150");
        my_window.document.write("<div style=\"padding-left: 10px;padding-right: 10px;\">\n" +
            "                    <h3>We don't offer Terms & Conditions rather it's Trust we wish to build. </h3>\n" +
            "\n" +
            "                    <h5>What's in these terms?</h5>\n" +
            "                    <p>These terms tell you the rules for using our website <a href=\"www.ayojn.com\" target=\"_blank\">www.ayojn.com</a> (our site)</p>\n" +
            "\n" +
            "                    <h5>Who we are and how to contact us</h5>\n" +
            "                    <p>www.ayojn.com is a site operated by Ayojn Limited (\"We\"). We are registered in England and Wales under company number 12666870 and have our registered office at Kemp House 160 City Road, London, EC1V 2NX<br/>\n" +
            "                        We are a limited company.<br/>\n" +
            "                        To contact us, please email connect@ayojn.com.\n" +
            "                    </p>\n" +
            "\n" +
            "                    <h5>By using our site you accept these terms</h5>\n" +
            "                    <p>By using our site, you confirm that you accept these terms of use and that you agree to comply with them.<br/>\n" +
            "                        If you do not agree to these terms, you must not use our site.<br/>\n" +
            "                        We recommend that you print a copy of these terms for future reference.<br/>\n" +
            "                        If you purchase services from our site, our Terms and conditions of supply will apply to the sales.\n" +
            "\n" +
            "                    </p>\n" +
            "\n" +
            "                    <h5>We may make changes to these terms\n" +
            "                    </h5>\n" +
            "                    <p>We amend these terms from time to time. Every time you wish to use our site, please check these terms to ensure you understand the terms that apply at that time.<br/>\n" +
            "                        We may make changes to our site.<br/>\n" +
            "                        We may update and change our site from time to time without providing you with notice.\n" +
            "                    </p>\n" +
            "\n" +
            "                    <h5>We may suspend or withdraw our site\n" +
            "                    </h5>\n" +
            "                    <p>Our site is made available free of charge.\n" +
            "                        We do not guarantee that our site, or any content on it, will always be available or be uninterrupted. We may suspend or withdraw or restrict the availability of all or any part of our site for business and operational reasons. We will try to give you reasonable notice of any suspension or withdrawal.<br/>\n" +
            "                        You are also responsible for ensuring that all persons who access our site through your internet connection are aware of these terms of use and other applicable terms and conditions, and that they comply with them.\n" +
            "                    </p>\n" +
            "\n" +
            "\n" +
            "                    <h5>You must keep your account details safe</h5>\n" +
            "                    <p>If you choose, or you are provided with, a user identification code, password or any other piece of information as part of our security procedures, you must treat such information as confidential. You must not disclose it to any third party.<br/>\n" +
            "                        We have the right to disable any user identification code or password, whether chosen by you or allocated by us, at any time, if in our reasonable opinion you have failed to comply with any of the provisions of these terms of use.<br/>\n" +
            "                        If you know or suspect that anyone other than you knows your user identification code or password, you must promptly notify us at connect@ayojn.com.\n" +
            "                    </p>\n" +
            "\n" +
            "                    <h5>How you may use material on our site</h5>\n" +
            "                    <p>We are the owner or the licensee of all intellectual property rights in our site, and in the material published on it.  Those works are protected by copyright laws and treaties around the world. All such rights are reserved.<br/>\n" +
            "                        You may print off one copy, and may download extracts, of any page(s) from our site for your personal use and you may draw the attention of others within your organisation to content posted on our site.<br/>\n" +
            "                        You must not modify the paper or digital copies of any materials you have printed off or downloaded in any way, and you must not use any illustrations, photographs, video or audio sequences or any graphics separately from any accompanying text.<br/>\n" +
            "                        Our status (and that of any identified contributors) as the authors of content on our site must always be acknowledged.<br/>\n" +
            "                        You must not use any part of the content on our site for commercial purposes without obtaining a licence to do so from us or our licensors.<br/>\n" +
            "                        If you print off, copy or download any part of our site in breach of these terms of use, your right to use our site will cease immediately and you must, at our option, return or destroy any copies of the materials you have made.\n" +
            "                    </p>\n" +
            "\n" +
            "                    <h5>Do not rely on information on this site</h5>\n" +
            "                    <p>The content on our site is provided for general information only. It is not intended to amount to advice on which you should rely. You must obtain professional or specialist advice before taking, or refraining from, any action on the basis of the content on our site.\n" +
            "                        Although we make reasonable efforts to update the information on our site, we make no representations, warranties or guarantees, whether express or implied, that the content on our site is accurate, complete or up to date.\n" +
            "                    </p>\n" +
            "\n" +
            "                    <h5>We are not responsible for websites we link to</h5>\n" +
            "                    <p>Where our site contains links to other sites and resources provided by third parties, these links are provided for your information only. Such links should not be interpreted as approval by us of those linked websites or information you may obtain from them.\n" +
            "                        We have no control over the contents of those sites or resources.\n" +
            "                    </p>\n" +
            "\n" +
            "\n" +
            "                    <h5>User-generated content is not approved by us</h5>\n" +
            "                    <p>This website may include information and materials uploaded by other users of the site, including reviews, bulletin boards and chat rooms. This information and these materials have not been verified or approved by us. The views expressed by other users on our site do not represent our views or values.\n" +
            "                        If you wish to complain about information and materials uploaded by other users please contact us on connect@ayojn.com.\n" +
            "                    </p>\n" +
            "\n" +
            "                    <h5>Our responsibility for loss or damage suffered by you</h5>\n" +
            "                    <p>Our website is intended solely for business-to-business use.  If you are a consumer, we cannot prevent you from browsing our website but if you choose to do so, our limitation of liability as outlined below applies to you equally as if you were a business user.<br/>\n" +
            "                        We do not exclude or limit in any way our liability to you where it would be unlawful to do so. This includes liability for death or personal injury caused by our negligence or the negligence of our employees, agents or subcontractors and for fraud or fraudulent misrepresentation.<br/>\n" +
            "                        Different limitations and exclusions of liability will apply to liability arising as a result of the supply of any products to you, which will be set out in our Terms and conditions of supply.\n" +
            "                        We exclude all implied conditions, warranties, representations or other terms that may apply to our site or any content on it.<br/>\n" +
            "\n" +
            "                    <ul class=\"moveleft\">We will not be liable to you for any loss or damage, whether in contract, tort (including negligence), breach of statutory duty, or otherwise, even if foreseeable, arising under or in connection with:\n" +
            "                        <li>use of, or inability to use, our site; or</li>\n" +
            "                        <li>use of or reliance on any content displayed on our site.</li>\n" +
            "                    </ul>\n" +
            "                    <ul class=\"moveleft\">\n" +
            "                        In particular, we will not be liable for:\n" +
            "                        <li> loss of profits, sales, business, or revenue;</li>\n" +
            "                        <li> business interruption;</li>\n" +
            "                        <li> loss of anticipated savings;</li>\n" +
            "                        <li> loss of business opportunity, goodwill or reputation; or</li>\n" +
            "                        <li> any indirect or consequential loss or damage.</li>\n" +
            "                    </ul>\n" +
            "                    </p>\n" +
            "\n" +
            "                    <h5>How we may use your personal information\n" +
            "                    </h5>\n" +
            "                    <p>We will only use your personal information as set out in our Privacy Policy.\n" +
            "                    </p>\n" +
            "\n" +
            "                    <h5>Uploading content to our site\n" +
            "                    </h5>\n" +
            "                    <p>Whenever you make use of a feature that allows you to upload content to our site, or to make contact with other users of our site, you must comply with the content standards set out in our Acceptable Use Policy.<br/>\n" +
            "                        You warrant that any such contribution does comply with those standards, and you will be liable to us and indemnify us for any breach of that warranty. This means you will be responsible for any loss or damage we suffer as a result of your breach of warranty.<br/>\n" +
            "                        Any content you upload to our site will be considered non-confidential and non-proprietary. You retain all of your ownership rights in your content, but you are required to grant us and other users of our site a limited licence to use, store and copy that content and to distribute and make it available to third parties. The rights you license to us are described in Rights you are giving us to use material you upload.<br/>\n" +
            "                        We also have the right to disclose your identity to any third party who is claiming that any content posted or uploaded by you to our site constitutes a violation of their intellectual property rights, or of their right to privacy.<br/>\n" +
            "                        We have the right to publish your logo, testimonials and remarks about our services rendered to you.<br/>\n" +
            "                        We have the right to remove any posting you make on our site if, in our opinion, your post does not comply with the content standards set out in our Acceptable Use Policy.<br/>\n" +
            "                        You are solely responsible for securing and backing up your content.\n" +
            "                    </p>\n" +
            "                    <h5>Rights you are giving us to use material you upload</h5>\n" +
            "                    <P>When you upload or post content to our site, you grant us the following rights to use that content:</P>\n" +
            "                    <ul class=\"moveleft\">\n" +
            "                        <LI><P>A perpetual, worldwide, non-exclusive, royalty-free, transferable\n" +
            "                                licence that allows us to use, reproduce, distribute, prepare\n" +
            "                                derivative works of, display, and perform that user-generated\n" +
            "                                content in connection with the service provided by our website and\n" +
            "                                across different media. We may also use the content to promote the\n" +
            "                                website or our service.</P>\n" +
            "                        <LI><P>A perpetual, worldwide, non-exclusive, royalty-free, transferable\n" +
            "                                licence that allows third parties (for example, other users,\n" +
            "                                partners or advertisers) to use the material which you upload for\n" +
            "                                their purposes or in accordance with the functionality of the site.</P>\n" +
            "                    </UL>\n" +
            "                    <h5>We are not responsible for viruses and you must not introduce them</h5>\n" +
            "                    <P>We do not guarantee that our site will be secure or free from bugs or\n" +
            "                        viruses.</P>\n" +
            "                    <P>You are responsible for configuring your information technology, computer\n" +
            "                        programmes and platform to access our site. You should use your own\n" +
            "                        virus protection software.</P>\n" +
            "                    <P>You must not misuse our site by knowingly introducing viruses, trojans,\n" +
            "                        worms, logic bombs or other material that is malicious or\n" +
            "                        technologically harmful. You must not attempt to gain unauthorised\n" +
            "                        access to our site, the server on which our site is stored or any\n" +
            "                        server, computer or database connected to our site. You must not\n" +
            "                        attack our site via a denial-of-service attack or a distributed\n" +
            "                        denial-of service attack. By breaching this provision, you would\n" +
            "                        commit a criminal offence under the Computer Misuse Act 1990. We will\n" +
            "                        report any such breach to the relevant law enforcement authorities\n" +
            "                        and we will co-operate with those authorities by disclosing your\n" +
            "                        identity to them. In the event of such a breach, your right to use\n" +
            "                        our site will cease immediately.</P>\n" +
            "                    <h5>Rules about linking to our site</h5>\n" +
            "                    <P>You may link to our home page, provided you do so in a way that is fair\n" +
            "                        and legal and does not damage our reputation or take advantage of it.</P>\n" +
            "                    <P>You must not establish a link in such a way as to suggest any form of\n" +
            "                        association, approval or endorsement on our part where none exists.</P>\n" +
            "                    <P>You must not establish a link to our site in any website that is not\n" +
            "                        owned by you.</P>\n" +
            "                    <P>Our site must not be framed on any other site, nor may you create a link\n" +
            "                        to any part of our site other than the home page.</P>\n" +
            "                    <P>We reserve the right to withdraw linking permission without notice.</P>\n" +
            "                    <P>The website in which you are linking must comply in all respects with the\n" +
            "                        content standards set out in our Acceptable Use Policy.</P>\n" +
            "                    <P>If you wish to link to or make any use of content on our site other than\n" +
            "                        that set out above, please contact connect@ayojn.com.</P>\n" +
            "\n" +
            "                    <h5>Which country's laws apply to any disputes?</h5>\n" +
            "                    <P>If you are a consumer, please note that these terms of use, their\n" +
            "                        subject matter and their formation, are governed by English law. You\n" +
            "                        and we both agree that the courts of England and Wales will have\n" +
            "                        exclusive jurisdiction except that if you are a resident of Northern\n" +
            "                        Ireland you may also bring proceedings in Northern Ireland, and if\n" +
            "                        you are resident of Scotland, you may also bring proceedings in\n" +
            "                        Scotland.</P>\n" +
            "                    <P>If you are a business, these terms of use, their subject matter and\n" +
            "                        their formation (and any non-contractual disputes or claims) are\n" +
            "                        governed by English law. We both agree to the exclusive jurisdiction\n" +
            "                        of the courts of England and Wales.</P>\n" +
            "\n" +
            "                    <p>Intellectual Property in Trademarks<br/>\n" +
            "                        We own the intellectual property rights in the trademarks “Ayojn”\n" +
            "                        and other logo components such the icon layout. These are registered\n" +
            "                        in the United Kingdom under classes 35 and 37. You are not permitted\n" +
            "                        to use them without our approval, unless they are part of material\n" +
            "                        you are using as permitted under How you may use material on our\n" +
            "                        site.</P>\n" +
            "\n" +
            "                    <h5>2. WEBSITE ACCEPTABLE USE POLICY</h5>\n" +
            "                    <p><strong>What's in these terms?</strong></p>\n" +
            "                    <P>This acceptable use policy sets out the content standards that apply when\n" +
            "                        you upload content to our site, make contact with other users on our\n" +
            "                        site, link to our site, or interact with our site in any other way,</P>\n" +
            "                    <p><strong>Who we are and how to contact us</strong></p>\n" +
            "                    <P>\n" +
            "                        <A HREF=\"http://www.yourstandbuilder.com/\" target=\"_blank\">www.ayojn.com</A>\n" +
            "                        is a site operated by Ayojn Limited (&quot;We&quot;). We are\n" +
            "                        registered in England and Wales under company number 12666870 and\n" +
            "                        have our registered office at Kemp House 160 City Road, London, EC1V\n" +
            "                        2NX</P>\n" +
            "                    <P>\n" +
            "                        We are a limited company.<br/>\n" +
            "                        To contact us, please email connect@ayojn.com<br/>\n" +
            "                        By using our site you accept these terms<br/>\n" +
            "                        By using our site, you confirm that you accept the terms of this policy and that you agree to comply with them.<br/>\n" +
            "                        If you do not agree to these terms, you must not use our site.<br/>\n" +
            "                        We recommend that you print a copy of these terms for future reference.</P>\n" +
            "\n" +
            "                    <h5>We may make changes to the terms of this policy</h5>\n" +
            "                    <P>We amend these terms from time to time. Every time you wish to use our\n" +
            "                        site, please check these terms to ensure you understand the terms\n" +
            "                        that apply at that time.</P>\n" +
            "                    <h5>Prohibited uses</h5>\n" +
            "                    <P>You may use our site only for lawful purposes.&nbsp; You may not use our\n" +
            "                        site:</P>\n" +
            "                    <UL>\n" +
            "                        <LI><P>In any way that breaches any applicable local, national or\n" +
            "                                international law or regulation.</LI></P>\n" +
            "                        <LI><P>In any way that is unlawful or fraudulent, or has any unlawful or\n" +
            "                                fraudulent purpose or effect.</LI></P>\n" +
            "                        <LI><P>For the purpose of harming or attempting to harm minors in any way.</LI></P>\n" +
            "                        <LI><P>To send, knowingly receive, upload, download, use or re-use any\n" +
            "                                material which does not comply with our content standards.</LI></P>\n" +
            "                        <LI><P>To transmit, or procure the sending of, any unsolicited or unauthorised\n" +
            "                                advertising or promotional material or any other form of similar\n" +
            "                                solicitation (spam).</LI></P>\n" +
            "                        <LI><P>To knowingly transmit any data, send or upload any material that\n" +
            "                                contains viruses, Trojan horses, worms, time-bombs, keystroke\n" +
            "                                loggers, spyware, adware or any other harmful programs or similar\n" +
            "                                computer code designed to adversely affect the operation of any\n" +
            "                                computer software or hardware.</LI></P>\n" +
            "                    </UL>\n" +
            "                    <h5>You also agree:</h5>\n" +
            "                    <UL>\n" +
            "                        <LI><P>Not to reproduce, duplicate, copy or re-sell any part of our site in\n" +
            "                                contravention of the provisions of our terms of website use.</LI></P>\n" +
            "                        <LI><P>Not to access without authority, interfere with, damage or disrupt:</LI></P>\n" +
            "                        <LI><P>any part of our site;</LI></P>\n" +
            "                        <LI><P>any equipment or network on which our site is stored;</LI></P>\n" +
            "                        <LI><P>any software used in the provision of our site; or</LI></P>\n" +
            "                        <LI><P>any equipment or network or software owned or used by any third party.</P></LI>\n" +
            "                    </UL>\n" +
            "                    <h5>Interactive services<h5>\n" +
            "                            <P>We may from time to time provide interactive services on our site,\n" +
            "                                including, without limitation:</P>\n" +
            "                            <UL>\n" +
            "                                <LI><P>Chat rooms.</LI></P>\n" +
            "                                <LI><P>Bulletin boards.</LI></P>\n" +
            "                                <LI><P>Review facilities.</LI></P>\n" +
            "                            </UL>\n" +
            "                            <P>Where we do provide any interactive service, we will provide clear\n" +
            "                                information to you about the kind of service offered, if it is\n" +
            "                                moderated and what form of moderation is used (including whether it\n" +
            "                                is human or technical).</P>\n" +
            "                            <P>We will do our best to assess any possible risks for users (and in\n" +
            "                                particular, for children) from third parties when they use any\n" +
            "                                interactive service provided on our site, and we will decide in each\n" +
            "                                case whether it is appropriate to use moderation of the relevant\n" +
            "                                service (including what kind of moderation to use) in the light of\n" +
            "                                those risks. However, we are under no obligation to oversee, monitor\n" +
            "                                or moderate any interactive service we provide on our site, and we\n" +
            "                                expressly exclude our liability for any loss or damage arising from\n" +
            "                                the use of any interactive service by a user in contravention of our\n" +
            "                                content standards, whether the service is moderated or not.</P>\n" +
            "                            <P>The use of any of our interactive services by a minor is subject to the\n" +
            "                                consent of their parent or guardian. We advise parents who permit\n" +
            "                                their children to use an interactive service that it is important\n" +
            "                                that they communicate with their children about their safety online,\n" +
            "                                as moderation is not foolproof. Minors who are using any interactive\n" +
            "                                service should be made aware of the potential risks to them.</P>\n" +
            "                            <P>Where we do moderate an interactive service, we will normally provide you\n" +
            "                                with a means of contacting the moderator, should a concern or\n" +
            "                                difficulty arise.</P>\n" +
            "                            <h5>Content standards</h5>\n" +
            "                            <P>These content standards apply to any and all material which you contribute\n" +
            "                                to our site (Contribution), and to any interactive services\n" +
            "                                associated with it.</P>\n" +
            "                            <P>The\n" +
            "                                Content Standards must be complied with in spirit as well as to the\n" +
            "                                letter. The standards apply to each part of any Contribution as well\n" +
            "                                as to its whole.</P>\n" +
            "                            <P>Ayojn\n" +
            "                                Limited will determine, in its discretion, whether a Contribution\n" +
            "                                breaches the Content Standards.</P>\n" +
            "                            <P>A Contribution must:</P>\n" +
            "                            <UL>\n" +
            "                                <LI><P>Be\n" +
            "                                        accurate (where it states facts).</LI></P>\n" +
            "                                <LI><P>Be\n" +
            "                                        genuinely held (where it states opinions).</LI></P>\n" +
            "                                <LI><P>\n" +
            "                                        Comply with the law applicable in England and Wales and in any country from which it is posted.</LI></P>\n" +
            "                            </UL>\n" +
            "                            <P>A Contribution must not:</P>\n" +
            "                            <UL>\n" +
            "                                <LI><P>\n" +
            "                                        Be\n" +
            "                                        defamatory of any person.</LI></P>\n" +
            "                                <LI><P>\n" +
            "                                        Be\n" +
            "                                        obscene, offensive, hateful or inflammatory.</LI></P>\n" +
            "                                <LI><P>\n" +
            "                                        Promote\n" +
            "                                        sexually explicit material.</LI></P>\n" +
            "                                <LI><P>\n" +
            "                                        Promote\n" +
            "                                        violence.</LI></P>\n" +
            "                                <LI><P>\n" +
            "                                        Promote\n" +
            "                                        discrimination based on race, sex, religion, nationality,\n" +
            "                                        disability, sexual orientation or age.</LI></P>\n" +
            "                                <LI><P>\n" +
            "                                        Infringe\n" +
            "                                        any copyright, database right or trademark of any other person.</LI></P>\n" +
            "                                <LI><P>\n" +
            "                                        Be\n" +
            "                                        likely to deceive any person.</LI></P>\n" +
            "                                <LI><P>\n" +
            "                                        Breach\n" +
            "                                        any legal duty owed to a third party, such as a contractual duty or\n" +
            "                                        a duty of confidence.</LI></P>\n" +
            "                                <LI><P>\n" +
            "                                        Promote\n" +
            "                                        any illegal activity.</LI></P>\n" +
            "                                <LI><P>\n" +
            "                                        Be\n" +
            "                                        in contempt of court.</LI></P>\n" +
            "                                <LI><P>\n" +
            "                                        Be\n" +
            "                                        threatening, abuse or invade another's privacy, or cause annoyance,\n" +
            "                                        inconvenience or needless anxiety.</LI></P>\n" +
            "                                <LI><P>\n" +
            "                                        Be\n" +
            "                                        likely to harass, upset, embarrass, alarm or annoy any other person.</LI></P>\n" +
            "                                <LI><P>\n" +
            "                                        Impersonate\n" +
            "                                        any person, or misrepresent your identity or affiliation with any\n" +
            "                                        person.</LI></P>\n" +
            "                                <LI><P>\n" +
            "                                        Give\n" +
            "                                        the impression that the Contribution emanates from Ayojn Limited,\n" +
            "                                        if this is not the case.</LI></P>\n" +
            "                                <LI><P>\n" +
            "                                        Advocate,\n" +
            "                                        promote, incite any party to commit, or assist any unlawful or\n" +
            "                                        criminal act such as (by way of example only) copyright infringement\n" +
            "                                        or computer misuse.</LI></P>\n" +
            "                                <LI><P>\n" +
            "                                        Contain\n" +
            "                                        a statement which you know or believe, or have reasonable grounds\n" +
            "                                        for believing, that members of the public to whom the statement is,\n" +
            "                                        or is to be, published are likely to understand as a direct or\n" +
            "                                        indirect encouragement or other inducement to the commission,\n" +
            "                                        preparation or instigation of acts of terrorism.</LI></P>\n" +
            "                                <LI><P>\n" +
            "                                        Contain\n" +
            "                                        any advertising or promote any services or web links to other sites.</LI></P>\n" +
            "                            </UL>\n" +
            "                            <h5>Breach\n" +
            "                                of this policy</h5>\n" +
            "                            <P>\n" +
            "                                When\n" +
            "                                we consider that a breach of this acceptable use policy has occurred,\n" +
            "                                we may take such action as we deem appropriate.&nbsp;</P>\n" +
            "                            <P>\n" +
            "                                Failure\n" +
            "                                to comply with this acceptable use policy constitutes a material\n" +
            "                                breach of the terms of use upon which you are permitted to use our\n" +
            "                                site, and may result in our taking all or any of the following\n" +
            "                                actions:</P>\n" +
            "                            <UL>\n" +
            "                                <LI><P>\n" +
            "                                        Immediate,\n" +
            "                                        temporary or permanent withdrawal of your right to use our site.</LI></P>\n" +
            "                                <LI><P>\n" +
            "                                        Immediate,\n" +
            "                                        temporary or permanent removal of any Contribution uploaded by you\n" +
            "                                        to our site.</LI></P>\n" +
            "                                <LI><P>\n" +
            "                                        Issue\n" +
            "                                        of a warning to you.</LI></P>\n" +
            "                                <LI><P>\n" +
            "                                        Legal\n" +
            "                                        proceedings against you for reimbursement of all costs on an\n" +
            "                                        indemnity basis (including, but not limited to, reasonable\n" +
            "                                        administrative and legal costs) resulting from the breach.</LI></P>\n" +
            "                                <LI><P>\n" +
            "                                        Further\n" +
            "                                        legal action against you.</LI></P>\n" +
            "                                <LI><P>\n" +
            "                                        Disclosure\n" +
            "                                        of such information to law enforcement authorities as we reasonably\n" +
            "                                        feel is necessary or as required by law.</LI></P>\n" +
            "                            </UL>\n" +
            "                            <P>We exclude our liability for all action we may take in response to\n" +
            "                                breaches of this acceptable use policy. The actions we may take are\n" +
            "                                not limited to those described above, and we may take any other\n" +
            "                                action we reasonably deem appropriate.</P>\n" +
            "                            <h5>Which country's laws apply to any disputes?</h5>\n" +
            "                            <P>If you are a consumer, please note that the terms of this policy, its\n" +
            "                                subject matter and its formation are governed by English law. You and\n" +
            "                                we both agree that the courts of England and Wales will have\n" +
            "                                exclusive jurisdiction except that if you are a resident of Northern\n" +
            "                                Ireland you may also bring proceedings in Northern Ireland, and if\n" +
            "                                you are resident of Scotland, you may also bring proceedings in\n" +
            "                                Scotland.</P>\n" +
            "                            <P>If you are a business, the terms of this policy, its subject matter and\n" +
            "                                its formation (and any non-contractual disputes or claims) are\n" +
            "                                governed by English law. We both agree to the exclusive jurisdiction\n" +
            "                                of the courts of England and Wales.</P>\n" +
            "\n" +
            "                            <h4>3. SUPPLY OF SERVICE - AGREED TERMS</h3>\n" +
            "                                <OL>\n" +
            "                                    <LI><P>\n" +
            "                                            About\n" +
            "                                            us<BR><BR>1.1 Company details. Ayojn Limited (company number\n" +
            "                                            12666870) (we and us) is a company registered in England and Wales\n" +
            "                                            and our registered office is at Kemp House 160 City Road, London,\n" +
            "                                            EC1V 2NX. We operate the website www.ayojn.com. <BR><BR>1.2\n" +
            "                                            Background<BR><BR>(a) These terms and conditions apply to any\n" +
            "                                            individual, sole trader, firm, company or other legal entity who\n" +
            "                                            registers as a User of this website (a Registered User).<BR><BR>(b)\n" +
            "                                            Ayojn Limited is an independent company which aims to facilitate\n" +
            "                                            connections between Clients/ Brands Registered Users (an CRU) who\n" +
            "                                            require the provision of Sponsored Outreach/ or services and\n" +
            "                                            Agencies (an ARU) who may be able to offer such provision. There are\n" +
            "                                            also Partner companies (PRU) which offer deals at the marketplace\n" +
            "                                            offered by Ayojn Limited. <BR><BR>(c) This facilitation of\n" +
            "                                            connections and the means of facilitation provided by us are\n" +
            "                                            hereinafter known as the Website Services.<BR><BR>(d) Registration\n" +
            "                                            is free of charge for all users.<BR><BR>(e) ARUs &amp; CRUs agree to\n" +
            "                                            pay a fee for the discovery or data sharing and connecting with the\n" +
            "                                            potential leads. <BR>(f) PRUs may/may not pay a commission to\n" +
            "                                            Ayojn. The deals run by PRUs are independent and don't have any\n" +
            "                                            connection or influence with Ayojn. Ayojn holds no liability\n" +
            "                                            incase of any dispute or damage incurred by ARUs/ CRUs or PRUs which\n" +
            "                                            may occur during their transactions.&nbsp;<BR><BR>1.3\n" +
            "                                            Contacting us. To contact us email our customer service team:\n" +
            "                                            connect@ayojn.com. How to give us formal notice of any matter under\n" +
            "                                            the Contract is set out in clause 2.<BR>&nbsp;</P>\n" +
            "                                </OL>\n" +
            "                                <OL START=2>\n" +
            "                                    <LI><P>\n" +
            "                                            Introduction<BR><BR>2.1\n" +
            "                                            If you become a Registered User you agree to be legally bound by\n" +
            "                                            this contract.<BR><BR>2.2 You may only become a Registered User on\n" +
            "                                            our site for business reasons.<BR><BR>2.3 This contract is only\n" +
            "                                            available in English. No other languages will apply to this\n" +
            "                                            contract<BR><BR>2.4 When becoming a Registered User you also agree\n" +
            "                                            to be legally bound by:<BR><BR>(a) Our Privacy\n" +
            "                                            Policy.<BR><BR>(b)\n" +
            "                                            Our Acceptable\n" +
            "                                            Use Policy,\n" +
            "                                            which sets out the permitted uses and prohibited uses of our site.\n" +
            "                                            When using our site, you must comply with this Acceptable Use\n" +
            "                                            Policy.<BR><BR>(c) Our Website\n" +
            "                                            Terms and Conditions of Use Policy,\n" +
            "                                            which includes information about us, our rights to modify or\n" +
            "                                            withdraw the website and information about our Content Standards\n" +
            "                                            which includes material published on it or linked to from it.<BR><BR>(d)\n" +
            "                                            Our Cookie\n" +
            "                                            Policy,\n" +
            "                                            which sets out information about the cookies on our site.<BR><BR>(e)\n" +
            "                                            Our Terms\n" +
            "                                            and conditions of Supply\n" +
            "                                            as contained in this document (the Contract).</P>\n" +
            "                                </OL>\n" +
            "\n" +
            "                                <P>   All these documents form part of this Contract as though set out in full\n" +
            "                                    here.</P>\n" +
            "                                <OL START=3>\n" +
            "                                    <LI><P>Our\n" +
            "                                            contract with you<BR><BR>3.1 Our contract. These terms and\n" +
            "                                            conditions (Terms) apply to the registration by you and supply of\n" +
            "                                            Website Services by us to you (Contract). They apply to the\n" +
            "                                            exclusion of any other terms that you seek to impose or incorporate,\n" +
            "                                            or which are implied by trade, custom, practice or course of\n" +
            "                                            dealing.<BR><BR>3.2 Entire agreement. The Contract is the entire\n" +
            "                                            agreement between you and us in relation to its subject matter. You\n" +
            "                                            acknowledge that you have not relied on any statement, promise or\n" +
            "                                            representation or assurance or warranty that is not set out in the\n" +
            "                                            Contract.<BR><BR>3.3 These Terms and the Contract are made only in\n" +
            "                                            the English language.<BR><BR>3.4 Your copy. You should print a copy\n" +
            "                                            of these Terms or save them to your computer for future reference.\n" +
            "                                        </P></li>\n" +
            "                                </OL>\n" +
            "                                <OL START=4>\n" +
            "                                    <LI><P>How\n" +
            "                                            it Works<BR><BR>4.1 Access to full website: to access the full\n" +
            "                                            www.ayojn.com website (the Site), users must register for an\n" +
            "                                            account, provide accurate and complete information, and keep their\n" +
            "                                            account information updated. Both CRUs and ARUs undergo the same\n" +
            "                                            account registration process except that CRUs may register free of\n" +
            "                                            charge but ARUs are required to become paid subscribers following\n" +
            "                                            the expiry of a 1 month free trial period.<BR><BR>4.2 Submissions:\n" +
            "                                            CRUs will follow the onscreen prompts to list their requirements for\n" +
            "                                            Outreach Services. This is known as a Project Listing.&nbsp; Project\n" +
            "                                            Listings will appear on the Registered User section of the website\n" +
            "                                            and all users may pCRUse the Project Listings.&nbsp; ARUs may\n" +
            "                                            respond to Project Listings by following the onscreen prompts to\n" +
            "                                            make a bid for the Project Listing (known as a Bid).&nbsp; The ARU\n" +
            "                                            will be required to list its terms and conditions for transacting\n" +
            "                                            any Booking as part of the Bid process.&nbsp; Project Listings and\n" +
            "                                            Bids are known together as Registered User Submissions (RU\n" +
            "                                            Submissions)<BR><BR>4.3 Moderation: RU Submissions may be moderated\n" +
            "                                            by us to ensure that their terms meet with our Acceptable Use\n" +
            "                                            Policy, Website Terms and Conditions and/ or to ensure that they are\n" +
            "                                            not in breach of the Contract or any applicable statutory or\n" +
            "                                            regulatory requirements. There may be a delay in RU Submissions\n" +
            "                                            appearing on the Registered User section of the website whilst such\n" +
            "                                            moderation takes place.&nbsp; However for the avoidance of doubt, we\n" +
            "                                            do not undertake to routinely oversee, monitor or moderate the\n" +
            "                                            content of RU Submissions.<BR><BR>4.4 Creation of Bookings: If a Bid\n" +
            "                                            is made by an ARU, the CRU will consider the terms of that Bid,\n" +
            "                                            undertake its own due diligence including in particular the terms of\n" +
            "                                            any contractual commitment required from it and assess whether or\n" +
            "                                            not to accept that Bid. If the CRU decides to accept a Bid from an\n" +
            "                                            ARU, it will follow the direct communication between both RUs.&nbsp;</P></li>\n" +
            "                                </OL>\n" +
            "                                <OL START=5>\n" +
            "                                    <LI><P>\n" +
            "                                            Registering\n" +
            "                                            as a User<BR><BR>5.1 Registration. Please follow the onscreen\n" +
            "                                            prompts to become a Registered User. You may only become a\n" +
            "                                            Registered User using the methods set out on the site.&nbsp; Once\n" +
            "                                            you follow the onscreen prompts and become a Registered User you\n" +
            "                                            have agreed to any conditions imposed on or limitations which apply\n" +
            "                                            to the Website Services specified in the registration process and\n" +
            "                                            subject to these Terms.<BR><BR>5.2 Correcting input errors. Our\n" +
            "                                            subscription process allows you to check and amend any errors before\n" +
            "                                            submitting your registration to us. Please check the registration\n" +
            "                                            carefully before confirming it. You are responsible for ensuring\n" +
            "                                            that your registration and any specification submitted by you in\n" +
            "                                            that registration is complete and accurate.<BR><BR>5.3 Acknowledging\n" +
            "                                            receipt of your registration. After you place your registration, you\n" +
            "                                            will receive an email from us acknowledging that we have received\n" +
            "                                            it, but please note that this does not mean that your registration\n" +
            "                                            has been accepted. Our acceptance of your registration will take\n" +
            "                                            place as described in clause 4.<BR><BR>5.4 Accepting your\n" +
            "                                            registration. Our acceptance of your registration takes place when\n" +
            "                                            we send an email to you to accept it (Registration Confirmation), at\n" +
            "                                            which point and on which date (Commencement Date) the Contract\n" +
            "                                            between you and us will come into existence. The Contract will\n" +
            "                                            relate only to those Website Services confirmed in the Registration\n" +
            "                                            Confirmation.<BR><BR>5.5 If we cannot accept your registration. If\n" +
            "                                            we are unable to supply you with the Website Services for any\n" +
            "                                            reason, we will inform you of this by email and we will not process\n" +
            "                                            your registration. If you have already paid for the Website\n" +
            "                                            Services, we will refund you the full amount.\n" +
            "                                        </P></li>\n" +
            "                                </OL>\n" +
            "                                <OL START=6>\n" +
            "                                    <LI><P>\n" +
            "                                            Our\n" +
            "                                            services<BR><BR>6.1 Descriptions and illustrations. Any descriptions\n" +
            "                                            or illustrations on our site are published for the sole purpose of\n" +
            "                                            giving an approximate idea of the services we provide. They will not\n" +
            "                                            form part of the Contract or have any contractual force.<BR><BR>6.2\n" +
            "                                            Compliance with specification. Subject to our right to amend the\n" +
            "                                            specification (see clause 3) we will supply the Website Services to\n" +
            "                                            you in accordance with the specification for the Website Services\n" +
            "                                            appearing on our website at the date of your registration in all\n" +
            "                                            material respects.<BR><BR>6.3 Changes to specification. We reserve\n" +
            "                                            the right to amend the specification of the Website Services if\n" +
            "                                            required by any applicable statutory or regulatory requirement\n" +
            "                                            within the UK or if the amendment will not materially affect the\n" +
            "                                            nature or quality of the Website Services, and we will notify you in\n" +
            "                                            advance of any such event.<BR><BR>6.4 Reasonable care and skill. We\n" +
            "                                            warrant to you that the Website Services will be provided using\n" +
            "                                            reasonable care and skill.<BR><BR>6.5 Time for performance. We will\n" +
            "                                            use all reasonable endeavours to meet any performance dates\n" +
            "                                            specified in the Registration Confirmation, but any such dates are\n" +
            "                                            estimates only and failure to perform the Website Services by such\n" +
            "                                            dates will not give you the right to terminate the Contract.\n" +
            "                                        </P></li>\n" +
            "                                </ol>\n" +
            "                                <OL START=7>\n" +
            "                                    <LI><P>\n" +
            "                                            Our\n" +
            "                                            Role in the Booking<BR><BR>7.1 Facilitation of connections. Our role\n" +
            "                                            is only to facilitate connections between CRUs who require Outreach\n" +
            "                                            Services and ARUs who may be able to offer Outreach Services. For\n" +
            "                                            the avoidance of doubt, we do not offer any Outreach Services\n" +
            "                                            whatsoever.&nbsp; Our role is to provide the Website Services\n" +
            "                                            through an online platform where CRUs and ARUs can find each\n" +
            "                                            other.<BR><BR>7.2 Responsibility for due diligence. We do not\n" +
            "                                            undertake or agree to and in fact will not undertake any due\n" +
            "                                            diligence as to the identity, creditworthiness or veracity of any\n" +
            "                                            information provided to us.&nbsp; It is the responsibility of the\n" +
            "                                            CRUs/ ARUs to select each other based on their own assessment of the\n" +
            "                                            information contained in the Project Listing and/or the Bid which\n" +
            "                                            due diligence should include, as a minimum, an assessment of the\n" +
            "                                            Outreach Services each requires or is offering and the contractual\n" +
            "                                            terms and rates offered by the other party<BR><BR>7.3 Further\n" +
            "                                            Information. If, after carrying out its own due diligence on the\n" +
            "                                            Project Listing or the Bid, an CRU or ARU considers that they\n" +
            "                                            require further information from the other party before entering\n" +
            "                                            into a Booking, it is the responsibility of the party who requires\n" +
            "                                            that information to obtain it.<BR><BR>7.4 Booking Contracts between\n" +
            "                                            Registered Users. Should you, as an CRU or ARU, make a Booking with\n" +
            "                                            another Registered User, the Booking Contract entered into will be\n" +
            "                                            on terms negotiated between you directly and you undertake to\n" +
            "                                            procure and pay for your own legal advice and assistance on the\n" +
            "                                            terms and conditions of that contract and the advisability or\n" +
            "                                            otherwise of entering into it.<BR><BR>7.5 No authority. We shall\n" +
            "                                            have no authority to:<BR><BR>(a) obtain Bookings from Registered\n" +
            "                                            Users; or<BR><BR>(b) enter into or conclude contracts with any\n" +
            "                                            person or company, including the Registered Users, for Bookings of\n" +
            "                                            the Stand Services.\n" +
            "                                        </P></li>\n" +
            "                                </OL>\n" +
            "                                <OL START=8>\n" +
            "                                    <LI><P>\n" +
            "                                            Obtaining\n" +
            "                                            a refund<BR><BR>NO refunds will be offered once the payment is made.\n" +
            "                                    </li></P>\n" +
            "                                </OL>\n" +
            "                                <OL START=9>\n" +
            "                                    <LI><P>\n" +
            "                                            Warranties\n" +
            "                                        </P></li>\n" +
            "                                </OL>\n" +
            "                                <OL START=10>\n" +
            "                                    <LI><P>\n" +
            "                                            We enter into this Contract based on the following assumptions and\n" +
            "                                            you undertake and warrant that they are accurate:<BR><BR>(a) that\n" +
            "                                            where you are an individual or sole trader Registered User that you\n" +
            "                                            are at least 18 years old;<BR><BR>(b) that where you are registering\n" +
            "                                            on behalf of a firm, company or other legal entity, that you have\n" +
            "                                            authority to enter into legal agreements on behalf of that\n" +
            "                                            organisation and to bind that organisation;<BR><BR>(c) that in\n" +
            "                                            entering into this agreement you understand and agree that the\n" +
            "                                            Website Services are intended to facilitate introductions between\n" +
            "                                            CRUs and ARUs only and that the terms and conditions of any Booking\n" +
            "                                            will be on separate terms agreed between those parties which we will\n" +
            "                                            not be a party to;<BR><BR>(d) that you warrant and represent to us\n" +
            "                                            that you are who you have held yourself out to be in the\n" +
            "                                            registration process and that you have the full right, power and\n" +
            "                                            authority to enter into, deliver and perform this agreement and any\n" +
            "                                            associated Booking;<BR><BR>(e) that in entering into this agreement\n" +
            "                                            you understand and agree that we do not undertake or agree to and in\n" +
            "                                            fact will not undertake any due diligence as to the identity,\n" +
            "                                            creditworthiness or veracity of any information provided to us in\n" +
            "                                            the specifications provided by Registered Users at registration and\n" +
            "                                            we expressly reliance on the warranty in Clause 1(d) and that you\n" +
            "                                            will act in accordance with your obligation in Clause 12.1(a);<BR><BR>(f)\n" +
            "                                            that you will provide us with all information required by or under\n" +
            "                                            the Electronic Commerce (EC Directive) Regulations 2002\n" +
            "                                            and or the Provision\n" +
            "                                            of Services Regulations 2009\n" +
            "                                            and agree that we are authorised to and will disclose such\n" +
            "                                            information when the Booking is confirmed;<BR><BR>(g) that you will\n" +
            "                                            act in good faith in connection with the Website Services, the\n" +
            "                                            Project Listing and/ or Bid and the provision of the Outreach\n" +
            "                                            Services and that you will enter into and perform any Booking\n" +
            "                                            Contract in good faith;<BR><BR>(h) that, where you are a ARU, you\n" +
            "                                            will during this Contract and in connection with the Outreach\n" +
            "                                            Services maintain in force with reputable underwriters or insurance\n" +
            "                                            companies, in commercially prudent amounts, policies of insurance\n" +
            "                                            against the risks legally required in the jurisdiction where the\n" +
            "                                            Booking Contract is to be performed and/ or customarily covered by\n" +
            "                                            companies providing similar services (which should as a minimum\n" +
            "                                            cover public and third party liability, business interruption and\n" +
            "                                            other appropriate risks) and that if requested to do so by the CRU,\n" +
            "                                            you shall provide them with copies of relevant policy certificates\n" +
            "                                            and details of the cover provided;<BR><BR>(i) that, in line with the\n" +
            "                                            Financial Action Task Force (FATF) recommendations on anti-money\n" +
            "                                            laundering and combating the financing of terrorism (AML/CFT), you\n" +
            "                                            are not domiciled in a high-risk, non-cooperative jurisdiction or\n" +
            "                                            monitored jurisdiction;<BR><BR>(j) that you are not economically\n" +
            "                                            barred /limited under the sanctions rules of any country worldwide\n" +
            "                                            and if your circumstances change such that you are included in such\n" +
            "                                            lists, you will cease using the website immediately;<BR><BR>(k) that\n" +
            "                                            Registered Users will not attempt to circumvent our processes and\n" +
            "                                            attempt to or actually exchange contact information for the purpose\n" +
            "                                            of soliciting Outreach Services or sales outside of this Contract\n" +
            "                                            and that if you are found to do so you will become liable for the\n" +
            "                                            Confirmation Fee as if the transaction had been processed\n" +
            "                                            appropriately under these Terms.<BR><BR>10.2 We enter into the\n" +
            "                                            Contract to express reliance on the assumptions in Clause 1.\n" +
            "                                        </P></li>\n" +
            "                                </OL>\n" +
            "                                <OL START=11>\n" +
            "                                    <LI><P>\n" +
            "                                            Indemnity<BR><BR>11.1\n" +
            "                                            You agree and undertake to indemnify us against all liabilities,\n" +
            "                                            costs, expenses, damages and losses (including but not limited to\n" +
            "                                            any direct, indirect or consequential losses, loss of profit, loss\n" +
            "                                            of reputation and all interest, penalties and legal costs\n" +
            "                                            (calculated on a full indemnity basis) and all other professional\n" +
            "                                            costs and expenses suffered or incurred by us arising out of or in\n" +
            "                                            connection with:<BR><BR>(a) any breach of the warranties contained\n" +
            "                                            in Clause 1;<BR><BR>(b) your breach or negligent performance or\n" +
            "                                            non-performance of this agreement;<BR><BR>(c) any claim made against\n" +
            "                                            us for actual or alleged infringement of a third party's\n" +
            "                                            intellectual property rights arising out of or in connection with\n" +
            "                                            the RU Submissions or the Outreach Services;<BR><BR>(d) any claim\n" +
            "                                            made against us by a third party arising out of or in connection\n" +
            "                                            with the provision of the Outreach Services, to the extent that such\n" +
            "                                            claim arises out of the breach, negligent performance or failure or\n" +
            "                                            delay in performance of this agreement by you, your employees,\n" +
            "                                            agents or subcontractors;<BR><BR>(e) any claim made against us by a\n" +
            "                                            third party for death, personal injury or damage to property arising\n" +
            "                                            out of or in connection with defective Goods, to the extent that the\n" +
            "                                            defect in the Goods is attributable to your acts or omissions, or\n" +
            "                                            those of your employees, agents or subcontractors.<BR><BR>11.2 This\n" +
            "                                            indemnity shall apply whether or not you have been negligent or at\n" +
            "                                            fault.<BR><BR>11.3 If a payment due from you under this clause is\n" +
            "                                            subject to tax (whether by way of direct assessment or withholding\n" +
            "                                            at its source), we shall be entitled to receive from you such\n" +
            "                                            amounts as shall ensure that the net receipt, after tax, to us in\n" +
            "                                            respect of the payment is the same as it would have been were the\n" +
            "                                            payment not subject to tax.\n" +
            "                                    </li></P>\n" +
            "                                </OL>\n" +
            "                                <OL START=12>\n" +
            "                                    <LI><P>\n" +
            "                                            Your\n" +
            "                                            obligations<BR><BR>12.1 It is your responsibility to ensure\n" +
            "                                            that:<BR><BR>(a) the terms of your registration are complete and\n" +
            "                                            accurate;<BR><BR>(b) you cooperate with us in all matters relating\n" +
            "                                            to the Website Services;<BR><BR>(c) you provide us with such\n" +
            "                                            information as we may reasonably require at registration to supply\n" +
            "                                            the Website Services, and ensure that such information is complete\n" +
            "                                            and accurate in all material respects;<BR><BR>(d) the performance of\n" +
            "                                            the Outreach Services listed in the Project Listing and/ or Bid is\n" +
            "                                            legally permitted in the jurisdiction where those Outreach Services\n" +
            "                                            are to be performed;<BR><BR>(e) if you are a CRU, that you are\n" +
            "                                            acting in good faith and you intend to proceed with any Booking\n" +
            "                                            which results from the supply of the Website Services;<BR><BR>(f) if\n" +
            "                                            you are a ARU, that you are acting in good faith and intend to and\n" +
            "                                            are able to fulfil the terms of any Booking which results from the\n" +
            "                                            supply of the Website Services;<BR><BR>(g) you obtain and maintain\n" +
            "                                            all necessary licences, permissions and consents which may be\n" +
            "                                            required in your jurisdiction for the Website Services or the\n" +
            "                                            Booking before the date on which the Website Services and/ or the\n" +
            "                                            Booking are to start;<BR><BR>(h) you comply with all applicable\n" +
            "                                            laws, relevant to the provision of the Website Services and the\n" +
            "                                            Booking;<BR><BR>(i) you read, understand and comply with our Website\n" +
            "                                            Terms &amp; Conditions, Acceptable Use Policy, Privacy Notice and\n" +
            "                                            Cookies Policy;<BR><BR>(j) you do not register for more than one\n" +
            "                                            account;<BR><BR>(k) the information that you supply in the public\n" +
            "                                            profile of your account complies with our Website Terms of\n" +
            "                                            Use.<BR><BR>12.2 If our ability to perform the Website Services is\n" +
            "                                            prevented or delayed by any failure by you to fulfil any obligation\n" +
            "                                            listed in clause 1 (Your Default):<BR><BR>(a) we will be entitled to\n" +
            "                                            suspend performance of the Website Services until you remedy Your\n" +
            "                                            Default, and to rely on Your Default to relieve us from the\n" +
            "                                            performance of the Website Services, in each case to the extent Your\n" +
            "                                            Default prevents or delays performance of the Website Services. In\n" +
            "                                            certain circumstances Your Default may entitle us to terminate the\n" +
            "                                            contract under clause 20 (Termination);<BR><BR>(b) we will not be\n" +
            "                                            responsible for any costs or losses you sustain or incur arising\n" +
            "                                            directly or indirectly from our failure or delay to perform the\n" +
            "                                            Website Services; and<BR><BR>(c) it will be your responsibility to\n" +
            "                                            reimburse us on written demand for any costs or losses we sustain or\n" +
            "                                            incur arising directly or indirectly from Your Default.</li></P>\n" +
            "                                </OL>\n" +
            "                                <OL START=13>\n" +
            "                                    <LI><P>\n" +
            "                                            Charges<BR><BR>13.1\n" +
            "                                            In consideration of us providing the Website Services you must pay\n" +
            "                                            our charges (Charges) in accordance with this clause 13.<BR><BR>13.2\n" +
            "                                            The Charges are the prices quoted on our site at the time you submit\n" +
            "                                            your registration.<BR><BR>13.3 If you wish to change the scope of\n" +
            "                                            the Website Services after we accept your registration, and we agree\n" +
            "                                            to such change, we will modify the Charges accordingly.<BR><BR>13.4\n" +
            "                                            We use our best efforts to ensure that the prices stated for the\n" +
            "                                            Website Services are correct at the time when the relevant\n" +
            "                                            information was entered into the system. However, please see clause\n" +
            "                                            7 for what happens if we discover an error in the price of the\n" +
            "                                            Website Services you registered.<BR><BR>13.5 We reserve the right to\n" +
            "                                            increase the Charges from time-to-time and we will notify you\n" +
            "                                            usually by email if we do so. You undertake and agree to accept such\n" +
            "                                            notification by email and that any such notice sent in this manner\n" +
            "                                            constitutes a valid notice of variation in accordance with the\n" +
            "                                            Contract.<BR><BR>13. 6 Our Charges are exclusive of VAT calculated\n" +
            "                                            at the prevailing rate in the UK as amended from time-to-time.<BR><BR>13.\n" +
            "                                            7 It is always possible that, despite our reasonable efforts, some\n" +
            "                                            of the Website Services on our site may be incorrectly priced. If\n" +
            "                                            the correct price for the Website Services is higher than the price\n" +
            "                                            stated on our site, we will contact you as soon as possible to\n" +
            "                                            inform you of this error and we will give you the option of\n" +
            "                                            continuing to purchase the Website Services at the correct price or\n" +
            "                                            cancelling your registration. We will not process your registration\n" +
            "                                            until we have your instructions. If we are unable to contact you\n" +
            "                                            using the contact details you provided during the registration\n" +
            "                                            process, we will treat the registration as cancelled and notify you\n" +
            "                                            in writing. However, if we mistakenly accept and process your\n" +
            "                                            registration where a pricing error is obvious and unmistakable and\n" +
            "                                            could reasonably have been recognised by you as a mispricing, we may\n" +
            "                                            cancel supply of the Website Services and refund you any sums you\n" +
            "                                            have paid.\n" +
            "                                        </P></li>\n" +
            "                                </OL>\n" +
            "                                <OL START=14>\n" +
            "                                    <LI><P>\n" +
            "                                            How\n" +
            "                                            to pay<BR><BR>14.1 Payment for the Website Services is in advance\n" +
            "                                            using a payment system powered by Paypal (www.paypal.com) who are\n" +
            "                                            responsible for the security of monies paid. You should familiarise\n" +
            "                                            yourself with the Stripe Terms and Conditions which can be reviewed\n" +
            "                                            by clicking on the following link\n" +
            "                                            <A HREF=\"https://www.paypal.com/uk/webapps/mpp/ua/privacy-full\" target=\"_blank\">https://www.paypal.com/uk/webapps/mpp/ua/privacy-full</A>.<BR><BR>14.2\n" +
            "                                            All of our subscription charges are based on United States Dollars\n" +
            "                                            (USD). If you bank in a different currency, your bank will be\n" +
            "                                            responsible for setting the exchange rate and converting our USD\n" +
            "                                            prices into your currency. Confirmation Fee charges can be made as\n" +
            "                                            Pounds Sterling, Euro or United States Dollars as specified in the\n" +
            "                                            corresponding Project Listing and as chosen by the CRU who published\n" +
            "                                            the Project Listing.<BR><BR>14.3 Payment for the Website Services is\n" +
            "                                            by direct deduction using the credit or debit card you used when you\n" +
            "                                            registered. This credit or debit card will be charged automatically\n" +
            "                                            each month - we accept the following cards:&nbsp; Visa, MasterCard,\n" +
            "                                            and American Express. The Confirmation Fee becomes payable when the\n" +
            "                                            Booking is confirmed by the RU after following the relevant onscreen\n" +
            "                                            prompts. You can pay for the Confirmation Fee via bank transfer\n" +
            "                                            using the bank account details which appear on the invoice you\n" +
            "                                            receive from us.\n" +
            "                                        </P></li>\n" +
            "                                </OL>\n" +
            "                                <OL START=15>\n" +
            "                                    <LI><P>\n" +
            "                                            Complaints<BR><BR>If a problem arises or you are dissatisfied with the Website Services,\n" +
            "                                            please notify us at connect@ayojn.com.&nbsp;\n" +
            "                                            It will assist us in achieving a timely and efficient resolution if\n" +
            "                                            you provide as much detail as possible about your issue when you\n" +
            "                                            notify us of your complaint.</P></li>\n" +
            "                                </OL>\n" +
            "                                <OL START=16>\n" +
            "                                    <LI><P>\n" +
            "                                            Intellectual\n" +
            "                                            property rights<BR><BR>16.1 All intellectual property rights in or\n" +
            "                                            arising out of or in connection with the Website Services (other\n" +
            "                                            than intellectual property rights in any materials provided by you)\n" +
            "                                            will be owned by us.<BR><BR>16.2 We agree to grant you a licence for\n" +
            "                                            the purpose of receiving and using the Website Services and such\n" +
            "                                            deliverables in your business. You may not sub-license, assign or\n" +
            "                                            otherwise transfer the rights granted in this clause 16.2.<BR><BR>16.3\n" +
            "                                            You agree to grant us a fully paid-up, non-exclusive, royalty-free,\n" +
            "                                            non-transferable licence to copy and modify any materials provided\n" +
            "                                            by you to us for the term of the Contract for the purpose of\n" +
            "                                            providing the Website Services to you.</li>\n" +
            "                                    </P>\n" +
            "                                </OL>\n" +
            "                                <OL START=17>\n" +
            "                                    <LI><P>\n" +
            "                                            How\n" +
            "                                            we may use your personal information<BR><BR>17.1 We will use any\n" +
            "                                            personal information you provide to us to:<BR><BR>(a) provide the\n" +
            "                                            Website Services;<BR><BR>(b) process your payment for the Website\n" +
            "                                            Services; and<BR><BR>(c) inform you about similar services that we\n" +
            "                                            provide, but you may stop receiving these at any time by contacting\n" +
            "                                            us.<BR><BR>17.2 Further details of how we will process personal\n" +
            "                                            information are set out in our Privacy Policy.</li>\n" +
            "                                    </P>\n" +
            "                                </OL>\n" +
            "                                <OL START=18>\n" +
            "                                    <LI><P>\n" +
            "                                            Limitation\n" +
            "                                            of liability: <BR><br>18.1 We have obtained insurance cover in respect\n" +
            "                                            of our own legal liability for individual claims not exceeding\n" +
            "                                            £100,000per claim. The limits and exclusions in this clause reflect\n" +
            "                                            the insurance cover we have been able to arrange and you are\n" +
            "                                            responsible for making your own arrangements for the insurance of\n" +
            "                                            any excess loss.<BR><BR>18.2 Nothing in the Contract limits any\n" +
            "                                            liability which cannot legally be limited, including liability\n" +
            "                                            for:<BR><BR>(a) death or personal injury caused by negligence;<BR><BR>(b)\n" +
            "                                            fraud or fraudulent misrepresentation; and<BR><BR>(c) breach of the\n" +
            "                                            terms implied by section 2 of the Supply of Goods and Services Act\n" +
            "                                            1982 (title and quiet possession).<BR><BR>18.3 Subject to clause 2,\n" +
            "                                            we will not be liable to you, whether in contract, tort (including\n" +
            "                                            negligence), for breach of statutory duty, or otherwise, arising\n" +
            "                                            under or in connection with the Contract for:<BR><BR>(a) loss of\n" +
            "                                            profits;<BR><BR>(b) loss of sales or business;<BR><BR>(c) loss of\n" +
            "                                            agreements or contracts;<BR><BR>(d) loss of anticipated\n" +
            "                                            savings;<BR><BR>(e) loss of use or corruption of software, data or\n" +
            "                                            information;<BR><BR>(f) loss of or damage to goodwill; and<BR><BR>(g)\n" +
            "                                            any indirect or consequential loss.<BR><BR>18.4 Subject to clause 2,\n" +
            "                                            our total liability to you arising under or in connection with the\n" +
            "                                            Contract, whether in contract, tort (including negligence), breach\n" +
            "                                            of statutory duty, or otherwise, will be limited to the total\n" +
            "                                            Charges paid under the Contract.<BR><BR>18.5 We have given\n" +
            "                                            commitments as to compliance of the Website Services with the\n" +
            "                                            relevant specification in clause 2. In view of these commitments,\n" +
            "                                            the terms implied by sections 3, 4 and 5 of the Supply of Goods and\n" +
            "                                            Services Act 1982 are, to the fullest extent permitted by law,\n" +
            "                                            excluded from the Contract.<BR><BR>18.6 Unless you notify us that\n" +
            "                                            you intend to make a claim in respect of an event within the notice\n" +
            "                                            period, we shall have no liability for that event. The notice period\n" +
            "                                            for an event shall start on the day on which you became, or ought\n" +
            "                                            reasonably to have become, aware of the event having occurred and\n" +
            "                                            shall expire 12 months from that date. The notice must be in writing\n" +
            "                                            and must identify the event and the grounds for the claim in\n" +
            "                                            reasonable detail.<BR><BR>18.7 Nothing in these Terms limits or\n" +
            "                                            affects the exclusions and limitations set out in our Terms and\n" +
            "                                            Conditions of Use.<BR><BR>18.8 This clause 18 will survive\n" +
            "                                            termination of the Contract.</li>\n" +
            "                                    </P>\n" +
            "                                </OL>\n" +
            "                                <OL START=19>\n" +
            "                                    <LI><P>\n" +
            "                                            Confidentiality<BR><BR>19.1\n" +
            "                                            We each undertake that we will not at any time disclose to any\n" +
            "                                            person any confidential information concerning one another's\n" +
            "                                            business, affairs, customers, clients or suppliers, except as\n" +
            "                                            permitted by clause 19.2.<BR><BR>19.2 We each may disclose the\n" +
            "                                            other's confidential information:<BR><BR>(a) to such of our\n" +
            "                                            respective employees, officers, representatives, subcontractors or\n" +
            "                                            advisers who need to know such information for the purposes of\n" +
            "                                            carrying out our respective obligations under the Contract. We will\n" +
            "                                            each ensure that such employees, officers, representatives,\n" +
            "                                            subcontractors or advisers comply with this clause 19; and<BR><BR>(b)\n" +
            "                                            as may be required by law, a court of competent jurisdiction or any\n" +
            "                                            governmental or regulatory authority.<BR><BR>(c) Each of us may only\n" +
            "                                            use the other's confidential information for the purpose of\n" +
            "                                            fulfilling our respective obligations under the Contract.\n" +
            "                                        </P></li>\n" +
            "                                </OL>\n" +
            "                                <OL START=20>\n" +
            "                                    <LI><P>\n" +
            "                                            Status<BR><BR>20.1\n" +
            "                                            Nothing in this agreement is intended to, or shall be deemed to,\n" +
            "                                            establish any partnership or joint venture between any of the\n" +
            "                                            parties, constitute any party the agent of another party, or\n" +
            "                                            authorise any party to make or enter into any commitments for or on\n" +
            "                                            behalf of any other party.<BR><BR>20.2 Each party confirms it is\n" +
            "                                            acting on its own behalf and not for the benefit of any other\n" +
            "                                            person.\n" +
            "                                        </P></li>\n" +
            "                                </OL>\n" +
            "                                <OL START=21>\n" +
            "                                    <LI><P>\n" +
            "                                            Dispute\n" +
            "                                            resolution procedure<BR><BR>21.1 If a dispute arises out of or in\n" +
            "                                            connection with this agreement or the performance, validity or\n" +
            "                                            enforceability of it (Dispute), then the parties shall follow the\n" +
            "                                            procedure set out in this clause.<BR><BR>21.2 Either party shall\n" +
            "                                            give to the other notice of the Dispute by email, setting out its\n" +
            "                                            nature and full particulars (Dispute Notice), together with relevant\n" +
            "                                            supporting documents. On service of the Dispute Notice, the parties\n" +
            "                                            shall attempt in good faith to resolve the Dispute.<BR><BR>21.3 No\n" +
            "                                            party may commence any arbitration proceedings under clause 25\n" +
            "                                            (Arbitration) in relation to the whole or part of the Dispute until\n" +
            "                                            180 days after service of the ADR notice, provided that the right to\n" +
            "                                            issue proceedings is not prejudiced by a delay.<BR><BR>21.4 If the\n" +
            "                                            dispute is not settled by mediation within 180 days of service of\n" +
            "                                            the ADR notice or within such further period as the parties may\n" +
            "                                            agree in writing, either party may issue arbitration or court\n" +
            "                                            proceedings in accordance with clause 25 (Arbitration) in this\n" +
            "                                            Agreement.\n" +
            "                                        </P></li>\n" +
            "                                </OL>\n" +
            "                                <OL START=22>\n" +
            "                                    <LI><P>\n" +
            "                                            Termination<BR><BR>22.1\n" +
            "                                            Without limiting any of our other rights, we may suspend the\n" +
            "                                            performance of the Website Services, or terminate the Contract with\n" +
            "                                            immediate effect by giving written notice to you if:<BR><BR>(a) you\n" +
            "                                            commit a material breach of any term of the Contract and (if such a\n" +
            "                                            breach is remediable) fail to remedy that breach within 14 days of\n" +
            "                                            you being notified in writing to do so;<BR><BR>(b) you fail to pay\n" +
            "                                            any amount due under the Contract on the due date for payment;<BR><BR>(c)\n" +
            "                                            you take any step or action in connection with you entering\n" +
            "                                            administration, provisional liquidation or any composition or\n" +
            "                                            arrangement with your creditors (other than in relation to a solvent\n" +
            "                                            restructuring), being wound up (whether voluntarily or by\n" +
            "                                            registration of the court, unless for the purpose of a solvent\n" +
            "                                            restructuring), having a receiver appointed to any of your assets or\n" +
            "                                            ceasing to carry on business or, if the step or action is taken in\n" +
            "                                            another jurisdiction, in connection with any analogous procedure in\n" +
            "                                            the relevant jurisdiction;<BR><BR>(d) you suspend, threaten to\n" +
            "                                            suspend, cease or threaten to cease to carry on all or a substantial\n" +
            "                                            part of your business; or<BR><BR>(e) your financial position\n" +
            "                                            deteriorates to such an extent that in our opinion your capability\n" +
            "                                            to adequately fulfil your obligations under the Contract has been\n" +
            "                                            placed in jeopardy.<BR><BR><BR>22.2 Termination of the Contract will\n" +
            "                                            not affect your or our rights and remedies that have accrued as at\n" +
            "                                            termination.<BR><BR>22.3 Any provision of the Contract that\n" +
            "                                            expressly or by implication is intended to come into or continue in\n" +
            "                                            force on or after termination will remain in full force and\n" +
            "                                            effect.\n" +
            "                                        </P></li>\n" +
            "                                </OL>\n" +
            "                                <OL START=23>\n" +
            "                                    <LI><P>\n" +
            "                                            Events\n" +
            "                                            outside our control<BR><BR>23.1 We will not be liable or responsible\n" +
            "                                            for any failure to perform, or delay in performance of, any of our\n" +
            "                                            obligations under the Contract that is caused by any act or event\n" +
            "                                            beyond our reasonable control (Event Outside Our Control).<BR><BR>23.2\n" +
            "                                            If an Event Outside Our Control takes place that affects the\n" +
            "                                            performance of our obligations under the Contract:<BR><BR>(a) we\n" +
            "                                            will contact you as soon as reasonably possible to notify you;\n" +
            "                                            and<BR><BR>(b) our obligations under the Contract will be suspended\n" +
            "                                            and the time for performance of our obligations will be extended for\n" +
            "                                            the duration of the Event Outside Our Control. We will arrange a new\n" +
            "                                            date for performance of the Website Services with you after the\n" +
            "                                            Event Outside Our Control is over.<BR><BR>23.3 You may cancel the\n" +
            "                                            Contract affected by an Event Outside Our Control which has\n" +
            "                                            continued for more than 30 days. To cancel please contact us. If you\n" +
            "                                            opt to cancel we will refund the price you have paid, less the\n" +
            "                                            charges reasonably and actually incurred us by performing the\n" +
            "                                            Website Services up to the date of the occurrence of the Event\n" +
            "                                            Outside Our Control.\n" +
            "                                        </P></li>\n" +
            "                                </OL>\n" +
            "                                <OL START=24>\n" +
            "                                    <LI><P>\n" +
            "                                            Communications\n" +
            "                                            between us<BR><BR>24.1 When we refer to &quot;in writing&quot; in\n" +
            "                                            these Terms, this includes email.<BR><BR>24.2 Any notice or other\n" +
            "                                            communication given under or in connection with the Contract must be\n" +
            "                                            in writing and be delivered personally, sent by pre-paid first class\n" +
            "                                            post or other next working day delivery service, or email.<BR><BR>24.3\n" +
            "                                            A notice or other communication is deemed to have been\n" +
            "                                            received:<BR><BR>(a) if delivered personally, on signature of a\n" +
            "                                            delivery receipt;<BR><BR>(b) if sent by pre-paid first class post or\n" +
            "                                            other next working day delivery service, at 9.00 am on the second\n" +
            "                                            working day after posting; or<BR><BR>(c) if sent by email, at 9.00\n" +
            "                                            am the next working day after transmission.<BR><BR>24.4 In proving\n" +
            "                                            the service of any notice, it will be sufficient to prove, in the\n" +
            "                                            case of a letter, that such letter was properly addressed, stamped\n" +
            "                                            and placed in the post and, in the case of an email, that such email\n" +
            "                                            was sent to the specified email address of the addressee.<BR><BR>24.5\n" +
            "                                            The provisions of this clause will not apply to the service of any\n" +
            "                                            proceedings or other documents in any legal action.\n" +
            "                                        </P></li>\n" +
            "                                </OL>\n" +
            "                                <OL START=25>\n" +
            "                                    <LI><P>\n" +
            "                                            Arbitration<BR><BR>25.1\n" +
            "                                            Any dispute arising out of or in connection with this contract,\n" +
            "                                            including any question regarding its existence, validity or\n" +
            "                                            termination, shall be referred to and finally resolved by\n" +
            "                                            arbitration under the LCIA Rules, which Rules are deemed to be\n" +
            "                                            incorporated by reference into this clause.<BR><BR>25.2 The number\n" +
            "                                            of arbitrators shall be one.<BR><BR>25.3 The seat, or legal place,\n" +
            "                                            of arbitration shall be London, United Kingdom.<BR><BR>25.4 The\n" +
            "                                            language to be used in the arbitral proceedings shall be\n" +
            "                                            English.<BR><BR>25.5 The governing law of the contract shall be the\n" +
            "                                            substantive law of England &amp; Wales.\n" +
            "                                        </P></li>\n" +
            "                                </OL>\n" +
            "                                <OL START=26>\n" +
            "                                    <LI><P>\n" +
            "                                            General<BR><BR>26.1\n" +
            "                                            Assignment and transfer.<BR><BR>(a) We may assign or transfer our\n" +
            "                                            rights and obligations under the Contract to another entity but will\n" +
            "                                            always notify you by posting on this webpage if this happens.<BR><BR>(b)\n" +
            "                                            You may only assign or transfer your rights or your obligations\n" +
            "                                            under the Contract to another person if we agree in writing.<BR><BR>26.2\n" +
            "                                            Variation. Any variation of the Contract only has effect if it is in\n" +
            "                                            writing and signed by you and us (or our respective authorised\n" +
            "                                            representatives).<BR><BR>26.3 Waiver If we do not insist that you\n" +
            "                                            perform any of your obligations under the Contract, or if we do not\n" +
            "                                            enforce our rights against you, or if we delay in doing so, that\n" +
            "                                            will not mean that we have waived our rights against you or that you\n" +
            "                                            do not have to comply with those obligations. If we do waive any\n" +
            "                                            rights, we will only do so in writing, and that will not mean that\n" +
            "                                            we will automatically waive any right related to any later default\n" +
            "                                            by you.<BR><BR>26.4 Severance. Each paragraph of these Terms\n" +
            "                                            operates separately. If any court or relevant authority decides that\n" +
            "                                            any of them is unlawful or unenforceable, the remaining paragraphs\n" +
            "                                            will remain in full force and effect.<BR><BR>26.5 Third party\n" +
            "                                            rights. The Contract is between you and us. No other person has any\n" +
            "                                            rights to enforce any of its terms.</P></li>\n" +
            "                                </OL>\n" +
            "\n" +
            "                                <P STYLE=\"background: #34495e; text-align:center;\">\n" +
            "                                    <FONT COLOR=\"#ffffff\"><B>Ayojn\n" +
            "                                            Limited</B><br/>\n" +
            "                                        Copyright © Ayojn\n" +
            "                                        Limited 2020&nbsp;</FONT></style></P>\n" +
            "\n" +
            "\n" +
            "                </div>");
    }

    function poptastic(url) {
        newwindow = window.open(url, 'name', 'height=400,width=200');
        if (window.focus) {
            newwindow.focus()
        }
    }

    function privacyWindow() {
        privacy_window = window.open("",
            "mywindow", "status=1,width=350,height=150");
        privacy_window.document.write('<h2>Privacy Policy</h2>\n' +
            '                <div style="padding-left: 10px;padding-right: 10px;">\n' +
            '                    <p>The policy: This privacy policy notice is served by Ayojn Limited, Kemp House, 160 City Road, London, EC1V 2NX under the website; www.ayojn.com. The purpose of this policy is to explain to you how we control, process, handle and protect your personal information through the business and while you browse or use this website. If you do not agree to the following policy you may wish to cease viewing / using this website, and or refrain from submitting your personal data to us.\n' +
            '                    </p>\n' +
            '                    <h5>Policy key definitions:</h5>\n' +
            '                    <p>\n' +
            '                        <ul class="moveleft">\n' +
            '                            <li>"I", "our", "us", or "we" refer to the business, Ayojn Limited.</li>\n' +
            '                            <li>"you", "the user" refer to the person(s) using this website.</li>\n' +
            '                             <li>GDPR means General Data Protection Act.</li>\n' +
            '                                <li>   PECR means Privacy & Electronic Communications Regulation.</li>\n' +
            '                                <li>    ICO means Information Commissioner\'s Office.</li>\n' +
            '                                <li>    Cookies mean small files stored on a users’ computer or device.</li>\n' +
            '\n' +
            '                        </ul>\n' +
            '                    </p>\n' +
            '                    <h5>Key principles of GDPR:</h5>\n' +
            '                    <p>Our privacy policy embodies the following key priciples; (a) Lawfullness, fairness and transparency, (b) Purpose limitation, (c) Data minimisation, (d) Accuracy, (e) Storage limitation, (f) Integrity and confidence, (g) Accountability.</p>\n' +
            '                    <h5>Processing of your personal data</h5>\n' +
            '                    <p>Under the GDPR (General Data Protection Regulation) we control and / or process any personal information about you electronically using the following lawful bases.</p>\n' +
            '                    <p>\n' +
            '                        <ul class="moveleft">\n' +
            '                            <li>We are exempt from registration in the ICO Data Protection Register because:\n' +
            '                            </li>\n' +
            '                            <li>We only process information necessary to establish or maintain membership or support.                   </li>\n' +
            '                        <li>We only process information necessary to provide or administer activities for people who are members of the organisation or have regular contact with it; </li>\n' +
            '                        <li>We only hold information about individuals whose data you need to process for this exempt purpose\n' +
            '                        </li>\n' +
            '                        <li>The personal data we process is restricted to personal information that is necessary for this exempt purpose\n' +
            '                        </li>\n' +
            '                        </ul>\n' +
            '                    </p>\n' +
            '                    <h5>In this section we have set out:</h5>\n' +
            '                    <p>\n' +
            '<ul class="moveleft">\n' +
            '<li>the general categories of personal data that we may process;\n' +
            '</li>\n' +
            '<li>the purposes for which we may process personal data; and\n' +
            '</li>\n' +
            '<li>the legal basis of the processing.\n' +
            '</li>\n' +
            '\n' +
            '</ul>\n' +
            '                    </p>\n' +
            '                    <p>We may process data about your use of our website and services ("usage data"). The usage data may include your IP address, approximate geographical location, browser type and version, operating system, referral source, length of visit, page views and website navigation paths, as well as information about the timing, frequency and pattern of your service use. The source of the usage data is our analytics tracking system, Google Analytics, and Google Adwords. This usage data may be processed for the purposes of analysing the use of the website and services. The legal basis for this processing is our legitimate interests, namely monitoring and improving our website and services.</p>\n' +
            '                    <p>We may process your account data ("account data"). The account data may include - but is not limited to - your name, email address, company, locations. The source of the account data is you. The account data may be processed for the purposes of operating our website, providing our services to you, ensuring the security of our website and services, maintaining back-ups of our databases and communicating with you. The legal basis for this processing is our legitimate interests, namely the proper administration of our website and business.\n' +
            '                    </p>\n' +
            '                    <p>This policy is effective as of 1 June 2020.We may process information contained in any listing you submit to us regarding Sponsored Outreach services ("listing data"). The enquiry data may include - but is not limited to - details of where you require it, the dates you require the service, the scale of the service. The source of the service data is you. If you do not accept and finalise a service from us we will store a record of the unfinished listing in your Dashboard for your own future reference. The service data may be processed for the purposes of operating our website, enabling any purchased/finalised projects to happen, ensuring the security of our website and services, maintaining back-ups of our databases and communicating with you. The legal basis for this processing is our legitimate interests.\n' +
            '                    </p>\n' +
            '                    <p>\n' +
            '                        We may process your personal data that are provided in the course of the use of our services ("service data"). The service data may include - but is not limited to - details of where you require the service, the dates you require the service, the scale of the service. The source of the service data is you. The service data may be processed for the purposes of operating our website, enabling any purchased/finalised projects to happen, ensuring the security of our website and services, maintaining back-ups of our databases and communicating with you. The legal basis for this processing is our legitimate interests.\n' +
            '\n' +
            '                    </p>\n' +
            '                    <p>\n' +
            '                        We may process information relating to transactions, including purchases of goods and services, that you enter into with us and/or through our website ("transaction data"). The transaction data may include your contact details, and the transaction details. We never store card details. The transaction data may be processed for the purpose of supplying the purchased goods and services and keeping proper records of those transactions. The legal basis for this processing is our legitimate interests, namely our interest in the proper administration of our website and business.\n' +
            '\n' +
            '                    </p>\n' +
            '                    <p>\n' +
            '                        We may process information that you provide to us for the purpose of unsubscribing to our email notifications and/or newsletters ("notification data"). The notification data may be processed for the purposes of sending you relevant notifications and/or newsletters. The legal basis for this processing is our legitimate interests, namely providing customers, who have expressed an interest in buying our services, up to date with our latest features, offers, and news. You can opt out of emails by click the unsubscribe link on any of our emails.\n' +
            '                    </p>\n' +
            '                    <p>\n' +
            '                        We may process information contained in or relating to any communication that you send to us ("correspondence data"). The correspondence data may include the communication content and metadata associated with the communication. We also record phone call content and the times of calls. Our website will generate the metadata associated with communications made using the website contact forms. The correspondence data may be processed for the purposes of communicating with you and record-keeping. The legal basis for this processing is our legitimate interests, namely the proper administration of our website and business and communications with users.\n' +
            '                    </p>\n' +
            '                    <p>\n' +
            '                        We may process any of your personal data identified in this policy where necessary for the establishment, exercise or defence of legal claims, whether in court proceedings or in an administrative or out-of-court procedure. The legal basis for this processing is our legitimate interests, namely the protection and assertion of our legal rights, your legal rights and the legal rights of others.\n' +
            '\n' +
            '                    </p>\n' +
            '                    <p>\n' +
            '                        We may process any of your personal data identified in this policy where necessary for the purposes of obtaining or maintaining insurance coverage, managing risks, or obtaining professional advice. The legal basis for this processing is our legitimate interests, namely the proper protection of our business against risks.\n' +
            '                    </p>\n' +
            '                    <h5>Providing your personal data to others\n' +
            '                    </h5>\n' +
            '                    <p>\n' +
            '                        We may disclose company name, address, contact names, phone numbers, and listing details to suppliers insofar as reasonably necessary for the purposes of providing sponsored outreach services you have requested. The services providers are only allowed to use and retain your data to allow the service you have requested to complete.\n' +
            '\n' +
            '                    </p>\n' +
            '                    <p>Financial transactions relating to our website and services are handled by our payment services provider, Paypal. We will share transaction data with our payment services providers only to the extent necessary for the purposes of processing your payments, refunding such payments and dealing with complaints and queries relating to such payments and refunds. You can find information about the payment services provider’s privacy policies and practices at www.paypal.com.\n' +
            '                    </p>\n' +
            '                    <p>\n' +
            '                        In addition to the specific disclosures of personal data set out in this Section 4, we may disclose your personal data where such disclosure is necessary for compliance with a legal obligation to which we are subject, or in order to protect your vital interests or the vital interests of another natural person. We may also disclose your personal data where such disclosure is necessary for the establishment, exercise or defence of legal claims, whether in court proceedings or in an administrative or out-of-court procedure.\n' +
            '\n' +
            '                    </p>\n' +
            '                    <h5>Providing Outreach services providers data to you\n' +
            '                    </h5>\n' +
            '                    <p>\n' +
            '                        We may disclose Outreach services providers contact data (name, phone number, company details) to you insofar as reasonably necessary for the purposes of providing the services you have requested. You are only allowed to use and retain this data to allow the service you have requested to complete.\n' +
            '\n' +
            '\n' +
            '                    </p>\n' +
            '                    <h5>Retaining and deleting personal data\n' +
            '                    </h5>\n' +
            '                    <p>This Section sets out our data retention policies and procedures, which are designed to help ensure that we comply with our legal obligations in relation to the retention and deletion of personal data.<br/>\n' +
            '                        Personal data that we process for any purpose or purposes shall not be kept for longer than is necessary for that purpose or those purposes.\n' +
            '                    </p>\n' +
            '                    <h5>We will retain your personal data as follows:\n' +
            '                    </h5>\n' +
            '                    <p>\n' +
            '                        Customer name, emails, listing information (including dates, listing specifics, and locations), service reviews. We will retain this information on your behalf for 6 years, to allow you to access your historical account details and, if eligible, any discounts or account credit that you may have received or earned as part of any incentives, offers, or promotions.<br/>\n' +
            '                        Notwithstanding the other provisions of this section, we may retain your personal data where such retention is necessary for compliance with a legal obligation to which we are subject, or in order to protect your vital interests or the vital interests of another natural person.\n' +
            '\n' +
            '                    </p>\n' +
            '                    <h5>Amendments\n' +
            '                    </h5>\n' +
            '                    <p>\n' +
            '                        We may update this policy from time to time by publishing a new version on our website.<br/>\n' +
            '                        You should check this page occasionally to ensure you are happy with any changes to this policy.<br/>\n' +
            '                        We may notify you of changes to this policy by email.<br/>\n' +
            '                        If, as determined by us, the lawful basis upon which we process your personal information changes, we will notify you about the change and any new lawful basis to be used if required. We shall stop processing your personal information if the lawful basis used is no longer relevant.\n' +
            '\n' +
            '                    </p>\n' +
            '                    <h5>Your individual rights</h5>\n' +
            '                    <p>\n' +
            '                        Under the GDPR your rights are as follows. You can read more about your rights in details here;\n' +
            '<ul class="moveleft">\n' +
            '                        <li>the right to be informed; </li>\n' +
            '                        <li> the right of access; </li>\n' +
            '                        <li> the right to rectification; </li>\n' +
            '                        <li> the right to erasure; </li>\n' +
            '                        <li> the right to restrict processing; </li>\n' +
            '                        <li> the right to data portability; </li>\n' +
            '                        <li> the right to object; and </li>\n' +
            '                        <li>  the right not to be subject to automated decision-making including profiling. </li>\n' +
            '\n' +
            '\n' +
            '                    </ul>\n' +
            '<br/>\n' +
            '                    You also have the right to complain to the ICO (www.ico.org.uk) if you feel there is a problem with the way we are handling your data.\n' +
            '<br/>\n' +
            '                    We handle subject access requests in accordance with the GDPR.\n' +
            '\n' +
            '                    </p>\n' +
            '\n' +
            '                    <h5>Internet cookies</h5>\n' +
            '                    <p>We use cookies on this website to provide you with a better user experience. We do this by placing a small text file on your device / computer hard drive to track how you use the website, to record or log whether you have seen particular messages that we display, to keep you logged into the website where applicable, to display relevant adverts or content, referred you to a third party website.</p>\n' +
            '                    <p>Some cookies are required to enjoy and use the full functionality of this website.\n' +
            '                    </p>\n' +
            '                    <p>We use a cookie control system which allows you to accept the use of cookies, and control which cookies are saved to your device / computer. Some cookies will be saved for specific time periods, where others may last indefinitely. Your web browser should provide you with the controls to manage and delete cookies from your device, please see your web browser options.\n' +
            '                    </p>\n' +
            '                    <p>Further information can be requested by emailing connect@ayojn.com\n' +
            '                    </p>\n' +
            '                    <h5>Data security and protection\n' +
            '                    </h5>\n' +
            '                    <p>We ensure the security of any personal information we hold by using secure data storage technologies and precise procedures in how we store, access and manage that information. Our methods meet the GDPR compliance requirement.\n' +
            '                    </p>\n' +
            '                    <h5>Fair & Transparent Privacy Explained\n' +
            '                    </h5>\n' +
            '                    <p>\n' +
            '                        We have provided some further explanations about user privacy and the way we use this website to help promote a transparent and honest user privacy methodology.\n' +
            '\n' +
            '                    </p>\n' +
            '                    <h5>Email marketing messages & subscription\n' +
            '                    </h5>\n' +
            '                    <p>\n' +
            '\n' +
            '                        Under the GDPR we use the consent lawful basis for anyone subscribing to our newsletter or marketing mailing list. We only collect certain data about you, as detailed in the "Processing of your personal data" above. Any email marketing messages we send are done so through an EMS, email marketing service provider. An EMS is a third-party service provider of software / applications that allows marketers to send out email marketing campaigns to a list of users.<br/>\n' +
            '                        Email marketing messages that we send may contain tracking beacons / tracked clickable links or similar server technologies in order to track subscriber activity within email marketing messages. Where used, such marketing messages may record a range of data such as; times, dates, I.P addresses, opens, clicks, forwards, geographic and demographic data. Such data, within its limitations will show the activity each subscriber made for that email campaign.<br/>\n' +
            '                        Any email marketing messages we send are in accordance with the GDPR and the PECR. We provide you with an easy method to withdraw your consent (unsubscribe) or manage your preferences / the information we hold about you at any time. See any marketing messages for instructions on how to unsubscribe or manage your preferences, otherwise contact the EMS provider.<br/>\n' +
            '                        Our EMS provider is; Google. We hold the following information about you within our EMS system;<br/>\n' +
            '                       <ul class="moveleft"> <li>Email address</li>\n' +
            '                        <li>I.P address</li>\n' +
            '                        <li>Subscription time & date</li>\n' +
            '                    </ul>\n' +
            '                        <ul class="moveleft">Resources & further information\n' +
            '                        <li>Overview of the GDPR - General Data Protection Regulation</li>\n' +
            '                        <li>Data Protection Act 2018</li>\n' +
            '                        <li>Privacy and Electronic Communications Regulations 2003</li>\n' +
            '                        <li> The Guide to the PECR 2003</li>\n' +
            '                    </ul>\n' +
            '\n' +
            '                    </p>\n' +
            '\n' +
            '                </div>');
    }

    function opentop_countries() {
        var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
        $.ajax({
            type: 'POST',
            url: "{{ route('topcountriesdata') }}",
            data: {_token: CSRF_TOKEN},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success: function (data) {
                console.log(data);
                $('#popupcountry').html(data);
                $('#topCountriesForm').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                toastr.error("Something went wrong", "Error");
            },
        });

    }

    function opentop_destination() {
        var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
        $.ajax({
            type: 'POST',
            url: "{{ route('topdestinationdata') }}",
            data: {_token: CSRF_TOKEN},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success: function (data) {
                console.log(data);
                $('#popupdestination').html(data);
                $('#topdestinationForm').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                toastr.error("Something went wrong", "Error");
            },
        });

    }

    function opentop_industries() {
        var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
        $.ajax({
            type: 'POST',
            url: "{{ route('topindustriesdata') }}",
            data: {_token: CSRF_TOKEN},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success: function (data) {
                console.log(data);
                $('#popupindustries').html(data);
                $('#topindustriesForm').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                toastr.error("Something went wrong", "Error");
            },
        });

    }

    function opentop_recipients() {
        var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
        $.ajax({
            type: 'POST',
            url: "{{ route('toprecieptdsdata') }}",
            data: {_token: CSRF_TOKEN},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success: function (data) {
                console.log(data);
                $('#popupindustries').html(data);
                $('#topindustriesForm').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                toastr.error("Something went wrong", "Error");
            },
        });

    }

    function openEditForm(id) {
        $('.loader').show();
        $.ajax({
            type: 'GET',
            url: "{{ route('getUserCount') }}" + '/' + id,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success: function (data) {
                $('.loader').hide();
                $('#edituserform').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                toastr.error("Something went wrong", "Error");
            },
        });
    }

    function openreferafriend() {
        $('.loader').show();
        $.ajax({
            type: 'GET',
            url: "{{ route('getReferFriends') }}",

            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success: function (data) {
                $('.loader').hide();
                console.log(data);
                $("#refer-user").html(data.html);
                $('#myModal').modal('show');


            },
            error: function (jqXHR, textStatus, errorThrown) {
                toastr.error("Something went wrong", "Error");
            },
        });

    }

    function displaySpecify(flagSpecify) {
        if (flagSpecify == 1) {
            $("#sponsorOtherSpecify").show();
            var event_type = $("#sponsorType").val();
            $('#sponsorOtherSpecifyValue').html('');
            //$('#sponsorOtherSpecifyValue').append('<option value="">Select Specify</option>');
        } else {
            $("#sponsoredOtherSpecify").show();
            var event_type = $("#sponsoredType").val();
            $('#sponsoredOtherSpecifyValue').html('');
            //$('#sponsoredOtherSpecifyValue').append('<option value="">Select Specify</option>');
        }
        $("#sponsoredOtherSpecify").show();
        var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
        $.ajax({
            type: 'POST',
            url: "{{ route('getspecify') }}",
            data: {_token: CSRF_TOKEN, event_type: event_type},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success: function (data) {
                console.log(data);
                if (data != '') {
                    // Loop through each of the results and append the option to the dropdown
                    $.each(data, function (k, v) {
                        if (flagSpecify == 1) {
                            $('#sponsorOtherSpecifyValue').append('<option value="' + v.id + '">' + v.specify_name + '</option>');
                        } else {
                            $('#sponsoredOtherSpecifyValue').append('<option value="' + v.id + '">' + v.specify_name + '</option>');
                        }
                    });
                }
                //$('#popupcountry').html(data);
                //$('#topCountriesForm').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                toastr.error("Something went wrong", "Error");
            },
        });
    }

    function displaySpecifyEdit(flagSpecify) {
        if (flagSpecify == 1) {
            //$("#sponsorOtherSpecify").show();
            var event_type = $("#sponsorType_edit").val();
            $('#sponsorOtherSpecifyValue_edit').html('');
            //$('#sponsorOtherSpecifyValue_edit').append('<option value="">Select Specify</option>');
        } else {
            //$("#sponsoredOtherSpecify").show();
            var event_type = $("#sponsorType_edit").val();
            $('#sponsoredOtherSpecifyValue').html('');
            //$('#sponsorOtherSpecifyValue_edit').append('<option value="">Select Specify</option>');
        }
        //$("#sponsoredOtherSpecify").show();
        var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
        $.ajax({
            type: 'POST',
            url: "{{ route('getspecify') }}",
            data: {_token: CSRF_TOKEN, event_type: event_type},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success: function (data) {
                //console.log(data);
                if (data != '') {
                    // Loop through each of the results and append the option to the dropdown
                    $.each(data, function (k, v) {
                        if (flagSpecify == 1) {
                            $('#sponsorOtherSpecifyValue_edit').append('<option value="' + v.id + '">' + v.specify_name + '</option>');
                        } else {
                            $('#sponsorOtherSpecifyValue_edit').append('<option value="' + v.id + '">' + v.specify_name + '</option>');
                        }

                    });
                }
                //$('#popupcountry').html(data);
                //$('#topCountriesForm').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                toastr.error("Something went wrong", "Error");
            },
        });
    }

    function getSpecifyBid() {
        var event_type = $("#sponsorr_type_bid").val();
        $('#specify_bid').html('');
        //$("#sponsoredOtherSpecify").show();
        var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
        $.ajax({
            type: 'POST',
            url: "{{ route('getspecify') }}",
            data: {_token: CSRF_TOKEN, event_type: event_type},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success: function (data) {
                //console.log(data);
                if (data != '') {
                    // $('#specify_bid').html('');
                    // Loop through each of the results and append the option to the dropdown
                    $.each(data, function (k, v) {

                        $('#specify_bid').append('<option value="' + v.id + '">' + v.specify_name + '</option>');
                    });
                }
                //$('#popupcountry').html(data);
                //$('#topCountriesForm').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                toastr.error("Something went wrong", "Error");
            },
        });
    }

    function getSpecifyBidEdit() {
        var event_type = $("#sponsorr_type_bid_edit").val();
        var bidid = $('#bidId').val();
        $('#specify_bid_edit').html('');
        //$("#sponsoredOtherSpecify").show();
        var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
        $.ajax({
            type: 'POST',
            url: "{{ route('getspecifyedit') }}",
            data: {_token: CSRF_TOKEN, event_type: event_type, bidid: bidid},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success: function (data) {
                //console.log(data);
                if (data != '') {
                    // $('#specify_bid').html('');
                    // Loop through each of the results and append the option to the dropdown
                    var selected = '';
                    $.each(data, function (k, v) {
                        if (v.selected == 1) {
                            selected = 'selected';
                        } else {
                            selected = '';
                        }
                        $('#specify_bid_edit').append('<option value="' + v.id + '"' + selected + '>' + v.specify_name + '</option>');
                    });
                }
                //$('#popupcountry').html(data);
                //$('#topCountriesForm').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                toastr.error("Something went wrong", "Error");
            },
        });
    }
    setTimeout(function () {
        $(".dataTables_info").hide()
        var hideElm = 'entries',
            regex = new RegExp(hideElm, 'g');

        $('#table_id_wrapper').html(function(i, html){
            return html.replace(regex, '<span style="display:none">' + hideElm + '</span>');
        });

    },500);

</script>
<!--End of Tawk.to Script-->
</body>
</html>
