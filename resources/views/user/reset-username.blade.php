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
	<div style="width: 480px;
    margin: 12% auto;">
		<div class="row">
			<div class="col-md-12">
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
				<b> We will send a mail to the registered Email address.<br/></b>
				<form action="{{ url('/sent-username') }}" method="post">
					{!! csrf_field() !!}
					<span>Hint incase you forget Username or Password</span>
					<div class="form-group has-feedback">
						<input type="text" name="mother" class="form-control"
							   placeholder="Nick Name, First Job, Favourite Colour or anything" required>
						<span class="fa fa-user form-control-feedback"></span>
						@if ($errors->has('email'))
							<span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
						@endif
					</div>
					<span>Mobile No</span>
					<div class="form-group has-feedback ">
						<input type="text" name="mobile_no" class="form-control"
							   placeholder="Mobile No" required>
						<span class="fa fa-phone form-control-feedback"></span>
					</div>
					<button type="submit"
							class="forgot_button"
					>Send Username
					</button>
				</form>
			</div>
		</div>
	</div>
 <script src="{{ asset('js/jquery1.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>


    <script>
    $(function () {
        $('[data-tooltip="tooltip"]').tooltip();
        $('[data-tooltip="true"]').tooltip();
    });
    </script>
@endsection
