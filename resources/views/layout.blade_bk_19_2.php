<?php
use App\Country;
$countries = Country::all();
use App\Industry;
$industries = Industry::all();
use App\SponsorrSpecify;
use App\SponsorrSpecifyList;
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

	<meta name="description" content="Sponsorship discovery platform">

	<link href="https://fonts.googleapis.com/css?family=PT+Sans:700" rel="stylesheet"/>
	<link href="https://fonts.googleapis.com/css?family=Rubik:400,500,700" rel="stylesheet"/>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet"/>
	<link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet"/>
	<link rel="stylesheet" href="{{ asset('css/style.css') }}"/>
	<link rel="stylesheet" href="{{ asset('css/style1.css') }}"/>
	<link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}"/>
	<link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}"/>
	<link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css"/>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css"/>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="{{ asset('js/toastr.min.js') }}"></script>
	<script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
	<script src="https://www.amcharts.com/lib/3/serial.js"></script>
	<script src="https://www.amcharts.com/lib/3/ammap.js"></script>
	<script src="https://www.amcharts.com/lib/3/maps/js/worldLow.js"></script>
	<script src="{{ asset('js/light.js') }}"></script>

	<script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
	<script src="https://www.amcharts.com/lib/3/themes/none.js"></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>


	<link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all"/>
	<!-- Default Theme -->
	<!-- Resourcedddddd style -->
	<title>Ayojn</title>
	<meta property="og:title" content="Thinking of sponsoring | Sponsorship discovery platform"/>
	<meta property="og:description" content="Sponsorship discovery platform"/>
	<meta property="og:url" content="https://sponsorr.co/" />

	<meta name="_token" content="{{ csrf_token() }}">
	<script>
      var base_url = "{{ url('/')}}";
	</script>

	<style>
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
						Edit {{ (Auth::user()->sponsor_type == 1)?'Offer ':'Manage or Receive' }} Profile</h4>
				</div>
				<div class="modal-body">
					<form class="edituserform" id="edituserform" method="post" onsubmit="return false"
						  name="edituserform">
						<div class="form-group">
							<!-- <h3>
								Let's gather the relevant details so that we can match your information requirements
							</h3> -->
						</div>
						<div class="form-group">
							<label>
								<strong>Email Id</strong>
							</label>
						</div>
						<div class="form-group">
							<input type="email" class="form-control" placeholder="Email" name="email_edit"
								   id="email_edit" value="{{ Auth::user()->email }}" onblur="validateEmail(this);">
						</div>
						<div class="form-group">
							<label>
								<strong>Entity</strong>
							</label>
						</div>
						<div class="form-group">
							<input type="text" class="form-control" placeholder="Entity" name="entity_edit"
								   id="entity_edit" value="{{ Auth::user()->entity }}">
						</div>
						<div class="form-group">
							<label>
								<strong>Country</strong>
							</label>
						</div>
						<div class="form-group">
							<select class="form-control" name="country_edit" id="country_edit">
								<option value="">Select Country</option>
								@if(!empty($countries))
									@foreach($countries as $c)
										@if(Auth::user()->country == $c->country_code)
											@php
												$selected='selected';
											@endphp
										@else
											@php
												$selected='';
											@endphp
										@endif
										<option
											value="<?= $c->country_code?>" {{ $selected }} ><?= $c->country_name?></option>
									@endforeach
								@endif
							</select>
						</div>
						<div class="form-group">
							<label>
								@if(Auth::user()->sponsor_type == 1)
									<strong>What do you wish to Sponsor?</strong>
								@else
									<strong>What do you want to get Sponsored</strong>
								@endif
							</label>
						</div>

						<div class="form-group">
							<select class="form-control" name="sponsorType_edit" id="sponsorType_edit"
									onchange="displaySpecifyEdit(1)">
								<option value="select">Select Any</option>
								<option value="Event" {{ (Auth::user()->sponsor_for == 'Event')?'selected':'' }}>Event
								</option>
								<option value="Campaign" {{ (Auth::user()->sponsor_for == 'Campaign')?'selected':'' }}>
									Campaign
								</option>
								<option value="Content" {{ (Auth::user()->sponsor_for == 'Content')?'selected':'' }}>
									Content
								</option>
								<option
									value="Sports Team" {{ (Auth::user()->sponsor_for == 'Sports Team')?'selected':'' }}>
									Sports Team
								</option>
								<option value="Venue" {{ (Auth::user()->sponsor_for == 'Venue')?'selected':'' }}>Venue
								</option>
								<option
									value="Not for Profit" {{ (Auth::user()->sponsor_for == 'Not for Profit')?'selected':'' }}>
									Not for Profit
								</option>
								<option
									value="Performing Arts" {{ (Auth::user()->sponsor_for == 'Performing Arts')?'selected':'' }}>
									Performing Arts
								</option>
								<option
									value="Think Tank" {{ (Auth::user()->sponsor_for == 'Think Tank')?'selected':'' }}>
									Think Tank
								</option>
								<option
									value="Knowledge Pool" {{ (Auth::user()->sponsor_for == 'Knowledge Pool')?'selected':'' }}>
									Knowledge Pool
								</option>
								<option
									value="Online Events" {{ (Auth::user()->sponsor_for == 'Online Events')?'selected':'' }}>
									Online Activities
								</option>
								<option
									value="Research" {{ (Auth::user()->sponsor_for == 'Research')?'selected':'' }}>
									Research
								</option>
								<option value="Other" {{ (Auth::user()->sponsor_for == 'Other')?'selected':'' }}>Other
								</option>
							</select>

						</div>
						<div id="sponsorOtherSpecifys" style="display: show !important;">
							<div class="form-group autocomplete">
								<label>
									<strong>Please Specify</strong>
								</label>

								{{--<input type="text" class="form-control" placeholder="e.g Conference, Music Festival, Tradeshow, Exhibition etc" id="sponsorOtherSpecifyValue">--}}
								<select class="form-control" name="sponsorOtherSpecifyValue_edit[]"
										id="sponsorOtherSpecifyValue_edit" multiple>
									@if(count($sponsorrlist) > 0)
										@foreach($sponsorrlist as $sponsor_list)
											<option
												value="{{ $sponsor_list->id }}" {{ (in_array($sponsor_list->id,$userwisesponsorarray))?'selected':'' }}>{{ $sponsor_list->specify_name }}</option>
										@endforeach
									@endif
								</select>
							</div>
						</div>
						@if(Auth::user()->sponsor_type == 1)
                            <?php if (! empty(Auth::user()->likeSponsorr)) {
                                $likeArray = explode(',', Auth::user()->likeSponsorr);
                            } else {
                                $likeArray = [];
                            }?>
							<div class="form-group">
								<label>
									<strong>Why would you like to Sponsor?</strong>
								</label>
							</div>
							<div class="form-group ">
								<select class="form-control" name="likeSponsorr_edit[]" id="likeSponsorr_edit" multiple>
									<option value="Message" <?php if (in_array('Message', $likeArray)) {
                                        echo "selected";
                                    }?>>Message
									</option>
									<option value="Leads" <?php if (in_array('Leads', $likeArray)) {
                                        echo "selected";
                                    }?>>Leads
									</option>
									<option value="Branding" <?php if (in_array('Branding', $likeArray)) {
                                        echo "selected";
                                    }?>>Branding
									</option>
								</select>
							</div>
						@endif

						<div class="form-group">
							<label>
								<strong>Where would you like this to be sponsored</strong>
							</label>
						</div>
						<div class="form-group">
							<select class="form-control" name="sponsorCountry_edit" id="sponsorCountry_edit">
								<option value="">Select Country</option>
								@if(!empty($countries))
									@foreach($countries as $c)
										<option
											value="<?= $c->country_code?>" {{ ($c->country_code == Auth::user()->country)?'selected':'' }}><?= $c->country_name?></option>
									@endforeach
								@endif
							</select>
						</div>


						<div class="form-group">
							<label>
								<strong>What's your ideal deal size </strong>
							</label>
						</div>

						<div class="form-group">
							<select class="form-control" name="sponsorBudget_edit" id="sponsorBudget_edit">
								<option value="">Select Any</option>
								<option
									value="Less than 2000" {{ (Auth::user()->sponsor_budget == 'Less than 2000')?'selected':'' }}>
									Less than $2000
								</option>
								<option
									value="Between 2000-5000" {{ (Auth::user()->sponsor_budget == 'Between 2000-5000')?'selected':'' }}>
									Between $2000-$5000
								</option>
								<option
									value="Between 5000-20000" {{ (Auth::user()->sponsor_budget == 'Between 5000-20000')?'selected':'' }}>
									Between $5000-$20000
								</option>
								<option
									value="Between 20000-50000" {{ (Auth::user()->sponsor_budget == 'Between 20000-50000')?'selected':'' }}>
									Between $20000-$50000
								</option>
								<option
									value="Above 20000" {{ (Auth::user()->sponsor_budget == 'Above 20000')?'selected':'' }}>
									Above $20000
								</option>

							</select>
						</div>
						<div class="form-group">
							<label>
								<strong>Select Industry </strong>
							</label>
						</div>
						<div class="form-group">
							<select class="form-control" name="sponsorIndustry_edit" id="sponsorIndustry_edit">
								<option value="test">Select your Industry</option>
								@foreach($industries as $in){
								<option
									value="{{ $in->id}}" {{ (Auth::user()->sponsor_industry == $in->id)?'selected':'' }}>{{ $in->name}}</option>
								}
								@endforeach
							</select>

						</div>
						{{--<button type="button" class="btn btn-default" onclick="secondPopupPrevious()">Previous</button>--}}
						<button type="submit" id="editformsubmit" onclick="UpdateUserForm()" class="btn btn-default">
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
        var usercountry_edit = $("#country_edit").val();
        var sponsorType_edit = $("#sponsorType_edit").val();
        var sponsorOtherSpecifyValue_edit = $("#sponsorOtherSpecifyValue_edit").val();
        var sponsorCountry_edit = $("#sponsorCountry_edit").val();
        var sponsorBudget_edit = $("#sponsorBudget_edit").val();
        var sponsorIndustry_edit = $("#sponsorIndustry_edit").val();
        var likeSponsorr_edit = $("#likeSponsorr_edit").val();


        // $('#create_post').prop('disabled', true);
        $.ajax({
          type: 'POST',
          url: '{{ route('edituserprofile') }}',
          headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
          data: {
            sponsorType_edit: sponsorType_edit,
            sponsorOtherSpecifyValue_edit: sponsorOtherSpecifyValue_edit,
            sponsorCountry_edit: sponsorCountry_edit,
            sponsorBudget_edit: sponsorBudget_edit,
            sponsorIndustry_edit: sponsorIndustry_edit,
            useremail_edit: useremail_edit,
            userentity_edit: userentity_edit,
            usercountry_edit: usercountry_edit,
            likeSponsorr_edit: likeSponsorr_edit
          },
          //cache: false,
          //contentType: false,
          //processData: false,
          success: function (response) {
            //console.log(response.status);
            var data = response;
            if (response.status) {
              toastr.success(response.msg, "Success");
              window.location.reload();
            } else {
              toastr.error(response.msg, "Error");
            }

            /*notification = new Notification('New post alert!', {
			 body: 'this is test', // content for the alert
			 icon: "https://pusher.com/static_logos/320x320.png" // optional image url
			 });*/

            // link to page on clicking the notification
            //
            //}

          },
          error: function (response) {
            toastr.error(response.responseJSON.msg, "Error");
            $(':input[type="submit"]').prop('disabled', false);
          },
        });

      }
	</script>
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
							<div class="col-md-2">
								<label class="custom-padding">
									<b>Want to</b>
								</label>
							</div>
							<div class="col-md-4">
								<select class="form-control" id="offerOption">
									<option value="">Select Any</option>
									<option value="1">Offer</option>
									<option value="2">Receive</option>
								</select>
							</div>
							<div class="col-md-4">
								<label class="custom-padding"><b>Sponsorship</b></label>
							</div>
						</div>
						<div class="row">

									<input type="checkbox" class="form-control" style="width: 22px;float: left" name="firstCheckBox" id="firstCheckBox"
										   value="1"><span style="line-height: 3;padding-left: 5px">By signing up, you agree to the <a
										href="javascript: popuponclick()">Terms
										of Service</a> and <a href="javascript: privacyWindow()">Privacy Policy</a>.</span>

