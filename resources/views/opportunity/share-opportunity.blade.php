@extends('layout')

@section('content')
    {{--	<script src="{{ asset('js/jquery.min.js') }}"></script>--}}

    {{--	<script src="{{ asset('js/bootstrap.min.js') }}"></script>--}}
    <script src="{{ asset('js/typed.min.js') }}"></script>
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <br><br><br><br>
    <section id="feed" class="speakers">
        <div class="container" style="padding-top: 15px;">
            <div class="row"><h3 style="text-align: center;">Launched Opportunities</h3>
            </div>
            <br>
            <br>
            <div class="row">
                <table id="table_id" class="display">
                    <thead>
                    <tr>
                        <th>Opportunity ID</th>
                        <th>Opportunity</th>
                        <th>Country</th>
                        <th>Industry</th>
                        <!--<th>Added Date</th> -->
                        <th>Action</th>
                        {{--						<th>Status</th>--}}
                    </tr>
                    </thead>
                    <tbody>
                    @if(!empty($bidData->first()))
                        <tr>
                            <td>#{{ $bidData->id }}</td>
                            <td>{{  $bidData->hashtag }}</td>
                            <td>{{  (!empty($bidData->country_name)) ? $bidData->country_name->country_name : "-" }}</td>
                            <td>{{ (!empty($bidData->industry)) ? $bidData->industry->name : '-'}}</td>
                        <!--<td>{{ Date("Y-m-d",strtotime($bidData->created_at)) }}</td> -->
                            <td id="vouch_{{ $bidData->id  }}">
                                @if(!empty(Auth::user()) && Auth::user()->sponsor_type == 1)
                                    @if(empty($bidData->vouchResponse))
                                        @if(!empty(Auth::user()) && Auth::user()->id != '')
                                            <a href="javascript:void(0)" onclick="getVouchForm({{$bidData->id}})"
                                               class="btn btn-primary">Vouch</a>
                                        @else
                                            <a href="javascript:void(0)" data-toggle="modal" data-target="#loginForm">
										<span
                                            class="label label-info">Vouch</span>
                                            </a>
                                        @endif
                                    @else

                                        @if(!empty($bidData->vouchResponse) && empty($bidData->vouchResponse->vouchesResponseUser->first()))
                                            <span class="badge badge-info">Pending</span>
                                        @else
                                            @if(!empty($bidData->vouchResponse->vouchesResponseUser->first()))
                                                <?php $email = '';
                                                foreach ($bidData->vouchResponse->vouchesResponseUser as $us) {
                                                    $email = $email . $us->opportunityUser->email . ", ";
                                                }?>
                                                <a href="javascript:void(0)" style="font-size: 25px"
                                                   onclick="displayEmail('{{ $email }}')"><span
                                                        class="badge badge-info">Respond</span></a>
                                            @endif
                                        @endif
                                    @endif
                                @endif
                            </td>
                            {{--							<td>--}}
                            {{--								@if(!empty($bidData->vouchResponse))--}}
                            {{--									<input type="checkbox"--}}
                            {{--										   onchange='changebid({{ $bidData->vouchResponse->id }},"{{ $bidData->vouchResponse->is_active }}")'--}}
                            {{--										   name="toggle-event-bid" data-toggle="toggle" data-on="On"--}}
                            {{--										   data-off="Off" {{ ($bidData->vouchResponse->is_active == 'On')?'checked':'' }}>--}}
                            {{--								@endif--}}
                            {{--							</td>--}}
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>

        </div>
        <div id="append-form"></div>
    </section>

    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>

    <script>
        function changebid(id, status) {
            $.ajax({
                type: 'POST',
                url: '{{ url('change-opportunity-status') }}',
                headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
                data: {
                    status: status,
                    id: id
                },
                success: function (response) {
                    //console.log(response.status);
                    var data = response;
                    if (data.status) {
                        toastr.success(response.message, "Success");
                        window.location.reload();
                    } else {
                        toastr.error(response.errors, "Error");
                    }


                    // link to page on clicking the notification

                    //}

                },
                error: function (response) {
                    toastr.error(response.responseJSON.msg, "Error");
                    $(':input[type="submit"]').prop('disabled', false);
                },
            });
        }

        function getVouchForm(vouchId) {
            $.ajax({
                url: "{{ url('/') }}" + '/add-vouch/' + vouchId,
                type: "get",
                async: false,
                headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
                success: function (data) {
                    if (data.html != '') {
                        $("#append-form").append(data.html);
                        $("#add-bid").modal('show');
                    }
                },
                error: function (data) {
                },
            })
        }

        function displayEmail(email) {
            swal("Log in/ Sign up to Explore this opportunity");
        }
    </script>
    {{--	<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">--}}
    {{--	<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>--}}

    <script>

        $(document).ready(function () {

            $('#table_id').DataTable();
        });
    </script>
@endsection
