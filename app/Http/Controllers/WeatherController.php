<?php

namespace App\Http\Controllers;

use App\Models\Weather;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    public function syncWeather()
    {
        $apiKey = 'a347966a3cc1e4f7867dccd9c4f2ca1f';
        $response = Http::get("http://api.openweathermap.org/data/2.5/weather?q=Derby&appid=$apiKey");
        // dd($response);
        if ($response->failed()) {
            return response()->json(['error' => 'Failed to fetch weather data'], 500);
        }


        $data = $response->json();

        $city = $data['name'];
        $humidity = $data['main']['humidity'];
        $temperature = $data['main']['temp'];

        $weather = Weather::where('city', $city)->first();

        if ($weather) {
            $weather->update([
                'humidity' => $humidity,
                'temperature' => $temperature,
            ]);
            return response()->json(['message' => 'Weather data synced successfully'], 200);
        } else {
            $weather = new Weather();
            $weather->city = $city;
            $weather->humidity = $humidity;
            $weather->temperature = $temperature;
            $weather->save();
            return response()->json(['message' => 'Weather data synced successfully'], 200);
        }
    }
}
