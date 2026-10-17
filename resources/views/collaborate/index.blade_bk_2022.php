@extends('layout')

@section('content')


    <link rel="stylesheet" type="text/css" href="{{ asset('css/datepicker.css') }}">
    <style>
        .error {
            color: red;
        }

        #exTab2 h3 {
        / / color: white;
        / / background-color: #428bca;
            padding: 5px 15px;
        }
    </style>
    <?php
    $f = Request::get('f');
    $cn = Request::get('c');
    $b = Request::get('b');
    $i = Request::get('i');
    $os = Request::get('os');

    $of = Request::get('of');
    $oc = Request::get('oc');
    $ob = Request::get('ob');
    $oi = Request::get('oi');
    $so = Request::get('so');
    ?>
    <section id="feed" class="speakers">
        <div class="container">
            <div class="row">
                <!-- banner section start -->
                <div id="exTab2" class="container">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#1" data-toggle="tab">Offer Collaboration</a>
                        </li>
                        <li><a href="#2" data-toggle="tab" id="cooll">Bid for Collaboration</a>
                        </li>
                    </ul>

                    <div class="tab-content ">
                        <div class="tab-pane active" id="1">
                            @if(Auth::user()->userstatus == 1)
                                <div class="row" style="margin-bottom: 25px;margin-top:25px;">
                                    <a href="javascript:void(0)" data-toggle="modal" class="btn btn-primary float-left"
                                       data-target="#add-event"
                                    >Offer Collaboration</a>
                                </div>
                                <form name="search">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <select class="form-control" name="os" id="o">
                                                <option value="">Objective</option>
                                                <option value="Creative" <?php if ($os == 'Creative') {
                                                    echo "selected";
                                                } ?>>Creative
                                                </option>
                                                <option value="Sales" <?php if ($os == 'Sales') {
                                                    echo "selected";
                                                } ?>>Sales
                                                </option>
                                                <option value="Marketing" <?php if ($os == 'Marketing') {
                                                    echo "selected";
                                                } ?>>Marketing
                                                </option>
                                                <option value="Operations" <?php if ($os == 'Operations') {
                                                    echo "selected";
                                                } ?>>
                                                    Operations
                                                </option>

                                            </select>
                                        </div>
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
                                            <select class="form-control" name="c" id="c">
                                                <option value="">Country</option>
                                                @if(!empty($countries))
                                                    @foreach($countries as $c)
                                                        <option
                                                            value="<?= $c->country_code?>" <?php if ($cn == $c->country_code) {
                                                            echo "Selected";
                                                        }?>><?= $c->country_name?></option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <select class="form-control" name="b" id="b">
                                                <option value="">Budget</option>
                                                <option value="200-500" <?php if ($b == '200-500') {
                                                    echo "selected";
                                                } ?>>200-500
                                                </option>
                                                <option value="500-2000" <?php if ($b == '500-2000') {
                                                    echo "selected";
                                                } ?>>500-2000
                                                </option>
                                                <option value="2000-5000" <?php if ($b == '2000-5000') {
                                                    echo "selected";
                                                } ?>>2000-5000
                                                </option>
                                                <option value="5000-10000" <?php if ($b == '5000-10000') {
                                                    echo "selected";
                                                } ?>>5000-10000
                                                </option>
                                                <option value="10000-20000" <?php if ($b == '10000-20000') {
                                                    echo "selected";
                                                } ?>>10000-20000
                                                </option>
                                                <option value="20000-30000" <?php if ($b == '20000-30000') {
                                                    echo "selected";
                                                } ?>>20000-30000
                                                </option>
                                                <option value="30000-50000" <?php if ($b == '30000-50000') {
                                                    echo "selected";
                                                } ?>>30000-50000
                                                </option>
                                                <option value="50000-100000" <?php if ($b == '50000-100000') {
                                                    echo "selected";
                                                } ?>>50000-100000
                                                </option>
                                                <option value="Above 100000" <?php if ($b == 'Above 100000') {
                                                    echo "selected";
                                                } ?>>Above 100000
                                                </option>
                                                <option value="In kind" <?php if ($b == 'In kind') {
                                                    echo "selected";
                                                } ?>>In kind
                                                </option>

                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <select class="form-control" name="i" id="i">
                                                <option value="">Your Industry</option>
                                                @foreach($industryLists as $in){
                                                <option value="{{ $in->id}}" <?php if ($i == $in->id) {
                                                    echo "Selected";
                                                }?>>{{ $in->name}}</option>
                                                }
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="type" value="2">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="submit" class="btn btn-primary"
                                            >Search
                                            </button> &nbsp;<a href="{{ url('collaborate') }}" title="refresh"
                                                               class="btn btn-primary"
                                            ><i class="fa fa-refresh" aria-hidden="true"></i></a></div>

                                    </div>
                                </form>
                                <br>
                                <div class="row">
                                    <table id="table_id" class="display">
                                        <thead>
                                        <tr>
                                            <th>Collaboration For</th>

                                            <th>Remote Country
                                                <hr style="margin-bottom: 0px;margin-top: 0px"></hr>
                                                City
                                            </th>
                                            <th>Geo focus</th>
                                            <th>Industry</th>
                                            <!--<th>Local Collaborator</th>
                                            <th>Collborat with</th> -->
                                            <th>Budget (USD $)</th>
                                            <th>Objective</th>
                                            <th>Status</th>
                                            <th>Expirty Date</th>
                                            <th>Bid List</th>
                                            <th>Share</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(!empty($myEvents->first()))
                                            @foreach($myEvents as $key=>$event)
                                                <tr>
                                                    <td>{{ $event->collaborate_for }} <br/> {{ $event->sub }}</td>

                                                    <td>{{  (!empty($event->remote_country) && !empty($event->remote_country_name)) ? $event->remote_country_name->country_name : '-' }}
                                                        <hr style="margin-top: 0;margin-bottom: 0">{{ (!empty($event->remote_city) && !empty($event->remote_city_name)) ? $event->remote_city_name->name : '-' }}
                                                    </td>
                                                    <td>{{ (!empty($event->country_name)) ? $event->country_name->country_name : '-' }}</td>
                                                    <td>{{ (!empty($event->industry)) ? $event->industry->name : '-' }}</td>
                                                    <!--<td>@if($event->with_local_focus == 1) Yes @else No @endif</td>
                                                    <td>{{ $event->collaborate_with }}</td> -->
                                                    <td>{{ $event->budget }}</td>
                                                    <td>{{ $event->objective }}</td>
                                                    <td> @if($event->status == 1)
                                                            Approved
                                                        @elseif($event->status == 2)
                                                            Reject
                                                        @elseif($event->status == 3)
                                                            Expired
                                                        @else Pending @endif
                                                    </td>
                                                    <td>{{ Date("Y-m-d",strtotime($event->expiry_date)) }}</td>
                                                    <td>
                                                        <a href="{{ url('collaborator').'/'.base64_encode($event->id) }}"><i
                                                                class="fa fa-list"></i> <span
                                                                class="badge badge-light">{{ $event->attendes->count() }}</span>
                                                        </a>
                                                    </td>
                                                    <td><span id="event_{{ $event->id }}" class="share-course-filed"
                                                              style="display: none"> {{ url('share-col')."/" . $event->share_id }}</span>
                                                        <a
                                                            href="javascript:void(0)"
                                                            class="btn btn-primary read-more-btn  share-opp-btn"
                                                            onclick="copyToClipboard('#event_{{ $event->id }}')">Copy
                                                            Web
                                                            link</a></td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div><h4>
                                        <br><br>
                                        Your account seems to be pending for approval. For now you can bid for collaborated opportunities. Go ahead and good luck with that !
                                    </h4></div>

                            @endif
                        </div>
                        <div class="tab-pane" id="2">
                            <div class="row" style="margin-bottom: 25px;margin-top:25px;">

                            </div>
                            <form name="search">
                                <div class="row">

                                    <div class="col-md-2">
                                        <select class="form-control" name="so" id="o">
                                            <option value="">Objective</option>
                                            <option value="Creative" <?php if ($so == 'Creative') {
                                                echo "selected";
                                            } ?>>Creative
                                            </option>
                                            <option value="Sales" <?php if ($so == 'Sales') {
                                                echo "selected";
                                            } ?>>Sales
                                            </option>
                                            <option value="Marketing" <?php if ($so == 'Marketing') {
                                                echo "selected";
                                            } ?>>Marketing
                                            </option>
                                            <option value="Operations" <?php if ($so == 'Operations') {
                                                echo "selected";
                                            } ?>>
                                                Operations
                                            </option>

                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select class="form-control" name="of">
                                            <option value="">Outreach Via</option>
                                            <option value="Event" <?php if ($of == 'Event') {
                                                echo "Selected";
                                            }?>>Event
                                            </option>
                                            <option value="Campaign" <?php if ($of == 'Campaign') {
                                                echo "Selected";
                                            }?>>Campaign
                                            </option>
                                            <option value="Content" <?php if ($of == 'Content') {
                                                echo "Selected";
                                            }?>>Content
                                            </option>
                                            <option value="Sports Team" <?php if ($of == 'Sports Team') {
                                                echo "Selected";
                                            }?>>Sports Team
                                            </option>
                                            <option value="Venue" <?php if ($of == 'Venue') {
                                                echo "Selected";
                                            }?>>Venue
                                            </option>
                                            <option value="Not for Profit" <?php if ($of == 'Not for Profit') {
                                                echo "Selected";
                                            }?>>Not for Profit
                                            </option>
                                            <option value="Performing Arts" <?php if ($of == 'Performing Arts') {
                                                echo "Selected";
                                            }?>>Performing Arts
                                            </option>
                                            <option value="Think Tank" <?php if ($of == 'Think Tank') {
                                                echo "Selected";
                                            }?>>Think Tank
                                            </option>
                                            <option value="Knowledge Pool" <?php if ($of == 'Knowledge Pool') {
                                                echo "Selected";
                                            }?>>Knowledge Pool
                                            </option>
                                            <option value="Online Events" <?php if ($of == 'Online Events') {
                                                echo "Selected";
                                            }?>>Online Activities
                                            </option>
                                            <option value="Other" <?php if ($of == 'Other') {
                                                echo "Selected";
                                            }?>>Other
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select class="form-control" name="oc" id="oc">
                                            <option value="">Country</option>
                                            @if(!empty($countries))
                                                @foreach($countries as $c1)
                                                    <option
                                                        value="<?= $c1->country_code?>" <?php if ($oc == $c1->country_code) {
                                                        echo "Selected";
                                                    }?>><?= $c1->country_name?></option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select class="form-control" name="ob" id="ob">
                                            <option value="">Budget</option>
                                            <option value="200-500" <?php if ($ob == '200-500') {
                                                echo "selected";
                                            } ?>>200-500
                                            </option>
                                            <option value="500-2000" <?php if ($ob == '500-2000') {
                                                echo "selected";
                                            } ?>>500-2000
                                            </option>
                                            <option value="2000-5000" <?php if ($ob == '2000-5000') {
                                                echo "selected";
                                            } ?>>2000-5000
                                            </option>
                                            <option value="5000-10000" <?php if ($ob == '5000-10000') {
                                                echo "selected";
                                            } ?>>5000-10000
                                            </option>
                                            <option value="10000-20000" <?php if ($ob == '10000-20000') {
                                                echo "selected";
                                            } ?>>10000-20000
                                            </option>
                                            <option value="20000-30000" <?php if ($ob == '20000-30000') {
                                                echo "selected";
                                            } ?>>20000-30000
                                            </option>
                                            <option value="30000-50000" <?php if ($ob == '30000-50000') {
                                                echo "selected";
                                            } ?>>30000-50000
                                            </option>
                                            <option value="50000-100000" <?php if ($ob == '50000-100000') {
                                                echo "selected";
                                            } ?>>50000-100000
                                            </option>
                                            <option value="Above 100000" <?php if ($ob == 'Above 100000') {
                                                echo "selected";
                                            } ?>>Above 100000
                                            </option>
                                            <option value="In kind" <?php if ($ob == 'In kind') {
                                                echo "selected";
                                            } ?>>In kind
                                            </option>

                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select class="form-control" name="oi" id="oi">
                                            <option value="">Your Industry</option>
                                            @foreach($industryLists as $in){
                                            <option value="{{ $in->id}}" <?php if ($i == $in->id) {
                                                echo "Selected";
                                            }?>>{{ $in->name}}</option>
                                            }
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary"
                                        >Search
                                        </button> &nbsp;<a href="{{ url('collaborate') }}" title="refresh"
                                                           class="btn btn-primary"
                                        ><i class="fa fa-refresh" aria-hidden="true"></i></a></div>

                                </div>
                            </form>
                            <br>
                            <div class="row">
                                <table id="attend" class="display">
                                    <thead>
                                    <tr>
                                        <th>Offered by</th>
                                        <th>Collaboration For</th>
                                        <th>Remote Country
                                            <hr style="margin-bottom: 0px;margin-top: 0px"></hr>
                                            City
                                        </th>
                                        <th>Geo focus</th>
                                        <th>Industry</th>
                                        <!--<th>Local Collaborator</th>
                                        <th>Collaborate with</th> -->
                                        <th>Budget (USD $)</th>
                                        <th>Objective</th>
                                        <th>Bid</th>
                                        <th>Share</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(!empty($othersEvents->first()))
                                        @foreach($othersEvents as $key1=>$oevent)
                                            <tr>
                                                <td>@if(!empty($oevent->organizer)) @if(!empty($oevent->organizer->entity )){{ $oevent->organizer->entity  }} @elseif($oevent->organizer->company_name) {{ $oevent->organizer->company_name }} @endif @else
                                                        - @endif</td>
                                                <td>{{ $oevent->collaborate_for }} <br/> {{ $oevent->sub }}</td>

                                                <td>{{  (!empty($oevent->remote_country) && !empty($oevent->remote_country_name)) ? $oevent->remote_country_name->country_name : '-' }}
                                                    <hr style="margin-top: 0;margin-bottom: 0">{{ (!empty($oevent->remote_city) && !empty($oevent->remote_city_name)) ? $oevent->remote_city_name->name : '-' }}
                                                </td>
                                                <td>{{ (!empty($oevent->country_name)) ? $oevent->country_name->country_name : '-' }}</td>
                                                <td>{{ (!empty($oevent->industry)) ? $oevent->industry->name : '-' }}</td>
                                                <!--<td>@if($oevent->with_local_focus == 1) Yes @else No @endif</td>
                                                <td>{{ $oevent->collaborate_with }}</td> -->
                                                <td>{{ $oevent->budget }}</td>
                                                <td>{{ $oevent->objective }}</td>
                                                <td>
                                                    @if(!empty($oevent->checkAttendes))
                                                        @if($oevent->checkAttendes->status == 1)
                                                            Accepted
                                                        @else
                                                            Pending
                                                        @endif
                                                    @else
                                                        <a href="javascript:void(0)"
                                                           onclick="OpenPopup({{ $oevent->id }})"><i
                                                                class="fa fa-paper-plane"></i> </a>
                                                    @endif

                                                </td>
                                                <td><span id="event_share_{{ $oevent->id }}" class="share-course-filed"
                                                          style="display: none"> {{ url('share-col')."/" . $oevent->share_id }}</span>
                                                    <a
                                                        href="javascript:void(0)"
                                                        class="btn btn-primary read-more-btn  share-opp-btn"
                                                        onclick="copyToClipboard('#event_share_{{ $oevent->id }}')">Copy
                                                        Web link</a></td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal" id="add-event" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
                            <h4 class="modal-title" id="myModalLabel">Ask for collboration</h4>
                            <h5 class="modal-title" id="myModalLabel">
                            </h5>
                        </div>
                        <div class="modal-body">
                            <form class="addEventForm" id="addEventForm" method="post" onsubmit="return false"
                                  name="addEventForm">
                                <div class="form-group">
                                    <!-- <h3>
                                        Let's gather the relevant details so that we can match your information requirements
                                    </h3> -->
                                </div>
                                <div class="form-group autocomplete">
                                    <label>
                                        <strong>What do you wish to collaborate for ?</strong>
                                    </label>

                                    <select class="form-control" name="collaborate_for" id="sponsorr_type_bid"
                                            onchange="getSpecifyBidNew()">
                                        <option value="">Select Any</option>
                                        <option value="Event">Event</option>
                                        <option value="Campaign">Campaign</option>
                                        <option value="Content">Content</option>
                                        <option value="Sports Team">Sports Team</option>
                                        <option value="Venue">Venue</option>
                                        <option value="Not for Profit">Not for Profit</option>
                                        <option value="Performing Arts">Performing Arts</option>
                                        <option value="Think Tank">Think Tank</option>
                                        <option value="Knowledge Pool">Knowledge Pool</option>
                                        <option value="Online Events">Online Activities</option>
                                        <option value="Research">Research</option>
                                        <option value="Other">Other</option>
                                    </select>

                                </div>
                                <div class="form-group autocomplete">
                                    <label>
                                        <strong>Specify</strong>
                                    </label>

                                    <select class="form-control" name="sub[]" id="specify_bid" multiple>
                                    </select>

                                </div>
                                <div class="form-group autocomplete">
                                    <label>
                                        <strong>Is this a remote opportunity?</strong>
                                    </label>

                                    <select name="remote_opportunity" id="remote_opportunity"
                                            onchange="displayCountry()" class="form-control">
                                        <option value="">Select Any One</option>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>

                                    </select>

                                </div>
                                <div class="form-group autocomplete" id="remote_country_id">
                                    <label>
                                        <strong>Remote Country</strong>
                                    </label>

                                    <select name="remote_country" id="remote_country" class="form-control"
                                            onchange="getCityNew()">
                                        <option value="">Select Country</option>
                                        @if(!empty($countries))
                                            @foreach($countries as $country)
                                                <option
                                                    value="{{ $country->country_code }}">{{ $country->country_name  }}</option>

                                            @endforeach
                                        @endif
                                    </select>

                                </div>
                                <div class="form-group autocomplete" id="remote_city_id">
                                    <label>
                                        <strong>Remote City</strong>
                                    </label>

                                    <select name="remote_city" id="remote_city" class="form-control">
                                        <option value="">Select City</option>
                                    </select>

                                </div>
                                <div class="form-group autocomplete">
                                    <label>
                                        <strong>Geo Focus</strong>
                                    </label>

                                    <select name="geo_focus" id="geo_focus" class="form-control">
                                        <option value="">Select Country</option>
                                        @if(!empty($countries))
                                            @foreach($countries as $country)
                                                <option
                                                    value="{{ $country->country_code }}">{{ $country->country_name  }}</option>

                                            @endforeach
                                        @endif
                                    </select>

                                </div>
                                <div class="form-group autocomplete">
                                    <label>
                                        <strong>Industry Focus</strong>
                                    </label>

                                    <select name="industry_focus" id="industry_focus" class="form-control">
                                        <option value="">Select Industry</option>
                                        @if(!empty($industryLists))
                                            @foreach($industryLists as $industry)
                                                <option value="{{ $industry->id }}">{{ $industry->name  }}</option>

                                            @endforeach
                                        @endif
                                    </select>

                                </div>
                                <div class="form-group autocomplete">
                                    <label>
                                        <strong>Do you wish this collaboration to be available only for local
                                            collabrators from the selected city ?</strong>
                                    </label>

                                    <select name="with_local_focus" id="with_local_focus" class="form-control">
                                        <option value="">Select Any One</option>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>

                                    </select>

                                </div>
                                <div class="form-group autocomplete">
                                    <label>
                                        <strong>Collaboration with?</strong>
                                    </label>

                                    <select name="collaborate_with" id="collaborate_with" class="form-control">
                                        <option value="">Select Any One</option>
                                        <option value="Agencies">Agencies</option>
                                        <option value="Communities">Communities</option>
                                        <option value="Freelancers">Freelancers</option>
                                        <option value="Networks">Networks</option>
                                        <option value="Everyone">Everyone</option>

                                    </select>

                                </div>
                                <div class="form-group autocomplete">
                                    <label>
                                        <strong>Approx Budget (USD) </strong>
                                    </label>

                                    <select class="form-control" name="budget" id="budget">
                                        <option value="">Select Any</option>
                                        <option value="200-500">200-500</option>
                                        <option value="500-2000">500-2000</option>
                                        <option value="2000-5000">2000-5000</option>
                                        <option value="5000-10000">5000-10000</option>
                                        <option value="10000-20000">10000-20000</option>
                                        <option value="20000-30000">20000-30000</option>
                                        <option value="30000-50000">30000-50000</option>
                                        <option value="50000-100000">50000-100000</option>
                                        <option value="Above 100000">Above 100000</option>
                                        <option value="In kind">In kind</option>

                                    </select>

                                </div>
                                <div class="form-group autocomplete">
                                    <label>
                                        <strong>Objective</strong>
                                    </label>

                                    <select class="form-control" name="objective" id="objective">
                                        <option value="">Select Any</option>
                                        <option value="Creative">Creative</option>
                                        <option value="Sales">Sales</option>
                                        <option value="Marketing">Marketing</option>
                                        <option value="Operations">Operations</option>
                                    </select>

                                </div>


                                <div class="form-group">
                                    <label>
                                        <strong>Expiry Date</strong>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder=""
                                           id="expiry_date"
                                           name="expiry_date">

                                </div>
                                {{--<button type="button" class="btn btn-default" onclick="secondPopupPrevious()">Previous</button>--}}
                                <button type="submit" id="addEvent" class="btn btn-default">Submit
                                </button>
                            </form>

                        </div>

                    </div>
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
                                    <input id="col_id" name="col_id" type="hidden">
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
        </div>
    </section>

    <script src="{{ asset('js/jquery1.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/typed.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.min.js"></script>
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://echeckout.co.uk/assets/js/moment.min.js"></script>
    <script src="{{ asset('js/datepicker.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.0/clipboard.min.js"></script>

    <script>

        function OpenPopup(bidId) {

            $("#col_id").val(bidId);
            $("#add-bid").modal('show');
        }

        function copyToClipboard(element) {
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val($(element).text()).select();
            document.execCommand("copy");
            $temp.remove();
            toastr.success('Web Link Copied', 'Success');
        }

        function displayCountry() {
            var remote_opportunity = $("#remote_opportunity").val();
            if (remote_opportunity == 1) {
                $("#remote_country_id").show();
                $("#remote_city_id").show();
            } else {
                $("#remote_country_id").hide();
                $("#remote_city_id").hide();
            }
        }

        function getSpecifyBidNew() {
            var event_type = $("#sponsorr_type_bid").val();
            $('#specify_bid').html('');
            //$("#sponsoredOtherSpecify").show();
            var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
            $.ajax({
                type: 'POST',
                url: "{{ route('getspecify') }}",
                data: {_token: CSRF_TOKEN, event_type: event_type},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                success: function (data) {
                    //console.log(data);
                    if (data != '') {
                        // $('#specify_bid').html('');
                        // Loop through each of the results and append the option to the dropdown
                        $.each(data, function (k, v) {

                            $('#specify_bid').append('<option value="' + v.specify_name + '">' + v.specify_name + '</option>');
                        });
                    }
                    //$('#popupcountry').html(data);
                    //$('#topCountriesForm').modal('show');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    toastr.error("Something went wrong", "Error");
                },
            });
        }

        function getCityNew() {
            $('.loader').show();
            var countryID = $("#remote_country").val();
            $.ajax({
                type: "POST",
                url: base_url + "/get-city",
                headers: {'X-CSRF-Token': $('meta[name="_token"]').attr('content')},
                data: {countryID: countryID},
                success: function (res) {
                    $('.loader').hide();
                    if (res) {
                        $("#remote_city").empty();
                        $("#remote_city").append('<option value="">Select City</option>');
                        $("#remote_city").append('<option value="141852">Across the Country </option>');
                        $.each(res, function (key, value) {
                            $("#remote_city").append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                }
            });
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

        function addBid() {
            var col_id = $("#col_id").val();
            var description = $("#description").val();
            var portfolio = $("#portfolio").val();
            $.ajax({
                type: 'POST',
                url: '{{ url('add-collaboration-response') }}',
                headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
                data: {
                    col_id: col_id,
                    description: description,
                    portfolio_link: portfolio,
                },
                success: function (response) {
                    //console.log(response.status);
                    var data = response;
                    if (data.status) {
                        $("#add-bid").modal('hide');
                        {{--const el = document.createElement('div')--}}
                        {{--el.innerHTML = "<a class='swal-button swal-button--confirm' href='{{ url('my-bids') }}'>View my bids</a>"--}}
                        {{--//el.innerHTML = "<strong>In the meanwhile let's help you prepare the Proposal using <a href='https://www.pandadoc.com' target='_blank'>PandaDoc</a>. At <a href='https://www.pandadoc.com' target='_blank'>PandaDoc</a> you can streamline all of your sales documents.</strong><br><br><a class='swal-button swal-button--confirm' href='{{ url('my-bids') }}'>View my bids</a>"--}}
                        {{--// swal('', data.message, 'success');--}}
                        {{--swal({--}}
                        {{--    title: "Great you made a Bid. Let's wait for the client to Read or Open the Bid for Negotiation. We will notify you once they do.",--}}
                        {{--    content: el,--}}
                        {{--})--}}
                         toastr.success(response.message, "Success");
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

        $(function () {
            @if(!empty($so) || !empty($of) || !empty($oc)|| !empty($ob)|| !empty($oi) || !empty(Request::get('type')))
            $("#cooll").trigger('click');
            @endif
            $("#remote_country_id").hide();
            $("#remote_city_id").hide();
            // $('#event_date').datetimepicker({
            //     // Formats
            //     // follow MomentJS docs: https://momentjs.com/docs/#/displaying/format/
            //     format: 'YYYY-MM-DD HH:mm:ss',
            //
            //     // Your Icons
            //     // as Bootstrap 4 is not using Glyphicons anymore
            //     icons: {
            //         time: 'fa fa-clock-o',
            //         date: 'fa fa-calendar',
            //         up: 'fa fa-chevron-up',
            //         down: 'fa fa-chevron-down',
            //         previous: 'fa fa-chevron-left',
            //         next: 'fa fa-chevron-right',
            //         today: 'fa fa-check',
            //         clear: 'fa fa-trash',
            //         close: 'fa fa-times'
            //     }
            // });
            $('#expiry_date').datetimepicker({
                // Formats
                // follow MomentJS docs: https://momentjs.com/docs/#/displaying/format/
                format: 'YYYY-MM-DD HH:mm:ss',

                // Your Icons
                // as Bootstrap 4 is not using Glyphicons anymore
                icons: {
                    time: 'fa fa-clock-o',
                    date: 'fa fa-calendar',
                    up: 'fa fa-chevron-up',
                    down: 'fa fa-chevron-down',
                    previous: 'fa fa-chevron-left',
                    next: 'fa fa-chevron-right',
                    today: 'fa fa-check',
                    clear: 'fa fa-trash',
                    close: 'fa fa-times'
                }
            });


        });

        function sendRequest(eventId) {

            swal({
                title: 'Are you sure?',
                text: 'Are you sure that you want to attend event?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Change it!'
            }).then(
                function (isConfirm) {
                    if (isConfirm.dismiss != 'cancel') {
                        $.ajax({
                            url: "{{ url('attend-event'). '/' }}" + eventId,
                            type: 'post',
                            data: {
                                "_token": "{{ csrf_token() }}"
                            },
                            success: function () {
                                // delete the row from the table

                                swal("Requested", "Request has been sent successfully.", "success");
                                setTimeout(function () {
                                    location.reload();
                                }, 2000)
                            },
                            error: function () {
                                // Show an alert with the result

                                swal("status not changed", "error");
                            }
                        });
                    }
                });
        }

        function CancelRequest(attendeId) {

            swal({
                title: 'Are you sure?',
                text: 'Are you sure that you want to cancel this event?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Change it!'
            }).then(
                function (isConfirm) {
                    if (isConfirm.dismiss != 'cancel') {
                        $.ajax({
                            url: "{{ url('cancel-event'). '/' }}" + attendeId,
                            type: 'post',
                            data: {
                                "_token": "{{ csrf_token() }}"
                            },
                            success: function () {
                                // delete the row from the table
                                swal("Requested", "Request has been sent successfully.", "success");
                                setTimeout(function () {
                                    location.reload();
                                }, 2000);
                            },
                            error: function () {
                                // Show an alert with the result

                                swal("status not changed", "error");
                            }
                        });
                    }
                });
        }

        $("#addEventForm").validate({
            rules: {
                collaborate_for: "required",
                sub: "required",
                remote_opportunity: "required",
                geo_focus: "required",
                industry_focus: "required",
                with_local_focus: "required",
                collaborate_with: "required",
                budget: "required",
                objective: "required",
                expiry_date: "required",
            },
            messages: {
                collaborate_for: "Please select any one",
                sub: "Please select atleast one ",
                remote_opportunity: "Please select",
                geo_focus: "Please select any country",
                industry_focus: "Please select any industry",
                with_local_focus: "Want to offer from local collborators",
                collaborate_with: "Select any One",
                budget: "Select Budget",
                objective: "Select Objective",
                expiry_date: "Select Expriy Date",
            },
            submitHandler: function (form) {

                var form_data = new FormData(document.getElementById("addEventForm"));
                $.ajax({
                    type: 'POST',
                    url: '{{ url('add-collborations') }}',
                    headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
                    data: form_data,
                    cache: false,
                    contentType: false, //must, tell jQuery not to process the data
                    processData: false,
                    success: function (response) {
                        var data = response;
                        if (data.status) {
                            toastr.success(response.message, "Success");

                        } else {
                            toastr.error(response.message, "Error");
                        }
                        setTimeout(function () {
                            location.reload();
                        }, 2000);

                    },
                    error: function (response) {
                        toastr.error(response.responseJSON.msg, "Error");
                        $(':input[type="submit"]').prop('disabled', false);
                    },
                });
            }
        });

        function checkLocation() {
            var eventType = $("#event_type").val();
            if (eventType == 'Hybrid' || eventType == 'Physical') {
                $("#location").show();
            } else {
                $("#location").hide();
            }
        }

        function checkFee() {
            var event_free_paid = $("#event_free_paid").val();
            if (event_free_paid == 'Paid') {
                $("#fee_check").show();
            } else {
                $("#fee_check").hide();
            }
        }

        $(document).ready(function () {
            $('#table_id').DataTable();
            $('#attend').DataTable();
        });
    </script>
@endsection

