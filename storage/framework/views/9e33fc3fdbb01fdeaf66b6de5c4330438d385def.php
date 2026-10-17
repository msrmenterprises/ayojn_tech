<!--Add title-->
<?php $__env->startSection('title',  'Ayojn'); ?>
<!--Main Body content-->
<?php $__env->startSection('content'); ?>
    <!-- //banner-top -->
    <!-- banner -->
    <!-- Content Header (Page header) -->

    <div id="loader" style="display: none; position: fixed; top: 50%; left: 50%;
    transform: translate(-50%, -50%); z-index: 9999; background: rgba(255,255,255,0.9);
    padding: 30px 40px; border-radius: 10px; text-align: center;">
    <div class="spinner" style="border: 4px solid #ccc; border-top: 4px solid #333;
        border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite;"></div>
    <p style="margin-top: 10px;">Sending...</p>
</div>

<style>
@keyframes  spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

    <section class="content-header">
        <h1>
            Vouch Code
            <small>List</small>

        </h1>


        <ol class="breadcrumb">


            <li><a href="<?php echo e(url('admin/dashboard')); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Vouch Code List</li>

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
                            <?php if(session()->has('success')): ?>
                                <div class="alert alert-success">
                                     <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <?php echo e(session()->get('success')); ?>

                                </div>
                            <?php endif; ?>
                            <?php if(session()->has('error')): ?>
                                <div class="alert alert-danger alert-dismissable">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <?php echo e(session()->get('error')); ?>

                                </div>
                            <?php endif; ?>
                            <?php if($errors->any()): ?>
                                <div class="alert alert-danger">
                                    <ul>
                                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><?php echo e($error); ?></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            <?php if(session('true')): ?>
                                <div class="alert alert-success alert-dismissable">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <?php echo e(session('true')); ?>

                                </div>
                            <?php endif; ?>
                            <?php if(session('false')): ?>
                                <div class="alert alert-danger alert-dismissable">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <?php echo e(session('false')); ?>

                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-12">
                            <a href="<?php echo e(route('vouch-create')); ?>" class="btn btn-primary ladda-button"
                               data-style="zoom-in">
                                <span class="ladda-label"><i class="fa fa-plus"></i> Add Vouch Code</span>
                            </a>

                             <a href="<?php echo e(route('bulk-vouch-upload')); ?>" class="btn btn-primary ladda-button"
                               data-style="zoom-in">
                                <span class="ladda-label"><i class="fa fa-upload"></i> Bulk Vouch Code Import</span>
                            </a>


                             <a href="javascript:void(0)" onclick="sharebulk()" class="btn btn-success ladda-button"
                               data-style="zoom-in">
                                <span class="ladda-label"><i class="fa fa-plane"></i> Bulk Voucher Share</span>
                            </a>
                        </div>
                    </div>

                    <div class="box-body table-responsive">
                        <table id="data-table" class="table table-bordered table-striped last-child-sm" width="100%"
                               data-page-length='100'>
                            <thead>
                            <tr>
                               <th><input type="checkbox" id="selected_users"></th>
                                <th width="2%">Id</th>
                                <th width="3%">Vouch Code</th>
                                <th width="3%">Vouch Amount</th>
                                <th width="3%">Status</th>
                                <th width="3%">Share</th>
                                <th width="5%">Action</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th width="1%"></th>
                                <th width="2%">Id</th>
                            
                                <th width="3%">Vouch Code</th>
                                <th width="3%">Vouch Amount</th>
                                <th width="3%">Status</th>
                                <th width="3%">Share</th>
                                <th width="5%">Action</th>
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
    
<script src="<?php echo e(asset('js/bootstrap.min.js')); ?>"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.11.0/sweetalert2.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.11.0/sweetalert2.min.js"></script>
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

