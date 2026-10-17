<!--Add title-->
<?php $__env->startSection('title',  'Ayojn'); ?>
<!--Main Body content-->
<?php $__env->startSection('content'); ?>
	<!-- //banner-top -->
	<!-- banner -->
	<!-- Content Header (Page header) -->

	<section class="content-header">
		<h1>
			Opportunity
			<small>List</small>

		</h1>


		<ol class="breadcrumb">


			<li><a href="<?php echo e(url('admin/dashboard')); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Opportunity List</li>

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
					</div>

					<div class="box-body table-responsive">
						<table id="data-table" class="table table-bordered table-striped last-child-sm" width="100%"
							   data-page-length='100'>
							<thead>
							<tr>
								<th width="3%"> Id</th>
								<!-- <th>Name</th> -->
								<th width="7%">Oppotunity Email</th>
								<th width="7%">Hashtag</th>
								<th width="7%">Sponsorr Industry</th>
								<th width="16%">Sponsorr Country</th>
								<th width="16%">Sponsorr City</th>
								<th width="10%">Added Date</th>
								<th width="10%">Vouch Count</th>
                <!-- <th>Reserved</th>
                <th>Payment Status</th>
                <th>Renew Date</th>
								<th width="10%">Change Status</th> -->
								<th width="5%">Action</th>
								
							</tr>
							</thead>
							<tfoot>
							<tr>
								<th width="3%"> Id</th>
								<!-- <th>Name</th> -->
								<th width="7%">Oppotunity Email</th>
								<th width="7%">Hashtag</th>
								<th width="7%">Sponsorr Industry</th>
								<th width="16%">Sponsorr Country</th>
								<th width="16%">Sponsorr City</th>
								<th width="10%">Added Date</th>
								<th width="10%">Vouch Count</th>
                <!-- <th>Reserved</th>
                <th>Payment Status</th>
                <th>Renew Date</th>
								<th width="10%">Change Status</th> -->
								<th width="15%">Action</th>
								
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

  <div class="modal fade" id="transactionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Transaction</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive" id="transactionModalBody">

                <table class="table table-bordered table-striped last-child-sm" width="100%" data-page-length='100'>
                  <thead>
                  <tr>
                    <th width="3%">Id</th>
                    <th>Transaction Date</th>
                    <th>Transaction Amount</th>
                    <th>Transaction Mode</th>
                    <th>Refrence No.</th>
                    <th>Reserved Years</th>
                    <th>Renewal Date</th>
                 
                  </tr>
                  </thead>
                  <tbody id="transactionModalBodyContent">
                  </tbody>
                </table>

              </div>
            </div>
          </div>
        </div>  
  </div>
  </div>
  </div>

	<script type="text/javascript">
      function copyToClipboard(element) {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($(element).text()).select();
        document.execCommand("copy");
        $temp.remove();
        toastr.success('Web Link Copied', 'Success');

      }

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
        var url = "<?php echo e(url('controlpanel/list-opportunity')); ?>";
        var oTable = $('#data-table').DataTable({
          // dom: "<'row'<'col-xs-12'<'col-xs-6'l><'col-xs-6'p>>r>"+
          // "<'row'<'col-xs-12't>>"+
          // "<'row'<'col-xs-12'<'col-xs-6'i><'col-xs-6'p>>>",
          bFilter: true,
          bLengthChange: true,
          ordering: false,
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

            {"data": "id"},
            {"data": "opportunity_email"},
            {"data": "hashtag", orderable: false, render: function(data, type, row) {
              return `<a href="javascript:void(0)" data-id="${row.id}" onclick="transaction(${row.id},${row.receiver_id})">${data}</a>`;
            }},
            {"data": "sponsor_industry"},
            {"data": "country_name"},
            {"data": "city_name"},
            {"data": "created_at"},
            {"data": "responseCount"},
            {"data": "action", orderable: false},
           
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
          text: 'Are you sure that you want to delete this opportunity?',
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.value) {
            var url = "<?php echo e(route('delete-opportunity')); ?>";
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
            var url = "<?php echo e(route('opportunity-change-status')); ?>";
            $('#spinner').show();
            $.ajax({
              url: url,
              method: "POST",
              headers: {"X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"},
              data: {opportunityId: id},
              success: function (data) {
                // oTable.draw();
                obj = jQuery.parseJSON(data);
                $('#spinner').hide();
                if (obj.status == 1) {
                  $('#cust_id' + id).addClass("label-success");
                  $('#cust_id' + id).removeClass("label-danger");
                  $('#cust_id' + id).html("<i class='fa fa-signal'></i>");
                } else {
                  $('#cust_id' + id).removeClass("label-success");
                  $('#cust_id' + id).addClass("label-danger");
                  $('#cust_id' + id).html("<i class='fa fa-signal'></i>");
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


      function transaction(id,user_id){
        var url = "<?php echo e(route('opportunity-transaction')); ?>";
        $.ajax({
          type: 'POST',
          url: url,
          data: {'id': id,'user_id': user_id},
          dataType: 'html',
          headers: {"X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"},
          success: function (response) {
            //var data = JSON.parse(response.opportunity);
            console.log(response);
            $('#transactionModal').modal('toggle');
            $('#transactionModalBodyContent').html(response);
          },
        })
      }

	</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>