
@extends('layout')

@section('content')

<style>
    .modal-body label{
        width:30%;
    }
</style>

 <div class="modal" id="userform" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 45.56 45.559">
                                <path id="Union_1" data-name="Union 1" class="cls-1"
                                      d="M22.426,22.426,0,44.852,22.426,22.426,0,44.852,22.426,22.426,0,0,22.426,22.426,0,0,22.426,22.426,44.852,0,22.426,22.426,44.852,0,22.426,22.426,44.852,44.852,22.426,22.426,44.852,44.852ZM22.426,22.426Z"
                                      transform="translate(0.354 0.354)"/>
                            </svg>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">
                          
                            Update your Profile. Once you click submit it will be locked for a review.
                        </h4>
                    </div>
                    <div class="modal-body">
                      
                              <div class="form-group" style="text-align: right;">
                              <label style="" class="badge badge-primary">
                                    <strong>Membership Type : 
                                    @if($user->user_type == 1)    
                                        Delta
                                    @endif
                                    @if($user->user_type == 2)    
                                        Sigma
                                    @endif
                                    @if($user->user_type == 3)    
                                        Alpha
                                    @endif
                                    @if($user->user_type == 4)    
                                        Omega
                                    @endif
                                    </strong>
                                </label>

                                <label style="margin-right:10px; background: #337ab7;" class="badge badge-primary">
                                <strong>User Type :
                                @if($user->sponsor_type == 1)
                                    Clients / Brands
                                @endif
                                @if($user->sponsor_type == 2)
                                    Networks, Agencies, Freelancers
                                @endif
                                @if($user->sponsor_type == 3)
                                    Partner
                                @endif
                                </strong>
                            </label>
                            </div>

                            <div class="form-group">
                                <label>
                                    <strong>Entity</strong>
                                </label>
                               
                                    : {{ $user->entity }}
                            </div>
                          
                            @if($user->sponsor_type == 2)
                                <div class="form-group">
                                    <label>
                                        <strong>Identify Yourself </strong>
                                    </label>

                                    : {{ $user->identity }}
                                </div>
                               
                            @endif

                             <div class="form-group">
                                <label>
                                    <strong>Username</strong>
                                </label>

                                : {{ $user->username }}
                            </div>

                          

                            <div class="form-group">
                                <label>
                                    <strong>Email Id</strong>
                                </label>

                                : {{ $user->email }}
                            </div>
                           
                            <div class="form-group">
                                <label>
                                    <strong>Mobile No</strong>
                                </label>

                                : {{ $user->phone_no }}
                            </div>
                           

                            <div class="form-group">
                                <label>
                                    <strong>Country</strong>
                                </label>

                               : {{ $user->country }}
                            </div>
                            
                            <div class="form-group">
                                <label>
                                    <strong>City</strong>
                                </label>

                                @php
                                    $city = DB::table('cities')->where('id', $user->city)->value('name');
                                @endphp 
                                : {{ $city }}
                            </div>
                         
 
                    
                    </div>

                </div>
            </div>
        </div>

<script src="{{ asset('js/jquery1.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/typed.min.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function(){
        //openprofile(14);
        var id = "{{ $user->id }}";
        
        $.ajax({
            type: 'GET',
            url: "{{ route('getprofile') }}" + '/' + id,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success: function (data) {
                $('.loader').hide();
                console.log(data);
                $('#userform').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                toastr.error("Something went wrong", "Error");
            },
        });
    
    });
</script>

@endsection

