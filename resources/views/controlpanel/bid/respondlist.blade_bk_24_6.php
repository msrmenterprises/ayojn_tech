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
			Response
			<small>List</small>

		</h1>


		<ol class="breadcrumb">


			<li><a href="{{ url('admin/dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Response List</li>

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
						@can('add user')
							<div class="col-md-12">
								<a href="{{ route('user.create') }}" class="btn btn-primary ladda-button"
								   data-style="zoom-in">
									<span class="ladda-label"><i class="fa fa-plus"></i> Add User</span>
								</a>
							</div>
						@endcan
					</div>

					<div class="box-body table-responsive">
						<table id="data-table" class="table table-bordered table-striped last-child-sm" width="100%"
							   data-page-length='100'>
							<thead>
							<tr>
								<th width="3%">Response Id</th>
								<th width="10%">Respond User Email</th>
								<th width="20%">Description</th>
								<th width="7%">Web Link</th>
								<th width="16%">Response Date</th>
								<th width="10%%">Bid Status</th>
							</tr>
							</thead>
							<tfoot>
							<tr>
								<th width="3%">Response Id</th>
								<th width="10%">Respond User Email</th>
								<th width="20%">Description</th>
								<th width="7%">Web Link</th>
								<th width="16%">Response Date</th>
								<th width="10%%">Bid Status</th>
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
        var url = "{{ url('controlpanel/list-response')}}" + "/{{ request()->segment(count(request()->segments())) }}";
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
            headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
            data: function (d) {

            }
          },
          columns: [

            {"data": "id"},
            {"data": "userEmail"},
            {"data": "description"},
            {"data": "portfolio_link"},

             {"data": "created_at"},
             {"data": "status"},

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


	</script>
@endsection