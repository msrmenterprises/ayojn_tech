<?php $__env->startSection('title', 'Ayojn'); ?>

<?php $__env->startSection('content'); ?>

<style>
.ticket-container {
    margin: auto;
    background: #fff;
    padding: 30px;
    border-radius: 10px;
}

.ticket-header h2 {
    margin-bottom: 15px;
}

.ticket-meta p {
    margin: 6px 0;
    font-size: 15px;
}

.badge {
    padding: 4px 10px;
    font-size: 12px;
    border-radius: 12px;
    color: #fff;
}

.status-user { background-color: #28a745; }
.status-agent { background-color: #17a2b8; }

.ticket-description {
    padding: 15px;
    background: #f8f9fa;
    border-left: 4px solid #007bff;
    margin-bottom: 25px;
}

.comments-section h3 {
    margin-bottom: 15px;
}

.comment {
    margin-bottom: 15px;
    padding: 12px 15px;
    background: #f1f3f5;
    border-radius: 8px;
}

.comment.agent {
    background: #d1ecf1;
    text-align: left;
}

.comment-time {
    font-size: 12px;
    color: #555;
}
</style>

<div class="container mt-4">

    <div class="ticket-container">

        
        <div class="ticket-header">
            <h2>Ticket #<?php echo e($ticket['id']); ?></h2>
        </div>

        
        <div class="ticket-meta">
            <p>
                <strong>Created At:</strong>
                <?php echo e($ticket['created_at']->format('d M Y h:i A')); ?>

            </p>

            <p>
                <strong>Opened By:</strong>
                <span class="badge status-user">Agent</span>
            </p>
        </div>

        
        <div class="ticket-description">
            <?php echo $ticket['message']; ?>

        </div>

        
        <div class="comments-section">
            <h3>Conversation</h3>

            <?php $__empty_1 = true; $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="comment <?php echo e($message['sender_type'] === 'agent' ? 'agent' : ''); ?>">
                    <strong>
                        <?php echo e($message['sender_type'] === 'user'
                            ? 'User'
                            : ($message['sender_name'] ?? 'Agent')); ?>

                    </strong>

                    <div class="comment-time">
                        <?php echo e($message['created_at']->format('d M Y h:i A')); ?>

                    </div>

                    <p><?php echo $message['message']; ?></p>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p>No messages found.</p>
            <?php endif; ?>
        </div>

        
        <div class="mt-4">
            <form method="POST" action="<?php echo e(route('agent.tickets.reply', $ticket['id'])); ?>">
                <?php echo csrf_field(); ?>
                <div class="form-group">
                    <textarea class="form-control" rows="4" name="message" placeholder="Type your reply..." required></textarea>
                </div>
                <button class="btn btn-primary mt-2">Send Reply</button>
            </form>
        </div>

    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>