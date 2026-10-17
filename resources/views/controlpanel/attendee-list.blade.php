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
            Attendees
            <small>List</small>

        </h1>


        <ol class="breadcrumb">


            <li><a href="{{ url('admin/dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Attendees List</li>

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
                                <th>Attendee email</th>
                                <th>Attendee Phone no</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>Attendee email</th>
                                <th>Attendee Phone no</th>
                                <th>Status</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @if(!empty($attendeedList->first()))
                                @foreach($attendeedList as $event)
                                    <tr>
                                        <td>{{ ($event->user) ? $event->user->email : '-' }}</td>
                                        <td>{{ ($event->user) ? $event->user->phone_no : '-' }}</td>
                                        <td> @if($event->status == 1)
                                                Approved
                                            @elseif($event->status == 2)
                                                Reject
                                            @else Pending @endif


                                        </td>

                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
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
            var oTable = $('#data-table').DataTable();
        });


    </script>
@endsection
