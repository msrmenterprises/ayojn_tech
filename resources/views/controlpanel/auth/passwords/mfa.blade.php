@extends('adminlte::master')

@section('adminlte_css')
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/css/auth.css') }}">
    @yield('css')
    <style>
    
        #login-overlay {
    display: none;
    position: fixed;
    z-index: 9999;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(255,255,255,0.7);
}
.overlay-spinner {
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
}
.spinner-border {
    display: inline-block;
    width: 2.5rem;
    height: 2.5rem;
    vertical-align: text-bottom;
    border: 0.3em solid #007bff;
    border-right-color: transparent;
    border-radius: 50%;
    animation: spinner-border .75s linear infinite;
}
@keyframes spinner-border {
    100% { transform: rotate(360deg); }
}
    </style>
@stop

@section('body_class', 'login-page')

@section('body')

  <div id="login-overlay" style="display:none;">
    <div class="overlay-spinner">
        <span class="spinner-border"></span>
        <div>Loading...</div>
    </div>
</div>
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}">{!! config('adminlte.logo', '<b>Admin</b>LTE') !!}</a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">Enter MFA</p>
            <form action="{{ route('verify_admin_mfa') }}" method="post">
                {!! csrf_field() !!}

                
                <div class="form-group has-feedback">
                    <input type="text" name="a_otp" class="form-control" value="{{ old('a_otp') }}"
                           placeholder="MFA Code" required>
                    <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                    @if ($errors->has('otp'))
                        <span class="help-block" style="color:red;">
                            <strong>{{ $errors->first('otp') }}</strong>
                        </span>
                    @endif
                    
                </div>
                <div class="form-group">
                    <a href="{{route('resent_admin_mfa')}}" class="text-right" style="display:block">Resent</a></div>

                    <div class="form-group">
                
                         <button type="submit"
                        class="btn btn-primary btn-block btn-flat">Login</button>
                   
                          <button type="button" class="btn btn-danger btn-block btn-flat" onclick="window.location.href='{{ url('/controlpanel/login') }}'">
                    Back  </button>
                   
</div>
               
              
            </form>
        </div>
        <!-- /.login-box-body -->
    </div><!-- /.login-box -->
@stop

@section('adminlte_js')

<script>
    $(function () {
         $('form').on('submit', function() {
        $('#login-overlay').show();
        $(this).find('button[type=submit]').prop('disabled', true);
    });
    });
</script>
    @yield('js')
@stop
