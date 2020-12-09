function showMap(lat, lng) {
    var element = document.getElementById('osm-map');

    element.style = 'height:300px;';

    var map = L.map(element);

    L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    var target = L.latLng(lat, lng);

    map.setView(target, 14);

    L.marker(target).addTo(map);
}
showMap(lat, lng);