<script>
$(function () {
  $('[data-toggle="tooltip"]').tooltip();
})
</script>

    <script type="text/javascript">
        var oTable;
        $(function () {
            var url = "<?php echo e(url('controlpanel/list-code')); ?>";
            var oTable = $('#data-table').DataTable({
                order:[['0','desc']],
                bFilter: true,
                bLengthChange: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: url,
                    type: "POST",
                    headers: {"X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"},
                    data: function (d) {

                    }
                },
                columns: [
                    {"data": "checkall", orderable: false, searchable: false},
                    {"data": "id"},
                    {"data": "vouch_code"},
                    {"data": "vouch_amount"},
                    {"data": "status"},
                    {"data" : "share"},
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
        $('#selected_users').change(function () {
            ///$('.groupCheckBox').not(this).prop('checked', this.checked);
            // var rows = users.rows({ 'search': 'applied' }).nodes();

            $('input[type="checkbox"]:not(:disabled)').prop('checked', this.checked);
        });
        $('#data-table tbody').on('change', 'input[type="checkbox"]', function () {
            // If checkbox is not checked
            if (!this.checked) {
                var el = $('#data-table').get(0);
                // If "Select all" control is checked and has 'indeterminate' property
                if (el && el.checked) {

                }
            }
        });

        function getallcheck() {
            var tbl = $('#data-table').DataTable();
            var data = new Array();
            $.each(tbl.rows().nodes().to$().find("input[name='selected_users[]']:checked"), function () {
                //alert($(this).val());
                data.push($(this).val());
            });
            if (data.length == "0") {
                console.log("123", data.length);
                swal("warning", "Please select atleast One user", "error");
            } else if ($("#status_type").val() == '') {
                swal("warning", "Please select status type", "error");
            } else {
                if ($("#status_type").val() == 2) {
                    swal({
                        title: 'Are you sure?',
                        text: 'Are you sure that you want to delete selected user?',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.value) {
                            var url = "<?php echo e(route('delete-users')); ?>";
                            $('#spinner').show();
                            $.ajax({
                                url: url,
                                method: "POST",
                                data: {userIds: data},
                                headers: {"X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"},
                                success: function (data) {
                                    obj = jQuery.parseJSON(data);
                                    if (obj.msg) {
                                        $("#status_type").prop("selectedIndex", 0);
                                        $('#spinner').hide();
                                        $('input[type="checkbox"]').prop('checked', false);
                                        swal("Delete", obj.msg, "success");
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
                } else {


                    swal({
                        title: 'Are you sure?',
                        text: 'Are you sure that you want to change status of selected users?',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, change it!'
                    }).then((result) => {
                        if (result.value) {
                            var url = "<?php echo e(route('users-change-status')); ?>";
                            $('#spinner').show();
                            $.ajax({
                                url: url,
                                method: "POST",
                                data: {userIds: data, status: $("#status_type").val()},
                                headers: {"X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"},
                                success: function (data) {
                                    obj = jQuery.parseJSON(data);
                                    if (obj.msg) {
                                        $("#status_type").prop("selectedIndex", 0);
                                        $('input[type="checkbox"]').prop('checked', false);
                                        $('#spinner').hide();
                                        swal("success", obj.msg, "success");
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
            }
        }

        function deleteCode(id) {
            swal({
                title: 'Are you sure?',
                text: 'Are you sure that you want to delete this code?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
               
                    var url = "<?php echo e(route('delete-code')); ?>";
                    $('#spinner').show();
                    $.ajax({
                        url: url,
                        method: "POST",
                        data: {user_id: id},
                        headers: {"X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"},
                        success: function (data) {
                            console.log(data);
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
                    var url = "<?php echo e(route('user-change-status')); ?>";
                    $('#spinner').show();
                    $.ajax({
                        url: url,
                        method: "POST",
                        headers: {"X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"},
                        data: {user_id: id},
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

        function changeType(id) {

            swal({
                title: 'Are you sure?',
                text: 'Are you sure that you want to change sponsorr type?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, change it!'
            }).then((result) => {
                if (result.value) {
                    var url = "<?php echo e(route('user-change-type')); ?>";
                    $('#spinner').show();
                    $.ajax({
                        url: url,
                        method: "POST",
                        headers: {"X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"},
                        data: {user_id: id},
                        success: function (data) {
                            obj = jQuery.parseJSON(data);
                            $('#spinner').hide();
                            if (obj.sponsor_type == 1) {
                                $('#user_id' + id).addClass("label-warning");
                                $('#user_id' + id).removeClass("label-info");
                                $('#user_id' + id).html("Offer Sponsorship");
                            } else {
                                $('#user_id' + id).removeClass("label-warning");
                                $('#user_id' + id).addClass("label-info");
                                $('#user_id' + id).html("Manage Or Receive Sponsorship");
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

        function EditUser(id) {
            $.ajax({
                type: 'POST',
                url: '<?php echo e(route('editusers')); ?>',
                headers: {"X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"},
                data: {id: id},
                success: function (response) {
                    var data = JSON.parse(response);
                    console.log(data);

                    $('#user_email').attr('value', data.email);
                    //$('#edit_package').html(response);
                    $('#exampleModal').modal('toggle');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    toastr.error("Something went wrong", "Error");
                },
            });
        }




        function share(id) {

            swal({
                title: "Enter Email ID",
                text: "Voucher code will be send in this email",
                type: 'success',
                input: 'text',
                inputValue: '',
                showCancelButton: true,        
                preConfirm: function (email) {
                document.getElementById('loader').style.display = 'block';
                return new Promise(function (resolve, reject) {
                    
                    if (email === '') {
                        reject('Email is required');
                        document.getElementById('loader').style.display = 'none';
                    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                        document.getElementById('loader').style.display = 'none';
                        reject('Please enter a valid email address');
                    } else {
                        
                        $.ajax({
                            type: 'POST',
                            url: '<?php echo e(route('voucher-share')); ?>',
                            headers: {"X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"},
                            data: {email: email, id: id},
                            success: function (response) {
                                swal("success", response.message, "success");
                                document.getElementById('loader').style.display = 'none';
                                setTimeout(() => location.reload(), 1500);
                            },
                            error: function (jqXHR) {

                                 if (jqXHR.status === 404) {
                                    document.getElementById('loader').style.display = 'none';
                                    swal("Error", jqXHR.responseJSON.message, "error");
                                } else {
                                    document.getElementById('loader').style.display = 'none';
                                    swal("Error", "Unexpected error. Please try again.", "error");
                                }
                                // $('#spinner').hide();
                                // swal("warning", "Please try again", "error");
                                // reject();
                            },
                        });
                    
                    }
                    
                })
            }
            })
        }
        


        function sharebulk(id) {


            let selected = [];
            document.querySelectorAll('input[name="selected_users[]"]:checked').forEach(function (checkbox) {
                selected.push(checkbox.value);
            });

            if (selected.length === 0) {
                swal("Warning", "Please select at least one voucher to share.", "warning");
                return;
            }


            swal({
                title: "Enter Email ID",
                text: "Voucher code will be send in this email",
                type: 'success',
                input: 'text',
                inputValue: '',
                showCancelButton: true,        
                preConfirm: function (email) {
                document.getElementById('loader').style.display = 'block';
                return new Promise(function (resolve, reject) {
                    
                    if (email === '') {
                        reject('Email is required');
                        document.getElementById('loader').style.display = 'none';
                    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                        document.getElementById('loader').style.display = 'none';
                        reject('Please enter a valid email address');
                    } else {
                        
                        $.ajax({
                            type: 'POST',
                            url: '<?php echo e(route('bulk-voucher-share')); ?>',
                            headers: {"X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"},
                            data: {email: email, ids: selected},
                            success: function (response) {
                                swal("success", response.message, "success");
                                document.getElementById('loader').style.display = 'none';
                                setTimeout(() => location.reload(), 1500);
                            },
                            error: function (jqXHR) {

                                 if (jqXHR.status === 404) {
                                    document.getElementById('loader').style.display = 'none';
                                    swal("Error", jqXHR.responseJSON.message, "error");
                                } else {
                                    document.getElementById('loader').style.display = 'none';
                                    swal("Error", "Unexpected error. Please try again.", "error");
                                }
                                // $('#spinner').hide();
                                // swal("warning", "Please try again", "error");
                                // reject();
                            },
                        });
                    
                    }
                    
                })
            }
            })
        }


        </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>