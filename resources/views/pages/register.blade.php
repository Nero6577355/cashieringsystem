@php
    $user = auth()->user();
    $role = $user->roles ?? '';
@endphp

@extends('layouts.app', [
    'namePage' => 'Table List',
    'class' => 'sidebar-mini',
    'activePage' => 'register',
])

@if($role !== 'manager')
    @section('content')
        <style>
            body {
                overflow: hidden;
            }
        </style>
        <div class="content d-flex justify-content-center align-items-center" style="height: 100vh;">
            <div class="alert alert-danger text-center">
                <strong>Error:</strong> You cannot view this page.
            </div>
        </div>
    @endsection
@endif

@if($role === 'manager')
    @section('content')
        <div class="panel-header panel-header-sm"></div>
        <div class="content">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card card-user">
                            <div class="image">
                                <img src="{{asset('assets')}}/img/register1.jpg" alt="...">
                            </div>
                        </div>
                        <div class="card-header">
                            <h5 class="title">{{__("Register New Cashier")}}</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('cashiers.store') }}" id="register-form">
                                @csrf
                                @if(session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif

                                @if(session('error'))
                                    <div class="alert alert-danger">{{ session('error') }}</div>
                                @endif

                                <input type="hidden" id="otp_sent" name="otp_sent" value="{{ old('otp_sent', 'false') }}">

                                <div class="row">
        <div class="col-md-7 pr-1">
            <div class="form-group">
                <label for="first_name">{{__("First Name")}}</label>
                <input type="text" id="first_name" name="first_name" class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}" value="{{ old('first_name') }}" required autocomplete="first_name">
                @if ($errors->has('first_name'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('first_name') }}</strong>
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-7 pr-1">
            <div class="form-group">
                <label for="middle_name">{{__("Middle Name")}}</label>
                <input type="text" id="middle_name" name="middle_name" class="form-control{{ $errors->has('middle_name') ? ' is-invalid' : '' }}" value="{{ old('middle_name') }}" autocomplete="middle_name">
                @if ($errors->has('middle_name'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('middle_name') }}</strong>
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-7 pr-1">
            <div class="form-group">
                <label for="last_name">{{__("Last Name")}}</label>
                <input type="text" id="last_name" name="last_name" class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" value="{{ old('last_name') }}" required autocomplete="last_name">
                @if ($errors->has('last_name'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('last_name') }}</strong>
                    </span>
                @endif
            </div>
        </div>
                                </div>

                                <!-- Hidden name field -->
                                <input type="hidden" id="name" name="name" value="">

                                <div class="row">
                                    <div class="col-md-7 pr-1">
                                        <div class="form-group">
                                            <label for="email">{{ __('E-Mail Address') }}</label>
                                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" {{ old('otp_sent') === 'true' ? 'readonly' : '' }}>
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <small id="email-help" class="form-text text-muted" style="{{ old('otp_sent') === 'true' ? 'display: block;' : 'display: none;' }}">OTP has been sent to this email. It can't be changed.</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-7 pr-1">
                                        <div class="form-group">
                                            <label>{{__("Password")}}</label>
                                            <input type="password" id="password" class="form-control" placeholder="{{ __('Password') }}" name="password" required>
                                            <span class="text-danger" id="password-error"></span>
                                            @include('alerts.feedback', ['field' => 'password'])
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-7 pr-1">
                                        <div class="form-group">
                                            <label>{{__("Confirm Password")}}</label>
                                            <input type="password" id="confirm-password" class="form-control" placeholder="{{ __('Confirm Password') }}" name="password_confirmation" required>
                                            <span class="text-danger" id="confirm-password-error"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-7 pr-1">
                                        <div class="form-group">
                                            <label for="otp">{{ __('Enter OTP for email confirmation') }}</label>
                                            <div class="input-group">
                                                <input id="otp" type="text" class="form-control @error('otp') is-invalid @enderror" name="otp" required style="width: 70%;">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-primary" id="sendOTP">Send OTP</button>
                                                </div>
                                            </div>
                                            @error('otp')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary btn-round">{{__('Register')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.getElementById('sendOTP').addEventListener('click', function () {
                    sendOTP();
                });

                document.getElementById('register-form').addEventListener('submit', function (event) {
                    event.preventDefault();

                    const firstName = document.getElementById('first_name').value.trim();
                    const middleName = document.getElementById('middle_name').value.trim();
                    const lastName = document.getElementById('last_name').value.trim();

                    if (firstName.trim() === '' || middleName.trim() === '' || lastName.trim() === '') {
                    alert('Please fill in first name, middle name, and last name.');
                    event.preventDefault(); // Prevent form submission
                }

                    const fullName = `${firstName} ${middleName} ${lastName}`.trim();
                    document.getElementById('name').value = fullName;

                    if (!validateForm()) {
                        return;
                    }

                    var formData = $(this).serialize();
                    var email = $('input[name="email"]').val();

                    $.ajax({
                        type: 'POST',
                        url: '{{ route('register.check-email') }}',
                        data: {
                            '_token': '{{ csrf_token() }}',
                            'email': email
                        },
                        success: function (data) {
                            if (data.exists) {
                                $('#email-error').text('This email has already been taken').show();
                            } else {
                                $('#register-form').unbind('submit').submit();
                            }
                        },
                        error: function (xhr) {
                            var errors = xhr.responseJSON.errors;
                            if (errors && errors.otp) {
                                $('#otp-error').text(errors.otp[0]).show();
                            }
                        }
                    });
                });
                

                function sendOTP() {
                    var email = document.getElementById('email').value;

                    axios.post('/generate-otp', { email: email })
                        .then(function (response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'OTP Sent',
                                text: 'OTP sent for email confirmation.',
                                showConfirmButton: false,
                                timer: 2000
                            });
                            document.getElementById('email').readOnly = true;
                            document.getElementById('email-help').style.display = 'block';
                            document.getElementById('otp_sent').value = 'true';
                        })
                        .catch(function (error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Failed to Send OTP',
                                text: 'There was an error sending OTP. Please try again later.',
                                showConfirmButton: false,
                                timer: 2000
                            });
                            console.error('Failed to send OTP', error);
                        });
                }

                    function validateNameFields() {
                    const firstName = document.getElementById('first_name').value.trim();
                    const middleName = document.getElementById('middle_name').value.trim();
                    const lastName = document.getElementById('last_name').value.trim();

                    if (firstName === '' || middleName === '' || lastName === '') {
                        alert('Please fill in first name, middle name, and last name.');
                        return false;
                    }

                    return true;
                }
                
                function validateForm() {
                    var password = $('input[name="password"]').val();
                    var confirmPassword = $('input[name="password_confirmation"]').val();

                    if (password.length < 8 || !containsSpecialCharacter(password) || !containsUpperCase(password) || !containsLowerCase(password) || !containsNumber(password)) {
                        $('#password-error').text('Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character.').show();
                        return false;
                    }

                    if (password !== confirmPassword) {
                        $('#confirm-password-error').text('The password field confirmation does not match.').show();
                        return false;
                    }

                    return true;
                }

                function containsSpecialCharacter(str) {
                    var pattern = /[ `!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~]/;
                    return pattern.test(str);
                }

                function containsUpperCase(str) {
                    var pattern = /[A-Z]/;
                    return pattern.test(str);
                }

                function containsLowerCase(str) {
                    var pattern = /[a-z]/;
                    return pattern.test(str);
                }

                function containsNumber(str) {
                    var pattern = /\d/;
                    return pattern.test(str);
                }

                @if(Session::has('register_success'))
                    Swal.fire(
                        'Success!',
                        '{{ Session::get('register_success') }}',
                        'success'
                    );
                @endif
            });
        </script>
    @endsection
@endif

