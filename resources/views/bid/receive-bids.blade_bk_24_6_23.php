@extends('layout')

@section('content')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <?php
    $f = Request::get('f');
    $cn = Request::get('c');
    $b = Request::get('b');
    $i = Request::get('i');
    $qa = Request::get('qa');

    use App\Country;use App\Industry;use App\SponsorrSpecify;use App\SponsorrSpecifyList;
    $countries = Country::all();
    $industries = Industry::all();
    $sponsorrlist = SponsorrSpecifyList::where('sponsorr_type', Auth::user()->sponsor_for)->get();
    $userwisesponsor = SponsorrSpecify::where('user_id', Auth::user()->id)->get();
    $userwisesponsorarray = [];
    if (count($userwisesponsor) > 0) {
        foreach ($userwisesponsor as $usersponsorr) {
            $userwisesponsorarray[] = $usersponsorr->specify_name;
        }
    }?>
    <!-- banner section start -->


    <br><br><br><br>
    <section id="feed" class="speakers">
        <div class="container">
            <div class="row"><a href="{{ url('my-bids') }}" class="btn btn-primary float-left"
                >My Bids</a>
                <div class="row">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                </div>
            </div>
            <br>
            <form name="search">
                <div class="row">
                    <div class="col-md-2">
                        <select class="form-control" name="f">
                            <option value="">Outreach Via</option>
                            <option value="Event" <?php if ($f == 'Event') {
                                echo "Selected";
                            }?>>Event
                            </option>
                            <option value="Campaign" <?php if ($f == 'Campaign') {
                                echo "Selected";
                            }?>>Campaign
                            </option>
                            <option value="Content" <?php if ($f == 'Content') {
                                echo "Selected";
                            }?>>Content
                            </option>
                            <option value="Sports Team" <?php if ($f == 'Sports Team') {
                                echo "Selected";
                            }?>>Sports Team
                            </option>
                            <option value="Venue" <?php if ($f == 'Venue') {
                                echo "Selected";
                            }?>>Venue
                            </option>
                            <option value="Not for Profit" <?php if ($f == 'Not for Profit') {
                                echo "Selected";
                            }?>>Not for Profit
                            </option>
                            <option value="Performing Arts" <?php if ($f == 'Performing Arts') {
                                echo "Selected";
                            }?>>Performing Arts
                            </option>
                            <option value="Think Tank" <?php if ($f == 'Think Tank') {
                                echo "Selected";
                            }?>>Think Tank
                            </option>
                            <option value="Knowledge Pool" <?php if ($f == 'Knowledge Pool') {
                                echo "Selected";
                            }?>>Knowledge Pool
                            </option>
                            <option value="Online Events" <?php if ($f == 'Online Events') {
                                echo "Selected";
                            }?>>Online Activities
                            </option>
                            <option value="Other" <?php if ($f == 'Other') {
                                echo "Selected";
                            }?>>Other
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" name="qa" id="qa">
                            <option value="">Available For</option>
                            <option value="Freelancers" <?php if ($f == "Freelancers") {
                                echo "Selected";
                            }?>>Freelancers
                            </option>
                            <option value="Agencies" <?php if ($f == "Agencies") {
                                echo "Selected";
                            }?>>Agencies
                            </option>
                            <option value="Networks" <?php if ($f == "Networks") {
                                echo "Selected";
                            }?>>Networks
                            </option>
                            <option value="Communities" <?php if ($f == "Communities") {
                                echo "Selected";
                            }?>>Communities
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" name="c" id="filter_c" onchange="getCity(7)">
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
                        <select class="form-control" name="f_c" id="f_c">
                            <option value="">Local Opportunities</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" name="b" id="b">
                            <option value="">Budget</option>
                            <option value="200-500" {{ ($b == '200-500')?'selected':'' }}>200-500</option>
                            <option value="500-2000" {{ ($b == '500-2000')?'selected':'' }}>500-2000</option>
                            <option value="2000-5000" {{ ($b == '2000-5000')?'selected':'' }}>2000-5000</option>
                            <option value="5000-10000" {{ ($b == '5000-10000')?'selected':'' }}>5000-10000</option>
                            <option value="10000-20000" {{ ($b == '10000-20000')?'selected':'' }}>10000-20000</option>
                            <option value="20000-30000" {{ ($b == '20000-30000')?'selected':'' }}>20000-30000</option>
                            <option value="30000-50000" {{ ($b == '30000-50000')?'selected':'' }}>30000-50000</option>
                            <option value="50000-100000" {{ ($b == '50000-100000')?'selected':'' }}>50000-100000
                            </option>
                            <option value="Above 100000" {{ ($b == 'Above 100000')?'selected':'' }}>Above 100000
                            </option>
                            <option value="In kind" {{ ($b == 'In kind')?'selected':'' }}>In kind</option>

                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" name="i" id="i">
                            <option value="">Industry</option>
                            @foreach($industries as $in){
                            <option value="{{ $in->id}}" <?php if ($i == $in->id) {
                                echo "Selected";
                            }?>>{{ $in->name}}</option>
                            }
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row" style="padding-top: 10px;">
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary"
                        >Search
                        </button> &nbsp;<a href="{{ url('bid') }}" title="refresh" class="btn btn-primary"
                        ><i class="fa fa-refresh" aria-hidden="true"></i></a></div>
                </div>
            </form>
            <br>
            <div class="row">
                <table id="table_id" class="display">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Outreach Via</th>
                        <th>Specific <br>Opportunity</th>
                        <th>Objective</th>
                        <th>Geo Focus</th>
                        <th>Expected Budget <br/>(USD $)</th>
                        <th>Industry</th>
                        <th>Action</th>
                        <th>Added Date</th>
                        <th>Share</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(!empty($bids->first()))
                        @foreach($bids as $bid)
                            <?php
                            $specification = '';
                            $specificationArray = [];
                            ?>
                            @if (!empty($bid->bidSpecify->first()))
                                @foreach ($bid->bidSpecify as $bidS)
                                    @php
                                        $specificationArray[] = $bidS->specifyName->specify_name;
                                        $specification = implode(', ',$specificationArray);
                                    @endphp
                                @endforeach
                            @endif
                            <tr>
                                <td>{{ $bid->id }}</td>
                                @if($bid->sponsor_for=='Online Events')
                                    <td> Online Activities</td>
                                @else
                                    <td>{{ $bid->sponsor_for }}</td>
                                @endif
                                <td>{{ $specification }}</td>
                                <td>{{  $bid->likeSponsorr }}</td>
                                <td>{{  (!empty($bid->country_name)) ? $bid->country_name->country_name : "-" }}
                                    / {{ (!empty($bid->city) && !empty($bid->city_name) && !empty($bid->city_name->name)) ? $bid->city_name['name'] : "-" }}</td>
                                <td>{{ $bid->sponsor_budget }}</td>
                                <td>{{ (!empty($bid->industry)) ? $bid->industry->name : '-'}}</td>
                                <td>@if(!empty($bid->bidResponse->first() && $bid->bidResponse->first()->is_accepted == 2))
                                        @if(!empty($bid->bider) && $bid->bider->email != '')
                                            <a href="javascript:void(0)"
                                               onclick="displayEmail('{{ $bid->bider->email  }}')"><i
                                                    class="fa fa-envelope"
                                                    aria-hidden="true"></i></a>
                                        @endif
                                    @endif &nbsp;&nbsp;
                                    @if(!empty($bid->bidResponse->first()))

                                        @if(!empty($bid->bidResponse->first()->is_accepted == 0))
                                            <a href="javascript:void(0)"><span
                                                    class="label label-warning ">Not Seen Yet</span></a>
                                        @elseif(!empty($bid->bidResponse->first()->is_accepted == 1))

                                            <a href="javascript:void(0)"><span
                                                    class="label label-primary">Read by Client</span></a>
                                        @else
                                            <a href="javascript:void(0)"><span
                                                    class="label label-success">Open for Negotiation</span></a>
                                        @endif
                                    @else

                                        @if($bid->city_bidder_from == 'Yes')
                                            @if($bid->city == Auth::user()->city)
                                                @if($bid->identity == "All" || $bid->identity == "Everyone" || $bid->identity == Auth::user()->identity)

                                                    <a href="javascript:void(0)"
                                                       onclick="OpenPopup('{{ $bid->id }}')"><span
                                                            class="label label-info">Bid</span></a>
                                                @else
                                                    <a href="javascript:void(0)"
                                                       onclick="displayErrorMessage()"><span
                                                            class="label label-info">Bid</span></a>
                                                @endif
                                            @else
                                                <a href="javascript:void(0)" onclick="displayErrorMessage()"><span
                                                        class="label label-info">Bid</span></a>
                                            @endif
                                        @else
                                            @if($bid->identity == "All" || $bid->identity == "Everyone" || $bid->identity == Auth::user()->identity)
                                                @if(Auth::user()->free_response  < 3 ||  Auth::user()->is_paid == 1)
                                                    <a href="javascript:void(0)"
                                                       onclick="OpenPopup('{{ $bid->id }}')"><span
                                                            class="label label-info">Bid</span></a>
                                                @else
                                                    <a href="javascript:void(0)"
                                                       onclick="paymentPopUp({{ $bid->id  }})"><span
                                                            class="label label-info">Bid</span></a>

                                                @endif

                                            @else
                                                <a href="javascript:void(0)" onclick="displayErrorMessage()"><span
                                                        class="label label-info">Bid</span></a>
                                            @endif
                                        @endif

                                    @endif
{{--                                    <a href="javascript:void(0)"--}}
{{--                                       onclick="OpenPopup('{{ $bid->id }}')"><span--}}
{{--                                            class="label label-info">Bid</span></a>--}}
                                </td>
                                <td>{{ Date("Y-m-d",strtotime($bid->created_at)) }}</td>
                                <td><span id="opportunity_{{ $bid->id }}" class="share-course-filed"
                                          style="display: none"> {{ url('share')."/" . $bid->share_id }}</span> <a
                                        href="javascript:void(0)" class="btn btn-primary read-more-btn  share-opp-btn"
                                        onclick="copyToClipboard('#opportunity_{{ $bid->id }}')">Copy Web link</a></td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>

        </div>
        <div class="modal" id="add-bid" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
                        <h4 class="modal-title" id="myModalLabel" style="text-align: center">Response</h4>
                        <h4 id="bid_id" style="text-align: center"></h4>
                    </div>
                    <div class="modal-body">
                        <form class="addBidForm" id="addBidForm" method="post" onsubmit="return false"
                              name="addBidForm">

                            <div class="form-group autocomplete">
                                <label>
                                    <strong>Write Your Pitch (In 200 characters)</strong>
                                </label>
                                <textarea id="description" name="description" class="form-control"
                                          placeholder="Let's talk about Oranges if the bid is about Oranges and NOT Apples. For e.g. tell a story about how you did it before and that could help the client for now. "
                                          maxlength="200"></textarea>
                                <input id="bid_input_id" name="bid_input_id" type="hidden">
                            </div>
                            <div class="form-group">
                                <label>
                                    <strong>Web Link <!--(Let’s build an event website for Free via our partner <a
                                            href="http://www.media101.tech" target="_blank">101 Media</a>)--> </strong>

                                </label>
                                <input id="portfolio" name="portfolio" class="form-control"
                                       placeholder="Website, Portfolio, Social Media Profile">
                            </div>

                            <button type="submit" id="addBid" class="btn btn-default">Submit</button>
                        </form>

                    </div>

                </div>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
    <style>.swal-button-container {
            display: none;
        }</style>
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
            toastr.success('Web Link Copied', 'Success');
        }

        $("#addBidForm").validate({
            rules: {
                portfolio: {
                    required: true,
                }, description: {
                    required: true,
                    maxlength: 200
                }
            },
            submitHandler: function (form) {
                //  console.log(form)
                addBid()
            }
        });

        function displayEmail(email) {
            const el = document.createElement('div')
           // el.innerHTML = "<strong>At this stage we highly recommend that you use <a href='https://www.pandadoc.com' target='_blank'>PandaDoc </a> create winning proposals and send it directly to the client at: " + email
            el.innerHTML = "<strong>Send it directly to the client at: " + email
            //swal("Good news ! Client would like to negotiate and discuss the proposal. At this stage we highly recommend that you use PandaDoc create winning proposals and send it directly to the client at: ", email);

            swal({
                title: "Good news ! Client would like to negotiate and discuss the proposal.",
                content: el,
            })
        }

        function paymentPopUp(id) {
            const el = document.createElement('div')
            const url = "{{ url('bid-response-payment/')}}" + "/" + id;
            el.innerHTML = "<a class='swal-button swal-button--confirm' href='" + url + "'>Pay $5</a>";
            //swal("Good news ! Client would like to negotiate and discuss the proposal. At this stage we highly recommend that you use PandaDoc create winning proposals and send it directly to the client at: ", email);
            swal({
                title: "Please pay to bid this opportunity",
                content: el,
            })
        }

        function displayErrorMessage() {
            toastr.error('Your Identity / Location is a mismatch for this bid as per the client specification. ', "Error");
        }

        function OpenPopup(bidId) {
            $("#bid_id").text("#" + bidId);
            $("#bid_input_id").val(bidId);
            $("#add-bid").modal('show');
        }

        function addBid() {
            var bid_id = $("#bid_input_id").val();
            var description = $("#description").val();
            var portfolio = $("#portfolio").val();
            $.ajax({
                type: 'POST',
                url: '{{ url('add-bid-response') }}',
                headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
                data: {
                    bid_id: bid_id,
                    description: description,
                    portfolio_link: portfolio,
                },
                success: function (response) {
                    //console.log(response.status);
                    var data = response;
                    if (data.status) {
                        $("#add-bid").modal('hide');
                        const el = document.createElement('div')
                        el.innerHTML = "<a class='swal-button swal-button--confirm' href='{{ url('my-bids') }}'>View my bids</a>"
                        //el.innerHTML = "<strong>In the meanwhile let's help you prepare the Proposal using <a href='https://www.pandadoc.com' target='_blank'>PandaDoc</a>. At <a href='https://www.pandadoc.com' target='_blank'>PandaDoc</a> you can streamline all of your sales documents.</strong><br><br><a class='swal-button swal-button--confirm' href='{{ url('my-bids') }}'>View my bids</a>"
                        // swal('', data.message, 'success');
                        swal({
                            title: "Great you made a Bid. Let's wait for the client to Read or Open the Bid for Negotiation. We will notify you once they do.",
                            content: el,
                        })
                        // toastr.success(response.message, "Success");
                        setTimeout(function () {
                            window.location.reload();
                        }, 1500);
                        //window.location.reload();
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
    </script>
    <script src="{{ asset('js/jquery1.min.js') }}"></script>

    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/typed.min.js') }}"></script>
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script>

        $(document).ready(function () {

            $('#table_id').DataTable();
        });
    </script>
@endsection
