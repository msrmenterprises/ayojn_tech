@extends('layout')

@section('content')


    <link rel="stylesheet" type="text/css" href="{{ asset('css/datepicker.css') }}">
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
                <div id="exTab2" class="container">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#1" data-toggle="tab">Host Event</a>
                        </li>
                        <li><a href="#2" data-toggle="tab">Attend Event</a>
                        </li>

                    </ul>

                    <div class="tab-content ">
                        <div class="tab-pane active" id="1">
                            @if(Auth::user()->userstatus == 1)
                                <div class="row" style="margin-bottom: 25px;margin-top:25px;">

                                    <a href="javascript:void(0)" data-toggle="modal" class="btn btn-primary float-left"
                                       data-target="#add-event"
                                    >Host an Event</a>

                                </div>

                                <div class="row">
                                    <table id="table_id" class="display">
                                        <thead>
                                        <tr>
                                            <th>Event Title</th>
                                            <th>Event start Date</th>
                                            <th>Event finish Date</th>
                                            <th>Geo focus</th>
                                            <th>Industry</th>
                                            <th>Timezone</th>
                                            <th>Event Type</th>
                                            <th>Event Location</th>
                                            <th>Event Fee<br/>Payment Link</th>
                                            <th>Status</th>
                                            <th>Attendees List</th>
                                            <th>Share</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(!empty($myEvents->first()))
                                            @foreach($myEvents as $key=>$event)
                                                <tr>
                                                    <td>{{ $event->event_title }}</td>
                                                    <td>{{ $event->event_date }}</td>
                                                    <td>{{ $event->event_finish }}</td>
                                                    <td>{{ (!empty($event->country_name)) ? $event->country_name->country_name : '-' }}</td>
                                                    <td>{{ (!empty($event->industry)) ? $event->industry->name : '-' }}</td>
                                                    <td>{{ $event->timezone }}</td>
                                                    <td>{{ $event->event_type }}</td>
                                                    <td>{{ $event->event_location }}</td>
                                                    <td>{{ $event->event_free_paid }}<br/>{{ $event->event_fee }}
                                                        <br/> @if(!empty($event->payment_link)) <a
                                                            href="{{ $event->payment_link }}" target="_blank"
                                                            class="btn btn-primary"
                                                        >Payment Link</a> @else - @endif</td>


                                                    <td> @if($event->status == 1)
                                                            Approved
                                                        @elseif($event->status == 2)
                                                            Reject
                                                        @else Pending @endif
                                                    </td>

                                                    <td>
                                                        <a href="{{ url('attendees').'/'.base64_encode($event->id) }}"><i
                                                                class="fa fa-list"></i> <span
                                                                class="badge badge-light">{{ $event->attendes->count() }}</span>
                                                        </a>
                                                    </td>
                                                    <td><span id="event_{{ $event->id }}" class="share-course-filed"
                                                              style="display: none"> {{ url('share-event')."/" . $event->share_id }}</span>
                                                        <a
                                                            href="javascript:void(0)"
                                                            class="btn btn-primary read-more-btn  share-opp-btn"
                                                            onclick="copyToClipboard('#event_{{ $event->id }}')">Copy
                                                            Web
                                                            link</a></td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                        <div class="tab-pane" id="2">
                            <div class="row" style="margin-bottom: 25px;margin-top:25px;">

                            </div>

                            <div class="row">
                                <table id="attend" class="display">
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
                                        <th>Share</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(!empty($othersEvents->first()))
                                        @foreach($othersEvents as $key1=>$oevent)
                                            <tr>
                                                <td>@if(!empty($oevent->organizer)) @if(!empty($oevent->organizer->entity )){{ $oevent->organizer->entity  }} @elseif($oevent->organizer->company_name) {{ $oevent->organizer->company_name }} @endif @else
                                                        - @endif</td>
                                                <td>{{ $oevent->event_title }}</td>
                                                <td>{{ $oevent->event_date }}</td>
                                                <td>{{ $oevent->event_finish }}</td>
                                                <td>{{ (!empty($oevent->country_name)) ? $oevent->country_name->country_name : '-' }}</td>
                                                <td>{{ (!empty($oevent->industry)) ? $oevent->industry->name : '-' }}</td>
                                                <td>{{ $oevent->timezone }}</td>
                                                <td>{{ $oevent->event_type }}</td>
                                                <td>{{ $oevent->event_location }}</td>
                                                <td>{{ $oevent->event_free_paid }} <br/> {{ $oevent->event_fee }}
                                                    <br/> @if(!empty($oevent->payment_link)) <a
                                                        href="{{ $oevent->payment_link }}" target="_blank"
                                                        class="btn btn-primary"
                                                    >Payment Link</a> @else - @endif </td>

                                            <!--<td>{{ date('Y-m-d',strtotime($oevent->created_at)) }}</td> -->
                                                <td>
                                                    @php
                                                        $diff =0;
                                                            $currentTime = \Carbon\Carbon::now();
                                                                $startDate =  \Carbon\Carbon::parse($oevent->event_date);
                                                                $diff = $currentTime->diffInHours($startDate);

                                                    @endphp
                                                    @if(!empty($oevent->checkAttendes))

                                                        @if($diff <= 48)
                                                            @if($oevent->checkAttendes->status == 1)
                                                                <a target="_blank" href="{{ $oevent->link  }}"
                                                                   class="btn btn-primary"
                                                                >Access</a>
                                                            @else
                                                                @if($oevent->checkAttendes->status == 0)
                                                                    Pending
                                                                @elseif($oevent->checkAttendes->status == 1)
                                                                    Approved
                                                                @else
                                                                    Cancel
                                                                @endif
                                                            @endif
                                                        @else

                                                            @if($oevent->checkAttendes->status == 0)
                                                                Pending
                                                            @elseif($oevent->checkAttendes->status == 1)
                                                                Approved
                                                            @else
                                                                Cancel
                                                            @endif

                                                            @if($oevent->checkAttendes->status ==0 || $oevent->checkAttendes->status ==1)
                                                            <!--<a href="javascript:void(0)"
