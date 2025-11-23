<!-- 
    MAP VIEW COMPONENT
    Component untuk menampilkan lokasi kost (read-only)
    Digunakan di halaman detail kost untuk tenant/visitor
-->

<?php
// Cek apakah koordinat tersedia
$hasCoordinates = !empty($kost['latitude']) && !empty($kost['longitude']);
?>

<?php if ($hasCoordinates): ?>
<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="bg-white rounded-lg shadow-md p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">
        <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
        Lokasi Kost
    </h3>
    
    <!-- Map Container -->
    <div id="mapView" style="height: 300px; width: 100%; border: 2px solid #ddd; border-radius: 8px;"></div>

    <!-- Info dan action buttons -->
    <div class="mt-3">
        <p class="text-xs text-gray-500 mb-2">
            <i class="fas fa-map-pin"></i> 
            <?= number_format($kost['latitude'], 6) ?>, <?= number_format($kost['longitude'], 6) ?>
        </p>
        <div class="flex flex-col gap-2">
            <a href="https://www.google.com/maps/dir/?api=1&destination=<?= $kost['latitude'] ?>,<?= $kost['longitude'] ?>" 
               target="_blank" 
               class="btn btn-success btn-sm text-center py-2 px-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm">
                <i class="fas fa-directions mr-1"></i> Petunjuk Arah
            </a>
            <a href="https://www.google.com/maps/search/?api=1&query=<?= $kost['latitude'] ?>,<?= $kost['longitude'] ?>" 
               target="_blank" 
               class="btn btn-primary btn-sm text-center py-2 px-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                <i class="fab fa-google mr-1"></i> Buka di Google Maps
            </a>
        </div>
    </div>
</div>

<script src="<?= asset('js/map.js') ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi map view
    initMapView(
        'mapView',
        <?= $kost['latitude'] ?>,
        <?= $kost['longitude'] ?>,
        '<?= addslashes($kost['name']) ?>',
        16
    );
});
</script>

<style>
.map-view-container {
    margin: 20px 0;
}

.leaflet-popup-content {
    min-width: 150px;
    text-align: center;
}

/* Fix z-index untuk leaflet agar tidak menutupi navbar */
.leaflet-pane,
.leaflet-top,
.leaflet-bottom {
    z-index: 1 !important;
}

.leaflet-control-container {
    z-index: 1 !important;
}

#mapView {
    position: relative;
    z-index: 1 !important;
}
</style>

<?php else: ?>
<!-- Jika tidak ada koordinat -->
<div class="alert alert-warning mt-4">
    <i class="fas fa-exclamation-triangle"></i>
    <strong>Lokasi peta belum tersedia</strong><br>
    <small>Pemilik kost belum mengatur lokasi di peta.</small>
</div>
<?php endif; ?>
