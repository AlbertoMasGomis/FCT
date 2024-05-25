<footer>
    <div id="menu-footer">
        <div id="logo" class="row">
            <div class="col"></div>
        </div>
        <div id="menu-1" class="row">
            <div class="col">
                <p>Sobre nosotros</p>
                <p>Historia</p>
                <p>Colaboradores</p>
            </div>
        </div>
        <div id="menu-2" class="row">
            <div class="col">
                <p>Servicios</p>
                <p>Empresas</p>
                <p>Escuelas</p>
                <p>Juniors</p>
            </div>
        </div>
        <div id="menu-3" class="row">
            <div class="col">
                <p>Contacto</p>
                <p>Privacidad</p>
            </div>
        </div>
        <div id="menu-4" class="row">
            <div class="col">
                <p id="current-date"></p>
            </div>
        </div>
        <div id="menu-5" class="row">
            <div class="col">
                <div id="weather"></div>
            </div>
        </div>
    </div>
</footer>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var currentDate = new Date();
        var options = { year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById("current-date").textContent = currentDate.toLocaleDateString('es-ES', options);
    });


    navigator.geolocation.getCurrentPosition(success, error);

    // Función de éxito para la geolocalización
    function success(position) {
        const latitude = position.coords.latitude;
        const longitude = position.coords.longitude;
        getWeather(latitude, longitude);
    }

    // Función de error para la geolocalización
    function error() {
        console.error('No se pudo obtener la ubicación del usuario');
    }

    // Función para obtener el tiempo meteorológico usando la latitud y longitud
    function getWeather(latitude, longitude) {
        const apiKey = "c9247ba56043bbc776a6d1d8e11c5752"; // Reemplaza con tu clave de API de OpenWeatherMap
        const apiUrl = `https://api.openweathermap.org/data/2.5/weather?lat=${latitude}&lon=${longitude}&appid=${apiKey}&units=metric`;

        // Hacer la solicitud AJAX
        const xhr = new XMLHttpRequest();
        xhr.open('GET', apiUrl, true);

        xhr.onload = function () {
            if (xhr.status === 200) {
                // Convertir la respuesta JSON en un objeto JavaScript
                const weatherData = JSON.parse(xhr.responseText);

                // Extraer la información del tiempo que deseas mostrar
                const temperature = weatherData.main.temp;
                const description = weatherData.weather[0].description;

                // Mostrar la información del tiempo en el footer
                const weatherElement = document.getElementById('weather');
                weatherElement.innerHTML = `El tiempo actual: ${temperature}°C`;
            } else {
                console.error('Error al obtener los datos meteorológicos');
            }
        };

        xhr.onerror = function () {
            console.error('Error de conexión');
        };

        // Enviar la solicitud
        xhr.send();
    }

    // Llamar a la función getWeather() cuando la página se cargue
    window.onload = getWeather;
</script>
</body>

</html>