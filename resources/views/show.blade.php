<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Data Display</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css"
        integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr"
        crossorigin="anonymous">
    <!-- CSS Files -->
    <link href="{{ asset('assets') }}/css/bootstrap.min.css" rel="stylesheet" />
    <link href="{{ asset('assets') }}/css/now-ui-dashboard.css?v=1.3.0" rel="stylesheet" />
    <style>
        .weather-widget {
            border-radius: 10px;
            /* Apply gradient background */
            background: linear-gradient(to bottom, #87CEEB, #1E90FF);
            /* Other existing styles */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .weather-widget::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('https://img.freepik.com/free-vector/gorgeous-clouds-background-with-blue-sky-design_1017-25501.jpg?size=626&ext=jpg&ga=GA1.1.87170709.1707696000&semt=ais');
            background-size: cover;
            background-position: center;
            filter: blur(5px);
            z-index: -1;
        }

        .weather-icon {
            width: 80px;
            height: 80px;
            margin-bottom: 10px;
        }

        .weather-location {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .weather-temp {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .weather-description {
            font-size: 14px;
            color: #666666;
            margin-bottom: 10px;
        }

        .weather-details {
            font-size: 14px;
            color: #666666;
        }

        .forecast {
            margin-top: 20px;
        }

        .forecast-item {
            padding: 5px;
            border-radius: 10px;
            background-color: #f4f4f4;
            margin-right: 10px;
            text-align: center;
        }

        .forecast-icon {
            width: 40px;
            height: 40px;
        }

        .forecast-temp {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .forecast-day {
            font-size: 12px;
            color: #666666;
        }

        .btn-back {
            border-radius: 25px;
            font-size: 16px;
            padding: 10px 30px;
            background-color: #007bff;
            color: #ffffff;
            border: none;
            transition: all 0.3s;
        }

        .btn-back:hover {
            background-color: #0056b3;
            color: #ffffff;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <!-- Manila Weather Widget -->
            <div class="col-md-6 mb-4">
                <div class="weather-widget">
                    <!-- Manila Weather Content -->
                    <!-- Manila Current Weather Content -->
                    <div class="weather-content">
                        <!-- Manila Current Weather Data -->
                        <div class="weather-icon">
                            <img src="https://openweathermap.org/img/wn/{{ $manilaWeather['weather'][0]['icon'] }}.png"
                                alt="{{ $manilaWeather['weather'][0]['description'] }}">
                        </div>
                        <div class="weather-location">Manila</div>
                        <div class="weather-temp">{{ $manilaWeather['main']['temp'] }}°C</div>
                        <div class="weather-description">{{ $manilaWeather['weather'][0]['description'] }}</div>
                        <div class="weather-details">
                            <i class="fas fa-wind"></i> Wind: {{ $manilaWeather['wind']['speed'] }} km/h<br>
                            <i class="fas fa-tint"></i> Humidity: {{ $manilaWeather['main']['humidity'] }}%
                        </div>
                        <!-- Forecast -->
                        <div class="forecast">
                            <div class="row justify-content-center"> <!-- Justify content -->
                                @foreach($manilaWeatherForecast['list'] as $forecast)
                                <div class="col-md-2 forecast-item">
                                    <div class="forecast-icon">
                                        <img src="https://openweathermap.org/img/wn/{{ $forecast['weather'][0]['icon'] }}.png"
                                            alt="{{ $forecast['weather'][0]['description'] }}">
                                    </div>
                                    <div class="forecast-temp">{{ $forecast['main']['temp'] }}°C</div>
                                    <div class="forecast-day">{{ date('D', strtotime($forecast['dt_txt'])) }}</div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cebu Weather Widget -->
            <div class="col-md-6 mb-4">
                <div class="weather-widget">
                    <!-- Cebu Weather Content -->
                    <!-- Cebu Current Weather Content -->
                    <div class="weather-content">
                        <!-- Cebu Current Weather Data -->
                        <div class="weather-icon">
                            <img src="https://openweathermap.org/img/wn/{{ $cebuWeather['weather'][0]['icon'] }}.png"
                                alt="{{ $cebuWeather['weather'][0]['description'] }}">
                        </div>
                        <div class="weather-location">Cebu</div>
                        <div class="weather-temp">{{ $cebuWeather['main']['temp'] }}°C</div>
                        <div class="weather-description">{{ $cebuWeather['weather'][0]['description'] }}</div>
                        <div class="weather-details">
                            <i class="fas fa-wind"></i> Wind: {{ $cebuWeather['wind']['speed'] }} km/h<br>
                            <i class="fas fa-tint"></i> Humidity: {{ $cebuWeather['main']['humidity'] }}%
                        </div>
                        <!-- Forecast -->
                        <div class="forecast">
                            <div class="row justify-content-center"> <!-- Justify content -->
                                @foreach($cebuWeatherForecast['list'] as $forecast)
                                <div class="col-md-2 forecast-item">
                                    <div class="forecast-icon">
                                        <img src="https://openweathermap.org/img/wn/{{ $forecast['weather'][0]['icon'] }}.png"
                                            alt="{{ $forecast['weather'][0]['description'] }}">
                                    </div>
                                    <div class="forecast-temp">{{ $forecast['main']['temp'] }}°C</div>
                                    <div class="forecast-day">{{ date('D', strtotime($forecast['dt_txt'])) }}</div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
       

        <!-- Button to go back to home -->
        <div class="row justify-content-center mt-3">
            <button class="btn btn-back" onclick="goBack()">Go Back</button>
        </div>
    </div>

    <!-- Now UI JavaScript -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
  <!-- CSS Files -->
  <link href="{{ asset('assets') }}/css/bootstrap.min.css" rel="stylesheet" />
  <link href="{{ asset('assets') }}/css/now-ui-dashboard.css?v=1.3.0" rel="stylesheet" />
    <script>
        // JavaScript function to go back to the home page
        function goBack() {
            window.history.back();
        }
    </script>
</body>

</html>
