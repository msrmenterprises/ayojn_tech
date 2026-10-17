@extends('adminlte::page')

@section('title', 'Ayojn')

@section('content_header')
    <h1>Edit Event</h1>
@stop

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Edit Event</h3>
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

        <form role="form" method="POST" name="editsubuser" id="editsubuser" action="{{ route('update-event') }}"
              enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" id="userid" name="event_id" value="{{ $user->id }}">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email_id">Event Link <b style="color: red">*</b></label>
                            <input type="text" class="form-control" id="link" name="link" value="{{ @$user->link }}"
                                   placeholder="Enter Event Link">
                        </div>
                    </div>
                </div>


            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-info pull-right" id="adduserbutton" name="adduserbutton">Update
                    Event <i class="fa fa-refresh fa-spin upload_spin" style="display: none"></i></button>
                <a href="{{ url('controlpanel/events') }}" class="btn btn-danger pull-right">Cancel</a>
            </div>
        </form>
    </div>
    <script>
        jQuery.validator.addMethod("nameValid", function (value, element) {
            return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
        });
        jQuery.validator.addMethod("phoneValid", function (value, element) {
            return this.optional(element) || /^[0-9]+$/.test(value);
        });
        jQuery.validator.addMethod("alphanumeric1", function (value, element) {
            return this.optional(element) || /^(?=.*[0-9])(?=.*[a-zA-Z])([a-zA-Z0-9-!@#$%&*?_]+)$/.test(value);
        });
        $("#editsubuser").validate({
            rules: {
                email: {
                    required: true,
                    pattern: /^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i
                }
            },
            messages: {
                email: {
                    required: "Enter a Valid Email ID",
                    pattern: "Enter a Valid Email ID"
                }
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
