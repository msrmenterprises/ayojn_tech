@extends('adminlte::page')
<!--Add title-->
@section('title',  'Ayojn')
<!--Main Body content-->
@section('content')
    <!-- //banner-top -->
    <!-- banner -->
    <!-- Content Header (Page header) -->

    <section class="content-header">
        <h1>
            Partner Bank
            <small>List</small>

        </h1>


        <ol class="breadcrumb">


            <li><a href="{{ url('admin/dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Partner Bank List</li>

        </ol>
    </section>
    <style type="text/css">
        .mb20 {
            margin-bottom: 20px;
        }

        .checkbox {
            margin-top: 0px;
        }
    </style>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <div class="col-md-12">
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
                            @if (session('true'))
                                <div class="alert alert-success alert-dismissable">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    {{ session('true') }}
                                </div>
                            @endif
                            @if (session('false'))
                                <div class="alert alert-danger alert-dismissable">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    {{ session('false') }}
                                </div>
                            @endif
                        </div>
                        <div class="col-md-12">
                            {{--                            <a href="{{ route('vouch-create') }}" class="btn btn-primary ladda-button"--}}
                            {{--                               data-style="zoom-in">--}}
                            {{--                                <span class="ladda-label"><i class="fa fa-plus"></i> Add Vouch Code</span>--}}
                            {{--                            </a>--}}
                        </div>
                    </div>

                    <div class="box-body table-responsive">
                        <table id="data-table" class="table table-bordered table-striped last-child-sm" width="100%"
                               data-page-length='100'>
                            <thead>
                            <tr>

                                <th width="3%">Sr no.</th>
                                <th width="3%">Company Name</th>
                                <th width="7%">Contact person</th>
                                <th width="7%">Email</th>
                                <th width="7%">Phone Number</th>
  
                            </tr>
                            </thead>
                            <tbody>
                                
                                @php $bnk =""; @endphp
                                @foreach($list as $lists)
                                @php
                                $bnk = DB::table('userbankdetails')->where('user_id',$lists->id)->get();
                                @endphp
                                <tr>

                                    <td>{{$loop->index+1}}</td>
                                    <td>{{$lists->company_name}}</td>
                                    <td>{{$lists->contact_person}}</td>
                                    <td>{{$lists->email}}</td>
                                    <td>{{$lists->phone_no}}</td>
                                </tr>

                                @if($bnk->count()>0)
                                <tr>
                                    <td colspan="8">
                                    <table class="table table-bordered" style="background-color:lavender;">
                                        <tr>
                                        <th>Bank Country</th>
                                        <th>Currency</th>
                                        <th>Bank Account/IBAN</th>
                                        <th>Bank code type</th>
                                        <th>Code No.</th>
                                        <th>Tax ID</th>
                                        <th>CreatedAt</th>
                                        </tr>
                                        @foreach($bnk as $bank)
                                        <tr>
                                            <td>{{$bank->my_bank_country}}</td>
                                            <td>{{$bank->my_currency}}</td>
                                            <td>{{$bank->my_bank_account	}}</td>
                                            <td>{{$bank->my_bank_code}}</td>
                                            <td>{{$bank->my_code_no}}</td>
                                            <td>{{$bank->my_taxid}}</td>
                                            <td>{{$bank->created_at}}</td>
                                        </tr>
                                        @endforeach
                                    </table>
                                    </td>
                                    </tr>
                                @endif
                                @endforeach
                            </tbody>
                         
                        </table>
                    </div>
                    <!-- form start -->
                    <div class="box-footer clearfix text-right">

                    </div>
                    <div id="append-form"></div>
                </div>
                <!-- /.box -->
            </div>
            <!--/.col (left) -->
        </div>
    </section>


@endsection
