@extends('layout')

@section('content')

    <!-- banner section start -->
    <link rel="stylesheet" href="{{ asset('css/slick-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/slick.css') }}">
    <style>
        .panel-login {
            border-color: #ccc;
            -webkit-box-shadow: 0px 2px 3px 0px rgba(0, 0, 0, 0.2);
            -moz-box-shadow: 0px 2px 3px 0px rgba(0, 0, 0, 0.2);
            box-shadow: 0px 2px 3px 0px rgba(0, 0, 0, 0.2);
        }

        .panel-login > .panel-heading {
            color: #00415d;
            background-color: #fff;
            border-color: #fff;
            text-align: center;
        }

        .panel-login > .panel-heading a {
            text-decoration: none;
            color: #666;
            font-weight: bold;
            font-size: 15px;
            -webkit-transition: all 0.1s linear;
            -moz-transition: all 0.1s linear;
            transition: all 0.1s linear;
        }

        .panel-login > .panel-heading a.active {
            color: #029f5b;
            font-size: 18px;
        }

        .panel-login > .panel-heading hr {
            margin-top: 10px;
            margin-bottom: 0px;
            clear: both;
            border: 0;
            height: 1px;
            background-image: -webkit-linear-gradient(left, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.15), rgba(0, 0, 0, 0));
            background-image: -moz-linear-gradient(left, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.15), rgba(0, 0, 0, 0));
            background-image: -ms-linear-gradient(left, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.15), rgba(0, 0, 0, 0));
            background-image: -o-linear-gradient(left, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.15), rgba(0, 0, 0, 0));
        }

        .panel-login input[type="text"], .panel-login input[type="email"], .panel-login input[type="password"] {
            height: 45px;
            border: 1px solid #ddd;
            font-size: 16px;
            -webkit-transition: all 0.1s linear;
            -moz-transition: all 0.1s linear;
            transition: all 0.1s linear;
        }

        .panel-login input:hover,
        .panel-login input:focus {
            outline: none;
            -webkit-box-shadow: none;
            -moz-box-shadow: none;
            box-shadow: none;
            border-color: #ccc;
        }

        .btn-login {
            background-color: #59B2E0;
            outline: none;
            color: #fff;
            font-size: 14px;
            height: auto;
            font-weight: normal;
            padding: 14px 0;
            text-transform: uppercase;
            border-color: #59B2E6;
        }

        .btn-login:hover,
        .btn-login:focus {
            color: #fff;
            background-color: #53A3CD;
            border-color: #53A3CD;
        }

        .forgot-password {
            text-decoration: underline;
            color: #888;
        }

        .forgot-password:hover,
        .forgot-password:focus {
            text-decoration: underline;
            color: #666;
        }

        .btn-register {
            background-color: #1CB94E;
            outline: none;
            color: #fff;
            font-size: 14px;
            height: auto;
            font-weight: normal;
            padding: 14px 0;
            text-transform: uppercase;
            border-color: #1CB94A;
        }

        .btn-register:hover,
        .btn-register:focus {
            color: #fff;
            background-color: #1CA347;
            border-color: #1CA347;
        }

        .error {
            color: red;
        }

    </style>

    <div class="flw main-body stories">
        <div class="inner-main-body">
            <div class="container">
                <div class="row section">
                    <div class="col-md-3 "></div>
                    <div class="col-md-6 ">
                        <div class="panel panel-login">
                            <div class="panel-heading">
                                <div class="row">
                                <h3 class="card-header" style="margin-bottom:10px;">MFA Verification</h3>
                                </div>
                                <div class="row">
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
                                <hr>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <form id="login-form" action="{{ url('partner/verify_mfa') }}" method="post"
                                              role="form" style="display: block;">

                           
                                           
                                            <div class="form-group" id="opt_div">
                                                <input type="text" name="p_otp" id="p_otp"
                                                       class="form-control" placeholder="OTP">
                                            </div>
                                    
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-6 col-sm-offset-3">
                                                        <input type="submit" name="login-submit" id="login-submit"
                                                               tabindex="4" class="form-control btn btn-login"
                                                               value="Verify & Login">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="text-center">
                                                            <a href="{{ url('/partner/resent_mfa') }}" tabindex="5"
                                                               class="forgot-password">Resend</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            
                                        </form>
                                
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="{{ asset('js/jquery1.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/typed.min.js') }}"></script>
    <script src="{{ asset('js/slick.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/additional-methods.min.js"></script>
    <script>
        $(function () {

           

            jQuery.validator.addMethod("specialChars", function (value, element) {
                var regex = new RegExp("^[0-9+]+$");
                var key = value;

                if (!regex.test(key)) {
                    return false;
                }
                return true;
            }, "please enter valid phone number");

            $('#login-form-link').click(function (e) {
                $("#login-form").delay(100).fadeIn(100);
                $("#register-form").fadeOut(100);
                $('#register-form-link').removeClass('active');
                $(this).addClass('active');
                e.preventDefault();
            });
            $('#register-form-link').click(function (e) {
                $("#register-form").delay(100).fadeIn(100);
                $("#login-form").fadeOut(100);
                $('#login-form-link').removeClass('active');
                $(this).addClass('active');
                e.preventDefault();
            });

        });

        $("#register-form").validate({
            rules: {
                company_name: 'required',
                partner_phone_no: {
                    required: true,
                    specialChars: true,
                },
                partner_password: 'required',
                partner_email: {
                    required: true,
                    email: true
                },
                contact_person: 'required',
                terms: {
                    required: true
                }
            }, messages: {
                company_name: 'Please enter company name',
                partner_phone_no: {
                    required: 'Please enter phone number',
                },
                partner_password: 'Please enter password',
                partner_email: {
                    required: "Please register with a professional email address",
                    email: "Please register with a professional email address"
                },
                terms: {
                    required: "Please accept terms & Policy"
                },
                contact_person: "Please enter contact person"
            },
            errorPlacement: function (error, element) {
                //Custom position: first name
                if (element.attr("name") == "terms") {
                    $("#errNm1").text('Please accept terms and conditions');
                } else {
                    error.insertAfter(element);

                }

            },
        });
    </script>
@endsection
