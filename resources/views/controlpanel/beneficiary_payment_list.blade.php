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
        Payment List
          
        </h1>


        <ol class="breadcrumb">
        <a class="btn btn-danger" href="{{url('controlpanel/all-beneficiary')}}">Back</a>    
            &nbsp;
        <button id="dwnldBtn" class="btn btn-success pull-right">
                <i class="fa fa-download"></i> Download Excel Sheet
        </button>

        </ol>
        <br/>
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

          
        });

    </script>


<script>
        $(document).ready(function () {
            $('#dwnldBtn').on('click', function () {
                downloadExcelTable('data-table');
            });

            function downloadExcelTable(tableID, filename = '') {
                const linkToDownloadFile = document.
                                           createElement("a");
                const fileType = 'application/vnd.ms-excel';
                const selectedTable = document.
                                      getElementById(tableID);
                const selectedTableHTML = selectedTable.outerHTML.
                                          replace(/ /g, '%20');

                filename = filename ? filename + '.xls' : 
                           'excel_data.xls';
                document.body.appendChild(linkToDownloadFile);

                if (navigator.msSaveOrOpenBlob) {
                    const myBlob = new Blob(['\ufeff',
                                   selectedTableHTML], {
                        type: fileType
                    });
                    navigator.msSaveOrOpenBlob(myBlob, filename);
                } else {
                    // Create a link to download
                    // the excel the file
                    linkToDownloadFile.href = 'data:' + fileType + 
                                               ', ' + selectedTableHTML;

                    // Setting the name of
                    // the downloaded file
                    linkToDownloadFile.download = filename;

                    // Clicking the download link 
                    // on click to the button
                    linkToDownloadFile.click();
                }
            }
        });
    </script>

@endsection
