@extends('adminlte::page')

@section('title', 'BDE Tracker|Change Password')

@section('content_header')
    <h1>Change Password</h1>
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Change Password</h3>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <!-- /.box-header -->
        <!-- form start -->
        <form role="form" class="form-horizontal" method="POST" name="changepassword" id="changepassword" action="{{ route('changepassword') }}">
            {{ csrf_field() }}
            <div class="box-body">
                <div class="form-group">
                    <label for="old_password" class="col-sm-2 control-label">Old password</label>
                    <div class="col-sm-4">
                        <input type="password" class="form-control" id="old_password" name="old_password" maxlength="30" value="{{ old('old_password') }}" placeholder="Enter old password">
                        <p class="help-block">Enter a Combination of at least Six Numbers and Letters.</p>
                    </div>
                </div>

                <div class="form-group">
                    <label for="new_password" class="col-sm-2 control-label">New password</label>
                    <div class="col-sm-4">
                        <input type="password" class="form-control" id="new_password" name="new_password" maxlength="10" value="{{ old('new_password') }}" placeholder="Enter new password">
                        <p class="help-block">Enter a Combination of at least Six Numbers and Letters.</p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="confirm_password" class="col-sm-2 control-label">Confirm new password</label>
                    <div class="col-sm-4">
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" {{ old('confirm_password') }} placeholder="Enter confirm password">
                    </div>
                </div>
            </div>
            <!-- /.box-body -->

            <div class="box-footer">
                <button type="submit" class="btn btn-info pull-right" id="adduserbutton" name="adduserbutton">Change password</button>
                <a href="{{  url('/admin') }}"  class="btn btn-danger pull-right">Cancel</a>
            </div>
        </form>
    </div>
    <script>
        jQuery.validator.addMethod("nameValid", function (value, element) {
            return this.optional(element) || /^[a-zA-Z\s]+$/.test(value); });
        jQuery.validator.addMethod("phoneValid", function (value, element) {
            return this.optional(element) || /^[0-9]+$/.test(value); });
        $("#changepassword").validate({
            rules: {
                old_password: {
                    required: true,
                },
                new_password: {
                    required: true,
                    minlength:6
                },
                confirm_password: {
                    required: true,
                    equalTo : "#new_password"
                }
            },
            /*errorPlacement: function (error, element) {
             if (element.attr("name") == "terms") {
             error.appendTo('#error');
             } else {
             error.insertAfter(element);
             }

             },*/
            //submitHandler: changepasswordform,
        });
        function changepasswordform() {
            var user_data = $("#changepassword").serialize();
            $(':input[type="submit"]').prop('disabled', true);
            //$('.main-loader').show();
            $.ajax({
                type: 'POST',
                url: '{{ route('changepassword') }}',
                data: user_data,
                success: function (response) {
                    var data = JSON.parse(response);
                    console.log(data);
                    if(data.status == 1)
                    {
                        toastr.success("Password updated successfully", "Success");
                        window.location.href = '{{ url('/controlpanel/home') }}';
                    }else{
                        var result = data.msg.split(',');
                        //console.log(result);
                        $.each( result, function( key, value ) {
                            toastr.error(value, "Error");
                            //alert( key + ": " + value );
                        });
                        $(':input[type="submit"]').prop('disabled', false);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error("Something went wrong", "Error");
                    $(':input[type="submit"]').prop('disabled', false);
                },
            });
            return false;
        }
    </script>
@stop
