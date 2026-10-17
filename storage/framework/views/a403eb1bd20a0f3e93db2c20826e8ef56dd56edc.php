<!--Add title-->
<?php $__env->startSection('title',  'Ayojn'); ?>
<?php $__env->startSection('content'); ?>


 <section class="content-header">
        <h1>
            Tickets
            <small>List</small>

        </h1>


        <ol class="breadcrumb">


            <li><a href="<?php echo e(url('admin/dashboard')); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Tickets List</li>

        </ol>
    </section>


     <section class="content">


        <div class="row">
            <div class="col-sm-12">

<div class="box box-primary">
                    <div class="box-header with-border"></div>
                     <div class="box-body table-responsive">
                        <table id="data-table" class="table table-bordered table-striped last-child-sm" width="100%">
    <thead>
        <tr>
            <th>ID</th>
            <th>Question</th>
            <th>Match Score</th>
            <th>Status</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($ticket['id']); ?></td>
                <td><a href="<?php echo e(route('agents.show', $ticket['id'])); ?>"><?php echo e($ticket['user_question']); ?></a></td>
                <td><?php echo e($ticket['match_score']); ?></td>
                <td><?php echo e($ticket['status']); ?></td>
                <td><?php echo e(\Carbon\Carbon::parse($ticket['created_at'])->format('d M Y H:i')); ?></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="5">No tickets found</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
</div>
</div>


</div>
 </div>
    </section>




        <script>
$(document).ready(function() {
    $('#data-table').DataTable({
        "order": [[ 0, "desc" ]]
    });

 
});
</script>

<script>
    $(document).ready(function () {
        setTimeout(function () {
            location.reload();
        }, 50000); // 50000 ms = 50 seconds
    });
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>