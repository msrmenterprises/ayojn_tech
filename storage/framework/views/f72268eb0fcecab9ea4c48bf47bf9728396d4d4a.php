<?php  $buttons = $db_buttons['directpay_add'];  ?>
<?php $__env->startSection('content'); ?>

<!-- <?php
echo session()->get('payment_token')."<br/><br/>".session()->get('payment_refresh_token');
?>  -->

<style>
    .input-group {
    position: relative;
    display: -webkit-box;
    display: -ms-flexbox;
        margin-bottom:20px;
    -ms-flex-wrap: wrap;
    /* flex-wrap: wrap; */
    /* -webkit-box-align: stretch; */
    -ms-flex-align: stretch;
    /* align-items: stretch; */
    width: 100%;
}
.input-group-prepend {
    margin-right: -1px;
}
.input-group-text {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    padding: 0.8rem .75rem;
    margin-bottom: 0;
    font-size: 13px;
    font-weight: 400;
    line-height: 1.5;
    color: #495057;
    text-align: center;
    white-space: nowrap;
    background-color: #e9ecef;
    border: 1px solid #ced4da;
    border-radius: .25rem;
}
.input-group>.custom-file {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex
;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
}
.input-group>.custom-file, .input-group>.custom-select, .input-group>.form-control {
    position: relative;
    -webkit-box-flex: 1;
    -ms-flex: 1 1 auto;
    flex: 1 1 auto;
    width: 1%;
    margin-bottom: 0;
}
.custom-file {
    position: relative;
    display: inline-block;
    width: 100%;
    height: calc(3.45rem + 2px);
    margin-bottom: 0;
}
.custom-file-input {
    position: relative;
    z-index: 2;
    width: 100%;
    height: calc(3.45rem + 2px);
    margin: 0;
    opacity: 0;
}
.input-group>.custom-file:not(:first-child) .custom-file-label, .input-group>.custom-file:not(:first-child) .custom-file-label::before {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}
.custom-file-label {
    position: absolute;
    top: 0;
    text-align: left;
    right: 0;
    left: 0;
    z-index: 1;
    height: calc(3.45rem + 2px);
    padding: 0.8rem .75rem;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    border: 1px solid #ced4da;
    border-radius: .25rem;
}
.custom-file-input:lang(en)~.custom-file-label::after {
    content: "Browse";
}
.custom-file-label::after {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    z-index: 3;
    display: block;
    height: calc(calc(3.45rem + 2px) - 1px* 2);
    padding: 0.8rem .75rem;
    line-height: 1.5;
    color: #495057;
    content: "Browse";
    background-color: #e9ecef;
    border-left: 1px solid #ced4da;
    border-radius: 0 .25rem .25rem 0;
}

.notification{
    margin-bottom: 20px;
    text-align: left;
    margin-left: 20px;
    color: red;

}
</style>

<div class="container">

<div class="row">

    

<div class="col-xs-12 col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3"  style="margin-top:50px;">

<?php if($errors->any()): ?>
        <div class="notification is-danger">
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

                    <div class="panel panel-default">
                        <!-- Default panel contents -->
                        <div class="panel-heading">Direct Payment</div>
                        <div class="panel-body text-center">
                        <form action="<?php echo e(route('offline-payment-direct')); ?>" id="gds_form" method="POST" class="text-center" enctype="multipart/form-data"> 
                        <!--form action="<?php echo e(route('offline-payment')); ?>" method="POST"-->  
                            <div class="row">
                                <div class="col-sm-9">
                                <select class="form-control" name="beneficiary_id" required style="border: solid 1px red;">
                                <option value="">Select Beneficiary</option>
                                <?php $__currentLoopData = $beneficiary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $beneficiaries): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($beneficiaries->id); ?>"  <?php if(old('beneficiary_id') == $beneficiaries->id): ?> <?php echo e('selected'); ?> <?php endif; ?>><?php echo e($beneficiaries->contact_name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                          </select>  
                                </div>
                                <div class="col-sm-3" style="padding-left:0px;">
                                <a href="<?php echo e(route('beneficiary-add')); ?>" class="btn btn-success" data-tooltip="true" data-placement="top"  title="<?php echo $buttons[0]['hover']; ?>"> <?php echo $buttons[0]['icon']; ?> <?php echo $buttons[0]['name']; ?> </a>
                                </div>
                            </div>
                            <br/>
                        <select id="options" class="form-control" name="purpose" required style="border: solid 1px red;">
                            <option value="">Select Purpose</option>
                            <?php $__currentLoopData = $purpose; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $purposes): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($purposes['id']); ?>"><?php echo e($purposes['name']); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            <?php if(!$purpose): ?>
                            <option value="5">Others</option>
                            <?php endif; ?>
                        </select>
                        <br/>
                        <input type="number" name="amount" value="<?php echo e(old('amount')); ?>" placeholder="Enter Amount" class="form-control" required style="border: solid 1px red;"/>
                        <br/>

                        <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Upload Receipt</span>
                        </div>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="receipt" id="inputGroupFile01">
                            <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                        </div>
                        </div>
                        <button type="submit" class="btn btn-primary" data-tooltip="true" data-placement="top"  title="<?php echo $buttons[1]['hover']; ?>"><?php echo $buttons[1]['icon']; ?> <?php echo $buttons[1]['name']; ?></button>
                        <!-- <input type="submit" class="btn btn-primary" value="pay Now " /> -->
                        </form>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row text-center">
            <?php if($response !=''): ?>
               <h3 style="color:red;"> <?php echo e($response['detail']['message']?? $response['message']); ?> </h3>
            <?php endif; ?>
            </div>

</div>


    <script src="<?php echo e(asset('js/jquery1.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/bootstrap.min.js')); ?>"></script>
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script>

    document.querySelector('.custom-file-input').addEventListener('change', function (e) {
        var fileName = document.getElementById("inputGroupFile01").files[0].name;
        var nextSibling = e.target.nextElementSibling;
        nextSibling.innerText = fileName;
    });

    
$(document).ready(function () {

$.ajax({
        type: 'get',
        dataType: "json",
        url: '<?php echo e(url('purposedropdown')); ?>',
        headers: {"X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"},
        success: function (response) {
           console.log(response.details);
         
           var select = $('#options');
           $.each(response.details, function(i,item) {
            select.append( '<option value="'
                 + item.id
                 + '">'
                 + item.name
                 + '</option>' ); 
            });

                },
               
            });
            

        });

$('#gds_form').submit(function() {
    var pass = true;
    if(pass == false){
        return false;
    }
    $(".loader").show();
    return true;
});


</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>