<!-- 
    MAP PICKER COMPONENT
    Component untuk owner memilih lokasi kost via interactive map
    Owner bisa: klik peta, drag marker, atau gunakan GPS
-->

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="map-picker-container">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-map-marked-alt"></i> Pilih Lokasi Kost di Peta
            </h5>
        </div>
        <div class="card-body">
            <!-- Instruksi -->
            <div class="alert alert-info mb-3">
                <i class="fas fa-info-circle"></i>
                <strong>Cara menggunakan:</strong>
                <ul class="mb-0 mt-2">
                    <li>Klik di peta untuk menandai lokasi kost Anda</li>
                    <li>Atau drag marker merah ke lokasi yang tepat</li>
                    <li>Atau gunakan tombol GPS untuk lokasi saat ini</li>
                </ul>
            </div>

            <!-- Tombol bantuan -->
            <div class="mb-3 d-flex gap-2">
                <button type="button" class="btn btn-success btn-sm" id="btnUseGPS">
                    <i class="fas fa-crosshairs"></i> Gunakan Lokasi Saya
                </button>
                <button type="button" class="btn btn-secondary btn-sm" id="btnSearchAddress">
                    <i class="fas fa-search"></i> Cari dari Alamat
                </button>
            </div>

            <!-- Search box (hidden by default) -->
            <div id="searchAddressBox" class="mb-3" style="display: none;">
                <div class="input-group">
                    <input type="text" class="form-control" id="addressSearch" 
                           placeholder="Masukkan alamat lengkap (contoh: Jl. Setia Budi No. 10, Medan Sunggal)">
                    <button class="btn btn-primary" type="button" id="btnDoSearch">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
            </div>

            <!-- Map Container -->
            <div id="mapPicker" style="height: 400px; width: 100%; border: 2px solid #ddd; border-radius: 8px;"></div>

            <!-- Coordinate Display -->
            <div class="mt-3">
                <p class="mb-1"><strong>Koordinat yang dipilih:</strong></p>
                <div class="alert alert-secondary mb-0">
                    <i class="fas fa-map-marker-alt"></i> <span id="coordText">
                        <?php if (!empty($kost['latitude']) && !empty($kost['longitude'])): ?>
                            <?= number_format($kost['latitude'], 6) ?>, <?= number_format($kost['longitude'], 6) ?>
                        <?php else: ?>
                            Klik peta untuk memilih lokasi
                        <?php endif; ?>
                    </span>
                </div>
            </div>

            <!-- Hidden inputs untuk menyimpan koordinat -->
            <input type="hidden" name="latitude" id="latitude" value="<?= $kost['latitude'] ?? '' ?>">
            <input type="hidden" name="longitude" id="longitude" value="<?= $kost['longitude'] ?? '' ?>">
        </div>
    </div>
</div>

<script src="<?= asset('js/map.js') ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ambil koordinat existing (jika edit mode)
    const existingLat = document.getElementById('latitude').value;
    const existingLng = document.getElementById('longitude').value;

    // Inisialisasi location picker
    const mapData = initLocationPicker(
        'mapPicker',
        'latitude',
        'longitude',
        existingLat ? parseFloat(existingLat) : null,
        existingLng ? parseFloat(existingLng) : null
    );

    // Tombol GPS - set marker ke lokasi user
    document.getElementById('btnUseGPS').addEventListener('click', function() {
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mendapatkan lokasi...';

        setMarkerToCurrentLocation(mapData.map, mapData.marker, 'latitude', 'longitude');

        setTimeout(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-crosshairs"></i> Gunakan Lokasi Saya';
        }, 2000);
    });

    // Toggle search box
    document.getElementById('btnSearchAddress').addEventListener('click', function() {
        const searchBox = document.getElementById('searchAddressBox');
        searchBox.style.display = searchBox.style.display === 'none' ? 'block' : 'none';
    });

    // Search address
    document.getElementById('btnDoSearch').addEventListener('click', function() {
        const address = document.getElementById('addressSearch').value.trim();
        if (!address) {
            alert('Masukkan alamat terlebih dahulu');
            return;
        }

        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mencari...';

        searchAddress(address, function(result) {
            if (result) {
                mapData.marker.setLatLng([result.lat, result.lng]);
                mapData.map.setView([result.lat, result.lng], 17);
                updateCoordinateInputs(result.lat, result.lng, 'latitude', 'longitude');
                updateMarkerPopup(mapData.marker, result.lat, result.lng);
            } else {
                alert('Alamat tidak ditemukan. Coba masukkan alamat yang lebih spesifik.');
            }

            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-search"></i> Cari';
        });
    });

    // Enter key untuk search
    document.getElementById('addressSearch').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('btnDoSearch').click();
        }
    });

    // Update coordinate display
    if (existingLat && existingLng) {
        document.getElementById('coordText').textContent = 
            `${parseFloat(existingLat).toFixed(6)}, ${parseFloat(existingLng).toFixed(6)}`;
    }
});
</script>

<style>
.map-picker-container {
    margin: 20px 0;
}

.d-flex.gap-2 > * {
    margin-right: 8px;
}

.d-flex.gap-2 > *:last-child {
    margin-right: 0;
}

#coordinate-display {
    font-family: 'Courier New', monospace;
    font-size: 14px;
}

.leaflet-popup-content {
    min-width: 200px;
}
</style>
