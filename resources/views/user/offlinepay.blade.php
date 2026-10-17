@extends('layout')
@php  $buttons = $db_buttons['directpay_add'];  @endphp
<?php use App\Helpers\CommonHelper; ?>
@section('content')

<!-- @php
echo session()->get('payment_token')."<br/><br/>".session()->get('payment_refresh_token');
@endphp  -->

<style>
    .input-group {
    position: relative;
    display: -webkit-box;
    display: -ms-flexbox;
        margin-bottom:20px;
    -ms-flex-wrap: wrap;
    /* flex-wrap: wrap; */
    /* -webkit-box-align: stretch; */
    -ms-flex-align: stretch;
    /* align-items: stretch; */
    width: 100%;
}
.input-group-prepend {
    margin-right: -1px;
}
.input-group-text {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    padding: 0.8rem .75rem;
    margin-bottom: 0;
    font-size: 13px;
    font-weight: 400;
    line-height: 1.5;
    color: #495057;
    text-align: center;
    white-space: nowrap;
    background-color: #e9ecef;
    border: 1px solid #ced4da;
    border-radius: .25rem;
}
.input-group>.custom-file {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex
;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
}
.input-group>.custom-file, .input-group>.custom-select, .input-group>.form-control {
    position: relative;
    -webkit-box-flex: 1;
    -ms-flex: 1 1 auto;
    flex: 1 1 auto;
    width: 1%;
    margin-bottom: 0;
}
.custom-file {
    position: relative;
    display: inline-block;
    width: 100%;
    height: calc(3.45rem + 2px);
    margin-bottom: 0;
}
.custom-file-input {
    position: relative;
    z-index: 2;
    width: 100%;
    height: calc(3.45rem + 2px);
    margin: 0;
    opacity: 0;
}
.input-group>.custom-file:not(:first-child) .custom-file-label, .input-group>.custom-file:not(:first-child) .custom-file-label::before {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}
.custom-file-label {
    position: absolute;
    top: 0;
    text-align: left;
    right: 0;
    left: 0;
    z-index: 1;
    height: calc(3.45rem + 2px);
    padding: 0.8rem .75rem;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    border: 1px solid #ced4da;
    border-radius: .25rem;
}
.custom-file-input:lang(en)~.custom-file-label::after {
    content: "Browse";
}
.custom-file-label::after {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    z-index: 3;
    display: block;
    height: calc(calc(3.45rem + 2px) - 1px* 2);
    padding: 0.8rem .75rem;
    line-height: 1.5;
    color: #495057;
    content: "Browse";
    background-color: #e9ecef;
    border-left: 1px solid #ced4da;
    border-radius: 0 .25rem .25rem 0;
}

.notification{
    margin-bottom: 20px;
    text-align: left;
    margin-left: 20px;
    color: red;

}
</style>

<div class="container">

<div class="row">

    

<div class="col-xs-12 col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3"  style="margin-top:50px;">

