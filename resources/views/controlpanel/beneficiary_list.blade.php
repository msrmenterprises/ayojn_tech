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
        Beneficiary List
            

        </h1>


        <ol class="breadcrumb">

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

    @if(session('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-check"></i> Success!</h4>
            {{ session('success') }}    
        </div>
    @endif

     @if(session('warning'))
        <div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-check"></i> Warning!</h4>
            {{ session('warning') }}    
        </div>
    @endif

   
 
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
                    <th>Company Name</th>
                    <th>Contact Name<br/>Email</th>
                    <th>Mobile</th>
                    <th>TaxID</th>
                    <th>Bank Country<br/>Currency</th>
                    <th>Bank Account</th>
                    <th>Bank Code<br/>Code no</th>
                    <th>Beneficiary country<br/>City<br/>Address</th>
                    
                    <th>Created Date</th>
                <th>Action</th>
                    
                </tr>
            </thead>
        @foreach($log as $table)
                @php
                $total = \DB::table('direct_payment')->where('beneficiary_id',$table->id)->get()->sum('transaction_amount');
                @endphp
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$table->company_name}}</td>
                    <td>{{$table->contact_name}}<br/>{{$table->email}}</td>
                
                    <td>{{$table->mobile_no}}</td>
                    <td>{{$table->tax_id}}</td>
                    <td>{{$table->bank_country}}<br/></td>
                    
                    <td>{{$table->bank_account}}</td>
                    <td>{{$table->bank_code}}<br/>{{$table->code_no}}</td>
                    
                    <td>{{$table->beneficiary_country}}<br/>{{$table->beneficiary_city}}<br/>{{$table->beneficiary_address}}</td>

                    <td>{{$table->created_at}}</td>
                    <td>
                        <a class='btn btn-primary btn-xs' href="{{route('controlpanel.all-beneficiary-payment', $table->id)}}" title="Total Payment"><i class="fa fa-usd"></i> {{$total}}</a>
                        <a class='btn btn-warning btn-xs' onclick="editBeneficiary({{$table->id}})" title="Edit"><i class="fa fa-pencil"></i> Edit</a>
                    </td>
                    
                   
                   
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


        function editBeneficiary(id) {
           $.ajax({
            url: '{{ route('beneficiary-edit-admin') }}',
            headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
            method: 'GET',
            data: {id: id},
            success: function (response) {
                Swal.fire({
                    title: 'Edit Beneficiary',
                    html: response.html,
                    width: '80%',
                    showCloseButton: true,
                    focusConfirm: false,
                    showConfirmButton: false,  
                });
                
            },
            error: function () {
                Swal.fire('Error', 'Failed to load data', 'error');
            }
            });
        }
    </script>

@endsection
