<?php  $buttons = $db_buttons['recieve_my_bid'];  ?>
<?php $__env->startSection('content'); ?>
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <?php
    use App\Country;
    $countries = Country::all();
    use App\Industry;
    $industries = Industry::all();
    use App\SponsorrSpecify;
    use App\SponsorrSpecifyList;
    $sponsorrlist = SponsorrSpecifyList::where('sponsorr_type', Auth::user()->sponsor_for)->get();
    $userwisesponsor = SponsorrSpecify::where('user_id', Auth::user()->id)->get();
    $userwisesponsorarray = [];
    if (count($userwisesponsor) > 0) {
        foreach ($userwisesponsor as $usersponsorr) {
            $userwisesponsorarray[] = $usersponsorr->specify_name;
        }
    }?>
	<!-- banner section start -->


	<br><br><br><br>
	<section id="feed" class="speakers">
		<div class="container">
			<a href="<?php echo e(url('bid')); ?>" class="btn btn-primary" data-tooltip="true" data-placement="top"  title="<?php echo $buttons[0]['hover']; ?>"
> <?php echo $buttons[0]['icon']; ?> <?php echo $buttons[0]['name']; ?> </a>
			<h4 style="text-align: center"> My Bids</h4>

			<br>
			<div class="row">
				<table id="table_id" class="table table-striped display">
					<thead>
					<tr>
						<th width="10%">Response Id</th>
						<th width="10%">Bid Id</th>
						<th width="10%">Sponsor For</th>
						<th width="10%">Budget</th>
						<!--<th width="30%">Description</th> -->
						<th width="10%">Link</th>
						<th width="10%">Date</th>
						<th width="10%">Action</th>
					</tr>
					</thead>
					<tbody>

					<?php if(!empty($bids->first())): ?>
						<?php $__currentLoopData = $bids; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $response): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

							<tr>
								<td><?php echo e($response->id); ?></td>
								<td><?php echo e($response->bid->id); ?></td>
								<td><?php echo e($response->bid->sponsor_for); ?></td>
								<td><?php echo e($response->bid->sponsor_budget); ?></td>
								<!--<td><?php echo e($response->description); ?></td> -->
								<td><?php echo e($response->portfolio_link); ?></td>
								<td><?php echo e(date('Y-m-d',strtotime($response->created_at))); ?></td>
								<td>
									<a href="javascript:void(0)" title="Read More" class="read-more" data-value="<?php echo e($response->id); ?>" data-tooltip="true" data-placement="top"  title="<?php echo $buttons[1]['hover']; ?>"
> <?php echo $buttons[1]['icon']; ?> <?php echo $buttons[1]['name']; ?> </a>
								</td>


							</tr>

						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <?php else: ?>
            <tr>
              <td colspan="7" align="center">
                <b>
                  No Data found
                </b>
              </td>
            </tr>
					<?php endif; ?>
					</tbody>
				</table>
			</div>

		</div>
		<div id="html"></div>
	</section>

  <script src="<?php echo e(asset('js/jquery1.min.js')); ?>"></script>

	<script src="<?php echo e(asset('js/bootstrap.min.js')); ?>"></script>
	<script src="<?php echo e(asset('js/typed.min.js')); ?>"></script>
	<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
	<script>

      // $(document).ready(function () {
      //   $('#table_id').DataTable();
      // });
	</script>

  <script type="text/javascript">
   
    $(".read-more").click(function () {
    
        response_id = $(this).data("value");
        $.ajax({
          type: 'GET',
          headers: {"X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"},
          url: '<?php echo e(url('get-bid-response')); ?>/' + response_id,

          cache: false,
          processData: false,
          success: function (response) {
            $("#html").html(response.html);
            $("#read-more-response").modal('show');
          },
          error: function (response) {
            toastr.error(response.responseJSON.msg, "Error");
            $(':input[type="submit"]').prop('disabled', false);
          },
        });
      });
    </script>

	<script>
      
      
      
      
      

      
      
      
      
      
      
      
      
      
      
      
      
      
      
      

      


      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      


      

      

      
      
      
      
      
      

      
	</script>
	
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>