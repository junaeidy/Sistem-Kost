<!-- Reusable Kost Card Component -->
<?php
/**
 * Kost Card Component
 * 
 * Usage:
 * include __DIR__ . '/../components/kost-card.php';
 * renderKostCard($kostData);
 * 
 * @param array $kost - Kost data array
 */

function renderKostCard($kost) {
    $genderColors = [
        'putra' => 'bg-blue-600',
        'putri' => 'bg-pink-600',
        'campur' => 'bg-purple-600'
    ];
    $genderIcons = [
        'putra' => 'fa-male',
        'putri' => 'fa-female',
        'campur' => 'fa-users'
    ];
    $genderColor = $genderColors[$kost['gender_type']] ?? 'bg-gray-600';
    $genderIcon = $genderIcons[$kost['gender_type']] ?? 'fa-users';
    
    $facilitiesCount = 0;
    if (!empty($kost['facilities'])) {
        $facilitiesArray = json_decode($kost['facilities'], true);
        $facilitiesCount = is_array($facilitiesArray) ? count($facilitiesArray) : 0;
    }
?>

<div class="kost-card bg-white rounded-xl shadow-md overflow-hidden hover:shadow-2xl transition-all transform hover:-translate-y-2">
    <!-- Kost Image -->
    <div class="relative h-52 bg-gray-200 overflow-hidden image-zoom-container">
        <?php if (!empty($kost['primary_photo'])): ?>
            <img src="<?= url('/' . $kost['primary_photo']) ?>" 
                 alt="<?= e($kost['name']) ?>"
                 class="w-full h-full object-cover"
                 loading="lazy">
        <?php else: ?>
            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-300 to-gray-400">
                <i class="fas fa-building text-6xl text-gray-500"></i>
            </div>
        <?php endif; ?>
        
        <!-- Gender Badge -->
        <span class="absolute top-3 right-3 px-3 py-1 <?= $genderColor ?> text-white text-xs font-semibold rounded-full shadow-lg">
            <i class="fas <?= $genderIcon ?> mr-1"></i>
            <?= ucfirst($kost['gender_type']) ?>
        </span>
        
        <!-- Verified Badge -->
        <span class="absolute top-3 left-3 px-3 py-1 bg-green-600 text-white text-xs font-semibold rounded-full shadow-lg">
            <i class="fas fa-check-circle mr-1"></i>Verified
        </span>
    </div>
    
    <!-- Kost Info -->
    <div class="p-5">
        <h3 class="text-xl font-bold text-gray-800 mb-2 truncate hover:text-blue-600 transition">
            <?= e($kost['name']) ?>
        </h3>
        
        <p class="text-sm text-gray-600 mb-4 flex items-start">
            <i class="fas fa-map-marker-alt text-red-500 mr-2 mt-1 flex-shrink-0"></i>
            <span class="line-clamp-2"><?= e($kost['location']) ?></span>
        </p>
        
        <!-- Price Range -->
        <div class="mb-4 bg-blue-50 p-3 rounded-lg">
            <?php if (!empty($kost['min_price']) && $kost['min_price'] > 0): ?>
                <p class="text-xs text-gray-500 mb-1">Mulai dari</p>
                <p class="text-2xl font-bold text-blue-600">
                    Rp <?= number_format($kost['min_price'], 0, ',', '.') ?>
                    <span class="text-sm text-gray-500 font-normal">/bulan</span>
                </p>
            <?php else: ?>
                <p class="text-xs text-gray-500 mb-1">Harga</p>
                <p class="text-lg font-semibold text-gray-400">
                    Belum Tersedia
                </p>
            <?php endif; ?>
        </div>
        
        <!-- Stats -->
        <div class="flex items-center justify-between text-sm text-gray-600 mb-4 pb-4 border-b border-gray-200">
            <div class="flex items-center">
                <i class="fas fa-star text-yellow-500 mr-1"></i>
                <span class="font-medium"><?= $facilitiesCount ?></span>
                <span class="ml-1 text-xs">Fasilitas</span>
            </div>
            <div class="flex items-center">
                <i class="fas fa-door-open text-green-500 mr-1"></i>
                <span class="font-medium"><?= $kost['available_rooms'] ?? 0 ?></span>
                <span class="ml-1 text-xs">Tersedia</span>
            </div>
        </div>
        
        <!-- Action Button -->
        <a href="<?= url('/kost/' . $kost['id']) ?>" 
           class="block w-full text-center px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition transform hover:scale-105 font-semibold shadow-md">
            <i class="fas fa-eye mr-2"></i>
            Lihat Detail
        </a>
    </div>
</div>

<?php
}
?>
