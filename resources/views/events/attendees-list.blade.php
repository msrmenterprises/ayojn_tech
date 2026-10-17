@extends('layout')

@section('content')
    <style>
        .error {
            color: red;
        }

        #exTab2 h3 {
        / / color: white;
        / / background-color: #428bca;
            padding: 5px 15px;
        }
    </style>

    <section id="feed" class="speakers">
        <div class="container">
            <div class="row">
                <!-- banner section start -->


                <div class="row" style="margin-bottom: 25px; text-align: center">
                    <h3>Attendees List for event</h3>
                </div>
                <div class="row" style="margin-bottom: 15px;">
                    <a href="{{ url('export-attendees') ."/". $eventId }}" class="btn btn-success">Export Attendees</a>
                    <br>
                </div>
                <div class="row">
                    <table id="table_id" class="display">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Attendee email</th>
                            <th>Attendee Phone no</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($attendeedList->first()))
                            @foreach($attendeedList as $event)
                                <tr>
                                    <td>{{ $event->id }}</td>
                                    <td>{{ ($event->user) ? $event->user->email : '-' }}</td>
                                    <td>{{ ($event->user) ? $event->user->phone_no : '-' }}</td>
                                    <td> @if($event->status == 1)
                                            Approved
                                        @elseif($event->status == 2)
                                            Reject
                                        @else Pending
                                            <a href="javascript:void(0)" onclick="changeStatus({{ $event->id }})"><i
                                                    class="fa fa-pencil-square-o"></i> </a>
                                        @endif


                                    </td>

                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>

    <script src="{{ asset('js/jquery1.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/typed.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.min.js"></script>
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#table_id').DataTable();
        });

        function changeStatus(attendeId) {

            swal({
                title: 'Are you sure?',
                text: 'Are you sure that you want to change status of attandee?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Change it!'
            }).then(
                function (isConfirm) {
                    console.log(isConfirm)
                    if (isConfirm.dismiss != 'cancel' && isConfirm.dismiss != 'overlay') {
                        $.ajax({
                            url: "{{ url('change-attendee-status'). '/' }}" + attendeId,
                            type: 'post',
                            data: {
                                "_token": "{{ csrf_token() }}"
                            },
                            success: function () {
                                // delete the row from the table
                                swal("Status Changed", "Request successfully sent.", "success");
                                setTimeout(function () {
                                    location.reload()
                                }, 1500);
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

