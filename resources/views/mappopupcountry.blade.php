<div class="modal" id="topCountriesForm">
	<div class="modal-dialog">
		<div class="modal-content">

			<!-- Modal Header -->
			<!--   <div class="modal-header">
				   <h4 class="modal-title">Modal Heading</h4>
				   <button type="button" class="close" data-dismiss="modal">&times;</button>
			   </div>-->

			<!-- Modal body -->
			<div class="modal-body">

				<div class="row">
					<?php
                    $color_array = array('progress-bar-info', 'progress-bar-warning', 'progress-bar-danger','progress-bar-success');
                    $x=0;
					?>
					@if(!empty($contry_userData))
						@foreach($contry_userData as $country_data)
							<?php  $x++;
                                $class = $color_array[$x%4];
							?>
						<div class="col-md-12 col-lg-12 align-self-center">
							<p class="text-muted font-13 mb-1">
								<i class="mdi mdi-checkbox-blank-circle mr-2 text-success"></i>{{ $country_data->counties->country_name.' (based on '.$country_data->user_count.' inputs)' }}   <span class="float-right">  </span>
							</p>
							<div class="progress" >
								<div class="progress-bar <?= $class ?> progress-bar-striped" role="progressbar" aria-valuenow="{{ $country_data->user_count }}" aria-valuemin="0" aria-valuemax="100" style="width:{{ $country_data->user_count }}%">

								</div>
							</div><br>


						</div>
						@endforeach
					@else
						<div class="col-md-12 col-lg-12 align-self-center">
							No data Found For this Country
						</div>
				@endif
				<!--<div id="bardiv"></div>-->
				</div>

			</div>


			<!-- Modal footer -->
			<div class="modal-footer">
				<button type="button" onclick="closePopup()" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>

		</div>
	</div>
</div>