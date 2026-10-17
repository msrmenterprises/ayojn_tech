;@extends('layout')

@section('content')
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	<br><br><br><br>
	<section id="feed" class="speakers">
		<div class="container">
			<br>
			<br>
			<div class="row">
				<table id="table_id" class="display">
					<thead>
					<tr>
						<th>Opportunity ID</th>
						<th>Wish to Sponsor</th>
						<th>Specific <br>Opportunity</th>
						<th>Why this Opportunity</th>
						<th>Where to Sponsor</th>
						<th>Expected Budget <br/>(USD $)</th>
						<th>Industry</th>
						<th>Action</th>
						<th>Added Date</th>
					</tr>
					</thead>
					<tbody>
					@if(!empty($bidData->first()))
                        <?php
                        $specification = '';
                        $specificationArray = [];
                        ?>
						@if (!empty($bidData->bidSpecify->first()))
							@foreach ($bidData->bidSpecify as $bidS)
								@php
									$specificationArray[] = $bidS->specifyName->specify_name;
									$specification = implode(', ',$specificationArray);
								@endphp
							@endforeach
						@endif
						<tr>
							<td>{{ $bidData->id }}</td>
							@if($bidData->sponsor_for=='Online Events')
								<td> Online Activities</td>
							@else
								<td>{{ $bidData->sponsor_for }}</td>
							@endif
							<td>{{ $specification }}</td>
							<td>{{  $bidData->likeSponsorr }}</td>
							<td>{{  (!empty($bidData->country_name)) ? $bidData->country_name->country_name : "-" }}</td>
							<td>{{ $bidData->sponsor_budget }}</td>
							<td>{{ (!empty($bidData->industry)) ? $bidData->industry->name : '-'}}</td>
							<td>@if(!empty($bidData->bidResponse->first() && $bidData->bidResponse->first()->is_accepted == 2))
									@if(!empty($bidData->bider) && $bidData->bider->email != '')
										<a href="javascript:void(0)"
										   onclick="displayEmail('{{ $bidData->bider->email  }}')"><i
												class="fa fa-envelope"
												aria-hidden="true"></i></a>
									@endif
								@endif &nbsp;&nbsp;
								@if(!empty(Auth::user()) && Auth::user()->id != '')
									@if(!empty($bidData->bidResponse->first()))

										@if(!empty($bidData->bidResponse->first()->is_accepted == 0))
											<a href="javascript:void(0)"><span
													class="label label-warning ">Not Seen Yet</span></a>
										@elseif(!empty($bidData->bidResponse->first()->is_accepted == 1))

											<a href="javascript:void(0)"><span
													class="label label-primary">Read by Client</span></a>
										@else
											<a href="javascript:void(0)"><span
													class="label label-success">Open for Negotiation</span></a>
										@endif
									@else
										<a href="javascript:void(0)" onclick="OpenPopup('{{ $bidData->id }}')"><span
												class="label label-info">Respond</span></a>
									@endif

									@else
									<a href="javascript:void(0)" data-toggle="modal" data-target="#loginForm">
										<span
											class="label label-info">Respond</span>
									</a>
										@endif



							</td>
							<td>{{ Date("Y-m-d",strtotime($bidData->created_at)) }}</td>

						</tr>
					@endif
					</tbody>
				</table>
			</div>

		</div>
		<div class="modal" id="add-bid" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
						<h4 class="modal-title" id="myModalLabel" style="text-align: center">Response</h4>
						<h4 id="bid_id" style="text-align: center"></h4>
					</div>
					<div class="modal-body">
						<form class="addBidForm" id="addBidForm" method="post" onsubmit="return false"
							  name="addBidForm">

							<div class="form-group autocomplete">
								<label>
									<strong>Write Your Pitch (In 200 characters)</strong>
								</label>
								<textarea id="description" name="description" class="form-control"
										  placeholder="Let's talk about Oranges if the bid is about Oranges and NOT Apples. For e.g. tell a story about how you did it before and that could help the client for now. "
										  maxlength="200"></textarea>
								<input id="bid_input_id" name="bid_input_id" type="hidden">
							</div>
							<div class="form-group">
								<label>
									<strong>Web Link (Let’s build an event website for Free via our partner <a
											href="http://www.media101.tech" target="_blank">101 Media</a> )</strong>

								</label>
								<input id="portfolio" name="portfolio" class="form-control"
									   placeholder="Website, Portfolio, Social Media Profile" data-validation="url">
							</div>

							<button type="submit" id="addBid" class="btn btn-default">Submit</button>
						</form>

					</div>

				</div>
			</div>
		</div>
	</section>
	<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
	<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
	<style>.swal-button-container {
			display: none;
		}</style>
	<script>
      $("#addBidForm").validate({
        rules: {
          portfolio: {
            required: true,
            url: true
          }, description: {
            required: true,
            maxlength: 200
          }
        },
        submitHandler: function (form) {
          addBid()
        }
      });

      function displayEmail(email) {
        swal("You may communicate directly with the client via :", email);
      }

      function OpenPopup(bidId) {
        $("#bid_id").text("#" + bidId);
        $("#bid_input_id").val(bidId);
        $("#add-bid").modal('show');
      }

      function addBid() {


        var bid_id = $("#bid_input_id").val();
        var description = $("#description").val();
        var portfolio = $("#portfolio").val();
        $.ajax({
          type: 'POST',
          url: '{{ url('add-bid-response') }}',
          headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
          data: {
            bid_id: bid_id,
            description: description,
            portfolio_link: portfolio,
          },
          success: function (response) {
            //console.log(response.status);
            var data = response;
            if (data.status) {
              $("#add-bid").modal('hide');
              const el = document.createElement('div')
              el.innerHTML = "<a class='swal-button swal-button--confirm' href='{{ url('my-bids') }}'>View my bids</a>"
              // swal('', data.message, 'success');
              swal({
                title: "Great you made a Bid. Let's wait till the client Read or Open the Bid for Negotiation. We will notify you once they do.",
                content: el,
              })
              // toastr.success(response.message, "Success");
              //window.location.reload();
            } else {
              toastr.error(response.errors, "Error");
            }


            // link to page on clicking the notification

            //}

          },
          error: function (response) {
            toastr.error(response.responseJSON.msg, "Error");
            $(':input[type="submit"]').prop('disabled', false);
          },
        });

      }
	</script>
	<script src="{{ asset('js/jquery1.min.js') }}"></script>

	<script src="{{ asset('js/bootstrap.min.js') }}"></script>
	<script src="{{ asset('js/typed.min.js') }}"></script>
	<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
	<script>

      $(document).ready(function () {

        $('#table_id').DataTable();
      });
	</script>
@endsection
