@extends('layouts.app', [
    'namePage' => 'Login page',
    'class' => 'login-page sidebar-mini ',
    'activePage' => 'login',
    'backgroundImage' => asset('assets') . "/img/login.jpg",
])

@section('content')
    <div class="content">
        <div class="container">
            <div class="col-md-12 ml-auto mr-auto">
                <div class="header bg-gradient-primary py-10 py-lg-2 pt-lg-12">
                    <div class="container">
                        <div class="header-body text-center mb-7">
                            <div class="row justify-content-center">
                                <div class="col-lg-12 col-md-9">
                                <img src="{{ asset('assets') }}/img/sugarbloom.png" alt="Sugarbloom Logo" style="width: 150px; height: 140px;">    
                                <h1 class="text-lead text-light mt-3 mb-0" style="font-family: 'Pacifico', cursive; font-size: 3rem;">Welcome to SugarBloom Bakery!</h1>
                                    <br>
                                    <p class="text-light" style="font-family: 'Roboto', sans-serif;">Where every bite is a taste of happiness.</p>
                                    @include('alerts.migrations_check')
                                <div class="col-lg-5 col-md-6">
                                    <!-- Add any additional content here -->
                                </div>
                            </div>
                        </div>
                    </div>        
            <div class="col-md-4 ml-auto mr-auto">
                <form id="loginForm" action="{{ route('ajax.login') }}" method="POST">
                    @csrf
                    <div class="card card-login card-plain">
                        <div class="card-body bg-transparent">
                            <div id="loginMessage" class="text-danger mb-2" style="display: none;"></div>
                            <div id="errorText" class="text-danger mb-2" style="display: none;"></div>
                            <div class="google-signin-container">
                                <div id="g_id_onload" 
                                    data-client_id="{{ env('GOOGLE_CLIENT_ID') }}"
                                    data-callback="onSignIn">
                                </div>
                                <div class="g_id_signin google-signin-button" data-type="standard">
                                    <span class="google-signin-text">Sign in with Google</span>
                                </div>
                               
                                    <style>
                                    .google-signin-container {
                                        display: flex;
                                        flex-direction: column;
                                        align-items: center;
                                    }

                                    .google-signin-button {
                                        display: inline-block;
                                        padding: 10px 20px;
                                        border: 1px solid #ccc;
                                        border-radius: 5px;
                                        background-color: #fff;
                                        cursor: pointer;
                                        transition: background-color 0.3s ease;
                                    }

                                    .google-signin-button:hover {
                                        background-color: #f1f1f1;
                                    }

                                    .google-signin-text {
                                        font-size: 16px;
                                        color: #333;
                                    }

                                    /* Responsive design for smaller screens */
                                    @media (max-width: 768px) {
                                        .google-signin-container {
                                            width: 100%;
                                        }

                                        .google-signin-button {
                                            width: 100%;
                                            text-align: center;
                                        }
                                    }
                                </style>
                            <br>
                            <div class="input-group no-border form-control-lg">
                                <span class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="now-ui-icons users_circle-08"></i>
                                    </div>
                                </span>
                                <input class="form-control" placeholder="{{ __('Email') }}" type="email" name="email" required autofocus>
                            </div>
                            <div class="input-group no-border form-control-lg">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="now-ui-icons objects_key-25"></i>
                                    </div>
                                </div>
                                <input placeholder="{{ __('Password') }}" class="form-control" name="password" type="password" required>
                            </div>
                        </div>
                        <div class="card-footer ">
                            <button type="submit" id="loginButton" class="btn btn-primary btn-round btn-lg btn-block mb-3">{{ __('Get Started') }}</button>
                            
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
<script src="{{ asset('assets') }}/js/core/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://accounts.google.com/gsi/client" async defer></script>

