@extends('layouts.app', [
    'class' => 'sidebar-mini',
    'namePage' => 'Weather Data Display',
    'activePage' => 'weather',
    'activeNav' => '',
])

@section('content')
<div class="panel-header panel-header-lg">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="widget">
                    <h2>Manila</h2>
                    @if(isset($manilaWeather['error']))
                        <div class="alert alert-danger" role="alert">
                            {{ $manilaWeather['error'] }}
                        </div>
                    @else
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>City:</th>
                                    <td>{{ $manilaWeather['name'] }}</td>
                                </tr>
                                <tr>
                                    <th>Temperature:</th>
                                    <td>{{ $manilaWeather['main']['temp'] }}°C</td>
                                </tr>
                                <tr>
                                    <th>Weather:</th>
                                    <td>{{ $manilaWeather['weather'][0]['description'] }}</td>
                                </tr>
                                <tr>
                                    <th>Humidity:</th>
                                    <td>{{ $manilaWeather['main']['humidity'] }}</td>
                                </tr>
                                <!-- Add more fields as needed -->
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="widget">
                    <h2>Cebu</h2>
                    @if(isset($cebuWeather['error']))
                        <div class="alert alert-danger" role="alert">
                            {{ $cebuWeather['error'] }}
                        </div>
                    @else
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>City:</th>
                                    <td>{{ $cebuWeather['name'] }}</td>
                                </tr>
                                <tr>
                                    <th>Temperature:</th>
                                    <td>{{ $cebuWeather['main']['temp'] }}°C</td>
                                </tr>
                                <tr>
                                    <th>Weather:</th>
                                    <td>{{ $cebuWeather['weather'][0]['description'] }}</td>
                                </tr>
                                <tr>
                                    <th>Humidity:</th>
                                    <td>{{ $cebuWeather['main']['humidity'] }}</td>
                                </tr>
                                <!-- Add more fields as needed -->
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
