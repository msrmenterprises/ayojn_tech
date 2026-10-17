
@extends('adminlte::page')
<!--Add title-->
@section('title',  'Ayojn')
<!--Main Body content-->
@section('content')

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Upload Users</h3>
        </div>
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif


        @if ($message = Session::get('error'))
            <div class="alert alert-danger alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif
        <form role="form" class="form-horizontal" method="POST" name="addcountry_import" id="addcountry_import" action="{{ route('import-excel') }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="box-body">
                <div class="form-group">
                    <label for="countryname" class="col-sm-2 control-label">User Data</label>
                    <div class="col-sm-10">
                        <input type="file" class="form-control" id="lead_file" name="lead_file" maxlength="50" placeholder="Country Name">
                    </div>
                </div>
            </div>
            <!-- /.box-body -->

            <div class="box-footer">
                <button type="submit" class="btn btn-info pull-right" id="addexportbutton" name="addexportbutton">Import Users</button>
                <!-- <a href="#"  class="btn btn-danger pull-right">Cancel</a> -->
            </div>
        </form>
    </div>
@stop