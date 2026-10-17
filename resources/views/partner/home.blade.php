@extends('layout')
@php  $buttons = $db_buttons['Partner'];  @endphp
@section('content')
    <!-- banner section start -->
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
    <section>
        <div class="container" style="padding-top: 89px;">
            <div class="row"><h3 style="text-align: center;margin-top: 15px">Users at Ayojn will receive a 30%
                    discount on their next service fee from Ayojn, if they buy any of your offered options. </h3>
            </div>
            <div class="row">
          
                <a href="{{ url('partner/new-offer') }}" class="btn btn-primary float-left" data-tooltip="true" data-placement="top"  title="{!!$buttons[0]['hover']!!}"
                >{!!$buttons[0]['icon']!!} {!!$buttons[0]['name']!!}</a>
           
            </div>
            <div class="row">
                <div class="col-md-12" style="padding-top: 15px;">
                    @if(session()->has('success'))
                        <div class="alert alert-success">
                            {{ session()->get('success') }}
                        </div>
                    @endif
                </div>
            </div>
            <br>
            <div class="row">
                <form name="search">
                    <div class="col-md-2">
                        <select class="form-control" name="f">
                            <option value="">Offer For</option>
                            <option value="Clients" <?php if ($f == 'Clients') {
                                echo "selected";
                            }?>>Clients
                            </option>
                            <option
                                value="Services" <?php if ($f == 'Services') {
                                echo "selected";
                            }?>>Services
                            </option>
                            <option
                                value="Both" <?php if ($f == 'Both') {
                                echo "selected";
                            }?>>Both
                            </option>
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
                        <button type="submit" class="btn btn-primary" data-tooltip="true" data-placement="top"  title="{!!$buttons[1]['hover']!!}"
                        >{!!$buttons[1]['icon']!!} {!!$buttons[1]['name']!!}
                        </button> &nbsp;<a href="{{ url('partner/home') }}" class="btn btn-primary" data-tooltip="true" data-placement="top"  title="{!!$buttons[2]['hover']!!}"
                        >{!!$buttons[2]['icon']!!} {!!$buttons[2]['name']!!}</a></div>
                </form>
            </div>
            <br>
            <div class="row">
                <table id="table_id" class="display">
                    <thead>
                    <tr>
                        <th>Offer For</th>
                        <th>Deal Type</th>
                        <th>Suited For</th>
                        <th>Core Offer</th>
                        <!-- <th>Discount (%)</th>
                       
                        <th>Currency</th>
                        <th>Ayojn's Incentive(%)</th> -->
                        <th>Available In</th>
                                    <th>Quantity</th>
                        <th>Status</th>
                        <th>Share</th>
                        <th>Weblink</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(!empty($offers))
                        @foreach($offers as $offer)
                                @php
                                $cnt =  \DB::table('offers_order_item')->where('offer_id',$offer->id)->where('status',0)->count();
                                @endphp
                            <tr>
                                <td>{{ $offer->offer_for }}</td>
                                <td>{{ $offer->identity }}</td>
                                <td>{{ (!empty($offer->industry) ? $offer->industry->name :'-') }}</td>
                                <td>{{ $offer->title }}</td>
                                <!-- <td>{{ $offer->discount }}</td>
                                <td>{{ $offer->currency??'' }}</td>
                                <td>{{ $offer->incentive??'' }}</td> -->
                                <td>{{ $offer->country->country_name??'' }}</td>
                                <td>{{ $offer->qty }}</td>
                                <td>
                                    @if($offer->admin_status == 0)
                                        Pending

                                    @elseif($cnt > 0)
                                        Active
                                    @else
                                        <input type="checkbox"
                                               onchange='changeOffer({{ $offer->id }},"{{ $offer->status }}")'
                                               name="toggle-event-bid" data-toggle="toggle" data-on="On"
                                               data-off="Off" {{ ($offer->status == 'On')?'checked':'' }}>
                                    @endif

                                </td>

                                <td><span id="offer_{{ $offer->id }}" class="share-course-filed"
                                          style="display: none"> {{ url('offer-share')."/" . $offer->share_id }}</span>
                                    <a href="javascript:void(0)" class="btn btn-primary btn-xs read-more-btn  share-opp-btn" data-tooltip="true" data-placement="top"  title="{!!$buttons[3]['hover']!!}"
                                        onclick="copyToClipboard('#offer_{{ $offer->id }}')">{!!$buttons[3]['icon']!!} {!!$buttons[3]['name']!!}</a></td>
                                <td>
                                    @if(!empty($offer->weblink))
                                        <a class="btn btn-primary btn-xs" href="{{ $offer->weblink }}" target="_blank" data-tooltip="true" data-placement="top"  title="{!!$buttons[4]['hover']!!}">{!!$buttons[4]['icon']!!} {!!$buttons[4]['name']!!}</a>
                                    @endif
                                </td>
                                <td><a href="{{route('offer-details',$offer->id)}}" class="btn btn-success btn-xs" data-tooltip="true" data-placement="top"  title="{!!$buttons[5]['hover']!!}">{!!$buttons[5]['icon']!!} {!!$buttons[5]['name']!!}

                                  <span style="background: #000; width: 20px; height: 20px; color: #fff;
    border-radius: 100%; font-size: 13px; display: block; text-align: center; line-height: 20px; position: absolute;  margin-top:-30px;  right: 20px;">{{$cnt}}</span>

</a></td>
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
  
  // var $jq = jQuery.noConflict();
  $(document).ready(function () {
    $('.your-toggle-class').bootstrapToggle();

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

            $("#table_id").DataTable({
                "order": [[ 1, "desc" ]]
            });
        });

        


    </script>
@endsection
