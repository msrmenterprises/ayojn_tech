<style>
	.button-redirect {
		background-color: #7cd1f9;
		color: #fff;
		border: none;
		box-shadow: none;
		border-radius: 5px;
		font-weight: 600;
		font-size: 14px;
		padding: 10px 24px;
		margin: 0;
		cursor: pointer;
	}
</style>
<div class="modal" id="add-reason" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 45.56 45.559">
						<path id="Union_1" data-name="Union 1" class="cls-1"
							  d="M22.426,22.426,0,44.852,22.426,22.426,0,44.852,22.426,22.426,0,0,22.426,22.426,0,0,22.426,22.426,44.852,0,22.426,22.426,44.852,0,22.426,22.426,44.852,44.852,22.426,22.426,44.852,44.852ZM22.426,22.426Z"
							  transform="translate(0.354 0.354)"/>
					</svg>
				</button>
				<h4 id="bid_id" style="text-align: center;margin-bottom: 18px !important;">
					Please provide reason to spam this reason
				</h4>
			</div>
			<div class="modal-body">

				<form class="addBidForm" id="addBidForm" method="post" onsubmit="return false"
					  name="addBidForm">

					<div id="add-opporutnity-form">
						<br>
						<div class="row">
							<div class="col-md-4"></div>
							<div class="col-md-4">
								<input type="hidden" value="{{ $responseDetail->id }}" id="responseId"
									   name="responseId">
							</div>
						</div>
						<div class="row">
							<div class="col-md-2">
								Reason
							</div>
							<div class="col-md-10">
								<textarea class="form-control" id="response" placeholder="Write Reason"
										  name="response"></textarea>
							</div>
						</div>
					</div>
					<button type="submit" id="addBid" class="btn btn-default" onclick="submitForm()"
							style="text-align: center">Submit
					</button>
				</form>

			</div>

		</div>
	</div>
</div>


<script>
  function submitForm() {
    var responseId = $("#responseId").val();
    var response = $("#response").val();
    if (response != '' && responseId != '') {
      $.ajax({
        url: "{{ url('/') }}" + '/add-reason',
        type: "post",
        async: false,
        data: {responseId: responseId, response: response},
        headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
        success: function (data) {
          if (data.status) {

            swal('', data.message, 'success');
            $("#add-bid").modal('hide');
            $("#append-form").html('');
            setTimeout(function () {
              location.reload()
            }, 1500)
          } else {
            swal('', data.message, 'error');
            $("#append-form").html('');
          }
        },
        error: function (data) {
        },
      })
    } else {
      $("#add-bid").modal('hide');
      $("#append-form").html('');
      swal("Please enter reason");
    }


  }
</script>
