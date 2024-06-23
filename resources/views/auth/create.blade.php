@extends('layouts.app', [
    'namePage' => 'Register page',
    'activePage' => 'register',
    'backgroundImage' => asset('assets') . "/img/register.jpg",
])

@section('content')
<div class="content">
    <div class="container">
        <div class="col-md-12 ml-auto mr-auto">
            <div class="header bg-gradient-primary py-10 py-lg-2 pt-lg-12">
                <div class="container">
                    <div class="header-body text-center mb-7">
                        <div class="row justify-content-center">
                            <div class="col-lg-5 col-md-6">
                                <div class="card card-signup">
                                    <div class="card-header card-header-primary text-center">
                                        <h4 class="card-title">{{ __('Register') }}</h4>
                                    </div>
                                    <div class="card-body">
                                        <form method="POST" action="{{ route('register') }}" id="register-form">
                                            @csrf
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="now-ui-icons users_circle-08"></i>
                                                    </span>
                                                </div>
                                                <div id="signinMessage" class="text-danger mb-2" style="display: none;"></div>
                                                <input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                                    placeholder="{{ __('Name') }}" name="name" value="{{ old('name') }}" required
                                                    autofocus>
                                                @if ($errors->has('name'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('name') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="now-ui-icons ui-1_email-85"></i>
                                                    </span>
                                                </div>
                                                <input type="email"
                                                    class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                                    placeholder="{{ __('Email') }}" name="email" value="{{ old('email') }}" required>
                                                @if ($errors->has('email'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="now-ui-icons objects_key-25"></i>
                                                    </span>
                                                </div>
                                                <input type="password"
                                                    class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                                    placeholder="{{ __('Password') }}" name="password" required>
                                                @if ($errors->has('password'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="now-ui-icons objects_key-25"></i>
                                                    </span>
                                                </div>
                                                <input type="password" class="form-control"
                                                    placeholder="{{ __('Confirm Password') }}" name="password_confirmation" required>
                                            </div>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="checkbox">
                                                    <span class="form-check-sign"></span>
                                                    {{ __('I agree to the') }}
                                                    <a href="#something">{{ __('terms and conditions') }}</a>.
                                                </label>
                                            </div>
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-primary btn-round mt-4">{{ __('Get Started') }}</button>
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
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        demo.checkFullPageBackgroundImage();
    });
</script>
@endpush