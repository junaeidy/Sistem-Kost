/**
 * Map JavaScript Library
 * Menggunakan Leaflet.js untuk interactive maps
 * Fitur: Location picker (drag/click marker) dan map view
 */

// ============================================
// MAP CONFIGURATION
// ============================================
const MAP_CONFIG = {
    defaultCenter: [3.5952, 98.6722], // Medan Sunggal
    defaultZoom: 13,
    maxZoom: 19,
    minZoom: 10,
    tileLayer: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
};

// ============================================
// LOCATION PICKER MAP (untuk owner set lokasi)
// ============================================
function initLocationPicker(mapId, latInputId, lngInputId, initialLat = null, initialLng = null) {
    // Gunakan koordinat initial atau default
    const lat = initialLat || MAP_CONFIG.defaultCenter[0];
    const lng = initialLng || MAP_CONFIG.defaultCenter[1];
    const zoom = initialLat ? 16 : MAP_CONFIG.defaultZoom;

    // Inisialisasi map
    const map = L.map(mapId).setView([lat, lng], zoom);

    // Tambahkan tile layer
    L.tileLayer(MAP_CONFIG.tileLayer, {
        attribution: MAP_CONFIG.attribution,
        maxZoom: MAP_CONFIG.maxZoom,
        minZoom: MAP_CONFIG.minZoom
    }).addTo(map);

    // Custom marker icon (merah untuk editing)
    const redIcon = L.icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    // Buat draggable marker
    const marker = L.marker([lat, lng], {
        draggable: true,
        icon: redIcon
    }).addTo(map);

    marker.bindPopup('<b>Drag marker ini</b><br>atau klik di peta untuk set lokasi kost').openPopup();

    // Update input saat marker di-drag
    marker.on('dragend', function(e) {
        const position = marker.getLatLng();
        updateCoordinateInputs(position.lat, position.lng, latInputId, lngInputId);
        updateMarkerPopup(marker, position.lat, position.lng);
    });

    // Update marker saat peta di-klik
    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        updateCoordinateInputs(e.latlng.lat, e.latlng.lng, latInputId, lngInputId);
        updateMarkerPopup(marker, e.latlng.lat, e.latlng.lng);
    });

    // Set initial values ke input
    updateCoordinateInputs(lat, lng, latInputId, lngInputId);

    return { map, marker };
}

