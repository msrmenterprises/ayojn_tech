@extends('layout')

@section('content')
	<section id="feed" class="speakers">
		<div class="container">
			<div class="row">
				<div class="col-md-2"></div>
				<div class="col-md-8" style="padding-top: 10px;">
					<h4>Let's exchange some views on how you handle sponsorship. The information you would share here
						will be used in a discreet manner for indexing purpose. Once you submit it you will be able to
						see the Global Sponsorship Index maintained by the users at Ayojn. </h4>
				</div>
			</div>
			<div class="row">
				<div class="col-md-3"></div>
				<div class="col-md-6">
					<form class="offer-form" id="offer-form" method="post" onsubmit="return false"
						  name="offer-form">

						<div class="form-group">
							<label>
								<strong>What do you want to get Sponsored?</strong>
							</label>
						</div>

						<div class="form-group">
							<select class="form-control" name="offer_sponsorr_type" id="offer_sponsorr_type"
									onchange="displaySpecifyEditForm(1)">
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
								<select class="form-control" name="offer_sponsorr_specify[]"
										id="offer_sponsorr_specify" multiple>
									@if(count($sponsorrlist) > 0)
										@foreach($sponsorrlist as $sponsor_list)
											<option
												value="{{ $sponsor_list->id }}" {{ (in_array($sponsor_list->id,$userwisesponsorarray))?'selected':'' }}>{{ $sponsor_list->specify_name }}</option>
										@endforeach
									@endif
								</select>
							</div>
						</div>
                        <?php if (! empty(Auth::user()->likeSponsorr)) {
                            $likeArray = explode(',', Auth::user()->likeSponsorr);
                        } else {
                            $likeArray = [];
                        }?>


						<div class="form-group">
							<label>
								<strong>Where would you like this to be sponsored</strong>
							</label>
						</div>
						<div class="form-group">
							<select class="form-control" name="offer_sponsorr_country" id="offer_sponsorr_country">
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
								<strong>What's your ideal budget(USD) </strong>
							</label>
						</div>

						<div class="form-group">
							<select class="form-control" name="offer_sponsorr_budget" id="offer_sponsorr_budget">
                                <option value="">Select Any</option>
                                <option value="200-500" {{ (Auth::user()->sponsor_budget == '200-500')?'selected':'' }}>200-500</option>
                                <option value="500-2000" {{ (Auth::user()->sponsor_budget == '500-2000')?'selected':'' }}>500-2000</option>
                                <option value="2000-5000" {{ (Auth::user()->sponsor_budget == '2000-5000')?'selected':'' }}>2000-5000</option>
                                <option value="5000-10000" {{ (Auth::user()->sponsor_budget == '5000-10000')?'selected':'' }}>5000-10000</option>
                                <option value="10000-20000" {{ (Auth::user()->sponsor_budget == '10000-20000')?'selected':'' }}>10000-20000</option>
                                <option value="20000-30000" {{ (Auth::user()->sponsor_budget == '20000-30000')?'selected':'' }}>20000-30000</option>
                                <option value="30000-50000" {{ (Auth::user()->sponsor_budget == '30000-50000')?'selected':'' }}>30000-50000</option>
                                <option value="50000-100000" {{ (Auth::user()->sponsor_budget == '50000-100000')?'selected':'' }}>50000-100000</option>
                                <option value="Above 100000" {{ (Auth::user()->sponsor_budget == 'Above 100000')?'selected':'' }}>Above 100000</option>
                                <option value="In kind" {{ (Auth::user()->sponsor_budget == 'In kind')?'selected':'' }}>In kind</option>

							</select>
						</div>
						<button type="submit" id="editformsubmit" onclick="OfferForm()" class="btn btn-default">
							Submit
						</button>
					</form>
				</div>
				<div class="col-md-3"></div>
			</div>
		</div>
	</section>
	<script>
      function OfferForm() {
        var offer_sponsorr_type = $("#offer_sponsorr_type").val();
        var offer_sponsorr_specify = $("#offer_sponsorr_specify").val();
        var offer_sponsorr_budget = $("#offer_sponsorr_budget").val();
        var offer_sponsorr_country = $("#offer_sponsorr_country").val();
        $.ajax({
          type: 'POST',
          url: '{{ route('update-offer-data') }}',
          headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
          data: {
            offer_sponsorr_type: offer_sponsorr_type,
            offer_sponsorr_specify: offer_sponsorr_specify,
            offer_sponsorr_budget: offer_sponsorr_budget,
            offer_sponsorr_country: offer_sponsorr_country,
          },
          success: function (response) {
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

      function displaySpecifyEditForm(flagSpecify) {
        var event_type = $("#offer_sponsorr_type").val();
        $('#offer_sponsorr_specify').html('');
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
                $('#offer_sponsorr_specify').append('<option value="' + v.id + '">' + v.specify_name + '</option>');

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
@endsection