{{--							<div class="col-md-12">--}}
{{--								<input type="checkbox" style="width: 22px;float: left" name="terms_condition" id="terms_condition" class="tearm-chkbox">--}}
{{--								<span style="line-height: 3;padding-left: 5px">Agree to Terms and Conditions</span>--}}
{{--							</div>--}}
						</div>

					</div>
					<button type="button" id="firstFormNext" class="btn btn-default nextOption firstFormNext"
							onclick="checkFirstPopupValidation()">Next
					</button>
				</form>
				<form class="formClass secondForm">
					<div class="row alert alert-danger" id="nameMessage">
						<strong>Warning!</strong> Please Enter Name
					</div>
					<div class="row alert alert-danger" id="passwordMessage">
						<strong>Warning!</strong> Please Enter Password
					</div>
					<div class="row alert alert-danger" id="emailMessage">
						<strong>Warning!</strong> Please Enter Email
					</div>
					<div class="row alert alert-danger" id="emailValidationMessage">
						<strong>Warning!</strong> Please Enter Valid Email
					</div>
					<div class="row alert alert-danger" id="organisationMessage">
						<strong>Warning!</strong> Please Enter Organisation
					</div>
					<div class="row alert alert-danger" id="countryMessage">
						<strong>Warning!</strong> Please Select Country
					</div>
					<div class="form-group" style="display: none">
						<input type="text" class="form-control" placeholder="Name" name="name" id="name" value="test">
					</div>
					<div class="form-group">
						<input type="email" class="form-control" placeholder="Email" name="email" id="email"
							   onblur="validateEmail(this);">
						<p id="emailNew" style="color:red">Please sign up using your work email address since we will
							conduct a validation.</p>
					</div>
					<div class="form-group">
						<input type="password" class="form-control" placeholder="Password" name="password"
							   id="password">
					</div>
					<div class="form-group">
						<input type="text" class="form-control" placeholder="Entity" name="entity" id="entity">
					</div>
					<div class="form-group" style="display: none">
						<input type="text" class="form-control" placeholder="Organisation" name="organisation"
							   id="organisation" value="test">
					</div>
					<div class="form-group">
						<select class="form-control" name="country" id="country">
							<option value="">Select Country</option>
							@if(!empty($countries))
								@foreach($countries as $c)
									<option value="<?= $c->country_code?>"><?= $c->country_name?></option>
								@endforeach
							@endif
						</select>
					</div>

					<button type="button" class="btn btn-default nextOption" onclick="firstPopupPrevious()">Previous
					</button>
					<button type="button" id="secondFormNext" class="btn btn-default nextOption secondFormNext"
							onclick="secondPopup()">Next
					</button>
				</form>


				<form class="formClass thirdForm" autocomplete="off" onsubmit="return false;">
					<div class="row alert alert-danger" id="sponsoredTypeMessage">
						<strong>Warning!</strong> Please Select Sponsor Type
					</div>
					<div class="row alert alert-danger" id="sponsoredCountryMessage">
						<strong>Warning!</strong> Please Select Where You Get Sponsor
					</div>
					<div class="row alert alert-danger" id="sponsoredBudgetMessage">
						<strong>Warning!</strong> Please Select Sponsor Budget
					</div>
					<div class="row alert alert-danger" id="sponsoredIndustryMessage">
						<strong>Warning!</strong> Please Select sponsor Industry
					</div>
					<div class="row alert alert-danger" id="sponsoredOtherSpecifyValueMessage">
						<strong>Warning!</strong> Please Specify
					</div>
					<!--<div class="form-group">
						<h3>
							Let's gather the relevant details so that we can match your information requirements
						</h3>
					</div> -->
					<div class="form-group">
						<label>
							<strong>What do you wish to Sponsor?</strong>
						</label>
					</div>

					<div class="form-group"><!--onchange="displayOther(2)"-->
						<select class="form-control" name="sponsoredType" id="sponsoredType"
								onchange="displaySpecify(2)">
							<option value="select">Select Any</option>
							<option value="Event">Event</option>
							<option value="Campaign">Campaign</option>
							<option value="Content">Content</option>

							<option value="Sports Team">Sports Team</option>
							<option value="Venue">Venue</option>
							<option value="Not for Profit">Not for Profit</option>
							<option value="Performing Arts">Performing Arts</option>
							<option value="Think Tank">Think Tank</option>
							<option value="Knowledge Pool">Knowledge Pool</option>
							<option value="Online Events">Online Activities</option>
							<option value="Research">Research</option>
							<option value="Other">Other</option>
						</select>

					</div>

					<div class="form-group  autocomplete" id="sponsoredOtherSpecify">
						<label>
							<strong>Please Specify</strong>
						</label>

						{{--<input type="text" class="form-control" placeholder="e.g Conference, Music Festival, Tradeshow, Exhibition etc" id="sponsoredOtherSpecifyValue">--}}
						<select class="form-control" name="sponsoredOtherSpecifyValue[]" id="sponsoredOtherSpecifyValue"
								multiple></select>

					</div>
					<div class="form-group">
						<label>
							<strong>Why would you like to Sponsor?</strong>
						</label>
					</div>
					<div class="form-group ">
						<select class="form-control" name="likeSponsorr[]" id="likeSponsorr" multiple>
							<option value="Message">Message</option>
							<option value="Leads">Leads</option>
							<option value="Branding">Branding</option>
						</select>
					</div>

					<div class="form-group">
						<label>
							<strong>Where would you like to sponsor this</strong>
						</label>
					</div>

					<div class="form-group">
						<select class="form-control" name="sponsoredCountry" id="sponsoredCountry">
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
							<strong>What's your expected budget in USD </strong>
						</label>
					</div>

					<div class="form-group">
						<select class="form-control" name="sponsoredBudget" id="sponsoredBudget">
							<option value="">Select Any</option>
							<option value="Less than 2000">Less than $2000</option>
							<option value="Between 2000-5000">Between $2000-$5000</option>
							<option value="Between 5000-20000">Between $5000-$20000</option>
							<option value="Between 20000-50000">Between $20000-$50000</option>
							<option value="Above 50000">Above $50000</option>

						</select>

					</div>
					<div class="form-group">
						<label>
							<strong>Select Industry </strong>
						</label>
					</div>
					<div class="form-group">
						<select class="form-control" name="sponsoredIndustry" id="sponsoredIndustry">
							<option value="">Select your Industry</option>
							@foreach($industries as $i){
							<option value="{{ $i->id}}">{{ $i->name}}</option>
							}
							@endforeach
						</select>

					</div>

					<button type="button" class="btn btn-default" onclick="secondPopupPrevious()">Previous</button>
					<button type="button" id="thirdFormId" class="btn btn-default" onclick="submitForm()">Submit
					</button>
				</form>
				<form class="formClass forthForm" autocomplete="off" onsubmit="return false;">
					<div class="row alert alert-danger" id="sponsorTypeMessage">
						<strong>Warning!</strong> Please Select Sponsor Type
					</div>
					<div class="row alert alert-danger" id="sponsorCountryMessage">
						<strong>Warning!</strong> Please Select Where You Get Sponsored
					</div>
					<div class="row alert alert-danger" id="sponsorBudgetMessage">
						<strong>Warning!</strong> Please Select Sponsored Budget
					</div>
					<div class="row alert alert-danger" id="sponsorIndustryMessage">
						<strong>Warning!</strong> Please Select sponsored Industry
					</div>
					<div class="row alert alert-danger" id="sponsorOtherSpecifyValueMessage">
						<strong>Warning!</strong> Please Specify
					</div>
					<!--<div class="form-group">
						<h3>
							Let's gather the relevant details so that we can match your information requirements
						</h3>
					</div> -->
					<div class="form-group">
						<label>
							<strong>What do you want to get Sponsored</strong>
						</label>
					</div>

					<div class="form-group">
						<select class="form-control" name="sponsorType" id="sponsorType" onchange="displaySpecify(1)">
							<option value="select">Select Any</option>
							<option value="Event">Event</option>
							<option value="Campaign">Campaign</option>
							<option value="Content">Content</option>
							<option value="Sports Team">Sports Team</option>
							<option value="Venue">Venue</option>
							<option value="Not for Profit">Not for Profit</option>
							<option value="Performing Arts">Performing Arts</option>
							<option value="Think Tank">Think Tank</option>
							<option value="Knowledge Pool">Knowledge Pool</option>
							<option value="Online Events">Online Activities</option>
							<option value="Research">Research</option>
							<option value="Other">Other</option>
						</select>

					</div>
					<div id="sponsorOtherSpecify">
						<div class="form-group autocomplete">
							<label>
								<strong>Please Specify</strong>
							</label>

							{{--<input type="text" class="form-control" placeholder="e.g Conference, Music Festival, Tradeshow, Exhibition etc" id="sponsorOtherSpecifyValue">--}}
							<select class="form-control" name="sponsorOtherSpecifyValue[]" id="sponsorOtherSpecifyValue"
									multiple></select>
						</div>
					</div>

					<div class="form-group">
						<label>
							<strong>Where would you like this to be sponsored</strong>
						</label>
					</div>
					<div class="form-group">
						<select class="form-control" name="sponsorCountry" id="sponsorCountry">
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
							<strong>What's your ideal deal size </strong>
						</label>
					</div>

					<div class="form-group">
						<select class="form-control" name="sponsorBudget" id="sponsorBudget">
							<option value="">Select Any</option>
							<option value="Less than 2000">Less than $2000</option>
							<option value="Between 2000-5000">Between $2000-$5000</option>
							<option value="Between 5000-20000">Between $5000-$20000</option>
							<option value="Between 20000-50000">Between $20000-$50000</option>
							<option value="Above 50000">Above $50000</option>

						</select>
					</div>
					<div class="form-group" style="display: none">
						<label>
							<strong>Select Industry </strong>
						</label>
					</div>
					<div class="form-group" style="display: none">
						<select class="form-control" name="sponsorIndustry" id="sponsorIndustry">
							<option value="test">Select your Industry</option>
							@foreach($industries as $in){
							<option value="{{ $in->id}}">{{ $in->name}}</option>
							}
							@endforeach
						</select>

					</div>
{{--					<div class="form-group">--}}
{{--						<label>--}}
{{--							<input type="checkbox" class="form-control" name="secondCheckBox" id="secondCheckBox"--}}
{{--								   value="1"> By signing up, you agree to the <a href="javascript: popuponclick()">Terms--}}
{{--								of Service</a> and <a href="javascript: privacyWindow()">Privacy Policy</a>.--}}
{{--						</label>--}}
{{--					</div>--}}
					<button type="button" class="btn btn-default" onclick="secondPopupPrevious()">Previous</button>
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
						<a href="{{ url('/password/reset') }}">Forgot password</a>
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
				<p>Sponsorr is a discovery platform for Sponsorship Opportunities.</p>
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
					Connect with us at <b>Live Chat</b> or alternatively write us at connect(at)sponsorr.co.
					<!--<br>SponsorrCo Ireland Private Limited<br>23, Trinity Technology & Enterprise Campus, <br>Pearse St, Grand Canal Dock, <br>Dublin 2, D02 WR66 -->
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
				<!--<h2>Sponsorr Terms of Service</h2> -->
				<div style="padding-left: 10px;padding-right: 10px;">
					<h3>We don't offer Terms & Conditions rather it's Trust we wish to build. </h3>

					<p>Keeping your business interest intact is the top priority at Sponsorr and instead of you agree to
						us, let's rather build Trust. Therefore please tick the box if you decide never to use Sponsorr
						for illicit activities and we promise never to share, sell, publicise any information about your
						activities at Sponsorr unless you do it by yourself. </p>

					<!--<h3>2. Use License</h3>
					<ol type="a">
						<li>Permission is granted to temporarily download one copy of the materials (information or
							software) on Sponsorr's website for personal, non-commercial transitory viewing only. This
							is the grant of a license, not a transfer of title, and under this license you may not:
							<ol type="i">
								<li>modify or copy the materials;</li>
								<li>use the materials for any commercial purpose, or for any public display (commercial
									or non-commercial);
								</li>
								<li>attempt to decompile or reverse engineer any software contained on Sponsorr's
									website;
								</li>
								<li>remove any copyright or other proprietary notations from the materials; or</li>
								<li>transfer the materials to another person or "mirror" the materials on any other
									server.
								</li>
							</ol>
						</li>
						<li>This license shall automatically terminate if you violate any of these restrictions and may
							be terminated by Sponsorr at any time. Upon terminating your viewing of these materials or
							upon the termination of this license, you must destroy any downloaded materials in your
							possession whether in electronic or printed format.
						</li>
					</ol>
					<h3>3. Disclaimer</h3>
					<ol type="a">
						<li>The materials on Sponsorr's website are provided on an 'as is' basis. Sponsorr makes no
							warranties, expressed or implied, and hereby disclaims and negates all other warranties
							including, without limitation, implied warranties or conditions of merchantability, fitness
							for a particular purpose, or non-infringement of intellectual property or other violation of
							rights.
						</li>
						<li>Further, Sponsorr does not warrant or make any representations concerning the accuracy,
							likely results, or reliability of the use of the materials on its website or otherwise
							relating to such materials or on any sites linked to this site.
						</li>
					</ol>
					<h3>4. Limitations</h3>
					<p>In no event shall Sponsorr or its suppliers be liable for any damages (including, without
						limitation, damages for loss of data or profit, or due to business interruption) arising out of
						the use or inability to use the materials on Sponsorr's website, even if Sponsorr or a Sponsorr
						authorized representative has been notified orally or in writing of the possibility of such
						damage. Because some jurisdictions do not allow limitations on implied warranties, or
						limitations of liability for consequential or incidental damages, these limitations may not
						apply to you.</p>
					<h3>5. Accuracy of materials</h3>
					<p>The materials appearing on Sponsorr's website could include technical, typographical, or
						photographic errors. Sponsorr does not warrant that any of the materials on its website are
						accurate, complete or current. Sponsorr may make changes to the materials contained on its
						website at any time without notice. However Sponsorr does not make any commitment to update the
						materials.</p>
					<h3>6. Links</h3>
					<p>Sponsorr has not reviewed all of the sites linked to its website and is not responsible for the
						contents of any such linked site. The inclusion of any link does not imply endorsement by
						Sponsorr of the site. Use of any such linked website is at the user's own risk.</p>
					<h3>7. Modifications</h3>
					<p>Sponsorr may revise these terms of service for its website at any time without notice. By using
						this website you are agreeing to be bound by the then current version of these terms of
						service.</p>
					<h3>8. Governing Law</h3>
					<p>These terms and conditions are governed by and construed in accordance with the laws of Ireland
						and you irrevocably submit to the exclusive jurisdiction of the courts in that State or
						location.</p> -->
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
					<p>Your privacy is important to us. It is Sponsorr's policy to respect your privacy regarding any
						information we may collect from you across our website, <a href="https://sponsorr.co">https://sponsorr.co</a>,
						and other sites we own and operate.</p>
					<p>We only ask for personal information when we truly need it to provide a service to you. We
						collect it by fair and lawful means, with your knowledge and consent. We also let you know why
						we’re collecting it and how it will be used.</p>
					<p>We only retain collected information for as long as necessary to provide you with your requested
						service. What data we store, we’ll protect within commercially acceptable means to prevent loss
						and theft, as well as unauthorised access, disclosure, copying, use or modification.</p>
					<p>We don’t share any personally identifying information publicly or with third-parties, except when
						required to by law.</p>
					<p>Our website may link to external sites that are not operated by us. Please be aware that we have
						no control over the content and practices of these sites, and cannot accept responsibility or
						liability for their respective privacy policies.</p>
					<p>You are free to refuse our request for your personal information, with the understanding that we
						may be unable to provide you with some of your desired services.</p>
					<p>Your continued use of our website will be regarded as acceptance of our practices around privacy
						and personal information. If you have any questions about how we handle user data and personal
						information, feel free to contact us.</p>
					<p>This policy is effective as of 4 February 2019.</p>
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
			<!-- The data reflected at Sponsorr is calibrated from the public domain access plus based on the inputs from the Users (Sponsors and Sponsorship Receivers/ Managers).-->
			<div class="modal-body">
				<h3 style="text-align: center"><b>How it Works!</b></h3>
				<p>1. Select if you wish to Offer/ Receive Sponsorship </p>
				<br>
				<p>2. Share what you wish to Sponsor or Get Sponsored </p>
				<br>
				<p>3. We Validate your Profile </p>
				<br>
				<p>4. Go Live - Spot Trends, Gain Insights &amp; Build Sponsorship</p>
				<br>
				<p><strong>Still not clear, don't worry ! Let us guide you through. <br/> <br/>Click on the chat button
						on the right hand
						side corner at the bottom of the web and we will be happy to help.<br/>Free to use platform, No
						cards required to Sign-up.</strong></p>


				<!-- <p>The data reflected at Sponsorr is based on the inputs from the Users (Sponsors/ Influencers and Sponsorship Receivers/ Managers).Please refer the below mentioned glossary to make full use of Sponsorr:
					<br><br>

					<b>Map</b> : This reflects the inputs given by various Users (Sponsors and Sponsorship receivers/ managers) based on their requirements. It's a dynamic map and shows the instant changes as the inputs are been fed by the users.
					<br><br>
					<b>Emerging From </b>: Reflects the top countries from where more number of Sponsors are emerging from.
					<br><br>
					<b>Directed To</b> : Reflects the top countries which are receiving more number of Sponsors.
					<br><br>
					<b>Biggest Spenders</b> : Reflects the top industries investing in Sponsorship (more number of industries rather than more amount of Sponsorship)
					<br><br>
					<b>Top Recipients</b>: Reflects the top recipients of Sponsorship (more number of industries rather than more amount of Sponsorship) </p>
					<br>
					 <b>Data</b>: Revision of data is done on an annual basis.</p>
					<br>
					<b>Input</b>: Every sign up is considered as an input and validity of inputs is carried out prior to approval. Once approved then only the input is fed in the map for projection.</p> -->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>

	</div>
