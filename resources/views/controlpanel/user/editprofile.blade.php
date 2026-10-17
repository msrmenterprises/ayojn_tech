@extends('adminlte::page')

@section('title', 'Ayojn')

@section('content_header')
	<h1>Edit User</h1>
@stop

@section('content')
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">Edit User</h3>
		</div>
		@if(session()->has('success'))
			<div class="alert alert-success">
				{{ session()->get('success') }}
			</div>
		@endif
		@if ($errors->any())
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif
		<!-- /.box-header -->
		<!-- form start -->

		<form role="form"  method="POST" name="editsubuser" id="editsubuser" action="{{ route('updateprofile') }}" enctype="multipart/form-data">
			{{ csrf_field() }}
			<input type="hidden" id="userid" name="userid" value="{{ $user->id }}">
			<div class="box-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="username" >Full Name <b style="color: red">*</b></label>
							<input type="text" class="form-control" id="username" name="username" value="{{ @$user->name }}" placeholder="Enter Full Name">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="email_id" >Email ID <b style="color: red">*</b></label>
							<input type="email" class="form-control" id="email" name="email" value="{{ @$user->email }}"  placeholder="Enter Email Id" readonly>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="contact_number" >Contact Number <b style="color: red">*</b></label>
							<input type="text" class="form-control" id="phone_no" name="phone_no" value="{{ @$user->phone_no }}" placeholder="Enter Contact Number">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="gender" >Gender </label>

							<select class="form-control select2" id="gender" name="gender"
									style="width: 100%;" onchange="changedate(this)">
								<option value="">Select Gender</option>
								<option value="Male" selected>Male</option>
								<option value="Female">Female</option>
							</select>

						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="username" >Company Name</label>
							<input type="text" class="form-control" id="company_name" name="company_name" value="{{ @$user->company_name }}" placeholder="Enter Company Name">
						</div>
					</div>
					<div class="col-md-6">
						<label for="profile_image" >Profile Image</label><br>
						<input type="file" class="form-control" id="profile_image" name="profile_image" onchange="ImagePreview(this)">
						<p class="error-block"> {{ $errors->first('profile_image', ':message') }}</p>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="address">Address</label>
							<textarea class="form-control" cols="3" rows="5" name="address" id="address">{{ $user->address }}</textarea>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="address">About Me</label>
							<textarea class="form-control" cols="3" rows="5" name="about_me" id="about_me">{{ $user->about_me }}</textarea>
						</div>
					</div>
				</div>
			</div>
			<!-- /.box-body -->
			<div class="box-footer">
				<button type="submit" class="btn btn-info pull-right" id="adduserbutton" name="adduserbutton">Update User <i class="fa fa-refresh fa-spin upload_spin" style="display: none" ></i></button>
				<a href=""  class="btn btn-danger pull-right">Cancel</a>
			</div>
		</form>
	</div>
<script>
	jQuery.validator.addMethod("nameValid", function (value, element) {
		return this.optional(element) || /^[a-zA-Z\s]+$/.test(value); });
	jQuery.validator.addMethod("phoneValid", function (value, element) {
		return this.optional(element) || /^[0-9]+$/.test(value); });
	jQuery.validator.addMethod("alphanumeric1", function(value, element) {
		return this.optional(element) || /^(?=.*[0-9])(?=.*[a-zA-Z])([a-zA-Z0-9-!@#$%&*?_]+)$/.test(value);
	});
	$("#editsubuser").validate({
		rules: {
			username: {
				required: true,
				nameValid:true
			},
			phone_no: {
				required: true,
				rangelength: [6, 16],
				phoneValid:true
			},
			email: {
				required: true,
				pattern: /^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i
			},
			profile_image: {
				extension: "image|mimes:jpeg,png,jpg,gif,svg"
			},
		},
		messages: {
			username: {
				//required:"Full Name is required",
				nameValid:"Enter valid Full Name"
			},
			email: {
				required: "Enter a Valid Email ID",
				pattern: "Enter a Valid Email ID"
			},
			phone_no:{
				// required:"Contact number is required",
				phoneValid:"Enter valid mobile no",
				rangelength:"Enter a Valid Contact Number"
			},
			profile_image: {
				extension: "Attach a Valid Image"
			},
		},
		/*errorPlacement: function (error, element) {
		 if (element.attr("name") == "terms") {
		 error.appendTo('#error');
		 } else {
		 error.insertAfter(element);
		 }

		 },*/
	});
</script>
@stop