onclick="CancelRequest({{ $oevent->checkAttendes->id }})"
title="cancel"><i
class="fa-2x fa fa-times-circle-o"
style="color: red"></i> </a> -->
                                                            @endif
                                                        @endif
                                                    @else
                                                        <a href="javascript:void(0)"
                                                           onclick="sendRequest({{ $oevent->id }})"><i
                                                                class="fa fa-paper-plane"></i> </a>
                                                    @endif
                                                </td>
                                                <td><span id="event_share_{{ $oevent->id }}" class="share-course-filed"
                                                          style="display: none"> {{ url('share-event')."/" . $oevent->share_id }}</span>
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
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal" id="add-event" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
                            <h4 class="modal-title" id="myModalLabel">Host Event</h4>
                            <h5 class="modal-title" id="myModalLabel">
                            </h5>
                        </div>
                        <div class="modal-body">
                            <form class="addEventForm" id="addEventForm" method="post" onsubmit="return false"
                                  name="addEventForm">
                                <div class="form-group">
                                    <!-- <h3>
                                        Let's gather the relevant details so that we can match your information requirements
                                    </h3> -->
                                </div>
                                <div class="form-group autocomplete">
                                    <label>
                                        <strong>Event Title</strong>
                                    </label>

                                    <input type="text" class="form-control" placeholder="Event Title" id="event_title"
                                           name="event_title">

                                </div>
                                <div class="form-group autocomplete">
                                    <label>
                                        <strong>Industry Focus</strong>
                                    </label>

                                    <select name="industry_focus" id="industry_focus" class="form-control">
                                        <option value="">Select Industry</option>
                                        @if(!empty($industryLists))
                                            @foreach($industryLists as $industry)
                                                <option value="{{ $industry->id }}">{{ $industry->name  }}</option>

                                            @endforeach
                                        @endif
                                    </select>

                                </div>
                                <div class="form-group autocomplete">
                                    <label>
                                        <strong>Geo Focus</strong>
                                    </label>

                                    <select name="geo_focus" id="geo_focus" class="form-control">
                                        <option value="">Select Country</option>
                                        @if(!empty($countries))
                                            @foreach($countries as $country)
                                                <option
                                                    value="{{ $country->country_code }}">{{ $country->country_name  }}</option>

                                            @endforeach
                                        @endif
                                    </select>

                                </div>
                                <div class="form-group">
                                    <label>
                                        <strong>Event Start Date/Time</strong>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Event Date" id="event_date"
                                           name="event_date">

                                </div>
                                <div class="form-group">
                                    <label>
                                        <strong>Event Finish Date/Time</strong>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Event Finish Date/Time"
                                           id="event_finish"
                                           name="event_finish">

                                </div>

                                <div class="form-group">
                                    <label>
                                        <strong>TimeZone</strong>
                                    </label>
                                </div>
                                <div class="form-group ">
                                    <select class="form-control" name="timezone" id="timezone">
                                        <option value="">Select Timezone</option>
                                        @if(!empty($newTimeZone))
                                            @foreach($newTimeZone as $timez)
                                                <option value="{!! $timez !!}">{!! $timez !!}</option>
                                            @endforeach
                                        @endif

                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>
                                        <strong>Event Type</strong>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <select class="form-control" name="event_type" id="event_type"
                                            onchange="checkLocation()">
                                        <option value="">Select Event Type</option>
                                        <option value="Physical">Physical</option>
                                        <option value="Online">Online</option>
                                        <option value="Hybrid">Hybrid</option>
                                    </select>
                                </div>
                                <div id="location" style="display: none">
                                    <div class="form-group">
                                        <label>
                                            <strong>Location</strong>
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Event Location"
                                               id="event_location"
                                               name="event_location">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>
                                        <strong>Event Paid/Free</strong>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <select class="form-control" name="event_free_paid" id="event_free_paid"
                                            onchange="checkFee()">
                                        <option value="">Select Event Type</option>
                                        <option value="Paid">Paid</option>
                                        <option value="Free">Free</option>
                                    </select>
                                </div>
                                <div id="fee_check" style="display: none">
                                    <div class="form-group">
                                        <label>
                                            <strong>Fee</strong>
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Amount (Currency)"
                                               id="event_fee"
                                               name="event_fee">
                                    </div>
                                    <div class="form-group">
                                        <label>
                                            <strong>Payment Link</strong>
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Payment Link"
                                               id="payment_link"
                                               name="payment_link">
                                    </div>
                                </div>
                                <!--<div class="form-group">
                                    <label>
                                        <strong>Web link for the Event page (if any)</strong>
                                    </label>
                                </div>-->
                                <div class="form-group">
                                    <input type="hidden" class="form-control" placeholder="Web link for the Event page"
                                           id="voucher_code"
                                           name="voucher_code">
                                </div>


                                {{--<button type="button" class="btn btn-default" onclick="secondPopupPrevious()">Previous</button>--}}
                                <button type="submit" id="addEvent" class="btn btn-default">Submit
                                </button>
                            </form>

                        </div>

                    </div>
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
    <script src="https://echeckout.co.uk/assets/js/moment.min.js"></script>
    <script src="{{ asset('js/datepicker.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.0/clipboard.min.js"></script>

    <script>
        function copyToClipboard(element) {
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val($(element).text()).select();
            document.execCommand("copy");
            $temp.remove();
            toastr.success('Web Link Copied', 'Success');
        }

        $(function () {
            $('#event_date').datetimepicker({
                // Formats
                // follow MomentJS docs: https://momentjs.com/docs/#/displaying/format/
                format: 'YYYY-MM-DD HH:mm:ss',

                // Your Icons
                // as Bootstrap 4 is not using Glyphicons anymore
                icons: {
                    time: 'fa fa-clock-o',
                    date: 'fa fa-calendar',
                    up: 'fa fa-chevron-up',
                    down: 'fa fa-chevron-down',
                    previous: 'fa fa-chevron-left',
                    next: 'fa fa-chevron-right',
                    today: 'fa fa-check',
                    clear: 'fa fa-trash',
                    close: 'fa fa-times'
                }
            });
            $('#event_finish').datetimepicker({
                // Formats
                // follow MomentJS docs: https://momentjs.com/docs/#/displaying/format/
                format: 'YYYY-MM-DD HH:mm:ss',

                // Your Icons
                // as Bootstrap 4 is not using Glyphicons anymore
                icons: {
                    time: 'fa fa-clock-o',
                    date: 'fa fa-calendar',
                    up: 'fa fa-chevron-up',
                    down: 'fa fa-chevron-down',
                    previous: 'fa fa-chevron-left',
                    next: 'fa fa-chevron-right',
                    today: 'fa fa-check',
                    clear: 'fa fa-trash',
                    close: 'fa fa-times'
                }
            });


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
                    if (isConfirm.dismiss != 'cancel') {
                        $.ajax({
                            url: "{{ url('attend-event'). '/' }}" + eventId,
                            type: 'post',
                            data: {
                                "_token": "{{ csrf_token() }}"
                            },
                            success: function () {
                                // delete the row from the table

                                swal("Requested", "Request has been sent successfully.", "success");
                                setTimeout(function () {
                                    location.reload();
                                }, 2000)
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
                    if (isConfirm.dismiss != 'cancel') {
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

        $("#addEventForm").validate({
            rules: {
                event_title: "required",
                industry_focus: "required",
                geo_focus: "required",
                event_date: "required",
                event_finish: "required",
                timezone: "required",
                event_type: "required",
                event_free_paid: "required",
            },
            messages: {
                event_title: "Please enter event title",
                industry_focus: "Please select industry",
                geo_focus: "Please select industry",
                event_date: "Please enter event date",
                event_finish: "Please enter event finish date",
                timezone: "Please select timezone",
                event_type: "Please select event type",
                event_free_paid: "Please select event paid or free",
            },
            submitHandler: function (form) {

                var form_data = new FormData(document.getElementById("addEventForm"));
                $.ajax({
                    type: 'POST',
                    url: '{{ url('add-event') }}',
                    headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
                    data: form_data,
                    cache: false,
                    contentType: false, //must, tell jQuery not to process the data
                    processData: false,
                    success: function (response) {
                        var data = response;
                        if (data.status) {
                            toastr.success(response.message, "Success");

                        } else {
                            toastr.error(response.message, "Error");
                        }
                        setTimeout(function () {
                            location.reload();
                        }, 2000);

                    },
                    error: function (response) {
                        toastr.error(response.responseJSON.msg, "Error");
                        $(':input[type="submit"]').prop('disabled', false);
                    },
                });
            }
        });

        function checkLocation() {
            var eventType = $("#event_type").val();
            if (eventType == 'Hybrid' || eventType == 'Physical') {
                $("#location").show();
            } else {
                $("#location").hide();
            }
        }

        function checkFee() {
            var event_free_paid = $("#event_free_paid").val();
            if (event_free_paid == 'Paid') {
                $("#fee_check").show();
            } else {
                $("#fee_check").hide();
            }
        }

        $(document).ready(function () {
            $('#table_id').DataTable();
            $('#attend').DataTable();
        });
    </script>
@endsection

