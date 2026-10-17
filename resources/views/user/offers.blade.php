@extends('layout')
@php  $buttons = $db_buttons['offer_markerplace'];  @endphp
@section('content')

<?php
    $f = Request::get('f');
    $cn = Request::get('c');
    $b = Request::get('b');
    $i = Request::get('i');
    $refund = Request::get('refund');
    $cdu = Request::get('cu');
    use App\Country;use App\Currency;use App\Industry;
    $countries = Country::all();
    $Currency = Currency::all();
    $industries = Industry::all();
    ?>
    
    <!-- banner section start -->
     <style>
        .btn-sm{padding: 5px 10px !important;}
     </style>
    <section>
        <div class="container" style="padding-top: 15px;">
            <div class="row">
                <h3 style="text-align: center;margin-top:110px; line-height:35px; display:none;">Any deal you pick from here will let you avail a discount of 30% on Ayojn’s next service fee.
                <br/>
                <span style="color:red;">Wallet Balance : <b>{{ Auth::user()->wallet_balance }} points</b>(1 Point = 1 USD) </span>
                </h3>


                <h3 style="text-align: center;margin-top:110px; line-height:35px; display:none;">
                <span style="color:red;">Wallet Balance : <b>{{ Auth::user()->wallet_balance }} points</b>(1 Point = 1 USD) </span>
                </h3>
                
            </div>

            
            <div class="row">
                <div class="col-md-12" style="padding-top: 15px;">
                    @if(session()->has('success'))

                        <div class="alert alert-success alert-dismissible show" role="alert">
                        <strong>Success!</strong> {{ session()->get('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>

                       
                    @endif

                    @if(session()->has('error'))

                        <div class="alert alert-danger alert-dismissible show" role="alert">
                        <strong>Warning!</strong> {{ session()->get('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>


                        @endif
                </div>
            </div>
            <br>
            <div class="row">
                <form name="search">
                    <div class="col-md-2">
                        <select class="form-control" name="f">
                            <option value="">Deal Amount</option>
                            <option value="Free" <?php if ($f == 'Free') { echo "selected"; }?>>Free</option>
                            <option value="Paid" <?php if ($f == 'Paid') { echo "selected"; }?>>Paid</option>
                            <option value="All">All</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" name="c" id="c">
                            <option value="">Country</option>
                            @if(!empty($countries))
                                @foreach($countries as $c)
                                    <option value="<?= $c->country_code?>" <?php if ($cn == $c->country_code) {
                                        echo "Selected";
                                    }?>><?= $c->country_name?></option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" name="b" id="b">
                            <option value="">Deal Type</option>
                            <option value="Tech" <?php if ($b == 'Tech') {
                                echo "selected";
                            }?>>Tech
                            </option>
                            <option value="Non-Tech" <?php if ($b == 'Non-Tech') {
                                echo "selected";
                            }?>>Non-Tech
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" name="i" id="i">
                            <option value="">Suited For</option>

                            @foreach($industries as $in){
                            <option
                                value="{{ $in->id}}" {{ (Auth::user()->sponsor_industry == $in->id)?'selected':'' }}>{{ $in->name}}</option>
                            }
                        @endforeach

                        <!-- <option value="Sales" <?php if ($i == 'Sales') {
                            echo "selected";
                        }?>>Sales
                            </option>
                            <option value="Marketing" <?php if ($i == 'Marketing') {
                            echo "selected";
                        }?>>Marketing
                            </option>
                            <option value="Finance" <?php if ($i == 'Finance') {
                            echo "selected";
                        }?>>Finance
                            </option>
                            <option value="Legal" <?php if ($i == 'Legal') {
                            echo "selected";
                        }?>>Legal
                            </option>
                            <option value="Administration" <?php if ($i == 'Administration') {
                            echo "selected";
                        }?>>Administration
                            </option>
                            <option value="HR" <?php if ($i == 'HR') {
                            echo "selected";
                        }?>>HR
                            </option> <option value="Operations" <?php if ($i == 'Operations') {
                            echo "selected";
                        }?>>Operations
                            </option> <option value="Others" <?php if ($i == 'Others') {
                            echo "selected";
                        }?>>Others
                            </option> -->
                        </select>
                    </div>
                    <div class="col-md-2">
                        <!-- <select class="form-control" name="cu" id="cu">
                            <option value="">Currency</option>
                            @if(!empty($Currency))
                                @foreach($Currency as $cu)
                                    <option value="{{$cu->name}}" <?php if ($cdu == $cu->name) {
                                        echo "Selected";
                                    }?>> {{ $cu->name }}</option>
                                @endforeach
                            @endif
                        </select> -->

                        <select class="form-control" name="refund" id="refund">
                            <option value=''>All</option>
                            <option value="NULL" <?php if ($refund == 'NULL') {echo "Selected";} ?>>Non-Refundable</option>
                            <option value="1" <?php if ($refund == '1') {echo "Selected";} ?>>Refundable</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary" data-tooltip="true" data-placement="top"  title="{!!$buttons[0]['hover']!!}"
                        >{!!$buttons[0]['icon']!!} {!!$buttons[0]['name']!!}
                        </button> &nbsp;<a href="{{ url('offers') }}" class="btn btn-primary" data-tooltip="true" data-placement="top"  title="{!!$buttons[1]['hover']!!}"
                        >{!!$buttons[1]['icon']!!} {!!$buttons[1]['name']!!}</a></div>
                </form>
            </div>
            <br>
            <div class="row">
                <table id="table_id" class="display" style="width:100%">
                    <thead>
                    <tr>
                        <th>Deal Type</th>
                        <th>Suited For</th>
                        <th>Core Offer</th>
                        <!-- <th>Discount (%)</th> -->
                        <th>Deal Amount</th>
                        <!-- <th>Currency</th> -->
                        <th>Available In</th>
                        <th>Quantity</th>
                        <th>Weblink</th>

                        <th style="text-align:center;"></th>
                        <th style="text-align:center;"></th>

                    </tr>
                    </thead>
                    <tbody>
                    @if(!empty($offers))
                        @foreach($offers as $offer)

                        @php
                        $usertype = Auth::user()->user_type;
                        $typename = \DB::table('user_type')->where('id',$usertype)->value('user_type');
                        $newtypename = "deal_amount_".$typename;
                        $free = '<span style="color:red; font-weight:bold;">Free</span>';
                        @endphp
                    

                            <tr>
                                <td>{{ $offer->identity }}</td>
                                <td>{{ (!empty($offer->industry) ? $offer->industry->name :'-') }}</td>
                                <td>{{ $offer->title }}</td>
                                <!-- <td>{{ $offer->discount }}</td> -->
                                <td>{!! (!empty($offer->$newtypename) ? config('sitevars.data.currency') .  $offer->$newtypename : $free) !!}</td>

                                <!-- <td>{{ $offer->currency??'' }}</td> -->

                                <td>{{ $offer->country->country_name??'' }}</td>
                                <td>{{ $offer->qty??'' }}</td>
                                <td>
                                    @if(!empty($offer->weblink))

                                    <a class="btn" href="{{ $offer->weblink }}" target="_blank" data-tooltip="true" data-placement="top"  title="{!!$buttons[4]['hover']!!}">{!!$buttons[4]['icon']!!} {!!$buttons[4]['name']!!} </a>

                                    @endif
                                </td>

                                <td><a href="{{ route('add_to_cart', $offer->id) }}" class="btn btn-success add-to-cart" data-tooltip="true" data-placement="top"  title="{!!$buttons[2]['hover']!!}">{!!$buttons[2]['icon']!!} {!!$buttons[2]['name']!!}</a></td>
                                <td><a href="{{ route('add_to_wish', $offer->id) }}" class="btn btn-primary add-to-wish" data-tooltip="true" data-placement="top"  title="{!!$buttons[3]['hover']!!}">{!!$buttons[3]['icon']!!} {!!$buttons[3]['name']!!}</a></td>

                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>

        </div>
    </section>
    <script src="{{ asset('js/jquery1.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/typed.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.0/clipboard.min.js"></script>
    <script>
        $(".share-opp-btn").on('click', function (e) {
            toastr.success('Web Link Copied', 'Success');
        });

        function copyToClipboard(element) {
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val($(element).text()).select();
            document.execCommand("copy");
            $temp.remove();
        }

        function changeOffer(id, status) {
            $.ajax({
                type: 'POST',
                url: '{{ url('partner/change-offer-status') }}',
                headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
                data: {
                    status: status,
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

         $(document).ready(function () {
             $('#table_id').DataTable();
         });

         $(document).on('submit','form',function(){
                $(".loader").show();
        });
    </script>
@endsection
