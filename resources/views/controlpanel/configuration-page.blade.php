@extends('adminlte::page')
<!--Add title-->
@section('title',  'Ayojn')
    <style>
  fieldset {
    border: 2px solid #4A90E2 !important;
    padding: 20px !important;
    margin: 20px 0 !important;
    border-radius: 8px;

  }

  /* Custom Input Group Styling */
.custom-input-group {
  border: 1px solid #007bff;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Input inside Input Group */
.custom-input {
  background-color: #f8f9fa;
  color: #333;
  border: none;
}

.input-group-append{
    position: absolute;
    top: 33px;
    z-index: 99999;
    float: right;
    right: 21px;
}

.input-group{
    display:contents !important;
}

.input-group-text {
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 3px;
    float: right;
    padding: 2px;
    font-size: 10px;
    top: 0px !important;
    /* display: ruby; */
    position: static;
}

  legend {
    padding: 0 10px !important;
    font-weight: bold !important;
    font-size: 1.2em !important;
    color: #4A90E2 !important;
    border:0px !important;
    margin-bottom: 0px !important;
    width: auto !important;
  }


  fieldset label {
    margin-top: 0px;
    display: block;
    font-weight: 500;
    color: #333;
  }
    </style>

@section('content')
    <!-- //banner-top -->
    <!-- banner -->
    <!-- Content Header (Page header) -->

  

    <section class="content-header">
        <h1>
            Ayojn Configuration
            <small>Configuration as per Application</small>
            

        </h1>


        <ol class="breadcrumb">

            <li><a href="{{ url('admin/dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Setting</li>

        </ol>
    </section>
  
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                 <div class="">
                            @if(session()->has('success'))
                                <div class="alert alert-success">
                                   <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
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
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">

                           
                            <fieldset>
                            <legend>Ayojn/User Earning</legend>
                          
                                <div class="form-group">

                                   <?php
                                       $datas = \DB::table('configuration')->get(); 
                                   ?>         


 <table class="table table-bordered table-striped">
                                <tr>
                                    <th>Sr.</th>
                                    <th>Config_text</th>
                                    <th>Ayojn Earn</th>
                                    <th>User Earn</th>
                                    <th>Action</th>
                                </tr>
                                  @foreach($datas as $data)
                                  <form action="{{route('configuration-update')}}" method="POST">
                                     @csrf
                                  <tr>
                                    <input type="hidden" name="config_text" value="{{$data->config_text}}" />
                                    <td>{{$loop->index+1}}</td>
                                    <td>{{$data->config_text}}</td>
                                    <td style="font-size:11px;">
                                        <input type="text" class="form-control" value="{{$data->config_amt}}" placeholder="" name="config_amt">
                                        <input type="radio" name="config_type" value="fixed" {{ $data->config_type == 'fixed' ? 'checked' : '' }}> Fixed <br/>
                                        <input type="radio" name="config_type" value="percentage" {{ $data->config_type == 'percentage' ? 'checked' : '' }}> Percentage
                                    </td>
                                    <td> <input type="text" class="form-control" value="{{$data->user_earn}}" placeholder="" name="user_earn" ></td>
                                    <td><button type="submit" class="btn btn-info mt-3">Update</button></td>
                                  </tr>
                                </form>
                                  

                                  @endforeach
</table>
                         
                            </fieldset>




                            </div>



                            <div class="col-md-6">

                            <fieldset>
                                

                                  <legend>Configuration User Spending/ReferralBonus</legend>    
                        
                           
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <th>Sr.</th>
                                    <th>User Type</th>
                                    <th>User Level Spending</th>
                                    <th>ReferraLBonus Credit</th>
                                    <th>Action</th>
                                </tr>
                                @foreach($user_type as $key => $usertype)
                                <form action="{{route('configuration-user-update')}}" method="POST">
                                     @csrf
                                    <tr>
                                        <input type="hidden" name="user_type" value="{{$usertype->user_type}}" />
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $usertype->user_type }}</td>
                                        <td><input type="text" name="level" class="form-control" value="{{ $usertype->level }}" /></td>
                                        <td><input type="text" name="refer_amount" class="form-control" value="{{ $usertype->refer_amount }}" /></td>
                                        <td><button class="btn btn-primary" type="submit">Update</button></td>
                                    </tr> 
                                </form>  
                                @endforeach
                              

                            </table>

                            </fieldset>

                                 <fieldset>
                                <legend>Outbound Email Configuration</legend>
                                <form action="{{route('outbound.email.update')}}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="email_host">Email </label>
                                    <input type="text" class="form-control" id="email_host" name="email_host" value="{{$details}}" required>
                                </div>
                                <div class="form-group">
                                <button type="submit" class="btn btn-warning">Update</button>
                                </div>
                                </form>
                            </fieldset>

                            <fieldset>
                                <legend>Demo MFA Delivery</legend>
                                <p>
                                    Active accounts on this list receive each MFA code at both their valid account email
                                    and the shared inbox below. The code is still required to sign in.
                                </p>

                                <form action="{{ route('mfa-demo.settings.update') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="mfa_demo_recipient">Shared demo inbox</label>
                                        <input
                                            type="email"
                                            class="form-control"
                                            id="mfa_demo_recipient"
                                            name="recipient_email"
                                            value="{{ optional($mfaDemoSettings)->recipient_email }}"
                                            required
                                        >
                                    </div>
                                    <button type="submit" class="btn btn-warning">Save inbox</button>
                                </form>

                                <hr>

                                <form action="{{ route('mfa-demo.users.store') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="demo_user_email">Add a demo account by email</label>
                                        <input
                                            type="email"
                                            class="form-control"
                                            id="demo_user_email"
                                            name="user_email"
                                            placeholder="Account email"
                                            required
                                        >
                                    </div>
                                    <button type="submit" class="btn btn-primary">Add demo account</button>
                                </form>

                                <table class="table table-bordered table-striped" style="margin-top: 15px;">
                                    <thead>
                                        <tr>
                                            <th>Demo account</th>
                                            <th>Status</th>
                                            <th>Manage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($mfaDemoAccounts as $demoAccount)
                                            <tr>
                                                <td>
                                                    {{ $demoAccount->name }}<br>
                                                    <small>{{ $demoAccount->email }} (ID {{ $demoAccount->user_id }})</small>
                                                </td>
                                                <td>{{ $demoAccount->is_active ? 'Active' : 'Inactive' }}</td>
                                                <td>
                                                    <form action="{{ route('mfa-demo.users.toggle', $demoAccount->id) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-xs btn-info">
                                                            {{ $demoAccount->is_active ? 'Disable' : 'Enable' }}
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('mfa-demo.users.remove', $demoAccount->id) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-xs btn-danger">Remove</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3">No demo accounts are configured.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </fieldset>


                            
                        </div>

                      
                      
                      </div>
                         
                    <!-- form start -->
                  
                </div>
                <!-- /.box -->
            </div>
            <!--/.col (left) -->
        </div>
    </section>


    <!-- Modal -->




<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.0/clipboard.min.js"></script>

    
@endsection
