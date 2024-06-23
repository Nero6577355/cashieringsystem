<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class WeatherController extends Controller
{
    public function getWeatherData($city)
    {
        try {
            // Initialize Guzzle HTTP client
            $client = new Client();

            // Make request to OpenWeatherMap API for current weather
            $response = $client->get('http://api.openweathermap.org/data/2.5/weather', [
                'query' => [
                    'q' => $city,
                    'appid' => '413e2aa1280bef2efd1f52ad78dc93a6',
                    'units' => 'metric',
                ]
            ]);

            // Decode the JSON response
            $data = json_decode($response->getBody(), true);

            // Check if the API returned an error
            if (isset($data['cod']) && $data['cod'] != 200) {
                return ['error' => $data['message']];
            }

            return $data;
        } catch (RequestException $e) {
            // Handle Guzzle HTTP request exceptions
            return ['error' => 'Failed to fetch weather data.'];
        }
    }

    public function getWeatherForecast($city)
    {
        try {
            $client = new Client();

            $response = $client->get('http://api.openweathermap.org/data/2.5/forecast', [
                'query' => [
                    'q' => $city,
                    'appid' => '413e2aa1280bef2efd1f52ad78dc93a6',
                    'units' => 'metric',
                    'cnt' => 5, // Request forecast for 5 days
                ]
            ]);

            // Decode the JSON response
            $data = json_decode($response->getBody(), true);

            // Check if the API returned an error
            if (isset($data['cod']) && $data['cod'] != 200) {
                return ['error' => $data['message']];
            }

            return $data;
        } catch (RequestException $e) {
            // Handle Guzzle HTTP request exceptions
            return ['error' => 'Failed to fetch weather forecast data.'];
        }
    }

    public function showWeatherData(Request $request)
    {
        // Get current weather data for Manila
        $manilaWeather = $this->getWeatherData('Manila');

        // Get current weather data for Cebu
        $cebuWeather = $this->getWeatherData('Cebu');

        // Get weather forecast data for Manila
        $manilaWeatherForecast = $this->getWeatherForecast('Manila');

        // Get weather forecast data for Cebu
        $cebuWeatherForecast = $this->getWeatherForecast('Cebu');

        return view('show', [
            'manilaWeather' => $manilaWeather,
            'cebuWeather' => $cebuWeather,
            'manilaWeatherForecast' => $manilaWeatherForecast,
            'cebuWeatherForecast' => $cebuWeatherForecast
        ]);
    }
}
