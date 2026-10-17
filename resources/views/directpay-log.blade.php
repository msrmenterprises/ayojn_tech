@extends('layout')
@php  $buttons = $db_buttons['directpay'];  @endphp
@section('content')
    <style>
        .history{padding-top:30px; display:table;}
        fieldset {
    font-family: sans-serif;
    border: 5px solid #1F497D;
    margin-bottom:30px;
    border-radius: 5px;
    padding: 15px;
}

fieldset legend {
    background: #1F497D;
    color: #fff;
    padding: 5px 10px ;
    font-size: 22px;
    border-radius: 5px;
    box-shadow: 0 0 0 5px #ddd;
    margin-left: 20px;
}
.table th{background-color:#e4e4e4;}
    </style>
<!-- 
    @php
echo session()->get('payment_token')."<br/><br/>".session()->get('payment_refresh_token');
@endphp  -->
    <div class="container history">
        
        <a href="{{route('beneficiary-list')}}" class="btn btn-success" data-tooltip="true" data-placement="top"  title="{!!$buttons[0]['hover']!!}">{!!$buttons[0]['icon']!!} {!!$buttons[0]['name']!!} </a>&nbsp;&nbsp;
        <a href="{{route('offline-pay')}}" class="btn btn-danger login-button" style="background-color:#d41b1b;" data-tooltip="true" data-placement="top"  title="{!!$buttons[1]['hover']!!}">{!!$buttons[1]['icon']!!} {!!$buttons[1]['name']!!}</a>
        <h2 style="padding-top:30px; padding-bottom:20px;">Direct Payment Transaction</h2>
        <div class="row">
        <?php echo $fieldset; ?>  
        </div>
    </div>
    <script>
            $(document).ready(function () {
                $('#table_id').DataTable({
                    "order": [[ 4, "desc" ]]
                });
            });
    </script>

    <script src="{{ asset('js/jquery1.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/typed.min.js') }}"></script>
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.0/clipboard.min.js"></script>
@endsection
