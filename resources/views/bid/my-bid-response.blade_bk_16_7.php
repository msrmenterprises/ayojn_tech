@extends('layout')

@section('content')
    <?php
    $f = Request::get('f');
    $cn = Request::get('c');
    $b = Request::get('b');
    $i = Request::get('i');

    ?>
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
            <a href="{{url('spam-bids') . '/'.$bidId}}" class="btn btn-primary">Irrevelant Bids</a>
            <h4 style="text-align: center"> Response for </h4>
            <h4 style="text-align: center"> Opportunity ID #{{ @$bidDetail->id }}</h4>
            <br>
            <div class="row">
                <form name="search">
                    <div class="col-md-3">
                        <select class="form-control" name="f">
                            <option value="">Select Indentity</option>
                            <option value="Freelancers" <?php if ($f == "Freelancers") {
                                echo "Selected";
                            }?>>Freelancers
                            </option>
                            <option value="Agencies" <?php if ($f == "Agencies") {
                                echo "Selected";
                            }?>>Agencies
                            </option>
                            <option value="Networks" <?php if ($f == "Networks") {
                                echo "Selected";
                            }?>>Networks
                            </option>
                            <option value="Communities" <?php if ($f == "Communities") {
                                echo "Selected";
                            }?>>Communities
                            </option>

                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" name="c" id="c-filter" onchange="getCity(5)">
                            <option value="">Select Country</option>
                            @if(!empty($countries))
                                @foreach($countries as $c)
                                    <option value="<?= $c->country_code?>" <?php if ($cn == $c->country_code) {
                                        echo "Selected";
                                    }?>><?= $c->country_name?></option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" name="city" id="city-filter">
                            <option value="">Select city</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" name="i" id="i">
                            <option value="">Select Any</option>
                            <option value="1">Read More</option>
                            <option value="2">Open For Negotiation</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary"
                        >Search
                        </button> &nbsp;<a href="{{ url('bid') }}" title="refresh" class="btn btn-primary"
                        ><i class="fa fa-refresh" aria-hidden="true"></i></a></div>
                </form>
            </div>
            <br>
            <div class="row">
                <table id="table_id" class="display">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Email</th>
                        <!--<th>Description</th>-->
                        <th>Link</th>
                        <th>Date</th>
                        <th>Action</th>
                        <th>Irrevelant</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(!empty($bidResponses->first()))
                        @foreach($bidResponses as $response)

                            <tr>
                                <td>{{ $response->id }}</td>
                                @if($response->is_accepted ==2)
                                    <td>{{ (!empty($response->userDetail)) ? $response->userDetail->email : ''  }}</td>
                                @else
                                    <td>XXXXXXgmail.com</td>
                            @endif
                            <!--<td>{{ $response->description }}</td> -->
                                <td>{{ $response->portfolio_link }}</td>
                                <td>{{ date('Y-m-d',strtotime($response->created_at)) }}</td>
                                <td>
                                    <a href="javascript:void(0)"
                                       title="Read More" onclick="readMore({{ $response->id }})" class="read-more"
                                       data-value="{{ $response->id }}">Read More&nbsp;<i class="fa fa-eye"
                                                                                          aria-hidden="true"></i></a>
                                    @if($response->is_accepted != 2)
                                        |
                                        <a href="javascript:void(0)" onclick="acceptBid({{ $response->id }})"
                                           title="Open for Negotiation">Open for Negotiation&nbsp;<i class="fa fa-check"
                                                                                                     aria-hidden="true"></i></a>
                                    @endif
                                    &nbsp;
                                </td>
                                <td><a href="javascript:void(0)" onclick="openReasonForm({{ $response->id }})"
                                       title="Mark Irrevelant">Mark Irrevelant</a></td>


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
        function openReasonForm(responseId) {
            $.ajax({
                url: "{{ url('/') }}" + '/add-reason/' + responseId,
                type: "get",
                async: false,
                headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
                success: function (data) {
                    if (data.html != '') {
                        $("#append-form").append(data.html);
                        $("#add-reason").modal('show');
                    }
                },
                error: function (data) {
                },
            })
        }

        function acceptBid(responseId) {
            $.ajax({
                type: 'GET',
                headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
                url: '{{ url('bid-accept') }}/' + responseId,

                cache: false,
                processData: false,
                success: function (response) {
                    swal("Bid opened for negotiation", {
                        icon: "success",
                    });
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                },
                error: function (response) {
                    toastr.error(response.responseJSON.msg, "Error");
                    $(':input[type="submit"]').prop('disabled', false);
                },
            });
            {{--swal({--}}
            {{--  title: "Are you sure?",--}}
            {{--  text: "You want to accept this bid?",--}}
            {{--  icon: "warning",--}}
            {{--  buttons: true,--}}
            {{--  dangerMode: true,--}}
            {{--})--}}
            {{--  .then((willDelete) => {--}}
            {{--    if (willDelete) {--}}

            {{--      $.ajax({--}}
            {{--        type: 'GET',--}}
            {{--        headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},--}}
            {{--        url: '{{ url('bid-accept') }}/' + responseId,--}}

            {{--        cache: false,--}}
            {{--        processData: false,--}}
            {{--        success: function (response) {--}}
            {{--          swal(response.message, {--}}
            {{--            icon: "success",--}}
            {{--          });--}}
            {{--        },--}}
            {{--        error: function (response) {--}}
            {{--          toastr.error(response.responseJSON.msg, "Error");--}}
            {{--          $(':input[type="submit"]').prop('disabled', false);--}}
            {{--        },--}}
            {{--      });--}}
            {{--    } else {--}}

            {{--    }--}}
            {{--  });--}}
        }

        function readMore(response_id) {
            //  response_id = $(this).data("value");
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
        }

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
