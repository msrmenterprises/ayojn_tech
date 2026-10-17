@extends('adminlte::page')
<!--Add title-->
@section('title',  'Ayojn')
<!--Main Body content-->
@section('content')
    <!-- //banner-top -->
    <!-- banner -->
    <!-- Content Header (Page header) -->

    <section class="content-header">
        <h1>
            Events
            <small>List</small>

        </h1>


        <ol class="breadcrumb">


            <li><a href="{{ url('admin/dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Events List</li>

        </ol>
    </section>
    <style type="text/css">
        .mb20 {
            margin-bottom: 20px;
        }

        .checkbox {
            margin-top: 0px;
        }
    </style>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <div class="col-md-12">
                            @if(session()->has('success'))
                                <div class="alert alert-success">
                                    {{ session()->get('success') }}
                                </div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @if (session('true'))
                                <div class="alert alert-success alert-dismissable">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    {{ session('true') }}
                                </div>
                            @endif
                            @if (session('false'))
                                <div class="alert alert-danger alert-dismissable">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    {{ session('false') }}
                                </div>
                            @endif
                        </div>
                        <div class="col-md-12">
                            {{--                            <a href="{{ route('vouch-create') }}" class="btn btn-primary ladda-button"--}}
                            {{--                               data-style="zoom-in">--}}
                            {{--                                <span class="ladda-label"><i class="fa fa-plus"></i> Add Vouch Code</span>--}}
                            {{--                            </a>--}}
                        </div>
                    </div>

                    <div class="box-body table-responsive">
                        <table id="data-table" class="table table-bordered table-striped last-child-sm" width="100%"
                               data-page-length='100'>
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Event Organizer</th>
                                <th>Event Title</th>
                                <th>Event Date</th>
                                <th>Event Finish</th>
                                <th>Industry focus</th>
                                <th>Geo focus</th>
                                <th>Timezone</th>
                                <th>Event Type</th>
                                <th>Event Location</th>
                                <th>Free / Paid</th>
                                <th>Event Fee</th>
                                <th>Payment link</th>
                                <th>Event Link</th>
                                <th>status</th>
                                <th>Created On</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>Id</th>
                                <th>Event Organizer</th>
                                <th>Event Title</th>
                                <th>Event Date</th>
                                <th>Event Finish</th>
                                <th>Industry focus</th>
                                <th>Geo focus</th>
                                <th>Timezone</th>
                                <th>Event Type</th>
                                <th>Event Location</th>
                                <th>Free / Paid</th>
                                <th>Event Fee</th>
                                <th>Payment link</th>
                                <th>Event Link</th>
                                <th>status</th>
                                <th>Created On</th>
                                <th>Action</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- form start -->
                    <div class="box-footer clearfix text-right">

                    </div>
                    <div id="append-form"></div>
                </div>
                <!-- /.box -->
            </div>
            <!--/.col (left) -->
        </div>
    </section>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.0/clipboard.min.js"></script>

    <script type="text/javascript">
        $(".share-opp-btn").on('click', function (e) {
            toastr.success('Web Link Copied', 'Success');
        });

        function copyToClipboard(element) {
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val($(element).text()).select();
            document.execCommand("copy");
            $temp.remove();
        }
        var oTable;
        $(function () {
            var url = "{{ URL::to('controlpanel/list-event')}}";
            var oTable = $('#data-table').DataTable({
                // dom: "<'row'<'col-xs-12'<'col-xs-6'l><'col-xs-6'p>>r>"+
                // "<'row'<'col-xs-12't>>"+
                // "<'row'<'col-xs-12'<'col-xs-6'i><'col-xs-6'p>>>",
                bFilter: true,
                bLengthChange: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: url,
                    type: "POST",
                    headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
                    data: function (d) {

                    }
                },
                columns: [
                    {"data": "id"},
                    {"data": "organizer"},
                    {"data": "event_title"},
                    {"data": "event_date"},
                    {"data": "event_finish"},
                    {"data": "country_name"},
                    {"data": "industry"},
                    {"data": "timezone"},
                    {"data": "event_type"},
                    {"data": "event_location"},
                    {"data": "event_free_paid"},
                    {"data": "event_fee"},
                    {"data": 'payment_link'},
                    {"data": 'voucher_code'},
                    {"data": 'status'},
                    {"data": 'created_at'},
                    {"data": "action", orderable: false}
                ], "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                    $(nRow).attr("data-id", aData['id']);
                    return nRow;
                },
            });

            $('#moveprofile').on('submit', function (e) {
                if ($('#admin_from').val() != '' && $('#admin_to').val() != '') {
                    oTable.draw();
                } else {
                    toastr.error("Admin From And To Is Required", "Error");
                }
                e.preventDefault();
            });
        });

        function deleteUser(id) {
            swal({
                title: 'Are you sure?',
                text: 'Are you sure that you want to delete this offer?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    var url = "{{ route('delete-offer') }}";
                    $('#spinner').show();
                    $.ajax({
                        url: url,
                        method: "POST",
                        data: {user_id: id},
                        headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
                        success: function (data) {
                            obj = jQuery.parseJSON(data);
                            if (obj.msg) {
                                $('#spinner').hide();
                                swal("Success", obj.msg, "success");
                                $('#data-table').DataTable().draw(false)

                            }
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            $('#spinner').hide();
                            swal("warning", "Please try again", "error");
                        }
                    });
                }
            })
        }

        function changeStatus(id) {

            swal({
                title: 'Are you sure?',
                text: 'Are you sure that you want to change status?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, change it!'
            }).then((result) => {
                if (result.value) {
                    var url = "{{ route('event-change-status') }}";
                    $('#spinner').show();
                    $.ajax({
                        url: url,
                        method: "POST",
                        headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
                        data: {event_id: id},
                        success: function (data) {
                            obj = jQuery.parseJSON(data);
                            $('#spinner').hide();
                            if (obj.status == 1) {
                                $('#cust_id' + id).addClass("label-success");
                                $('#cust_id' + id).removeClass("label-danger");
                                $('#cust_id' + id).html("Approved");
                            } else {
                                $('#cust_id' + id).removeClass("label-success");
                                $('#cust_id' + id).addClass("label-danger");
                                $('#cust_id' + id).html("Disapproved");
                            }
                            swal("Updated", obj.msg, "success");
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            $('#spinner').hide();
                            swal("warning", "Please try again", "error");
                        }
                    });
                }
            })
        }


    </script>
@endsection
