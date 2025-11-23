<?php 
$pageTitle = $pageTitle ?? 'Detail Kost';
$kost = $kost ?? [];
$photos = $photos ?? [];
$kamars = $kamars ?? [];
?>

<!-- Kost Info Card -->
<div class="bg-white rounded-lg shadow-md p-4 md:p-6 mb-4 md:mb-6">
    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4 mb-4 md:mb-6">
        <div>
            <h2 class="text-xl md:text-2xl font-bold text-gray-800 mb-2"><?= e($kost['name']) ?></h2>
            <div class="space-y-2 text-sm md:text-base">
                <p class="text-gray-600 truncate">
                    <i class="fas fa-map-marker-alt mr-2 text-red-500"></i><?= e($kost['location']) ?>
                </p>
                <p class="text-gray-600">
                    <i class="fas fa-home mr-2 text-blue-500"></i><?= e($kost['address']) ?>
                </p>
                <p class="text-gray-600">
                    <i class="fas fa-user-tie mr-2 text-green-500"></i>
                    Owner: <strong><?= e($kost['owner_name']) ?></strong> (<?= e($kost['owner_email']) ?>)
                </p>
                <p class="text-gray-600">
                    <i class="fas fa-phone mr-2 text-purple-500"></i><?= e($kost['owner_phone']) ?>
                </p>
            </div>
        </div>
        
        <div class="text-center lg:text-right">
            <p class="text-2xl md:text-3xl font-bold text-blue-600 mb-2">
                Rp <?= number_format($kost['price'], 0, ',', '.') ?>
            </p>
            <p class="text-xs md:text-sm text-gray-500 mb-3">per bulan</p>
            <span class="inline-block px-3 md:px-4 py-2 text-xs md:text-sm font-medium rounded-full
                <?= $kost['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' ?>">
                <?= ucfirst($kost['status']) ?>
            </span>
        </div>
    </div>

    <!-- Kost Details -->
    <div class="grid grid-cols-3 gap-3 md:gap-4 mb-4 md:mb-6 p-3 md:p-4 bg-gray-50 rounded-lg">
        <div class="text-center">
            <i class="fas fa-venus-mars text-xl md:text-2xl text-purple-600 mb-1 md:mb-2"></i>
            <p class="text-xs md:text-sm text-gray-600">Tipe</p>
            <p class="font-semibold text-gray-800 text-xs md:text-base">
                <?= $kost['gender_type'] === 'putra' ? 'Khusus Putra' : ($kost['gender_type'] === 'putri' ? 'Khusus Putri' : 'Campur') ?>
            </p>
        </div>
        <div class="text-center">
            <i class="fas fa-door-open text-xl md:text-2xl text-blue-600 mb-1 md:mb-2"></i>
            <p class="text-xs md:text-sm text-gray-600">Total Kamar</p>
            <p class="font-semibold text-gray-800 text-xs md:text-base"><?= count($kamars) ?> kamar</p>
        </div>
        <div class="text-center">
            <i class="fas fa-check-circle text-xl md:text-2xl text-green-600 mb-1 md:mb-2"></i>
            <p class="text-xs md:text-sm text-gray-600">Kamar Tersedia</p>
            <p class="font-semibold text-gray-800 text-xs md:text-base">
                <?= count(array_filter($kamars, fn($k) => $k['status'] === 'available')) ?> kamar
            </p>
        </div>
    </div>

    <!-- Facilities -->
    <div class="mb-4 md:mb-6">
        <h3 class="text-base md:text-lg font-semibold text-gray-800 mb-3">
            <i class="fas fa-star text-yellow-500 mr-2"></i>Fasilitas
        </h3>
        <div class="flex flex-wrap gap-1 md:gap-2">
            <?php 
            $facilitiesData = $kost['facilities'] ?? '';
            $facilities = [];
            
            // Check if it's JSON format
            if (!empty($facilitiesData)) {
                if (strpos($facilitiesData, '[') === 0) {
                    // It's JSON format
                    $facilities = json_decode($facilitiesData, true) ?? [];
                } else {
                    // It's comma-separated format
                    $facilities = array_filter(array_map('trim', explode(',', $facilitiesData)));
                }
            }
            
            foreach ($facilities as $facility): 
                if (!empty($facility)):
            ?>
                <span class="px-2 md:px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs md:text-sm">
                    <i class="fas fa-check mr-1"></i><?= e($facility) ?>
                </span>
            <?php 
                endif;
            endforeach; 
            ?>
        </div>
    </div>

    <!-- Description -->
    <div class="mb-4 md:mb-6">
        <h3 class="text-base md:text-lg font-semibold text-gray-800 mb-3">
            <i class="fas fa-align-left mr-2"></i>Deskripsi
        </h3>
        <div class="prose max-w-none text-gray-700 text-sm md:text-base">
            <?php if (!empty($kost['description'])): ?>
                <?= $kost['description'] ?>
            <?php else: ?>
                <p class="text-gray-500 italic">Tidak ada deskripsi</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Photos -->
    <?php if (!empty($photos)): ?>
        <div class="mb-4 md:mb-6">
            <h3 class="text-base md:text-lg font-semibold text-gray-800 mb-3">
                <i class="fas fa-images mr-2"></i>Foto Kost
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2 md:gap-4">
                <?php foreach ($photos as $photo): ?>
                    <div class="relative group">
                        <?php if (!empty($photo['photo_url'])): ?>
                            <img src="<?= asset($photo['photo_url']) ?>" 
                                 alt="Kost Photo" 
                                 class="w-full h-32 md:h-40 object-cover rounded-lg cursor-pointer hover:opacity-90 transition"
                                 onclick="window.open(this.src, '_blank')">
                        <?php else: ?>
                            <img src="https://placehold.co/600x400?text=No+Image" 
                                 alt="No Photo" 
                                 class="w-full h-32 md:h-40 object-cover rounded-lg">
                        <?php endif; ?>
                        <?php if ($photo['is_primary']): ?>
                            <span class="absolute top-1 md:top-2 right-1 md:right-2 px-1 md:px-2 py-0.5 md:py-1 bg-yellow-500 text-white text-xs rounded-full">
                                <i class="fas fa-star"></i> <span class="hidden md:inline">Primary</span>
                            </span>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Action Buttons -->
    <div class="border-t border-gray-200 pt-4 md:pt-6">
        <h3 class="text-base md:text-lg font-semibold text-gray-800 mb-3 md:mb-4">Aksi</h3>
        <div class="flex flex-col sm:flex-row sm:flex-wrap gap-2 md:gap-3">
            
            <!-- Update Status -->
            <form action="<?= url('/admin/kost/' . $kost['id'] . '/status') ?>" method="POST" class="inline">
                <?= csrf_field() ?>
                <select name="status" onchange="if(confirm('Ubah status kost?')) this.form.submit()" 
                        class="w-full sm:w-auto px-3 md:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm md:text-base">
                    <option value="active" <?= $kost['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= $kost['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </form>
            
            <!-- Delete Button -->
            <form action="<?= url('/admin/kost/' . $kost['id'] . '/delete') ?>" method="POST" 
                  onsubmit="return confirm('Yakin ingin menghapus kost ini? Data tidak dapat dikembalikan!')" class="inline w-full sm:w-auto">
                <?= csrf_field() ?>
                <button type="submit" class="w-full sm:w-auto px-4 md:px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm md:text-base">
                    <i class="fas fa-trash mr-2"></i>Hapus Kost
                </button>
            </form>
            
            <a href="<?= url('/admin/kost') ?>" class="w-full sm:w-auto text-center px-4 md:px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 text-sm md:text-base">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>
</div>

<!-- Kamar List -->
<div class="bg-white rounded-lg shadow-md p-4 md:p-6">
    <h3 class="text-base md:text-lg font-semibold text-gray-800 mb-4">
        <i class="fas fa-door-open mr-2"></i>Daftar Kamar (<?= count($kamars) ?>)
    </h3>
    
    <?php if (empty($kamars)): ?>
        <p class="text-gray-500 text-center py-8 text-sm md:text-base">Belum ada kamar</p>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-4">
            <?php foreach ($kamars as $kamar): ?>
                <div class="border border-gray-200 rounded-lg p-3 md:p-4 hover:shadow-md transition">
                    <div class="flex items-start justify-between mb-3">
                        <div class="min-w-0">
                            <h4 class="font-semibold text-gray-800 text-sm md:text-base truncate"><?= e($kamar['name']) ?></h4>
                            <p class="text-xs md:text-sm text-gray-600">Lantai <?= e($kamar['floor'] ?? '-') ?></p>
                        </div>
                        <span class="px-2 py-1 text-xs rounded-full whitespace-nowrap ml-2
                            <?= $kamar['status'] === 'available' ? 'bg-green-100 text-green-800' : 
                                ($kamar['status'] === 'occupied' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') ?>">
                            <?= ucfirst($kamar['status']) ?>
                        </span>
                    </div>
                    <p class="text-base md:text-lg font-bold text-blue-600 mb-2">
                        <?php if ($kamar['price'] > 0): ?>
                            Rp <?= number_format($kamar['price'], 0, ',', '.') ?>/bln
                        <?php else: ?>
                            Sama dengan kost
                        <?php endif; ?>
                    </p>
                    <div class="text-xs md:text-sm text-gray-600">
                        <p><i class="fas fa-ruler-combined mr-1"></i> <?= e($kamar['size'] ?? '-') ?> m²</p>
                        <?php if (!empty($kamar['facilities'])): ?>
                            <?php 
                            $kamarFacilities = [];
                            $facilitiesData = $kamar['facilities'];
                            if (strpos($facilitiesData, '[') === 0) {
                                $kamarFacilities = json_decode($facilitiesData, true) ?? [];
                            } else {
                                $kamarFacilities = array_filter(array_map('trim', explode(',', $facilitiesData)));
                            }
                            ?>
                            <div class="flex flex-wrap gap-1 mt-1">
                                <?php foreach (array_slice($kamarFacilities, 0, 3) as $facility): ?>
                                    <span class="px-2 py-1 bg-gray-200 text-gray-800 rounded text-xs">
                                        <?= e($facility) ?>
                                    </span>
                                <?php endforeach; ?>
                                <?php if (count($kamarFacilities) > 3): ?>
                                    <span class="px-2 py-1 text-gray-500 text-xs">+<?= count($kamarFacilities) - 3 ?> lagi</span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
