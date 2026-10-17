@extends('layout')

@section('content')
    <style>

        .forgot_button {
            height: 45px;
            width: 150px;
            text-align: center;
            line-height: 45px;
            margin-right: 0;
            border-radius: 40px;
            background: #52616D;
            color: #fff;
        }

    </style>
    <!-- Resources -->
	<div class="container">
		<br/><br/><br/>
			<div class="row" style="margin-top:40px !important; display: flex;
justify-content: center;">
				<div class="col-md-6">
					@if (session('message'))
						<div class="alert alert-danger">
							{{ session('message') }}
						</div>
					@endif
					@if (session('sucess_message'))
						<div class="alert alert-success">
							{{ session('sucess_message') }}
						</div>
					@endif
					<span> <h3 style="margin:0 !important;">Redeem Your points</h3></span>
				
					<span> <h5>Your Current Points : <strong>{{ Auth::user()->wallet_marketplace }}</strong></h5/></span>
					<span> <h5>Minimum 5 Points Required for Redemption(Transfer Charges may apply). </h5/></span>
					<form action="{{ url('/sent-request') }}" method="post">
						{!! csrf_field() !!}
						 <div class="row">
                                <div class="col-sm-9">
                                <select class="form-control" name="beneficiary_id" required style="border: solid 1px red;">
                                <option value="">Select Beneficiary</option>
                                @foreach($beneficiary as $beneficiaries)
                                    <option value="{{$beneficiaries->id}}">{{$beneficiaries->contact_name}}</option>
                                @endforeach
                          </select>  
                                </div>
                                <div class="col-sm-3" style="padding-left:0px;">
                                <a href="{{route('beneficiary-add')}}" class="btn btn-success "><i class="fa fa-user"></i> Beneficiary Add</a>
                                </div>
                            </div>
                            <br/>
                             <div class="row">
							<div class="col-sm-12">
						
						<div class="form-group has-feedback">
							<textarea type="text" name="notes" class="form-control"
									  placeholder="Please enter your bank details or paypal id for redemption." required></textarea>
						</div>
						@if(Auth::user()->wallet_marketplace == 0)
							<button type="button"
									class="btn btn-primary read-more-btn" onclick="requestRequest()"
							>Send Request
							</button>
						@else
							<button type="submit"
									class="btn btn-primary read-more-btn  share-opp-btn"
							>Send Request
							</button>
						@endif
					</div>
				</div>
					</form>
				</div>
			</div>
		
	</div>

	 <script src="{{ asset('js/jquery1.min.js') }}"></script>
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
	
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script>
		$(function(){
			 $('[data-tooltip="tooltip"]').tooltip();
        $('[data-tooltip="true"]').tooltip();
		});
        function requestRequest() {
            toastr.error('You have insufficient balance in wallet', "Error");
        }
    </script>
@endsection
