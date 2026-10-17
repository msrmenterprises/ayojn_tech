@extends('layout')

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
                        <th>Event Organizer</th>
                        <th>Event Title</th>
                        <th>Event start Date</th>
                        <th>Event finish Date</th>
                        <th>Geo focus</th>
                        <th>Industry</th>
                        <th>Timezone</th>
                        <th>Event Type</th>
                        <th>Event Location</th>
                        <th>Event Fee <br/> Payment Link</th>
                        <th>Want to Attend</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(!empty($othersEvents->first()))
                        @foreach($othersEvents as $key1=>$oevent)
                            <tr>
                                <td>@if(!empty($oevent->organizer)) {{ $oevent->organizer->entity }} @else
                                        - @endif</td>
                                <td>{{ $oevent->event_title }}</td>
                                <td>{{ $oevent->event_date }}</td>
                                <td>{{ $oevent->event_finish }}</td>
                                <td>{{ (!empty($oevent->country_name)) ? $oevent->country_name->country_name : '-' }}</td>
                                <td>{{ (!empty($oevent->industry)) ? $oevent->industry->name : '-' }}</td>
                                <td>{{ $oevent->timezone }}</td>
                                <td>{{ $oevent->event_type }}</td>
                                <td>{{ $oevent->event_location }}</td>
                                <td>{{ $oevent->event_free_paid }} <br/> {{ $oevent->event_fee }} <br/>

                                    @if(!empty($oevent->payment_link)) <a
                                            href="{{ $oevent->payment_link }}" target="_blank"
                                            class="btn btn-primary"
                                    >Payment Link</a> @else - @endif

                                </td>

                                <td>
                                    @php
                                        $diff =0;
                                            $currentTime = \Carbon\Carbon::now();
                                                $startDate =  \Carbon\Carbon::parse($oevent->event_date);
                                                $diff = $currentTime->diffInHours($startDate);

                                    @endphp
                                    @if(!empty(Auth::user()) && Auth::user()->id != '')
                                        @if(!empty($oevent->checkAttendes))
                                            @if($diff <= 48)
                                                <a target="_blank" href="{{ $oevent->link  }}"
                                                   class="btn btn-primary"
                                                >Attend Event</a>
                                            @else
                                                @if($oevent->checkAttendes->status ==0)
                                                    Pending
                                                @elseif($oevent->checkAttendes->status ==1)
                                                    Approved
                                                @else
                                                    Cancel
                                                @endif

                                                @if($oevent->checkAttendes->status ==0 || $oevent->checkAttendes->status ==1)
                                                    <a href="javascript:void(0)"
                                                       onclick="CancelRequest({{ $oevent->checkAttendes->id }})"
                                                       title="cancel"><i
                                                            class="fa-2x fa fa-times-circle-o"
                                                            style="color: red"></i> </a>
                                                @endif
                                            @endif
                                        @else
                                            <a href="javascript:void(0)"
                                               onclick="sendRequest({{ $oevent->id }})"><i
                                                    class="fa fa-paper-plane"></i> </a>
                                        @endif
                                    @else
                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#loginForm">
                                            <i
                                                class="fa fa-paper-plane"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
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

        $(document).ready(function () {

            $('#table_id').DataTable();
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