// Update koordinat ke hidden input
function updateCoordinateInputs(lat, lng, latInputId, lngInputId) {
    const latInput = document.getElementById(latInputId);
    const lngInput = document.getElementById(lngInputId);
    
    if (latInput) latInput.value = lat.toFixed(8);
    if (lngInput) lngInput.value = lng.toFixed(8);

    // Update display jika ada
    const coordText = document.getElementById('coordText');
    if (coordText) {
        coordText.textContent = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
    }
    
    const coordDisplay = document.getElementById('coordinate-display');
    if (coordDisplay) {
        coordDisplay.innerHTML = `<i class="fas fa-map-marker-alt"></i> ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
    }
}

// Update popup marker dengan koordinat
function updateMarkerPopup(marker, lat, lng) {
    marker.bindPopup(
        `<b>Lokasi Kost</b><br>` +
        `Lat: ${lat.toFixed(6)}<br>` +
        `Lng: ${lng.toFixed(6)}<br>` +
        `<small class="text-muted">Drag untuk ubah posisi</small>`
    ).openPopup();
}

// ============================================
// MAP VIEW (read-only untuk tampilan detail)
// ============================================
function initMapView(mapId, lat, lng, title = 'Lokasi Kost', zoom = 16) {
    if (!lat || !lng) {
        console.error('Koordinat tidak valid');
        return null;
    }

    // Inisialisasi map
    const map = L.map(mapId).setView([lat, lng], zoom);

    // Tambahkan tile layer
    L.tileLayer(MAP_CONFIG.tileLayer, {
        attribution: MAP_CONFIG.attribution,
        maxZoom: MAP_CONFIG.maxZoom
    }).addTo(map);

    // Custom marker icon (biru untuk view)
    const blueIcon = L.icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    // Tambahkan marker
    const marker = L.marker([lat, lng], {
        icon: blueIcon
    }).addTo(map);

    marker.bindPopup(`<b>${title}</b><br>${lat.toFixed(6)}, ${lng.toFixed(6)}`).openPopup();

    return { map, marker };
}

// ============================================
// MAP CLUSTERING (untuk multiple markers)
// ============================================
function initClusterMap(mapId, locations) {
    if (!locations || locations.length === 0) {
        console.warn('Tidak ada lokasi untuk ditampilkan');
        return null;
    }

    // Inisialisasi map
    const map = L.map(mapId).setView(MAP_CONFIG.defaultCenter, MAP_CONFIG.defaultZoom);

    // Tambahkan tile layer
    L.tileLayer(MAP_CONFIG.tileLayer, {
        attribution: MAP_CONFIG.attribution,
        maxZoom: MAP_CONFIG.maxZoom
    }).addTo(map);

    // Tambahkan markers
    const markers = [];
    locations.forEach(location => {
        if (location.latitude && location.longitude) {
            const marker = L.marker([location.latitude, location.longitude])
                .bindPopup(createClusterPopup(location));
            markers.push(marker);
            marker.addTo(map);
        }
    });

    // Auto-fit bounds jika ada markers
    if (markers.length > 0) {
        const group = L.featureGroup(markers);
        map.fitBounds(group.getBounds().pad(0.1));
    }

    return { map, markers };
}

// Buat popup content untuk cluster map
function createClusterPopup(location) {
    const photo = location.primary_photo 
        ? `<img src="/uploads/kost/${location.primary_photo}" style="width:200px;height:120px;object-fit:cover;border-radius:4px;margin-bottom:8px;">` 
        : '';
    
    const price = location.min_price 
        ? `<p class="mb-1"><strong>Mulai dari:</strong> Rp ${parseInt(location.min_price).toLocaleString('id-ID')}/bulan</p>` 
        : '';
    
    const rooms = location.available_kamar 
        ? `<p class="mb-1"><small>${location.available_kamar} kamar tersedia</small></p>` 
        : '';

    return `
        <div style="min-width:200px;">
            ${photo}
            <h6 style="margin:0 0 8px 0;">${location.name}</h6>
            ${price}
            ${rooms}
            <a href="/pages/detail/${location.id}" class="btn btn-sm btn-primary mt-2" style="font-size:12px;">
                Lihat Detail
            </a>
        </div>
    `;
}

// ============================================
// UTILITY FUNCTIONS
// ============================================

// Get current location dari browser
function getCurrentLocation(callback) {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                callback({
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                });
            },
            function(error) {
                console.error('Error getting location:', error);
                callback(null);
            }
        );
    } else {
        console.error('Geolocation not supported');
        callback(null);
    }
}

// Set marker ke current location
function setMarkerToCurrentLocation(map, marker, latInputId, lngInputId) {
    getCurrentLocation(function(position) {
        if (position) {
            marker.setLatLng([position.lat, position.lng]);
            map.setView([position.lat, position.lng], 16);
            updateCoordinateInputs(position.lat, position.lng, latInputId, lngInputId);
            updateMarkerPopup(marker, position.lat, position.lng);
        } else {
            alert('Tidak dapat mengakses lokasi Anda. Pastikan GPS aktif dan izinkan akses lokasi.');
        }
    });
}

// Search address (menggunakan Nominatim)
function searchAddress(address, callback) {
    const url = `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(address)}&format=json&limit=1`;
    
    fetch(url, {
        headers: {
            'User-Agent': 'SistemKost/1.0'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data && data.length > 0) {
            callback({
                lat: parseFloat(data[0].lat),
                lng: parseFloat(data[0].lon),
                display_name: data[0].display_name
            });
        } else {
            callback(null);
        }
    })
    .catch(error => {
        console.error('Geocoding error:', error);
        callback(null);
    });
}
