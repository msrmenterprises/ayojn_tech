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
            Offer
            <small>List</small>

        </h1>


        <ol class="breadcrumb">


            <li><a href="{{ url('admin/dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Offer List</li>

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

                    <div class="box-body table-responsive">
                        <table id="data-table" class="table table-bordered table-striped last-child-sm" width="100%"
                               data-page-length='100'>
                            <thead>
                            <tr>
                            <th width="3%">Id</th>
                                <!-- <th>Name</th> -->
                                <th width="7%">E-mail</th>
                                <th width="7%">Phone no</th>
                                <th width="15%">Sponsorship Type</th>
                                <th width="7%">Entity</th>
                                <th width="10%">Identity</th>
                                <th width="7%">Last Login</th>
                                <th width="7%">Created At</th>
                                <!-- <th width="7%">Action</th> -->

                            </tr>
                            </thead>
                            <tbody>

                                    @foreach($userlist as $key => $userlists)

                                    <tr>
                                    <td>{{$userlists->id}}</td>
                                    <td>{{$userlists->email}}</td>
                                    <td>{{$userlists->phone_no}}</td>
                                    <td>
                                        @if($userlists->sponsor_type==2)
                                            Receive user
                                            @else
                                            Offer user
                                        @endif
                                    </td>
                                    <td>{{$userlists->entity}}</td>
                                    <td>{{$userlists->identity}}</td>
                                    <td>{{$userlists->last_login_at??''}}</td>
                                    <td>{{$userlists->created_at??''}}</td>
                                    <!-- <td>
                                        @php
                                        $user_id = $userlists->id;
                                        $offer_id = Request::segment(4);

                                        $status = \DB::table('offers_order_item')
                                        ->join('offers_order', 'offers_order.id', '=', 'offers_order_item.offer_id')
                                        ->where('offers_order.id',$offer_id)->where('offers_order.user_id',$user_id)->value('status');
                                        @endphp

                                        @if($status==4)
                                        <a href="javascript:void(0)"  onclick="changeStatus({{$userlists->id}})" class="btn btn-danger btn-sm"><i class="fa fa-check"></i> Approve {{$status}}</a>
                                        @else
                                        <a href="javascript:void(0)" class="btn btn-success btn-sm"><i class="fa fa-check"></i> Paid {{$status}}</a>
                                        @endif
                                    </td> -->
                                    </tr>

                                    @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                            <th width="3%">Id</th>

                                <th width="7%">E-mail</th>
                                <th width="7%">Phone no</th>
                                <th width="15%">Sponsorship Type</th>
                                <th width="7%">Entity</th>
                                <th width="10%">Identity</th>

                                <th width="7%">Last Login</th>
                                <th width="7%">Created At</th>
                                <!-- <th width="7%">Action</th> -->


                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- form start -->
                    <div class="box-footer clearfix text-right">

                    </div>

                </div>
                <!-- /.box -->
            </div>
            <!--/.col (left) -->
        </div>
    </section>



    <script>

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
        var url = "{{ route('offer-buy-status') }}";
        $('#spinner').show();
        $.ajax({
            url: url,
            method: "POST",
            headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
            data: {user_id: id, offer_id: {{Request::segment(4)}}},
            success: function (data) {
                console.log(data)
                $('#spinner').hide();
                if(data==1)
                {
                    location.reload();
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
        </script>
   @endsection
