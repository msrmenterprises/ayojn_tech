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
            Profile Review
            <small>List</small>

        </h1>


        <ol class="breadcrumb">

            <li>
            <li>
            <li><a href="{{ url('admin/dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
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
                    </div>
                    <div class="box-body table-responsive">
                        <table id="data-table" class="table table-bordered table-striped last-child-sm" width="100%"
                               data-page-length='100'>
                            <thead>
                            <tr>
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
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th width="7%">E-mail</th>
                                <th width="7%">Phone no</th>
                                <th width="15%">Sponsorship Type</th>
                                <th width="7%">Entity</th>
                                <th width="10%">Identity</th>
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
                                "_token": "{{ csrf_token() }}"
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
            var oTable = $('#data-table').DataTable({
                // dom: "<'row'<'col-xs-12'<'col-xs-6'l><'col-xs-6'p>>r>"+
                // "<'row'<'col-xs-12't>>"+
                // "<'row'<'col-xs-12'<'col-xs-6'i><'col-xs-6'p>>>",
                bFilter: true,
                bLengthChange: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('review-list-data') }}",
                    type: "POST",
                    headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
                    data: function (d) {

                    }
                },
                columns: [
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
                    {"data": "wallet_balance"},
                    {"data": "last_login_at"},
                    {"data": "created_at"},
                    {"data": 'userstatus', "name": 'userstatus'},
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
                            var url = "{{ route('delete-users') }}";
                            $('#spinner').show();
                            $.ajax({
                                url: url,
                                method: "POST",
                                data: {userIds: data},
                                headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
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
                            var url = "{{ route('users-change-status') }}";
                            $('#spinner').show();
                            $.ajax({
                                url: url,
                                method: "POST",
                                data: {userIds: data, status: $("#status_type").val()},
                                headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
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
                    var url = "{{ route('delete-user') }}";
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
                    var url = "{{ route('user-change-status') }}";
                    $('#spinner').show();
                    $.ajax({
                        url: url,
                        method: "POST",
                        headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
                        data: {user_id: id},
                        success: function (data) {
                            obj = jQuery.parseJSON(data);
                            $('#spinner').hide();
                            location.reload();
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
                url: "{{ url('/') }}" + '/controlpanel/user-change-status-suspand/' + responseId,
                type: "get",
                async: false,
                headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
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
                    var url = "{{ route('user-change-status-suspand') }}";
                    $('#spinner').show();
                    $.ajax({
                        url: url,
                        method: "POST",
                        headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
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
                    var url = "{{ route('user-change-type') }}";
                    $('#spinner').show();
                    $.ajax({
                        url: url,
                        method: "POST",
                        headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
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
                url: '{{ route('editusers') }}',
                headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
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
@endsection
