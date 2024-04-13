 function getWeather() {
            const cityInput = document.getElementById('cityInput');
            const city = cityInput.value.trim();

            if (city === '') {
                alert('Please enter a city name');
                return;
            }

            const url = `http://localhost:8000/api/weather`;

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ city: city })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Weather Data:', data);
                displayWeather(data);
            })
            .catch(error => {
                console.error('Error fetching weather data:', error);
            });
        }

        function displayWeather(data) {
    const weatherInfo = document.getElementById('weatherInfo');
    localStorage.clear();




    const weatherData = {
        city: data.data.city,
        temperature: data.data.temperature,
        humidity: data.data.humidity
    };

    const weatherDataString = JSON.stringify(weatherData);

    const storageKey = 'weatherData';
    localStorage.setItem(storageKey, weatherDataString);
    const storedWeatherData = localStorage.getItem('weatherData');

if (storedWeatherData) {
    const parsedWeatherData = JSON.parse(storedWeatherData);

    // Update weatherInfo HTML with retrieved weather data
    weatherInfo.innerHTML = `
        <h2>${parsedWeatherData.city}</h2>
        <p>Temperature: ${parsedWeatherData.temperature}°C</p>
        <p>Humidity: ${parsedWeatherData.humidity}</p>
    `;
} else {
    // Handle case when weather data is not found in localStorage
    weatherInfo.innerHTML = '<p>No weather data available.</p>';
}
}