@push('js')
<script>
$(document).ready(function() {
    demo.checkFullPageBackgroundImage();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Retrieve persisted states from localStorage
    var wrongAttempts = parseInt(localStorage.getItem('wrongAttempts')) || 0;
    var disableLogin = localStorage.getItem('disableLogin') === 'true';

    if (disableLogin) {
        $('#loginMessage').text('Too many login attempts. Please try again later.').css('color', 'red').show();
    }

    $('#loginForm').submit(function(e) {
        e.preventDefault();

        if (disableLogin) {
            return;
        }

        $('#loginButton').prop('disabled', true);
        $('#loginMessage').hide().text('');

        $.ajax({
            url: '{{ route("ajax.login") }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    $('#loginButton').html('Redirecting....');
                    setTimeout(function() {
                        window.location.href = '{{ route("home") }}';
                    }, 2000);  // Redirect after 2 seconds
                } else {
                    if (response.message === 'Invalid password') {
                        wrongAttempts++;
                        localStorage.setItem('wrongAttempts', wrongAttempts);
                        if (wrongAttempts >= 3) {
                            $('#loginMessage').text('Too many login attempts. Please try again later.').css('color', 'red').show();
                            disableLogin = true;
                            localStorage.setItem('disableLogin', true);
                        } else {
                            $('#loginMessage').text('Invalid password. Attempts left: ' + response.attemptsLeft).css('color', 'red').show();
                        }
                    } else {
                        $('#loginMessage').text(response.message).css('color', 'red').show();
                    }
                }
            },
            error: function(xhr, status, error) {
                if (xhr.status === 429) {
                    var countDown = 60;
                    var interval = setInterval(function() {
                        $('#loginMessage').text('Too many login attempts. Please try again after ' + countDown + ' seconds.').css('color', 'red').show();
                        countDown--;
                        if (countDown === 0) {
                            clearInterval(interval);
                            $('#loginButton').prop('disabled', false).html('{{ __("Get Started") }}');
                            wrongAttempts = 0;
                            disableLogin = false;
                            localStorage.removeItem('wrongAttempts');
                            localStorage.removeItem('disableLogin');
                        }
                    }, 1000);
                } else {
                    console.error(xhr.responseText);
                    $('#loginMessage').text('Invalid credentials. Attempts left: ' + xhr.responseJSON.attemptsLeft).css('color', 'red').show();
                    $('#loginButton').prop('disabled', false);
                }
            },
            complete: function() {
                setTimeout(function() {
                    $('#loginButton').prop('disabled', false).html('{{ __("Get Started") }}');
                    wrongAttempts = 0;
                    disableLogin = false;
                    localStorage.removeItem('wrongAttempts');
                    localStorage.removeItem('disableLogin');
                }, 60000);
            }
        });
    });
});


       
    </script>
@endpush
<!-- Include SweetAlert library -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function decodeJwtResponse(token) {
    let base64Url = token.split('.')[1];
    let base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
    let jsonPayload = decodeURIComponent(atob(base64).split('').map(function(c) {
        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
    }).join(''));
    return JSON.parse(jsonPayload);
}

$.ajaxSetup({
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
});

window.onSignIn = googleUser => {
    var user = decodeJwtResponse(googleUser.credential);
    if (user) {
        $.ajax({
            url: 'login',
            method: 'post',
            data: { email: user.email }, // pass the email to your controller
            beforeSend: function() {},
            success: function(response) {
                if (response.status === 'success') {
                    console.log(response.message); // This will log 'Login successful'
                    Swal.fire({
                        icon: 'success',
                        title: 'Login Successful',
                        text: 'Redirecting...',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                        didClose: () => {
                            window.location.href = '{{ route("home") }}';
                        }
                    });
                } else {
                    // Display error message for unauthorized account or other errors
                    Swal.fire({
                        icon: 'error',
                        title: 'Unauthorized Account',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true
                    });
                }
            },
            error: function(xhr, status, error) {
                // Handle error cases
                console.error(xhr.responseText);
                // Display error message
                Swal.fire({
                    icon: 'error',
                    title: 'Error: ' + xhr.status,
                    text: 'An error occurred while processing your request.',
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true
                });
            }
        });
    } else {
        // Handle case when user is not authenticated
        console.error('User not authenticated');
    }
};
</script>
