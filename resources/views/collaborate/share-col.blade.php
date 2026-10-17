@extends('layout')

@section('content')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <br><br><br><br>
    <section id="feed" class="speakers">
        <div class="container">
            <br>
            <br>
            <div class="row">
                <table id="attend" class="display table">
                    <thead>
                    <tr>
                        <th>Offered by</th>
                        <th>Collaboration For</th>
                        <th>Remote Country
                            <hr style="margin-bottom: 0px;margin-top: 0px"></hr>
                            City
                        </th>
                        <th>Geo focus</th>
                        <th>Industry</th>
                       <!-- <th>Local Collaborator</th>
                        <th>Collaborate with</th> -->
                        <th>Budget (USD $)</th>
                        <th>Objective</th>
                        <th>Bid</th>
                        <th>Share</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(!empty($othersEvents->first()))
                        @foreach($othersEvents as $key1=>$oevent)
                            <tr>
                                <td>@if(!empty($oevent->organizer)) @if(!empty($oevent->organizer->entity )){{ $oevent->organizer->entity  }} @elseif($oevent->organizer->company_name) {{ $oevent->organizer->company_name }} @endif @else
                                        - @endif</td>
                                <td>{{ $oevent->collaborate_for }} <br/> {{ $oevent->sub }}</td>

                                <td>{{  (!empty($oevent->remote_country) && !empty($oevent->remote_country_name)) ? $oevent->remote_country_name->country_name : '-' }}
                                    <hr style="margin-top: 0;margin-bottom: 0">{{ (!empty($oevent->remote_city) && !empty($oevent->remote_city_name)) ? $oevent->remote_city_name->name : '-' }}
                                </td>
                                <td>{{ (!empty($oevent->country_name)) ? $oevent->country_name->country_name : '-' }}</td>
                                <td>{{ (!empty($oevent->industry)) ? $oevent->industry->name : '-' }}</td>
                                <!--<td>@if($oevent->with_local_focus == 1) Yes @else No @endif</td>
                                <td>{{ $oevent->collaborate_with }}</td> -->
                                <td>{{ $oevent->budget }}</td>
                                <td>{{ $oevent->objective }}</td>
                                <td>
                                    @if(!empty(Auth::user()) && Auth::user()->id != '')
                                        @if(!empty($oevent->checkAttendes))
                                            @if($oevent->checkAttendes->status == 1)
                                                Accepted
                                            @else
                                                Pending
                                            @endif
                                        @else
                                            <a href="javascript:void(0)"
                                               onclick="OpenPopup({{ $oevent->id }})"><i
                                                    class="fa fa-paper-plane"></i> </a>
                                        @endif
                                    @else
                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#loginForm">
                                            <i
                                                class="fa fa-paper-plane"></i>
                                        </a>
                                    @endif

                                </td>
                                <td><span id="event_share_{{ $oevent->id }}" class="share-course-filed"
                                          style="display: none"> {{ url('share-col')."/" . $oevent->share_id }}</span>
                                    <a
                                        href="javascript:void(0)"
                                        class="btn btn-primary read-more-btn  share-opp-btn"
                                        onclick="copyToClipboard('#event_share_{{ $oevent->id }}')">Copy
                                        Web link</a></td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
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
                                    <input id="col_id" name="col_id" type="hidden">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <strong>Web Link <!--(Let’s build an event website for Free via our partner <a
                                            href="http://www.media101.tech" target="_blank">101 Media</a>)--> </strong>

                                    </label>
                                    <input id="portfolio" name="portfolio" class="form-control"
                                           placeholder="Website, Portfolio, Social Media Profile">
                                </div>

                                <button type="submit" id="addBid" class="btn btn-default">Submit</button>
                            </form>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>


    <script src="{{ asset('js/jquery1.min.js') }}"></script>

    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/typed.min.js') }}"></script>
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script>
        function OpenPopup(bidId) {

            $("#col_id").val(bidId);
            $("#add-bid").modal('show');
        }


        $(document).ready(function () {

            $('#attend').DataTable();
        });

        function sendRequest(eventId) {

            swal({
                title: 'Are you sure?',
                text: 'Are you sure that you want to attend event?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Change it!'
            }).then(
                function (isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            url: "{{ url('attend-event'). '/' }}" + eventId,
                            type: 'post',
                            data: {
                                "_token": "{{ csrf_token() }}"
                            },
                            success: function () {
                                // delete the row from the table
                                swal("Requested", "Request has been deleted successfully.", "success");

                            },
                            error: function () {
                                // Show an alert with the result

                                swal("status not changed", "error");
                            }
                        });
                    }
                });
        }

        function CancelRequest(attendeId) {

            swal({
                title: 'Are you sure?',
                text: 'Are you sure that you want to cancel this event?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Change it!'
            }).then(
                function (isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            url: "{{ url('cancel-event'). '/' }}" + attendeId,
                            type: 'post',
                            data: {
                                "_token": "{{ csrf_token() }}"
                            },
                            success: function () {
                                // delete the row from the table
                                swal("Requested", "Request has been sent successfully.", "success");
                                setTimeout(function () {
                                    location.reload();
                                }, 2000);
                            },
                            error: function () {
                                // Show an alert with the result

                                swal("status not changed", "error");
                            }
                        });
                    }
                });
        }
    </script>
@endsection
