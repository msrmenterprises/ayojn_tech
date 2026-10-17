@extends('layout')
@php  $buttons = $db_buttons['recieve_my_bid'];  @endphp
@section('content')
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <?php
    use App\Country;
    $countries = Country::all();
    use App\Industry;
    $industries = Industry::all();
    use App\SponsorrSpecify;
    use App\SponsorrSpecifyList;
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
			<a href="{{url('bid')}}" class="btn btn-primary" data-tooltip="true" data-placement="top"  title="{!!$buttons[0]['hover']!!}"
> {!!$buttons[0]['icon']!!} {!!$buttons[0]['name']!!} </a>
			<h4 style="text-align: center"> My Bids</h4>

			<br>
			<div class="row">
				<table id="table_id" class="table table-striped display">
					<thead>
					<tr>
						<th width="10%">Response Id</th>
						<th width="10%">Bid Id</th>
						<th width="10%">Sponsor For</th>
						<th width="10%">Budget</th>
						<!--<th width="30%">Description</th> -->
						<th width="10%">Link</th>
						<th width="10%">Date</th>
						<th width="10%">Action</th>
					</tr>
					</thead>
					<tbody>

					@if(!empty($bids->first()))
						@foreach($bids as $response)

							<tr>
								<td>{{ $response->id }}</td>
								<td>{{ $response->bid->id }}</td>
								<td>{{ $response->bid->sponsor_for }}</td>
								<td>{{ $response->bid->sponsor_budget }}</td>
								<!--<td>{{ $response->description }}</td> -->
								<td>{{ $response->portfolio_link }}</td>
								<td>{{ date('Y-m-d',strtotime($response->created_at)) }}</td>
								<td>
									<a href="javascript:void(0)" title="Read More" class="read-more" data-value="{{ $response->id }}" data-tooltip="true" data-placement="top"  title="{!!$buttons[1]['hover']!!}"
> {!!$buttons[1]['icon']!!} {!!$buttons[1]['name']!!} </a>
								</td>


							</tr>

						@endforeach

            @else
            <tr>
              <td colspan="7" align="center">
                <b>
                  No Data found
                </b>
              </td>
            </tr>
					@endif
					</tbody>
				</table>
			</div>

		</div>
		<div id="html"></div>
	</section>

  <script src="{{ asset('js/jquery1.min.js') }}"></script>

	<script src="{{ asset('js/bootstrap.min.js') }}"></script>
	<script src="{{ asset('js/typed.min.js') }}"></script>
	<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
	<script>

      // $(document).ready(function () {
      //   $('#table_id').DataTable();
      // });
	</script>

  <script type="text/javascript">
   
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
    </script>

	<script>
      {{--function acceptBid(responseId){--}}
      {{--  $.ajax({--}}
      {{--    type: 'GET',--}}
      {{--    headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},--}}
      {{--    url: '{{ url('bid-accept') }}/' + responseId,--}}

      {{--    cache: false,--}}
      {{--    processData: false,--}}
      {{--    success: function (response) {--}}
      {{--      swal("Bid opened for negotiation", {--}}
      {{--        icon: "success",--}}
      {{--      });--}}
      {{--      setTimeout(function () {--}}
      {{--        location.reload();--}}
      {{--      },1000);--}}
      {{--    },--}}
      {{--    error: function (response) {--}}
      {{--      toastr.error(response.responseJSON.msg, "Error");--}}
      {{--      $(':input[type="submit"]').prop('disabled', false);--}}
      {{--    },--}}
      {{--  });--}}

      {{--}--}}


      {{--$("#addBid").click(function () {--}}
      {{--  var bid_id = $("#bid_input_id").val();--}}
      {{--  var description = $("#description").val();--}}
      {{--  var portfolio = $("#portfolio").val();--}}
      {{--  $.ajax({--}}
      {{--    type: 'POST',--}}
      {{--    url: '{{ url('add-bid-response') }}',--}}
      {{--    headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},--}}
      {{--    data: {--}}
      {{--      bid_id: bid_id,--}}
      {{--      description: description,--}}
      {{--      portfolio_link: portfolio,--}}
      {{--    },--}}
      {{--    success: function (response) {--}}
      {{--      //console.log(response.status);--}}
      {{--      var data = response;--}}
      {{--      if (data.status) {--}}
      {{--        toastr.success(response.message, "Success");--}}
      {{--        window.location.reload();--}}
      {{--      } else {--}}
      {{--        toastr.error(response.errors, "Error");--}}
      {{--      }--}}


      {{--      // link to page on clicking the notification--}}

      {{--      //}--}}

      {{--    },--}}
      {{--    error: function (response) {--}}
      {{--      toastr.error(response.responseJSON.msg, "Error");--}}
      {{--      $(':input[type="submit"]').prop('disabled', false);--}}
      {{--    },--}}
      {{--  });--}}

      {{--});--}}
	</script>
	
@endsection
