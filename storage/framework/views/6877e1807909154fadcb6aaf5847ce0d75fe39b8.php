<?php $__env->startSection('title', 'Ayojn'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Add Vouch Code</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<style>
#loader {
  display: none; /* Hidden by default */
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.7); /* Black with opacity */
  z-index: 9999; /* Make sure it's on top */
  display: flex;
  justify-content: center;
  align-items: center;
}

.spinner {
  border: 6px solid #f3f3f3;
  border-top: 6px solid #ffffff;
  border-radius: 50%;
  width: 60px;
  height: 60px;
  animation: spin 1s linear infinite;
}

@keyframes  spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>


<div id="loader" style="display:none;">
  <div class="spinner"></div>
</div>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>"/>
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Bulk upload Vouch Code</h3>    
        </div>
       
    <!-- /.box-header -->
        <!-- form start -->

        <form role="form" method="POST" name="editsubuser" id="editsubuser" action="<?php echo e(route('bulk-vouch-upload-store')); ?>"
              enctype="multipart/form-data">
            <?php echo e(csrf_field()); ?>

            <div class="box-body">

           

      <?php if(session()->has('success')): ?>
            <div class="alert alert-success">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Success!</strong>
                <?php echo e(session()->get('success')); ?>

            </div>
        <?php endif; ?>


         <?php if(session()->has('warning')): ?>
            <div class="alert alert-warning">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Warning!</strong>
                <?php echo e(session()->get('warning')); ?>

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


                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="vouch_code">Select File<b style="color: red">*</b> <a href='<?php echo e(asset("sample/voucher-code.xlsx")); ?>' download >Download Sample file</a></label>
                            <input type="file" class="form-control" id="vouch_file" name="vouch_file" required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group" style="margin-top: 25px;">
                            
                             <button type="submit" class="btn btn-info pull-left" id="adduserbutton" name="adduserbutton">Add Vouch code<i class="fa fa-refresh fa-spin upload_spin" style="display: none"></i></button>
                        </div>
                    </div>
                </div>

            </div>
          
        </form>
    </div>
    <script>
        jQuery.validator.addMethod("nameValid", function (value, element) {
            return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
        });
        jQuery.validator.addMethod("phoneValid", function (value, element) {
            return this.optional(element) || /^[0-9]+$/.test(value);
        });
        jQuery.validator.addMethod("alphanumeric1", function (value, element) {
            return this.optional(element) || /^(?=.*[0-9])(?=.*[a-zA-Z])([a-zA-Z0-9-!@#$%&*?_]+)$/.test(value);
        });
        $("#editsubuser").validate({
            rules: {
                vouch_code: {
                    required: true,
                },
                vouch_amount: {
                    required: true,
                }
            },
            messages: {
                vouch_code: {
                    required: "Enter a vouch code",
                },
                vouch_amount: {
                    required: "Enter a vouch amount",
                }
            },
            /*errorPlacement: function (error, element) {
             if (element.attr("name") == "terms") {
             error.appendTo('#error');
             } else {
             error.insertAfter(element);
             }

             },*/
        });


 document.getElementById('editsubuser').addEventListener('submit', function(e) {
    // Prevent default submit
    if (!this.checkValidity()) {
      e.preventDefault(); // Form is invalid
      return false;
    }

    // Only show spinner if form is valid
    document.getElementById('loader').style.display = 'flex';
  });

    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>