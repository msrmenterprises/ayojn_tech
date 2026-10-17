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
            Direct Payment
            <small>List</small>

        </h1>


        <ol class="breadcrumb">


            <li><a href="{{ url('admin/dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Direct Payment List</li>

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
                    

                    <div class="box-body table-responsive">
                        <table id="data-table" class="table table-bordered table-striped last-child-sm" width="100%"
                               data-page-length='100'>
                            <thead>
                            <tr>
                                <th>Transaction ID</th>
                                <th>User</th>
                                <th>Purpose</th>
                                <th>Amount</th>
                                <th>Transaction Date</th>
                                <th>Status </th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($log as $logs)

                                    <?php
                                    $user = DB::table('users')->where('id',$logs->user_id)->value('name');
                                    // if($logs->status_desc !='')
                                    // {
                                    //     $tb ="<table class='' style='margin-bottom:0px;'>";
                                    //     foreach (json_decode($logs->status_desc) as $key => $value) {
                                    //         if ($key === array_key_last(json_decode($logs->status_desc))) {
                                    //             $tb .="<tr><td width='100'>$value->status</td><td>".date('d M, Y, H:i',strtotime($value->date))."</td></tr>";
                                    //         }
                                    //     }
                                    //     $tb .="</table>";
                                    // }
                                    ?>
                                    <tr>
                                        <td>{{$logs->collection_id}}</td>
                                        <td>{{$user}}</td>
                                        <td>{{$logs->purpose}}</td>
                                        <td>{{$logs->amount}}</td>
                                        <td>{{date('d M, Y, H:i',strtotime($logs->created_at))}}</td>
                                        <td>{{$logs->status_desc}}</td>
                                        <td>
                                        <!--form action="{{route('xsquare-status-check-admin')}}" method="POST">
                                            <input type='hidden' value='{{$logs->collection_id}}' name='collection_id' />   
                                            <button type='submit' class='btn btn-primary btn-sm'><i class='fa fa-refresh'></i> Check Status</button>
                                        </form-->
                                        </td>
                                    </tr>
                                @endforeach
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
    
        var oTable;
        $(function () {
            var oTable = $('#data-table').DataTable({
              
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

 
    </script>
@endsection