</div>

<div class="modal" id="referafriend">
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
				<h4 class="modal-title" id="myModalLabel">Recommend a Colleague/ Peer</h4>
			</div>
			<div class="modal-body">
				<form method="post" name="referafriend_form" id="referafriend_form">

					<div class="form-group">
						<input type="email" class="form-control" placeholder="Email id 1" id="emailone">
					</div>
					<div class="form-group">
						<input type="email" class="form-control" placeholder="Email id 2" id="emailtwo">
					</div>
					<div class="form-group">
						<input type="email" class="form-control" placeholder="Email id 3" id="emailthree">
					</div>
					<div class="form-group">
						<input type="email" class="form-control" placeholder="Email id 4" id="emailfour">
					</div>
					<div class="form-group">
						<input type="email" class="form-control" placeholder="Email id 5" id="emailfive">
					</div>
					<div class="form-group">
						<input type="email" class="form-control" placeholder="Email id 6" id="emailsix">
					</div>
					<div class="form-group">
						<input type="email" class="form-control" placeholder="Email id 7" id="emailseven">
					</div>
					<div class="form-group">
						<input type="email" class="form-control" placeholder="Email id 8" id="emaileight">
					</div>
					<div class="form-group">
						<input type="email" class="form-control" placeholder="Email id 9" id="emailnine">
					</div>
					<div class="form-group">
						<input type="email" class="form-control" placeholder="Email id 10" id="emailten">
					</div>
					<div class="form-group">
						<button type="button" id="referafriend_button" class="btn btn-default"
								onclick="referFriendForm()">Submit
						</button>
						<a class="btn btn-danger" data-dismiss="modal">Cancel</a>
					</div>

				</form>
			</div>

		</div>
	</div>
