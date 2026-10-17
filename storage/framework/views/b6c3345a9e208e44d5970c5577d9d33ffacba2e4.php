<?php $__env->startSection('title',  'Ayojn'); ?>
<?php $__env->startSection('content'); ?>
    <style>
        .history{padding-top:30px; display:table;}
        fieldset {
    font-family: sans-serif;
    border: 5px solid #1F497D;
    margin-bottom:30px;
    border-radius: 5px;
    padding: 15px;
}

fieldset legend {
    background: #1F497D;
    color: #fff;
    padding: 5px 10px ;
    font-size: 22px;
    border-radius: 5px;
    box-shadow: 0 0 0 5px #ddd;
    margin-left: 20px;
}
.table th{background-color:#e4e4e4;}
    </style>
  <section class="content-header">
        <h1>
            Wallet History
            <small>List</small>

        </h1>


        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">User</li>    
           
           
            <li><a href="<?php echo e(url('admin/dashboard')); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">User List</li>

        </ol>
    </section>

    <section class="content">
    <div class="box box-primary">
 
    <div class="box-body table-responsive">
    <div class="container history">
        
        
        <div class="row">
        <?php echo $fieldset; ?>  
        </div>

    
    </div>
</div>
</div>
    </section>
    <script>
          $(document).ready(function () {

                $('#table_id').DataTable({
                    "order": [[ 0, "desc" ]]
                });
        });
    </script>

    <script src="<?php echo e(asset('js/jquery1.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/bootstrap.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/typed.min.js')); ?>"></script>
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.0/clipboard.min.js"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>