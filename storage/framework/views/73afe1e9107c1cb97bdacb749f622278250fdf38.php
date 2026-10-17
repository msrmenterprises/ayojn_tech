<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
    <?php  $buttons = $db_buttons['recommend'];  ?>

    <?php
        if(Auth::user()->sponsor_type == 3){
            $url = url('partner-refer').'/' .Auth::user()->refer_code;
        }
        else{
            $url = url('refer').'/' .Auth::user()->refer_code;
        }
    ?>
    <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Refer & Earn</h4>
                <h5 class="modal-title">Refer your clients, colleagues or anyone who may benefit using Ayojn. Share
                    the unique link, it comes with a USD <?php if(Auth::user()->sponsor_type != 3): ?> 30 <?php else: ?> 100 <?php endif; ?>
                    discount voucher for anyone who signs up using this link. In return you would earn 30% of the
                    service fees (in cash) whenever your referred users spend at Ayojn.</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-9">
                        <span id="refer_<?php echo e($userData->id); ?>" class="share-course-filed"
                              style="display: none"><?php echo e($url); ?> </span>

                        <input class="form-control" value="<?php echo e($url); ?>" disabled>
                    </div>
                    <div class="col-md-3">
                        <a href="javascript:void(0)" class="btn btn-primary read-more-btn  share-opp-btn-refer" data-tooltip="true" data-placement="top"  title="<?php echo $buttons[0]['hover']; ?>"
                           onclick="copyToClipboardRefered('#refer_<?php echo e($userData->id); ?>')"> <?php echo $buttons[0]['icon']; ?> <?php echo $buttons[0]['name']; ?> </a>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 mb-5" style=line-height:30px;>
                        <b>Referral Registered : <?php echo e($existingReferedUsers->count()); ?></b><br>
                        <b><?php echo e(config('sitevars.data.ayojn_marketplace')); ?> : <?php echo e(Auth::user()->wallet_marketplace); ?></b> &nbsp;&nbsp;
                        <a href="<?php echo e(url('redeem-request')); ?>" class="btn btn-primary btn-sm" data-tooltip="true" data-placement="top"  title="<?php echo $buttons[1]['hover']; ?>"><?php echo $buttons[1]['icon']; ?> <?php echo $buttons[1]['name']; ?></a> <br/>
                        <b> <?php echo e(config('sitevars.data.ayojn_reward')); ?> : <?php echo e(Auth::user()->wallet_balance); ?> points</b>(1 Point = 1 USD) <br>
                    </div>
                </div>
        <hr/>



        <div class="row">
            <div class="col-md-12">
                <h4 style="margin-bottom:10px;">Redeem Voucher</h4>
                <div class="form-group">
                    <input type="text" class="form-control" id="vouch_code" name="vouch_code"
                           placeholder="Enter your Voucher code here"> 
                           </div>
                           </div>
            <div class="col-md-12">
                <div class="form-group">
                    <a class="btn btn-primary" id="apply_refer_code">Apply</a>
                </div>  
            </div>
        </div>


                
                
                
                
                
                
                
                
                
                
                
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
<script>
    $(".share-opp-btn-refer").on('click', function (e) {
        toastr.success('Web Link Copied', 'Success');
    });

    $("#apply_refer_code").on('click', function (e) {
        e.preventDefault();
        var vouch_code = $("#vouch_code").val();
        if (vouch_code == '') {
            toastr.error('Please enter voucher code', 'Error');
            $("#vouch_code").focus();
            return false;
        }
        $.ajax({
            type: "POST",
            url: "<?php echo e(url('voucher-redeem')); ?>",
            data: {
                vouch_code: vouch_code,
                _token: '<?php echo e(csrf_token()); ?>'
            },
            success: function (response) {
                if (response.status == 'success') {
                    $('#myModal').modal('hide');
                    openreferafriend(); 
                    $("#vouch_code").val('');
                    toastr.success(response.msg, 'Success');
                    //location.reload();
                } else {
                    toastr.error(response.msg, 'Error');
                }
            },
            error: function (xhr, status, error) {
                toastr.error('An error occurred while applying the voucher.', 'Error');
            }
        });
    });


    function copyToClipboardRefered(element) {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($(element).text()).select();
        document.execCommand("copy");
        $temp.remove();
    }
</script>
