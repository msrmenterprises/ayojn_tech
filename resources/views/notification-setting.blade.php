@extends('layout')
@php  $buttons = $db_buttons['notification'];  @endphp

@section('content')
    <style>

        .cmn-toggle {
            position: absolute;
            margin-left: -9999px;
            visibility: hidden;
        }

        .cmn-toggle + label {
            display: block;
            position: relative;
            cursor: pointer;
            outline: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        input.cmn-toggle-round-flat + label {
            padding: 2px;
            width: 75px;
            height: 30px;
            background-color: #dddddd;
            -webkit-border-radius: 60px;
            -moz-border-radius: 60px;
            -ms-border-radius: 60px;
            -o-border-radius: 60px;
            border-radius: 60px;
            -webkit-transition: background 0.4s;
            -moz-transition: background 0.4s;
            -o-transition: background 0.4s;
            transition: background 0.4s;
        }

        input.cmn-toggle-round-flat + label:before, input.cmn-toggle-round-flat + label:after {
            display: block;
            position: absolute;
            content: "";
        }

        input.cmn-toggle-round-flat + label:before {
            top: 2px;
            left: 2px;
            bottom: 2px;
            right: 2px;
            background-color: #fff;
            -webkit-border-radius: 60px;
            -moz-border-radius: 60px;
            -ms-border-radius: 60px;
            -o-border-radius: 60px;
            border-radius: 60px;
            -webkit-transition: background 0.4s;
            -moz-transition: background 0.4s;
            -o-transition: background 0.4s;
            transition: background 0.4s;
        }

        input.cmn-toggle-round-flat + label:after {
            top: 4px;
            left: 4px;
            bottom: 4px;
            width: 22px;
            background-color: #dddddd;
            -webkit-border-radius: 52px;
            -moz-border-radius: 52px;
            -ms-border-radius: 52px;
            -o-border-radius: 52px;
            border-radius: 52px;
            -webkit-transition: margin 0.4s, background 0.4s;
            -moz-transition: margin 0.4s, background 0.4s;
            -o-transition: margin 0.4s, background 0.4s;
            transition: margin 0.4s, background 0.4s;
        }

        input.cmn-toggle-round-flat:checked + label {
            background-color: #27A1CA;
        }

        input.cmn-toggle-round-flat:checked + label:after {
            margin-left: 45px;
            background-color: #27A1CA;
        }
    </style>
    <div class="container">
        <br/>
        <br/>
        <br/>
        <br/>
        <div class="panel-group" id="accordion" style="margin-top: 31px;">
            <div class="faqHeader text-center">

                @if(session()->has('success'))
                    <div class="alert alert-success">
                        {{ session()->get('success') }}
                    </div>
                @endif
                @if (!empty($errors->first()))
                    <div class="alert alert-danger">
                        {{ $errors->first() }}

                    </div>
                @endif
            </div>
        </div>
        <form action="{{ route('set-notifications') }}" method="post">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3">
                    <div class="panel panel-default">
                        <!-- Default panel contents -->
                        <div class="panel-heading">Notification Settings
                            <button type="submit" class="btn btn-primary btn-sm pull-right" data-tooltip="true" data-placement="top"  title="{!!$buttons[0]['hover']!!}">{!!$buttons[0]['icon']!!} {!!$buttons[0]['name']!!} </button>
                        </div>
                        <!-- List group -->
                        <ul class="list-group">
{{--                            <li class="list-group-item">--}}
{{--                                <div class="switch">--}}
{{--                                    In-app Notification--}}
{{--                                    --}}
{{--                                    <label for="cmn-toggle-4"></label>--}}
{{--                                   --}}
{{--                                </div>--}}
{{--                            </li>--}}
                            <input type="hidden" name="inApp"  value="1">
                            <input type="hidden" name="id" value="{{ $notificationData->id }}">
                            <li class="list-group-item">
                                Email Notification
                                <div class="switch">
                                    <input id="cmn-toggle-5" class="cmn-toggle cmn-toggle-round-flat"
                                           type="checkbox" onchange="checkDays()" value="1"
                                           name="emailNotification" @if($notificationData->email == 1) checked @endif>
                                    <label for="cmn-toggle-5"></label>
                                </div>
                                <div id="days_id" style="display: none">
                                    Please select the day you wish to be notified or say as it happens.
                                    <select class="form-control" name="days">
                                        <option value="">As it Happens</option>
                                        <option value="1" @if($notificationData->days == 1) Selected @endif>Monday
                                        </option>
                                        <option value="2" @if($notificationData->days == 2) Selected @endif>Tuesday
                                        </option>
                                        <option value="3" @if($notificationData->days == 3) Selected @endif>Wednesday
                                        </option>
                                        <option value="4" @if($notificationData->days == 4) Selected @endif>Thursday
                                        </option>
                                        <option value="5" @if($notificationData->days == 5) Selected @endif>Friday
                                        </option>
                                        <option value="6" @if($notificationData->days == 6) Selected @endif>Saturday
                                        </option>
                                        <option value="7" @if($notificationData->days == 7) Selected @endif>Sunday
                                        </option>

                                    </select>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script>
        @if($notificationData->email == 1)
        checkDays();

        @endif
        function checkDays() {
            if ($("input[name='emailNotification']:checked").val() != 'undefined' && $("input[name='emailNotification']:checked").val() == 1) {
                $("#days_id").show();
            } else {
                $("#days_id").hide();

            }

        }
    </script>
@endsection
