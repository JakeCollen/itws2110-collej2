document.addEventListener("DOMContentLoaded", () => {
    const cityInput = document.getElementById("cityInput");
    const searchBtn = document.getElementById("searchBtn");
    const locationBtn = document.getElementById("locationBtn");

    // Get location on load
    getUserLocation();

    // Get value put in search bar when button is clicked
    searchBtn.addEventListener("click", () => {
    const city = cityInput.value.trim();
    if (city) {
        getWeatherByCity(city);
        cityInput.value = "";
    }
    });

    // Get value put in search bar when enter is hit
    cityInput.addEventListener("keydown", e => {
    if (e.key === "Enter") {
        searchBtn.click();
    }
    });

    // Gets user's location when button is clicked
    locationBtn.addEventListener("click", getUserLocation);
});

// Gets user's location using the geolocation API
function getUserLocation() {
    showError("Getting your location...");
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
        pos => getWeatherByCoords(pos.coords.latitude, pos.coords.longitude),
        () => showError("Can't get your location."),
        // Don't need exact location, timeout after 5 seconds if not found, save location if called recently
        { enableHighAccuracy: false, timeout: 5000, maximumAge: 60000 }
        );
    } else {
        showError("Can't get your location.");
    }
    cityInput.value = "";
}

// Creates the OpenWeather API call for city
function getWeatherByCity(city) {
    const url = `https://api.openweathermap.org/data/2.5/weather?q=${city}&units=imperial&appid=00bf2ed667e73b694661859f30d1b7e2`;
    requestWeather(url);
}

// Creates the OpenWeather API call for lat/long
function getWeatherByCoords(lat, lon) {
    const url = `https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&units=imperial&appid=00bf2ed667e73b694661859f30d1b7e2`;
    requestWeather(url);
}

// Calls the OpenWeather API and calls function for Unsplash
function requestWeather(url) {
    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
    if (this.readyState === 4) {
        if (this.status === 200) {
            const data = JSON.parse(this.responseText);
            updateUI(data);
            getUnsplashImage(data.weather[0].description);
        } else {
            showError("Try another city");
        }
    }
    };
    xhr.open("GET", url, true);
    xhr.send();
}

// Updates the page information after it is fetched
function updateUI(data) {
    document.getElementById("error").textContent = "";
    document.getElementById("city").textContent = data.name;
    document.getElementById("temp").textContent = Math.round(data.main.temp) + " °F";
    document.getElementById("desc").textContent = uppercase(data.weather[0].description);
    // Retrieve image for given weather
    document.getElementById("icon").src = `https://openweathermap.org/img/wn/${data.weather[0].icon}@2x.png`;
    // Round the temperature, wind speed, change pressure from hPa to inHg and round
    document.getElementById("details").innerHTML = `
    Feels like: ${Math.round(data.main.feels_like)} °F<br>
    Humidity: ${data.main.humidity}%<br>
    Wind: ${Math.round(data.wind.speed)} mph<br>
    Pressure: ${(data.main.pressure * 0.02953).toFixed(2)} inHg
    `;
}

// Calls Unsplash API and retrieves image
function getUnsplashImage(query) {
    const req = new XMLHttpRequest();
    req.onreadystatechange = function() {
    if (this.readyState === 4 && this.status === 200) {
        const results = JSON.parse(this.responseText).results;
        if (results.length > 0) {
            document.body.style.backgroundImage = `url(${results[0].urls.full})`;
        }
    }
    };
    req.open("GET", `https://api.unsplash.com/search/photos?query=${query}&orientation=landscape&client_id=uT-vxVSaNa4CblrsomocfSdp5FXnDZREfP7h7atUgUI`, true);
    req.send();
}

// Update error field if one is encountered
function showError(msg) {
    document.getElementById("error").textContent = msg;
}

// Capitalizes the first letter of every word
function uppercase(string) {
    const words = string.split(" ");
    for (var i=0; i<words.length; i++) {
        words[i] = words[i][0].toUpperCase() + words[i].substr(1);
    }
    return words.join(" ");
}