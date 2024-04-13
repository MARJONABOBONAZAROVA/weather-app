<?php

namespace App\Console\Commands;

use App\Models\Weather;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SyncWeatherData extends Command
{
    protected $signature = 'weather:sync';

    protected $description = 'Sync weather data from OpenWeather API to the database';

    public function handle()
    {
        $apiKey = 'a347966a3cc1e4f7867dccd9c4f2ca1f';
        $response = Http::get("http://api.openweathermap.org/data/2.5/weather?q=Derby&appid=$apiKey");

        if ($response->failed()) {
            $this->error('Failed to fetch weather data');
            return;
        }


        $data = $response->json();

        $city = $data['name']; // Assuming 'name' contains city name
        $humidity = $data['main']['humidity'];
        $temperature = $data['main']['temp'];

        // Check if data already exists for this city
        $weather = Weather::where('city', $city)->first();

        if ($weather) {
            // Update existing record
            $weather->update([
                'humidity' => $humidity,
                'temperature' => $temperature,
            ]);
            $this->info("Weather data for $city updated successfully");
        } else {
            // Create new record
            $weather = new Weather();
            $weather->city = $city;
            $weather->humidity = $humidity;
            $weather->temperature = $temperature;
            $weather->save();
            $this->info("Weather data for $city synced successfully");
        }

        $this->info('Weather data synced successfully');
    }
}
