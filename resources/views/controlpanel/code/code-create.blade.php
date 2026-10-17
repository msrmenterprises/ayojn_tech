@extends('adminlte::page')

@section('title', 'Ayojn')

@section('content_header')
    <h1>Add Vouch Code</h1>
@stop

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Add Vouch Code</h3>
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

        <form role="form" method="POST" name="editsubuser" id="editsubuser" action="{{ route('vouch-add') }}"
              enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="box-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="vouch_code">Vouch Code<b style="color: red">*</b></label>
                            <input type="text" class="form-control" id="vouch_code" name="vouch_code"
                                   placeholder="Enter Vouch Code">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="vouch_amount">Vouch Amount<b style="color: red">*</b></label>
                            <input type="number" class="form-control" id="vouch_amount" name="vouch_amount"
                                   placeholder="Enter Vouch Amount">
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-info pull-left" id="adduserbutton" name="adduserbutton">Add Vouch code<i class="fa fa-refresh fa-spin upload_spin" style="display: none"></i></button>
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
                vouch_code: {
                    required: true,
                },
                vouch_amount: {
                    required: true,
                }
            },
            messages: {
                vouch_code: {
                    required: "Enter a vouch code",
                },
                vouch_amount: {
                    required: "Enter a vouch amount",
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
