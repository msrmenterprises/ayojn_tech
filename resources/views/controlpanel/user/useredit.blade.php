@extends('adminlte::page')

@section('title', 'Sponsorr')

@section('content_header')
    <h1>Edit User</h1>
@stop

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
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

        <form role="form" method="POST" name="editsubuser" id="editsubuser" action="{{ route('updateuser') }}"
              enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" id="userid" name="userid" value="{{ $user->id }}">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email_id">Email ID <b style="color: red">*</b></label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ @$user->email }}"
                                   placeholder="Enter Email Id">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="remark">Remark<b style="color: red">*</b></label>
                            <textarea type="text" class="form-control" id="remark" name="remark"
                                      placeholder="Remark"> {{ @$user->remark }}</textarea>
                        </div>
                    </div>
                </div>
                @if($user->sponsor_type == 2)
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email_id">Identity <b style="color: red">*</b></label>
                                <select name="identity" id="identity" class="form-control" required>
                                    <option value="">Select Indentity</option>
                                    <option value="Freelancers" @if($user->identity == 'Freelancers') selected @endif>
                                        Freelancers
                                    </option>
                                    <option value="Agencies" @if($user->identity == 'Agencies') selected @endif>Agencies</option>
                                    <option value="Networks"
                                            @if($user->identity == 'Networks') selected @endif>Networks
                                    </option>
<option value="Communities"
                                            @if($user->identity == 'Communities') selected @endif>Communities
                                    </option>

                                </select>
                            </div>
                        </div>
                    </div>
                @endif
                @if($user->sponsor_type == 3)
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="company_name">Company Name <b style="color: red">*</b></label>
                                <input type="text" class="form-control" id="company_name" name="company_name"
                                       value="{{ @$user->company_name }}"
                                       placeholder="Enter Company Name" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="contact_person">Contact Person <b style="color: red">*</b></label>
                                <input type="text" class="form-control" id="contact_person" name="contact_person"
                                       value="{{ @$user->contact_person }}"
                                       placeholder="Enter Contact Person" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="HQ">HQ <b style="color: red">*</b></label>
                                <input type="text" class="form-control" id="HQ" name="HQ" value="{{ @$user->HQ }}"
                                       placeholder="Enter HQ" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone_no">Phone No <b style="color: red">*</b></label>
                                <input type="text" class="form-control" id="phone_no" name="phone_no"
                                       value="{{ @$user->phone_no }}"
                                       placeholder="Enter Phone No" required>
                            </div>
                        </div>
                    </div>

                @endif
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-info pull-right" id="adduserbutton" name="adduserbutton">Update
                    User <i class="fa fa-refresh fa-spin upload_spin" style="display: none"></i></button>
                <a href="" class="btn btn-danger pull-right">Cancel</a>
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
