@extends('adminlte::page')
<!--Add title-->
@section('title',  'Ayojn')
<!--Main Body content-->
@section('content')
    <!-- //banner-top -->
    <!-- banner -->
    <!-- Content Header (Page header) -->

    <section class="content-header">
        <h1>Refund <small>List</small></h1>

        <ol class="breadcrumb">
            <li><a href="{{ url('admin/dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Refund List</li>
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
                  <th>Sr.</th>
                  <th>Collection Id</th>
                  <th>Reference No.</th>
                  <th>Purpose</th>
                  <th>Amount</th>
                  <th>Date</th>
                  <th>Mode</th>
                  <th>Status</th>
                  <th>Receipt</th>

              </tr>
          </thead>

          @foreach($log as $logs)
          <?php
          if($logs->purpose==1){$purpose = "Purchase of Goods";}
          if($logs->purpose==2){$purpose = "Invoice payment";}
          if($logs->purpose==3){$purpose = "Advance Payment";}
          if($logs->purpose==4){$purpose = "Commission";}
          if($logs->purpose==5){$purpose = "Others";}

          if($logs->transaction_amount){$amount = $logs->transaction_amount;}
          else{$amount=$logs->amount;}

          if($logs->receipt){$rec = "<a href='receipt/$logs->receipt'>Download</a>";}
          else{$rec="";}

          ?>
          <tr>
              <td>{{$loop->iteration}}</td>
              <td>{{$logs->collection_id }}</td>
              <td>{{$logs->transaction_reference}}</td>
              <td>{{$purpose}}</td>
              <td>{{$amount}}</td>
              <td>{{$logs->payment_date}}</td>
              <td>{{$logs->payment_mode}}</td>
              <td>{{$logs->status_desc}}</td>
              <td><?= $rec; ?></td>
              </tr>

          @endforeach

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

<script type="text/javascript">
</script>
@endsection
