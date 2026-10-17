<?php $__env->startSection('content'); ?>
<style>
.tooltip {
    width: 300px;
}
.input-group {
  display: table;
  border-collapse: collapse;
  width: 100%;
}

.input-group > * {
  display: table-cell;
  /* border: 1px solid #ddd; */
  vertical-align: middle;
  background-color: #e4e4e4;
  width: 20%;
}

.input-group-icon {
  background-color: #eee;
  color: #777;
  padding: 0 12px;
}

.input-group-area {
  width: 100%;
}

.swal2-container{
    z-index: 99999 !important;
}

    </style>
	<style>
		.error {
			color: red;
		}

		/*the container must be positioned relative:*/
		.autocomplete {
			position: relative;
			display: inline-block;
		}

		input {
			border: 1px solid transparent;
			background-color: #f1f1f1;
			padding: 10px;
			font-size: 16px;
		}

		input[type=text] {
			background-color: #f1f1f1;
			width: 100%;
		}

		input[type=submit] {
			background-color: DodgerBlue;
			color: #fff;
			cursor: pointer;
		}

		.autocomplete-items {
			position: absolute;
			border: 1px solid #d4d4d4;
			border-bottom: none;
			border-top: none;
			z-index: 99;
			/*position the autocomplete items to be the same width as the container:*/
			top: 100%;
			left: 0;
			right: 0;
		}

		.autocomplete-items div {
			padding: 10px;
			cursor: pointer;
			background-color: #fff;
			border-bottom: 1px solid #d4d4d4;
		}

		/*when hovering an item:*/
		.autocomplete-items div:hover {
			background-color: #e9e9e9;
		}

		/*when navigating through the items using the arrow keys:*/
		.autocomplete-active {
			background-color: DodgerBlue !important;
			color: #ffffff;
		}
	</style>
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <?php
    $f = Request::get('f');
    $cn = Request::get('c');
    $b = Request::get('b');
    $i = Request::get('i');

    use App\Country;use App\Industry;use App\SponsorrSpecify;use App\SponsorrSpecifyList;
    $countries = Country::all();
    $industries = Industry::all();
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
		<div class="container" style="text-align: center;padding-top: 15px;">

			<div class="row">
				<h3>Build Opportunities</h3>
			</div>
			<div class="row">
				<div class="col-md-3"></div>
				<div class="col-md-6">
					Let’s build opportunities based on your past, present & future expertise
				</div>
			</div>

			<br>
			<form name="add-opportunity" method="post" id="add-opportunity" action="<?php echo e(url('add-new-opportunity')); ?>">
				<div id="add-opporutnity-form">
					<div class="row">
						<div class="col-md-3"></div>
						<div class="col-md-6">
							<select class="form-group form-control opportunity_country" name="opportunity_country"
									onchange="changeEffect(); getCity(8)"
									id="opportunity_country" required>
								<option value="">Select Country</option>
								<?php if(!empty($countries)): ?>
									<?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
										<option value="<?php echo e($c->country_code); ?>"><?php echo e($c->country_name); ?></option>
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
								<?php endif; ?>
							</select>
						</div>
					</div>
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-6">
                            <select class="form-group form-control opportunity_country" name="opportunity_city" id="opportunity_city" required onchange="changeEffect()">
                                <option value="">Select City</option>
                            </select>
                        </div>
                    </div>
					<div class="row">
						<div class="col-md-3"></div>
						<div class="col-md-6">
							<select class="form-group form-control opportunity_industry" name="opportunity_industry"
									onchange="changeEffect()"
									id="opportunity_industry" required>
								<option value="">Select Industry</option>
								<?php $__currentLoopData = $industries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $in): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>{
								<option value="<?php echo e($in->id); ?>"><?php echo e($in->name); ?></option>
								}
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3"></div>
						<div class="col-md-6" >

            <div class="input-group" style="margin-bottom: 15px;">
              
              <input type="text" name="hashtags" style="text-transform:uppercase; z-index:0;" class="form-group form-control hashtag" placeholder="#BuildAnOpportunity" id="hashtags" required readonly>
              <div class="input-group-prepend" style="display: none;">
                <div class="input-group-text">
                  <input type="checkbox" aria-label="Checkbox for following text input" class="reserved" name="reserved"> Reserved 
                  <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" title="Lorem ipsum is a dummy or placeholder text commonly used in graphic design, publishing, and web development. Its purpose is to permit a page layout to be designed, independently of the copy that will subsequently populate it"></i>
                </div>
              </div>
            </div>

            <span class="msg" style="display:none;">Available</span>
						
						</div>
					</div>


          <div class="row year_row" style="display: none;">
						<div class="col-md-3"></div>
						<div class="col-md-6 " >
							<select class="form-control reserved_year" name="reserved_year">
                <option value="">--Select Year--</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
              </select>
						</div>
					</div>


				</div>

      
          <input type="hidden" name="amount" value="5">
       
        <br/>
				<div class="row">
					<div class="col-md-12">
						<button type="submit" class="btn btn-primary submit" disabled>Submit
						</button> &nbsp;
						
						
					</div>
				</div>

				<input type="hidden" id="counter" name="counter" value="1">
			</form>
			<br>
		</div>
		<div id="myModal" class="modal fade" role="dialog">
			<div class="modal-dialog">

				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-body">
						<br>
						<br>
						<p>Great to see you launch a new opportunity, wait for a while for the vouch from the clients.
							We will notify you once the first vouch comes in.</p>
						<br>
						<!--<p>In the meanwhile let's prepare you to build a winning proposal using <a
								href="https://www.proposify.com/" target="_blank">Proposify.</a></p> -->

						<br>
						<a href="<?php echo e(url('opportunity')); ?>" class="btn btn-primary">Go to My Opportunities</a>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>

			</div>
		</div>
	</section>



    <div class="modal" id="paynow_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false" data-backdrop="static" style="z-index:9999;">
      <div class="modal-dialog modal-dialog-centered modal-sm" role="document" style="width:450px;">
        <div class="modal-content">
          <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title" id="myModalLabel" style="text-align: center">Payment Details</h4>
          
          </div>
          <div class="modal-body">


          <div id="wallet_row" class="form-group autocomplete" style="width: 100%; display: block; margin-left: auto; margin-bottom:10px; margin-right: 0;">
                    <label>
                    
                    <input type="checkbox" name="list" value="" class="click_wallet form-control" style="height: 16px; width: 16px; margin-top: 3px; position: absolute;" />
                    <span style="margin-left: 25px;">Pay via Wallet</span>
                    <input type="hidden" id="wallet_use" name="wallet_use" value="0" />
                    </label>
                    <label class="pull-right" id="wallet_balance">
                    
                    <?php echo e(Auth::user()->wallet_balance); ?>

                    </label>
          </div>


                <div id="voucher_row" class="form-group autocomplete" style="width: 100%; display: block; margin-left: auto; margin-bottom:30px; margin-right: 0;">
                    <label>
                    <input type="checkbox" name="list" value="" class="click_voucher form-control" style="height: 16px; width: 16px; margin-top: 3px; position: absolute;" />
                    <span style="margin-left: 25px;">Pay via Voucher</span>
                    </label>
                    <label id="voucher_td" style="display: inline-block; float: right; width: 50%;">
                        <div class="input-group" style="position:absolute;     width: 50%;">
                        <input type="search" class="form-control voucher_textbox" id="voucher_code" placeholder="Voucher code" >
                        <span class="input-group-btn">
                        <input type="hidden" id="vouch_amount" />
                        <button class="btn btn-primary voucher_button" type="button" >
                        </span> <i class='fa fa-thumbs-o-up'></i> Apply
                        </button>
                        
                        </div>

                    
                    <button class="btn btn-success btn-sm"><i class='fa fa-thumbs-o-up'></i> Apply</button>    
                </label>
          </div>


                <hr style="margin-top: 5px;  margin-bottom: 10px;"/>
            <div class="form-group autocomplete" style="margin-bottom: 0px; width: 100%; display: block; margin-left: auto; margin-right: 0;">
                <label>
                  <strong>Final Paid Amount</strong>
                </label>
                <label class="pull-right">
                  <span id="paidamt">
                                  5
                                </span> 
                </label>
                
            </div>

            <hr style="margin-top: 5px;"/>
            <div class="form-group autocomplete text-center">
            <a href="javascript:void(0)" class="btn btn-primary button-primary-color paynowfinal" data-bid=""> Pay Now</a>
                    
                </div>

          </div>
        </div>
      </div>
    </div>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.min.js"></script>
  <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
  <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
   
	<?php if(session()->has('success_messagesss')): ?>
		<script>
          setTimeout(function () {
            $("#myModal").modal('show');
          }, 2000);
		</script>
	<?php endif; ?>
	<script>

      $('input[name=hashtags]').on('change',function(e){
        var hashtags = $(this).val();
        if(hashtags !=''){
          $.ajax({
          type: 'POST',
          url: '<?php echo e(url('hashtags-check')); ?>',
          headers: {"X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"},
          data: {
            hashtags: hashtags,
          },
          success: function (response) {
            console.log(response.status);
            var data = response;
            if (data.status) {
              $(".msg").attr('style','color:green').html('Hashtag is Available').show();
              toastr.success(response.message, "Success");
              $(".input-group-prepend").show();
              $(".submit").removeAttr("disabled");
             // window.location.reload();
            } else {
              $(".msg").attr('style','color:red').html('Hashtag not Available').show();
              toastr.error(response.message, "Error");
              $(".input-group-prepend").hide();
              $(".submit").attr("disabled","disabled");
            }

          },
          error: function (response) {
            toastr.error(response.responseJSON.msg, "Error");
          },
        });
        }
        
      });
      

      $(".reserved").click(function () {
        if ($(this).is(':checked')) {
         $(".year_row").show();
         $(".year_row").find("select").attr("required", true);
         $(".msg").hide();
        } else {
          $(".year_row").hide();  
          $(".year_row").find("select").attr("required", false);
          $(".msg").show();
        } 
      });
      



      $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
        $('#add_fields').click(function () {
          add_inputs()
        });

        $(document).on('click', '.remove_fields', function () {
          $(this).closest('.record').remove();
        });

        function add_inputs() {
          var counter = parseInt($('#counter').val());
          var html = '<hr ><span class="record"><div class="row">' +
            '<div class="col-md-3"></div>' +
            '<div class="col-md-6">' +
            '<input type="text" class="form-group form-control hashtag" name="hashtags[' + counter + ']" placeholder="BuildAnOpportunity " required>' +
            '</div>' +
            '</div>' +
            '<div class="row">' +
            '<div class="col-md-3"></div>' +
            '<div class="col-md-6">' +
            '<select class="form-group form-control opportunity_country" name="opportunity_country[' + counter + ']" required>' +
            '<option value="">Select Country</option>';
            <?php
                if(! empty($countries)) {
                foreach($countries as $c) {?>
              html += '<option value="<?php echo e($c->country_code); ?>" ><?php echo e($c->country_name); ?></option>';
            <?php }
                }
                ?>
              html += '</select>' +
              '</div>' +
              '</div>' +
              '<div class="row">' +
              '<div class="col-md-3"></div>' +
              '<div class="col-md-6">' +
              '<select class="form-group form-control opportunity_industry" name="opportunity_industry[' + counter + ']" required>' +
              '<option value="">Select Industry</option>';
            <?php
                if(! empty($industries)) {
                foreach($industries as $in){ ?>
              html += '<option value="<?php echo e($in->id); ?>"><?php echo e(trim($in->name)); ?></option>';
            <?php }
                }
                ?>
              html += '</select>' +
              '</div><div class="col-md-3"><a title="Add More" class=" remove_fields btn btn-danger">' +
              'Remove</a></div>' +
              '</div> </span>';


          // var html = '<div class="record"><input type="text" placeholder="Name" name="name_' + counter + '" class="name_input"><input type="email" name="email_' + counter + '" placeholder="Email" class="email_input"><button type="button" class="remove_fields">-</button></div>';

          $('#add-opporutnity-form').append(html);
          $('#counter').val(counter + 1);
        }
      });
      $("#add-opportunity").validate({
          submitHandler: function(form) {
                $("#paynow_modal").modal('show');
                $("#voucher_row #voucher_code, #voucher_row .voucher_button").prop("disabled", true);
                return false;
        }
      });

      function displayEmail(email) {
        swal(email);
      }

      function OpenPopup(bidId) {
        $("#bid_id").text("#" + bidId);
        $("#bid_input_id").val(bidId);
        $("#add-bid").modal('show');
      }

      function addBid() {
        var bid_id = $("#bid_input_id").val();
        var description = $("#description").val();
        var portfolio = $("#portfolio").val();
        $.ajax({
          type: 'POST',
          url: '<?php echo e(url('add-bid-response')); ?>',
          headers: {"X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"},
          data: {
            bid_id: bid_id,
            description: description,
            portfolio_link: portfolio,
          },
          success: function (response) {
            //console.log(response.status);
            var data = response;
            if (data.status) {
              toastr.success(response.message, "Success");
              window.location.reload();
            } else {
              toastr.error(response.errors, "Error");
            }


            // link to page on clicking the notification

            //}

          },
          error: function (response) {
            toastr.error(response.responseJSON.msg, "Error");
            $(':input[type="submit"]').prop('disabled', false);
          },
        });

      }
	</script>
	<script src="<?php echo e(asset('js/jquery1.min.js')); ?>"></script>
	<script src="<?php echo e(asset('js/bootstrap.min.js')); ?>"></script>
	<script src="<?php echo e(asset('js/typed.min.js')); ?>"></script>
	<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
	<script>
      /*An array containing all the country names in the world:*/
      var Hashtags = [];

      function changeEffect() {
        var countryId = $("#opportunity_country").val();
        var industryId = $("#opportunity_industry").val();
        var opportunity_city = $("#opportunity_city").val();
        if (countryId != '' && industryId != ''&& opportunity_city != '') {

          $("#hashtags").attr("readonly", false);
          $.ajax({
            type: 'GET',
            url: '<?php echo e(url('get-hashtags')); ?>',
            headers: {"X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"},
            data: {
              countryId: countryId,
              industryId: industryId,
                opportunity_city: opportunity_city,
            },
            success: function (response) {
              //console.log(response.status);

              if (response.hashTags != '') {
                Hashtags = response.hashTags.split(',');
              }
              autocomplete(document.getElementById("hashtags"), Hashtags);
            },
            error: function (response) {
            },
          });
        }
      }

      function autocomplete(inp, arr) {
        /*the autocomplete function takes two arguments,
		the text field element and an array of possible autocompleted values:*/
        var currentFocus;
        /*execute a function when someone writes in the text field:*/
        inp.addEventListener("input", function (e) {
          var a, b, i, val = this.value;
          /*close any already open lists of autocompleted values*/
          closeAllLists();
          if (!val) {
            return false;
          }
          currentFocus = -1;
          /*create a DIV element that will contain the items (values):*/
          a = document.createElement("DIV");
          a.setAttribute("id", this.id + "autocomplete-list");
          a.setAttribute("class", "autocomplete-items");
          /*append the DIV element as a child of the autocomplete container:*/
          this.parentNode.appendChild(a);
          /*for each item in the array...*/
          for (i = 0; i < arr.length; i++) {
            /*check if the item starts with the same letters as the text field value:*/
            if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
              /*create a DIV element for each matching element:*/
              b = document.createElement("DIV");
              /*make the matching letters bold:*/
              b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
              b.innerHTML += arr[i].substr(val.length);
              /*insert a input field that will hold the current array item's value:*/
              b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
              /*execute a function when someone clicks on the item value (DIV element):*/
              b.addEventListener("click", function (e) {
                /*insert the value for the autocomplete text field:*/
                inp.value = this.getElementsByTagName("input")[0].value;
                /*close the list of autocompleted values,
				(or any other open lists of autocompleted values:*/
                closeAllLists();
              });
              a.appendChild(b);
            }
          }
        });
        /*execute a function presses a key on the keyboard:*/
        inp.addEventListener("keydown", function (e) {
          var x = document.getElementById(this.id + "autocomplete-list");
          if (x) x = x.getElementsByTagName("div");
          if (e.keyCode == 40) {
            /*If the arrow DOWN key is pressed,
			increase the currentFocus variable:*/
            currentFocus++;
            /*and and make the current item more visible:*/
            addActive(x);
          } else if (e.keyCode == 38) { //up
            /*If the arrow UP key is pressed,
			decrease the currentFocus variable:*/
            currentFocus--;
            /*and and make the current item more visible:*/
            addActive(x);
          } else if (e.keyCode == 13) {
            /*If the ENTER key is pressed, prevent the form from being submitted,*/
            e.preventDefault();
            if (currentFocus > -1) {
              /*and simulate a click on the "active" item:*/
              if (x) x[currentFocus].click();
            }
          }
        });

        function addActive(x) {
          /*a function to classify an item as "active":*/
          if (!x) return false;
          /*start by removing the "active" class on all items:*/
          removeActive(x);
          if (currentFocus >= x.length) currentFocus = 0;
          if (currentFocus < 0) currentFocus = (x.length - 1);
          /*add class "autocomplete-active":*/
          x[currentFocus].classList.add("autocomplete-active");
        }

        function removeActive(x) {
          /*a function to remove the "active" class from all autocomplete items:*/
          for (var i = 0; i < x.length; i++) {
            x[i].classList.remove("autocomplete-active");
          }
        }

        function closeAllLists(elmnt) {
          /*close all autocomplete lists in the document,
		  except the one passed as an argument:*/
          var x = document.getElementsByClassName("autocomplete-items");
          for (var i = 0; i < x.length; i++) {
            if (elmnt != x[i] && elmnt != inp) {
              x[i].parentNode.removeChild(x[i]);
            }
          }
        }

        /*execute a function when someone clicks in the document:*/
        document.addEventListener("click", function (e) {
          closeAllLists(e.target);
        });
      }


      /*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/
	</script>
	<script>

      $(document).ready(function () {
        $('#table_id').DataTable();
      });
	</script>

  <script>
     $(".click_wallet").click(function(){
            

            check = $(".click_wallet").prop("checked");
            if(check) 
            {
              
                if(<?php echo e(Auth::user()->wallet_balance); ?> > 5)
                {
                    var chk = parseInt($("#paidamt").html());
                    var pamt = <?php echo e(Auth::user()->wallet_balance); ?>-chk;
                    $("#wallet_balance").html(pamt);
                    $("#paidamt").html(0);
                    $("#wallet_use").val(pamt);
                    
                    var chkagain = parseInt($("#paidamt").html());
                    if(chkagain==0)
                    {
                        $("#voucher_row #voucher_code, #voucher_row .voucher_button").prop("disabled", true);
                        
                    }
                    
                    
                }
                else{
                    var chk = parseInt($("#paidamt").html());
                    var pamt = chk-<?php echo e(Auth::user()->wallet_balance); ?>;
                    $("#wallet_balance").html(0);
                    $("#paidamt").html(pamt);
                    $("#wallet_use").val(<?php echo e(Auth::user()->wallet_balance); ?>);
        
                
                }
            }
            else
            {
                var chk = parseInt($("#paidamt").html());
                if(<?php echo e(Auth::user()->wallet_balance); ?> > 5)
                {
                    $("#paidamt").html(5);
                }
                else{
                    $("#paidamt").html(chk+<?php echo e(Auth::user()->wallet_balance); ?>);
                }
                $("#wallet_balance").html(<?php echo e(Auth::user()->wallet_balance); ?>);
              
                $("#wallet_use").val(0);
            }
                
        });

            
        $(".click_voucher").click(function(){

            check = $(".click_voucher").prop("checked");
            if(check) {
                $("#voucher_td :input").prop("disabled", false);
                $(".voucher_button").click(function(){
                    var code = $(".voucher_textbox").val();
                    if(code ==""){alert('please enter voucher code'); $(".voucher_textbox").focus(); return false;}
                    swal({
                    title: "Are you sure",
                    text: "You want to apply this code",
                    icon: "warning",
                    buttons: true,
                    }).then(
                        function (isConfirm) {
                            if (isConfirm) 
                            {
                                
                                $.ajax({
                                    url: "<?php echo e(route('check-vouch-code')); ?>",
                                    type: 'POST',
                                    data: {code: code},
                                    headers: {
                                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                                    },
                                    success: function (data) {
                                        if(data > 0){
                                            $("#vouch_amount").val(data);
                                            swal("Success", "Voucher has been applied", "success");
                                            var pamt = $("#paidamt").html();
                                            console.log(data);
                                            console.log(pamt);
                                            if(data < parseInt(pamt)){
                                                var fval = pamt-data;
                                            }
                                            else{
                                                var fval = 0;
                                            }
                                            
                                            $("#paidamt").html(fval);
                                            if(pamt==0)
                                            {
                                                $("#wallet_row :input").prop("disabled", true);
                                                $("#gateway_row :input").prop("disabled", true);
                                            }
                                            $("#voucher_td :input").prop('disabled',true);
                                        }
                                        else{
                                            swal("Warning", "voucher not exist or used", "warning");
                                        }
                                        
                                    },
                                    error: function () {
                                        swal("error", "There's been an error", "error");
                                    }
                                });


                            }
                        });

                
                });
            }
            else{
                var chk = parseInt($("#paidamt").html());
                var vamt = parseInt($("#vouch_amount").val());
                $("#voucher_td :input").prop("disabled", true);
                $(".voucher_textbox").val('');
                if($(".click_wallet").is(':checked')) 
                {
                    var plusvalue = chk+vamt;
                    var finalvalue = plusvalue-<?php echo e(Auth::user()->wallet_balance); ?>

                    $("#paidamt").html(finalvalue);
                }
                if($(".click_voucher").is(':checked')) 
                {
                    $("#paidamt").html(chk+vamt);
                }
                else{
                    $("#paidamt").html(5);
                }
                
                $("#wallet_row :input").prop("disabled", false);
                $("#gateway_row :input").prop("disabled", false);
                var vamt = parseInt($("#vouch_amount").val(0));
            }
        });



        $(document).on('click', '.paynowfinal', function (e) {

            $(".loader ").show();
            var amt = $("#paidamt").html();
            var voucher_code = $("#voucher_code").val();
            var opportunity_country = $("#opportunity_country").val();
            var opportunity_city = $("#opportunity_city").val();
            var opportunity_industry = $("#opportunity_industry").val();
            var hashtags = $("#hashtags").val();
            var reserved = $(".reserved").prop("checked");
            var reserved_year = $(".reserved_year").val();

            
            if(reserved){
                var reserved = 1;
            }else{
                var reserved = 0;
            }
            if($(".click_wallet").prop("checked"))
            {
                var wallet = $("#wallet_use").val();
            }
            else{
                var wallet = 0;
            }
           
            $.ajax({
                    url: "<?php echo e(route('add-new-opportunity-paynow')); ?>",
                    type: 'POST',
                    data: {wallet:wallet, amount: amt, voucher_code: voucher_code, opportunity_country:opportunity_country,opportunity_city:opportunity_city,opportunity_industry:opportunity_industry,hashtags:hashtags,reserved:reserved, reserved_year:reserved_year},
                    headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
                    success: function (data) {
                    console.log(data);
                    if(data['flag'] == 1)
                    {   
                        if(data['link'] !="")
                        {
                            window.location.replace(data['link']);
                        }else{

                            alert("Opportunities created successfully");
                            location.reload();
                        }
                        
                    }
                    
                    },
                    error: function () {
                    alert("something went wrong!");
                    }
                });

            });
  </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>