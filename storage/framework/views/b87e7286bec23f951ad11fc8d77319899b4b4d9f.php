<!--Add title-->
<?php $__env->startSection('title',  'Ayojn'); ?>
<!--Main Body content-->
<?php $__env->startSection('content'); ?>
    <!-- //banner-top -->
    <!-- banner -->
    <!-- Content Header (Page header) -->
    <?php if (app('request')->input('type') == 'offer') {
        $url = URL::to('controlpanel/list/users/offer');
    } else if (app('request')->input('type') == 'manage') {
        $url = URL::to('controlpanel/list/users/manage');
    } else if (app('request')->input('type') == 'incomplete') {
        $url = URL::to('controlpanel/list/users/incomplete');
    } else {
        $url = URL::to('controlpanel/list/users');
    }?>
    <section class="content-header">
        <h1>
            User
            <small>List</small>

        </h1>


        <ol class="breadcrumb">

            <li>
                <div class="float-right">
                    <form method="post" id="selected_criteria_form" name="selected_criteria_form"
                          action="<?php echo e(url('controlpanel/exportUser')); ?>">
                        <button class="btn btn-primary">Export</button>
                    </form>
                </div>
            <li>
            <li>
            <li>
            <li><a href="<?php echo e(url('admin/dashboard')); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">User List</li>

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
                                    <?php echo e(session()->get('success')); ?>

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
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('add user')): ?>
                            <div class="col-md-12">
                                <a href="<?php echo e(route('user.create')); ?>" class="btn btn-primary ladda-button"
                                   data-style="zoom-in">
                                    <span class="ladda-label"><i class="fa fa-plus"></i> Add User</span>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="row">

                        <div class="col-md-7">
                            <div class="col-md-4">
                                <select name="status_type" class="form-control" id="status_type">
                                    <option value="">Select Status</option>
                                    <option value="1">Approved</option>
                                    <option value="0">Disapproved</option>
                                    <option value="2">Delete</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <a href="javascript:void(0)" class="btn btn-primary" data-style="zoom-in"
                                   onclick="getallcheck()">
                                    <span class="ladda-label">Change Status</span>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <div class="float-right">
                                    <form method="post" id="selected_criteria_form" name="selected_criteria_form"
                                          action="<?php echo e(url('controlpanel/exportReferUser')); ?>">
                                        <button class="btn btn-warning">Export Refer User</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-body table-responsive">
                        <table id="data-table" class="table table-bordered table-striped last-child-sm" width="100%"
                               data-page-length='100'>
                            <thead>
                            <tr>
                                <th><input type="checkbox" name="selected_users" id="selected_users"></th>
                                <th width="3%">Id</th>
                                <!-- <th>Name</th> -->
                                <th width="7%">E-mail</th>
                                <th width="7%">Phone no</th>
                                <th width="15%">Sponsorship Type</th>
                                <th width="7%">Entity</th>
                                <th width="10%">Identity</th>
                                <!-- <th>Organization</th> -->
                                <!-- <th>Sponsorship For</th> -->
                                <th width="16%">Sponsorship dealsize</th>
                                <th width="20%">Spex Plateform</th>
                                <th width="10%">Sponsor's industry</th>
                                <th width="7%">Country of Origin</th>
                                <th width="7%">City of Origin</th>
                                <th width="7%">Country Directed To</th>
                                <th width="7%">Referral Count</th>
                                <th width="7%">Mode Of Register</th>
                                <th width="7%">Wallet Balance</th>
                                <th width="7%">Last Login</th>
                                <th width="7%">Created At</th>
                                <th width="10%">Status</th>
                                <th width="5%">Action</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th></th>
                                <th width="3%">Id</th>
                                <!-- <th>Name</th> -->
                                <th width="7%">E-mail</th>
                                <th width="7%">Phone no</th>
                                <th width="15%">Sponsorship Type</th>
                                <th width="7%">Entity</th>
                                <th width="10%">Identity</th>
                                <!-- <th>Organization</th> -->
                                <!-- <th>Sponsorship For</th> -->
                                <th width="16%">Sponsorship dealsize</th>
                                <th width="20%">Spex Plateform</th>
                                <th width="10%">Sponsor's industry</th>
                                <th width="7%">Country of Origin</th>
                                <th width="7%">City of Origin</th>
                                <th width="7%">Country Directed To</th>
                                <th width="7%">Referral Count</th>
                                <th width="7%">Mode Of Register</th>
                                <th width="7%">Wallet Balance</th>
                                <th width="7%">Last Login</th>
                                <th width="7%">Created At</th>
                                <th width="10%">Status</th>
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
            <div id="append-form"></div>
            <!--/.col (left) -->
        </div>
        <div class="modal fade" id="edit_user">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Edit User</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box box-primary">

                                    <!-- /.box-header -->
                                    <!-- form start -->
                                    <form role="form" method="POST" name="edituser" id="edituser">
                                        <?php echo e(csrf_field()); ?>

                                        <input type="hidden" id="userid" name="userid" value="">
                                        <div class="box-body">
                                            <div class="form-group">
                                                <label for="countryname">User Email <b style="color: red">*</b></label>

                                                <input type="text" class="form-control" id="user_email"
                                                       name="user_email" value="" maxlength="50"
                                                       placeholder="User Email">

                                            </div>
                                        </div>
                                        <!-- /.box-body -->

                                        <div class="box-footer">
                                            <button type="submit" class="btn btn-info pull-right" id="editcountrybutton"
                                                    name="editcountrybutton">Update User <i
                                                    class="fa fa-refresh fa-spin upload_spin" style="display: none"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger pull-right" data-dismiss="modal"
                                                    aria-label="Close">Cancel
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        ...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script type="text/javascript">
        var oTable;

        $(document).on('click', '.delete-button', function (e) {
            e.preventDefault();
            var url = $(this).data('url');

            swal({
                title: "Are you sure?",
                text: "Are you sure that you want to delete this Role?",
                icon: "warning",
                buttons: true,
                dangerMode: true
            }).then(
                function (isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            data: {
                                "_token": "<?php echo e(csrf_token()); ?>"
                            },
                            success: function () {
                                // delete the row from the table

                                oTable.draw();
                                swal("Role Deleted", "User has been deleted successfully.", "success");
                            },
                            error: function () {
                                // Show an alert with the result

                                swal("Role Not Deleted", "There's been an error. Your User might not have been deleted.", "error");
                            }
                        });
                    }
                });

        });

        $(function () {
            var url = "<?php echo e($url); ?>";
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
                    headers: {"X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"},
                    data: function (d) {

                    }
                },
                columns: [
                    {data: 'check', name: 'check', sorting: false, orderable: false},
                    {"data": "id"},
                    {"data": "email"},
                    {"data": "phone_no"},
                    {"data": "sponsor_type"},
                    {"data": "entity"},
                    {"data": "identity"},
                    {"data": "sponsor_budget"},
                    {"data": "specification"},
                    {"data": "sponsor_industry"},
                    {"data": "country_name"},
                    {"data": "city_name"},
                    {"data": "country_sponsorr"},
                    {"data": "referral_count"},
                    {"data": "refer_by_user"},
                    {"data": "wallets"},
                    {"data": "last_login_at"},
                    {"data": "created_at"},
                    {"data": 'userstatus', "name": 'userstatus'},
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
            $('input[type="checkbox"]').prop('checked', this.checked);
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

        function deleteUser(id) {
            swal({
                title: 'Are you sure?',
                text: 'Are you sure that you want to delete this user?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    var url = "<?php echo e(route('delete-user')); ?>";
                    $('#spinner').show();
                    $.ajax({
                        url: url,
                        method: "POST",
                        data: {user_id: id},
                        headers: {"X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"},
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

        function userChangeStatusSuspendWithComment(responseId) {
            $.ajax({
                url: "<?php echo e(url('/')); ?>" + '/controlpanel/user-change-status-suspand/' + responseId,
                type: "get",
                async: false,
                headers: {"X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"},
                success: function (data) {
                    if (data.html != '') {
                        $("#append-form").append(data.html);
                        $("#add-reason").modal('show');
                    }
                },
                error: function (data) {
                },
            })
        }

        function changeSuspendStatus(id) {

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
                    var url = "<?php echo e(route('user-change-status-suspand')); ?>";
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
                                $('#user_id' + id).addClass("label-danger");
                                $('#user_id' + id).html("Suspended");
                            } else {
                                $('#user_id' + id).addClass("label-danger");
                                $('#user_id' + id).html("Suspend");
                            }
                            swal("Updated", obj.msg, "success");
                            setTimeout(function () {
                                location.reload()
                            }, 1500)
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

    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>