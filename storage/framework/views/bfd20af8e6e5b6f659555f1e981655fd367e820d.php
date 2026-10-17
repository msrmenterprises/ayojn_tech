<!--Add title-->
<?php $__env->startSection('title',  'Ayojn'); ?>
<!--Main Body content-->
<?php $__env->startSection('content'); ?>
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

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-check"></i> Success!</h4>
            <?php echo e(session('success')); ?>    
        </div>
    <?php endif; ?>

     <?php if(session('warning')): ?>
        <div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-check"></i> Warning!</h4>
            <?php echo e(session('warning')); ?>    
        </div>
    <?php endif; ?>

   
 
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
        <?php $__currentLoopData = $log; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $table): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                $total = \DB::table('direct_payment')->where('beneficiary_id',$table->id)->get()->sum('transaction_amount');
                ?>
                <tr>
                    <td><?php echo e($loop->iteration); ?></td>
                    <td><?php echo e($table->company_name); ?></td>
                    <td><?php echo e($table->contact_name); ?><br/><?php echo e($table->email); ?></td>
                
                    <td><?php echo e($table->mobile_no); ?></td>
                    <td><?php echo e($table->tax_id); ?></td>
                    <td><?php echo e($table->bank_country); ?><br/></td>
                    
                    <td><?php echo e($table->bank_account); ?></td>
                    <td><?php echo e($table->bank_code); ?><br/><?php echo e($table->code_no); ?></td>
                    
                    <td><?php echo e($table->beneficiary_country); ?><br/><?php echo e($table->beneficiary_city); ?><br/><?php echo e($table->beneficiary_address); ?></td>

                    <td><?php echo e($table->created_at); ?></td>
                    <td>
                        <a class='btn btn-primary btn-xs' href="<?php echo e(route('controlpanel.all-beneficiary-payment', $table->id)); ?>" title="Total Payment"><i class="fa fa-usd"></i> <?php echo e($total); ?></a>
                        <a class='btn btn-warning btn-xs' onclick="editBeneficiary(<?php echo e($table->id); ?>)" title="Edit"><i class="fa fa-pencil"></i> Edit</a>
                    </td>
                    
                   
                   
                </tr>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
            url: '<?php echo e(route('beneficiary-edit-admin')); ?>',
            headers: {"X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"},
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

<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>