@if($errors->any())
        <div class="notification is-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>
    @endif

     @if(session()->get('warning'))
        <div class="alert alert-success alert-dismissible" role="alert" >
            {{ session()->get('warning') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        @if(session()->get('success'))
        <div class="alert alert-success alert-dismissible" role="alert" >
            {{ session()->get('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

                    <div class="panel panel-default">
                        <!-- Default panel contents -->
                        <div class="panel-heading">Direct Payment</div>
                        <div class="panel-body text-center">
                        <form action="{{route('offline-payment-direct')}}" id="gds_form" method="POST" class="text-center" enctype="multipart/form-data"> 
                        <!--form action="{{route('offline-payment')}}" method="POST"-->  
                            <div class="row">
                                <div class="col-sm-5">
                                <select class="form-control" name="beneficiary_id" required style="border: solid 1px red;">
                                <option value="">Select Beneficiary</option>
                                @foreach($beneficiary as $beneficiaries)
                               
                                    <option value="{{$beneficiaries->id}}"  @if(session()->get('selected') == $beneficiaries->id ) {{'selected'}} @endif  @if(old('beneficiary_id') == $beneficiaries->id) {{ 'selected' }} @endif>{{$beneficiaries->contact_name}}</option>
                               
                             @endforeach
                          </select>  
                                </div>
                                <div class="col-sm-3" style="padding-left:0px;">
                                <a href="{{route('beneficiary-add')}}" class="btn btn-success" data-tooltip="true" data-placement="top"  title="{!!$buttons[0]['hover']!!}"> {!!$buttons[0]['icon']!!} {!!$buttons[0]['name']!!} </a>
                                
                                </div>
                                <div class="col-sm-4" style="padding-left:0px;">
                                    <a href="javascript:void(0);" class="btn btn-primary" id="openUsernameSearch" data-tooltip="true" data-placement="top" title="Beneficiary Search">
                                        <i class="fa fa-search"></i> Search by user name
                                    </a>
                                </div>
                            </div>
                            <br/>
                        <select id="options" class="form-control" name="purpose" required style="border: solid 1px red;">
                            <option value="">Select Purpose</option>
                            @foreach($purpose as $purposes)
                                <option value="{{$purposes['id']}}">{{$purposes['name']}}</option>
                            @endforeach

                            @if(!$purpose)
                            <option value="5">Others</option>
                            @endif
                        </select>
                        <br/>
                        <input type="number" autocomplete="" id="amount" name="amount" value="{{old('amount')}}" placeholder="Enter Amount" class="form-control" required style="border: solid 1px red;"/>
                        <br/>

                        <div class="input-group mb-3">
                        <strong>{{config('sitevars.data.service_charge')}} ({{config('sitevars.data.currency')}})</strong>
                        {{CommonHelper::getConfig('directpay')['ayojn_service']}}%
                        </div>

                       

                        <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Payble Amount</span>
                        </div>
                        <div class="custom-file">
                            <input type="number" id="total" name="" readonly placeholder="Payble Amount" class="form-control" style="height:36px;"/>
                            <input type="hidden" name="ayojn_earn" id="ayojn_earn"/>
                        </div>
                        </div>
                        
                        <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Upload Receipt</span>
                        </div>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="receipt" id="inputGroupFile01">
                            <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                        </div>
                        </div>
                        <button type="submit" class="btn btn-primary" data-tooltip="true" data-placement="top"  title="{!!$buttons[1]['hover']!!}">{!!$buttons[1]['icon']!!} {!!$buttons[1]['name']!!}</button>
                        <!-- <input type="submit" class="btn btn-primary" value="pay Now " /> -->
                        </form>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row text-center">
            @if($response !='')
               <h3 style="color:red;"> {{ $response['detail']['message']?? $response['message'] }} </h3>
            @endif
            </div>

</div>


<!-- Username Search Modal -->
<div class="modal fade" id="usernameSearchModal" tabindex="-1" role="dialog" aria-labelledby="usernameSearchLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="usernameSearchLabel">Search by user name</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="text" id="searchUsernameInput" class="form-control" placeholder="Type username...">
        <div id="searchResults" style="margin-top:10px;"></div>
      </div>
    </div>
  </div>
</div>


<script src="{{ asset('js/jquery1.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script>

$('#openUsernameSearch').on('click', function() {
    $('#usernameSearchModal').modal('show');
    $('#searchUsernameInput').val('');
    $('#searchResults').html('');
});


$('#searchUsernameInput').on('keyup', function() {
    var query = $(this).val();
    if(query.length < 2) {
        $('#searchResults').hide(); // Hide the results table
        return;
    }
    $.ajax({
        url: '{{ url("search-username") }}',
        type: 'GET',
        data: { q: query },
        success: function(data) {
            $('#searchResults').html(data).show(); // Show the table when data is returned
        }
    });
});



document.querySelector('.custom-file-input').addEventListener('change', function (e) {
    var fileName = document.getElementById("inputGroupFile01").files[0].name;
    var nextSibling = e.target.nextElementSibling;
    nextSibling.innerText = fileName;
});

$(document).ready(function () {
$.ajax({
        type: 'get',
        dataType: "json",
        url: '{{ url('purposedropdown') }}',
        headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
        success: function (response) {
           console.log(response.details);
         
           var select = $('#options');
           $.each(response.details, function(i,item) {
            select.append( '<option value="'
                 + item.id
                 + '">'
                 + item.name
                 + '</option>' ); 
            });

                },
               
            });
            

        });

$('#gds_form').submit(function() {
    var pass = true;
    if(pass == false){
        return false;
    }
    $(".loader").show();
    return true;
});


</script>
<script>
        $(document).ready(function () {
            $('#amount').on('input', function () {
                var amount = parseFloat($(this).val());
                if (!isNaN(amount)) {
                    var total = amount + (amount * 0.{{CommonHelper::getConfig('directpay')['ayojn_service']}});  
                    $('#total').val(total.toFixed(2));
                    $('#ayojn_earn').val((amount * 0.{{CommonHelper::getConfig('directpay')['ayojn_service']}}));
                } else {
                    $('#total').val('');
                }
            });
        });
    </script>

@endsection