</div>
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
<script src="{{ asset('js/custom-jquery.js') }}"></script>

<script src="{{ asset('js/jquery.bs.gdpr.cookies.js') }}"></script>
<script type="text/javascript">
  //comment by Akash
  // var settings = {
  //   message: 'We use tools, such as cookies, to enable essential services and functionality on our site and to collect data on how visitors interact with our site, products and services. By clicking Accept, you agree to our use of these tools for analytics and support.',
  //   messageMaxHeightPercent: 30,
  //   delay: 1000,
  //   allowAdvancedOptions: false,
  //   acceptButtonLabel: 'Got it',
  //   OnAccept : function() {

  //     var preferences = $.fn.bsgdprcookies.GetUserPreferences();
  //     console.log(preferences);

  //   }
  // }

  // $(document).ready(function() {
  //   $('body').bsgdprcookies(settings);
  //   $("#bs-gdpr-cookies-modal-more-link").hide();
  //   $('#cookiesBtn').on('click', function(){
  //     $('body').bsgdprcookies(settings, 'reinit');
  //   });
  // });

  function loginForm() {
    if ($("#loginEmail").val() == '' || $("#loginPassword").val() == '') {
      toastr.info('All Fileds are mandatory');

    } else {
      $.ajax({
        type: 'POST',
        url: base_url + '/login',
        headers: {'X-CSRF-Token': $('meta[name="_token"]').attr('content')},
        data: {email: $("#loginEmail").val(), password: $("#loginPassword").val()},
        success: function (data) {
          if (data.status == 0) {
            toastr.info('Please verify your email');
          } else {
            console.log(data);
            if (data.auth) {
              window.location.href = base_url + data.intended;
            } else {
              toastr.info('Invalid credential');
            }
          }


          //
        }
      })
    }
  }

  function referFriendForm() {
    $("#referafriend_button").attr('disabled', true);
    if ($("#emailone").val() == '' && $("#emailtwo").val() == '' && $("#emailthree").val() == '' && $("#emailfour").val() == '' && $("#emailfive").val() == '' && $("#emailsix").val() == '' && $("#emailseven").val() == '' && $("#emaileight").val() == '' && $("#emailnine").val() == '' && $("#emailten").val() == '') {
      toastr.info('Enter Atleast One Email Address');
    } else {
      //  var refer_data = $("#referafriend_form").serialize();
      $('#referafriend_button').prop('disabled', true);
      //$('#referafriend_button').attr("disabled", "disabled");
      $.ajax({
        type: 'POST',
        url: base_url + '/referAFriend',
        headers: {'X-CSRF-Token': $('meta[name="_token"]').attr('content')},
        data: {
          emailone: $("#emailone").val(),
          emailtwo: $("#emailtwo").val(),
          emailthree: $("#emailthree").val(),
          emailfour: $("#emailfour").val(),
          emailfive: $("#emailfive").val(),
          emailsix: $("#emailsix").val(),
          emailseven: $("#emailseven").val(),
          emaileight: $("#emaileight").val(),
          emailnine: $("#emailnine").val(),
          emailten: $("#emailten").val()
        },
        success: function (data) {
          $("#referafriend_button").attr('disabled', false);
          if (data.status) {
            toastr.success(data.msg);
            //window.location.href = base_url+data.intended;
          } else {
            toastr.info(data.msg);
          }
          $('#referafriend').modal('hide');
          $('#referafriend_button').prop('disabled', false);
          //
        }
      })
    }
  }
