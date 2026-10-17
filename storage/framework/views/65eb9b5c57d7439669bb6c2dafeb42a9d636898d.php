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
    <div class="container history">
        
        <h3 style="text-align:center;">My Wallet Transaction</h3>
        <div class="row">
        <?php echo $fieldset; ?>  
        </div>

    
    </div>
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

<?php echo $__env->make('layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>