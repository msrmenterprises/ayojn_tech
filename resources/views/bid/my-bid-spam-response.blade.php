@extends('layout')

@section('content')
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <?php
    use App\Country;use App\Industry;use App\SponsorrSpecify;use App\SponsorrSpecifyList;
    $countries = Country::all();
    $industries = Industry::all();
    $sponsorrlist = SponsorrSpecifyList::where('sponsorr_type', Auth::user()->sponsor_for)->get();
    $userwisesponsor = SponsorrSpecify::where('user_id', Auth::user()->id)->get();
    $userwisesponsorarray = [];
    if (count($userwisesponsor) > 0) {
        foreach ($userwisesponsor as $usersponsorr) {
            $userwisesponsorarray[] = $usersponsorr->specify_name;
        }
    }?>
	<!-- banner section start -->


	<br><br><br><br>
	<section id="feed" class="speakers">
		<div class="container">
			<a href="{{url('bid')}}" class="btn btn-primary">Back</a>
			<h4 style="text-align: center">Irrevelant Response for </h4>
			<h4 style="text-align: center"> Opportunity ID #{{ @$id }}</h4>
			<br>
			<div class="row">
				<table id="table_id" class="display">
					<thead>
					<tr>
						<th>Id</th>
						<th>Email</th>
						<th>Description</th>
						<th>Link</th>
						<th>Date</th>
						<th>Reason</th>
						<th>Irrevelant Response</th>
					</tr>
					</thead>
					<tbody>

					@if(!empty($spamBidResponse->first()))
						@foreach($spamBidResponse as $response)

							<tr>
								<td>{{ $response->id }}</td>
								@if($response->is_accepted ==2)
									<td>{{ (!empty($response->userDetail)) ? $response->userDetail->email : ''  }}</td>
								@else
									<td>XXXXXXgmail.com</td>
								@endif
								<td>{{ $response->description }}</td>
								<td>{{ $response->portfolio_link }}</td>
								<td>{{ date('Y-m-d',strtotime($response->created_at)) }}</td>
								<td>
									{{ $response->spam_reason }}
									&nbsp;
								</td>
								<td><a href="{{ url('restore-spam') . '/' . $response->id }}"
									   title="Restore">Restore</a></td>


							</tr>
						@endforeach
					@endif
					</tbody>
				</table>
			</div>
			<div id="append-form"></div>
		</div>
		<div id="html"></div>
	</section>

	<script>

      $(".read-more").click(function () {
        response_id = $(this).data("value");
        $.ajax({
          type: 'GET',
          headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
          url: '{{ url('get-bid-response') }}/' + response_id,

          cache: false,
          processData: false,
          success: function (response) {
            $("#html").html(response.html);
            $("#read-more-response").modal('show');
          },
          error: function (response) {
            toastr.error(response.responseJSON.msg, "Error");
            $(':input[type="submit"]').prop('disabled', false);
          },
        });
      });

      $("#addBid").click(function () {
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
              toastr.success(response.message, "Success");
              window.location.reload();
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

      });
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