</script>
{{--<script type="text/javascript" src="https://cdn.ywxi.net/js/1.js" async></script>--}}
<!--Start of Tawk.to Script-->
<script type="text/javascript">
  var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
  (function () {
    var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
    s1.async = true;
    s1.src = 'https://embed.tawk.to/5bc6fd1661d0b770925179bb/default';
    s1.charset = 'UTF-8';
    s1.setAttribute('crossorigin', '*');
    s0.parentNode.insertBefore(s1, s0);
  })();
  var newwindow;

  function popuponclick() {
    my_window = window.open("",
      "mywindow", "status=1,width=350,height=150");
    my_window.document.write("<h3>We don't offer Terms & Conditions rather it's Trust we wish to build.</h3><p>Keeping your business interest intact is the top priority at Sponsorr and instead of you agree to us, let's rather build Trust. Therefore please tick the box if you decide never to use Sponsorr for illicit activities and we promise never to share, sell, publicise any information about your activities at Sponsorr unless you do it by yourself. </p>");
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
    privacy_window.document.write('<h2>Privacy Policy</h2><div style="padding-left: 10px;padding-right: 10px;"><p>Your privacy is important to us. It is Sponsorr\'s policy to respect your privacy regarding any information we may collect from you across our website, <a href="https://sponsorr.co">https://sponsorr.co</a>, and other sites we own and operate.</p><p>We only ask for personal information when we truly need it to provide a service to you. We collect it by fair and lawful means, with your knowledge and consent. We also let you know why we’re collecting it and how it will be used.</p><p>We only retain collected information for as long as necessary to provide you with your requested service. What data we store, we’ll protect within commercially acceptable means to prevent loss and theft, as well as unauthorised access, disclosure, copying, use or modification.</p><p>We don’t share any personally identifying information publicly or with third-parties, except when required to by law.</p><p>Our website may link to external sites that are not operated by us. Please be aware that we have no control over the content and practices of these sites, and cannot accept responsibility or liability for their respective privacy policies.</p><p>You are free to refuse our request for your personal information, with the understanding that we may be unable to provide you with some of your desired services.</p><p>Your continued use of our website will be regarded as acceptance of our practices around privacy and personal information. If you have any questions about how we handle user data and personal information, feel free to contact us.</p><p>This policy is effective as of 4 February 2019.</p></div>');
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
    $.ajax({
      type: 'GET',
      url: "{{ route('getUserCount') }}" + '/' + id,
      headers: {
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
      },
      success: function (data) {
        console.log(data);
        if (data.edit_count >= 4) {
          toastr.info("You can not edit profile more then 4 times.");
        } else {
          $('#edituserform').modal('show');
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        toastr.error("Something went wrong", "Error");
      },
    });
  }

  function openreferafriend() {
    $.ajax({
      type: 'GET',
      url: "{{ route('getReferFriends') }}",

      headers: {
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
      },
      success: function (data) {
        console.log(data);
        if (data.referCount >= 10) {
          toastr.info("You have recommended Sponsorr to 10 of your colleagues/ peers. If you wish to share it with more people then feel free to share the weblink with them.");
        } else {
          if ("0" in data.referList) {
            console.log(data.referList[0].email_address);
            $('#emailone').val(data.referList[0].email_address);
            $('#emailone').attr("disabled", "disabled");
          } else {
            $('#emailone').val('');
          }
          if ("1" in data.referList) {
            console.log(data.referList[1].email_address);
            $('#emailtwo').val(data.referList[1].email_address);
            $('#emailtwo').attr("disabled", "disabled");
          } else {
            $('#emailtwo').val('');
          }
          if ("2" in data.referList) {
            console.log(data.referList[2].email_address);
            $('#emailthree').val(data.referList[2].email_address);
            $('#emailthree').attr("disabled", "disabled");
          } else {
            $('#emailthree').val('');
          }
          if ("3" in data.referList) {
            console.log(data.referList[3].email_address);
            $('#emailfour').val(data.referList[3].email_address);
            $('#emailfour').attr("disabled", "disabled");
          } else {
            $('#emailfour').val('');
          }
          if ("4" in data.referList) {
            console.log(data.referList[4].email_address);
            $('#emailfive').val(data.referList[4].email_address);
            $('#emailfive').attr("disabled", "disabled");
          } else {
            $('#emailfive').val('');
          }
          if ("5" in data.referList) {
            console.log(data.referList[5].email_address);
            $('#emailsix').val(data.referList[5].email_address);
            $('#emailsix').attr("disabled", "disabled");
          } else {
            $('#emailsix').val('');
          }
          if ("6" in data.referList) {
            console.log(data.referList[6].email_address);
            $('#emailseven').val(data.referList[6].email_address);
            $('#emailseven').attr("disabled", "disabled");
          } else {
            $('#emailseven').val('');
          }
          if ("7" in data.referList) {
            console.log(data.referList[7].email_address);
            $('#emaileight').val(data.referList[7].email_address);
            $('#emaileight').attr("disabled", "disabled");
          } else {
            $('#emaileight').val('');
          }
          if ("8" in data.referList) {
            console.log(data.referList[8].email_address);
            $('#emailnine').val(data.referList[8].email_address);
            $('#emailnine').attr("disabled", "disabled");
          } else {
            $('#emailnine').val('');
          }
          if ("9" in data.referList) {
            console.log(data.referList[9].email_address);
            $('#emailten').val(data.referList[9].email_address);
            $('#emailten').attr("disabled", "disabled");
          } else {
            $('#emailten').val('');
          }
          $('#referafriend').modal('show');
          $.each(data.referList, function (index, value) {

          });
        }
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
</script>
<!--End of Tawk.to Script-->
</body>
</html>
