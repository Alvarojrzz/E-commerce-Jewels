export const map = L.map('map', {
    center: [37.99157144595255, -1.184877957008847],
    zoom: 16,
    gestureHandling: true
});

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap'
}).addTo(map);

L.marker([37.99157144595255, -1.184877957008847])
    .addTo(map)
    .bindPopup(`
<h3 class="map-popup-title">JoyasJewels</h3>
<p class="map-popup-text">Taller principal desde 2025</p>
`);