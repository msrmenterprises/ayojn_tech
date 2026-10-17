@extends('layout')

@section('content')
    <!-- banner section start -->
    <style>
        .body-contianer.only-scroll-content {
            overflow:auto;
            padding-bottom: 0;
        }
    </style>
    <div class="flw main-body stories" style="height:300px; overflow:auto;">
        <div class="inner-main-body">
            <div class="container">
                <div class="row section">
                    <div class="col-lg-6 col-xs-12">
                        <div class="banner-text">

                            <h1 class="black-color m-0" style="font-weight: 300 !important; ">
                                #DOOHUAE : RoO* via DOOH
								<br/>RoO: Return on Objectives
                                <br/>
                                Gulf Standard Time: 10:00 - 13:00
                            </h1>

                            <a href="javascript:void(0)" class="btn btn-primary-new" data-toggle="modal"
                               data-target="#loginForm">Access the Event</a>


                        </div>
                    </div>
                    <div class="col-lg-6 col-xs-12">
                        <img src="images/DOOH_UAE.jpg" class="img-responsive"/>
                    </div>
                </div>

                <div class="row section">
                    <div class="col-lg-12 col-xs-12">
                        <p>Attention Economy has led us at a crossroad where marketers are fighting for the scarce commodity of attention and with digital ad spend climbing up, MENA is still an underperformer in terms of growth. Digital is the way to go and with rapid digital transformation push in the region overall, the operators must leverage smart technologies to integrate digital and stay relevant and exceed in terms of ROI to the advertisers.</p>
                        <p>Digital OOH growth trajectory depends upon a comprehensive readjustment to the new realities.</p>
                        <p>#DOOHUAE is an interactive platform for dialogue and networking for senior executives in the media space to discuss how to leverage the smart technologies to integrate digital and stay relevant and exceed in terms of ROO to the advertisers.</p>
                        <p>This event is a focused platform to explore case studies & market insights for the Brand owners, Marketing Professionals and the Technology Providers.</p>
                        <p>* Attendance is limited to the number of seats available.</p>
                        <p>Agenda Highlights:
                            <ul>
                                <li>Case Studies</li>
                                <li>Agency Presentations</li>
                                <li>Panel Talks</li>
                                <li>Networking Opportunities</li>
                                <li>Booth/ Exhibition Opportunities</li>

                            </ul>
                        </p>

                    </div>

                </div>


            </div>

        </div>
    </div>


    <script src="{{ asset('js/jquery1.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/typed.min.js') }}"></script>

@endsection
