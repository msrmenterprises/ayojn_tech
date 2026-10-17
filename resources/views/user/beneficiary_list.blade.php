@extends('layout')
@php  $buttons = $db_buttons['beneficiary_list'];  @endphp
@section('content')

<style>
.display td {font-size:13px;}
</style>

<!-- 
    @php
echo session()->get('payment_token')."<br/><br/>".session()->get('payment_refresh_token');
@endphp  -->
    <div class="container" style="margin-bottom:50px;">
    @if(session()->get('success'))
    <div class="alert alert-success alert-dismissible" role="alert" style="margin-top:120px;">
         {{ session()->get('success') }}
         <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

     @if(session()->get('warning'))
     <div class="alert alert-warning alert-dismissible" role="alert" style="margin-top:120px;">
         {{ session()->get('warning') }}
         <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    
  <a href="{{route('beneficiary-add')}}" class="btn btn-success" data-tooltip="true" data-placement="top"  title="{!!$buttons[0]['hover']!!}"
>{!!$buttons[0]['icon']!!} {!!$buttons[0]['name']!!} </a>
  <a href="{{route('offline-pay')}}" class="btn btn-danger login-button" style="background-color:#d41b1b;" data-tooltip="true" data-placement="top"  title="{!!$buttons[1]['hover']!!}">{!!$buttons[1]['icon']!!} {!!$buttons[1]['name']!!}</a>
  <br/><br/>
    <div class="">
    


         
        <div class="table-responsive">
        <table class="table display" id="table_id">
            <thead>
                <tr>
                    <th>Sr.</th>
                    <th>Company Name</th>
                    <th>Contact Name<br/>Email</th>
                    <th>Mobile</th>
                    <th>TaxID</th>
                    <th>Bank Country<br/>Currency</th>
                    <th>Bank Account</th>
                    <th>Bank Code<br/>Code no</th>
                    <th>Beneficiary country<br/>City<br/>Address</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
        @foreach($data as $table)

                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$table->company_name}}</td>
                    <td>{{$table->contact_name}}<br/>{{$table->email}}</td>
                
                    <td>{{$table->mobile_no}}</td>
                    <td>{{$table->tax_id}}</td>
                    <td>{{$table->bank_country}}<br/></td>
                    
                    <td>{{$table->bank_account}}</td>
                    <td>{{$table->bank_code}}<br/>{{$table->code_no}}</td>
                    
                    <td>{{$table->beneficiary_country}}<br/>{{$table->beneficiary_city}}<br/>{{$table->beneficiary_address}}</td>
                    <td>{{$table->created_at}}</td>
                    <td>
                        <a href="{{route('beneficiary-edit',$table->id)}}" class="btn btn-success btn-sm" data-tooltip="true" data-placement="top"  title="{!!$buttons[2]['hover']!!}" >{!!$buttons[2]['icon']!!} {!!$buttons[2]['name']!!}</a>
                        <a href="#" class="btn btn-danger btn-sm delete" data-id="{{$table->id}}" data-tooltip="true" data-placement="top"  title="{!!$buttons[3]['hover']!!}" >{!!$buttons[3]['icon']!!} {!!$buttons[3]['name']!!}</a>
                    </td>
                </tr>

        @endforeach
        </table>
</div>
    </div>
</div>
   

    <script src="{{ asset('js/jquery1.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

    <script>
          $(document).ready(function () {
                $('#table_id').DataTable();
        });

        $(document).on('click', '.delete', function () {

            if (confirm('Are you sure, you want to delete this?')) {
            var id = $(this).attr('data-id');
            $.ajax({
                type: 'POST',
                url: '{{ url("beneficiary-delete") }}',
                headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
                data: {
                    id: id
                },
                success: function (response) {
                    var data = response;
                    if (data.status) {
                        toastr.success(response.message, "Success");
                        window.location.reload();
                    } else {
                        toastr.error(response.errors, "Error");
                    }
                },
                error: function (response) {
                    toastr.error(response.responseJSON.msg, "Error");
                    $(':input[type="submit"]').prop('disabled', false);
                },
            });
            }
        });

    </script>
@endsection
