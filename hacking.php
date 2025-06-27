<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interactive Weather System</title>
    <link href="https://fonts.googleapis.com/css2?family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <style>
        h1 {
            font-size: 5.3vw;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .btn {
            background-color: black;
            border: 2px solid #20c20e;
            color: #20c20e;
            padding: 8px 16px;
            margin: 0 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn:hover {
            background-color: #20c20e;
            border-color: black;
            color: black;
        }

        .btn-select {
            background-color: #20c20e;
            border-color: black;
            color: black;
        }

        .jumbotron {
            text-align: center;
            background-color: black;
            padding: 20px;
            margin-bottom: 20px;
        }

        body {
            background-color: black;
            margin: 0;
            padding: 0;
            font-family: 'Share Tech Mono', monospace;
        }

        .hacker-text {
            color: #20c20e;
            font-family: 'Share Tech Mono', monospace;
        }

        table {
            border: 1px solid #20c20e;
            border-collapse: collapse;
            margin: 0 auto;
            width: 80%;
            max-width: 600px;
        }

        td {
            border: 1px solid #20c20e;
            text-align: center;
            padding: 10px 20px;
        }

        .conditions-container {
            padding: 20px 0;
        }

        .btn-container {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }

        #weather-icon img {
            width: 50px;
            height: auto;
        }

        .container-fluid {
            padding: 20px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="jumbotron hacker-text">
                    <h1>HACKER WEATHER</h1>
                    
                    <div class="row btn-group btn-container">
                        <button class="btn hacker-text c-degrees">C&deg;</button>
                        <button class="btn hacker-text btn-select f-degrees">F&deg;</button>
                    </div>

                    <div>
                        <p class="hacker-text" id="ip-address"></p>
                    </div>

                    <div>
                        <p class="hacker-text" id="location-data"></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="conditions-container row">
            <div class="col-lg-12">
                <h2 class="hacker-text" id="conditions">Conditions</h2>
                <table class="hacker-text">
                    <tbody>
                        <tr>
                            <td id="weather-icon"></td>
                            <td id="weather"></td>
                        </tr>
                        <tr>
                            <td>Temperature:</td>
                            <td id="temp"></td>
                        </tr>
                        <tr>
                            <td>Feels Like:</td>
                            <td id="feels-like"></td>
                        </tr>
                        <tr>
                            <td>Wind:</td>
                            <td id="wind"></td>
                        </tr>
                        <tr>
                            <td>Wind Gusts:</td>
                            <td id="gust"></td>
                        </tr>
                        <tr>
                            <td>Humidity:</td>
                            <td id="humidity"></td>
                        </tr>
                        <tr>
                            <td>Dew Point:</td>
                            <td id="dew-point"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize with Fahrenheit by default
            getLocationF();

            // Set up click handlers
            $(".c-degrees").on("click", function() {
                $(".c-degrees").addClass("btn-select").removeClass("hacker-text");
                $(".f-degrees").addClass("hacker-text").removeClass("btn-select");
                getLocationC();
            });

            $(".f-degrees").on("click", function() {
                $(".f-degrees").addClass("btn-select").removeClass("hacker-text");
                $(".c-degrees").addClass("hacker-text").removeClass("btn-select");
                getLocationF();
            });

            // Weather functions
            function getLocationF() {
                $.getJSON("https://ipapi.co/json/", function(ip) {
                    $("#ip-address").html("IP: " + ip.ip + " " + ip.org + " " + ip.asn);
                    $("#location-data").html(ip.latitude + "," + ip.longitude + " " + ip.city + ", " + ip.region + " " + ip.postal + " " + ip.country_name);
                    getWeatherF(ip.postal);
                }).fail(function() {
                    $("#location-data").html("Could not detect location. Using default ZIP code.");
                    getWeatherF("10001"); // Default to New York if location fails
                });
            }

            function getWeatherF(zip) {
                // Note: Wunderground API is no longer publicly available
                // You'll need to replace this with a different weather API
                // This is a placeholder implementation
                
                // Simulated response since Wunderground API is no longer available
                const simulatedResponse = {
                    current_observation: {
                        weather: "Partly Cloudy",
                        temp_f: "72",
                        icon_url: "https://icons-for-weather.com/partly-cloudy.png",
                        feelslike_f: "74",
                        wind_dir: "NW",
                        wind_mph: "8",
                        wind_gust_mph: "12",
                        relative_humidity: "65%",
                        dewpoint_f: "60"
                    }
                };
                
                updateWeatherUI(simulatedResponse, 'F');
                
                // Actual API call would look like this (but won't work with Wunderground):
                /*
                $.get("https://api.wunderground.com/api/748bf5540d91162d/conditions/q/" + zip + ".json", function(weatherF) {
                    updateWeatherUI(weatherF, 'F');
                }).fail(function() {
                    alert("Weather data unavailable. Please try again later.");
                });
                */
            }

            function getLocationC() {
                $.getJSON("https://ipapi.co/json/", function(ip) {
                    $("#ip-address").html("IP: " + ip.ip + " " + ip.org + " " + ip.asn);
                    $("#location-data").html(ip.latitude + "," + ip.longitude + " " + ip.city + ", " + ip.region + " " + ip.postal + " " + ip.country_name);
                    getWeatherC(ip.postal);
                }).fail(function() {
                    $("#location-data").html("Could not detect location. Using default ZIP code.");
                    getWeatherC("10001"); // Default to New York if location fails
                });
            }

            function getWeatherC(zip) {
                // Simulated response since Wunderground API is no longer available
                const simulatedResponse = {
                    current_observation: {
                        weather: "Partly Cloudy",
                        temp_c: "22",
                        icon_url: "https://icons-for-weather.com/partly-cloudy.png",
                        feelslike_c: "23",
                        wind_dir: "NW",
                        wind_kph: "13",
                        wind_gust_kph: "19",
                        relative_humidity: "65%",
                        dewpoint_c: "16"
                    }
                };
                
                updateWeatherUI(simulatedResponse, 'C');
                
                // Actual API call would look like this (but won't work with Wunderground):
                /*
                $.get("https://api.wunderground.com/api/748bf5540d91162d/conditions/q/" + zip + ".json", function(weatherC) {
                    updateWeatherUI(weatherC, 'C');
                }).fail(function() {
                    alert("Weather data unavailable. Please try again later.");
                });
                */
            }

            function updateWeatherUI(weatherData, unit) {
                $("#weather").html(weatherData.current_observation.weather);
                $("#weather-icon").html('<img src="' + weatherData.current_observation.icon_url + '">');
                $("#humidity").html(weatherData.current_observation.relative_humidity);

                if (unit === 'F') {
                    $("#temp").html(weatherData.current_observation.temp_f + " F&deg;");
                    $("#feels-like").html(weatherData.current_observation.feelslike_f + " F&deg;");
                    $("#wind").html(weatherData.current_observation.wind_dir + " " + weatherData.current_observation.wind_mph + " MPH");
                    $("#gust").html(weatherData.current_observation.wind_gust_mph + " MPH");
                    $("#dew-point").html(weatherData.current_observation.dewpoint_f + " F&deg;");
                } else {
                    $("#temp").html(weatherData.current_observation.temp_c + " C&deg;");
                    $("#feels-like").html(weatherData.current_observation.feelslike_c + " C&deg;");
                    $("#wind").html(weatherData.current_observation.wind_dir + " " + weatherData.current_observation.wind_kph + " KPH");
                    $("#gust").html(weatherData.current_observation.wind_gust_kph + " KPH");
                    $("#dew-point").html(weatherData.current_observation.dewpoint_c + " C&deg;");
                }
            }
        });
    </script>
</body>
</html>