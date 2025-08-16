const map = L.map('map').setView([7.8731, 80.7718], 8); // Centered on Sri Lanka

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(map);

let marker;

// Handle map click
map.on('click', function (e) {
    placeMarker(e.latlng.lat, e.latlng.lng);
});

// Drop marker and update inputs
function placeMarker(lat, lng) {
    document.getElementById('lat').value = lat;
    document.getElementById('lng').value = lng;

    if (!marker) {
        marker = L.marker([lat, lng]).addTo(map);
    } else {
        marker.setLatLng([lat, lng]);
    }

    map.setView([lat, lng], 13);
}

// GPS / Current location
function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            placeMarker(position.coords.latitude, position.coords.longitude);
        }, function () {
            alert("Location access denied or unavailable.");
        });
    } else {
        alert("Geolocation is not supported by your browser.");
    }
}
