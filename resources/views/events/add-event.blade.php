@extends('layout')

@section('content')
    <style>
        input {
            border: 1px solid transparent;
            background-color: #f1f1f1;
            padding: 10px;
            font-size: 16px;
        }

        input[type=text] {
            background-color: #f1f1f1;
            width: 100%;
        }

        input[type=submit] {
            background-color: DodgerBlue;
            color: #fff;
            cursor: pointer;
        }
    </style>
    <section id="feed" class="speakers">
        <div class="container">
            <div class="row" style="text-align: center;padding-top: 15px;">
                <div class="row">
                    <h3>Host Event</h3>
                </div>
                <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-6">
                        Let’s host your event with ayojn
                    </div>
                </div>
                <!-- banner section start -->
                <form name="add-event" method="post" id="add-event" action="{{ url('add-new-event') }}">
                    <div id="add-event-form">
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="event_title" id="event_title"
                                       placeholder="Event Title">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">
                                <input type="date" class="form-control" name="event_date" id="event_date"
                                       placeholder="Event Date">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">

                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary"
                            >Submit
                            </button> &nbsp;
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </section>

    <script src="{{ asset('js/jquery1.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/typed.min.js') }}"></script>
@endsection
