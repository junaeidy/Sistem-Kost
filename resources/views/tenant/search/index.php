<?php 
$pageTitle = $pageTitle ?? 'Cari Kost';
$kostList = $kostList ?? [];
$filters = $filters ?? [];
$totalResults = $totalResults ?? 0;
$isSearching = $isSearching ?? false;
?>

<!-- Page Header -->
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Cari Kost</h1>
    <p class="text-gray-600 mt-2">Temukan kost yang sesuai dengan kebutuhan Anda</p>
</div>

<!-- Search & Filter Section -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <form method="GET" action="<?= url('/tenant/search') ?>" class="space-y-4">
        
        <!-- Search by Location/Keyword -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-search mr-1"></i> Kata Kunci
                </label>
                <input type="text" 
                       name="q" 
                       value="<?= e($filters['q'] ?? $_GET['q'] ?? '') ?>"
                       placeholder="Cari kost..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-venus-mars mr-1"></i> Tipe Gender
                </label>
                <select name="gender" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Semua Tipe</option>
                    <option value="putra" <?= ($filters['gender'] ?? $_GET['gender'] ?? '') === 'putra' ? 'selected' : '' ?>>Putra</option>
                    <option value="putri" <?= ($filters['gender'] ?? $_GET['gender'] ?? '') === 'putri' ? 'selected' : '' ?>>Putri</option>
                    <option value="campur" <?= ($filters['gender'] ?? $_GET['gender'] ?? '') === 'campur' ? 'selected' : '' ?>>Campur</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-sort mr-1"></i> Urutkan
                </label>
                <select name="sort_by" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="created_at" <?= ($filters['sort_by'] ?? '') === 'created_at' ? 'selected' : '' ?>>Terbaru</option>
                    <option value="min_price" <?= ($filters['sort_by'] ?? '') === 'min_price' ? 'selected' : '' ?>>Harga</option>
                    <option value="name" <?= ($filters['sort_by'] ?? '') === 'name' ? 'selected' : '' ?>>Nama</option>
                </select>
            </div>
        </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-map-marker-alt mr-1"></i> Lokasi
                </label>
                <input type="text" 
                       name="location" 
                       value="<?= e($filters['location'] ?? $_GET['location'] ?? '') ?>"
                       placeholder="Cari berdasarkan lokasi..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
        </div>

        <!-- Price Range -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-money-bill-wave mr-1"></i> Range Harga
                </label>
                <select name="price" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <?php 
                    $priceFilter = $filters['price'] ?? $_GET['price'] ?? '';
                    ?>
                    <option value="">Semua Harga</option>
                    <option value="0-500000" <?= $priceFilter === '0-500000' ? 'selected' : '' ?>>&lt; 500rb</option>
                    <option value="500000-1000000" <?= $priceFilter === '500000-1000000' ? 'selected' : '' ?>>500rb - 1jt</option>
                    <option value="1000000-2000000" <?= $priceFilter === '1000000-2000000' ? 'selected' : '' ?>>1jt - 2jt</option>
                    <option value="2000000-9999999" <?= $priceFilter === '2000000-9999999' ? 'selected' : '' ?>>&gt; 2jt</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-wifi mr-1"></i> Fasilitas
                </label>
                <select name="facilities" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <?php 
                    $facilitiesFilter = $filters['facilities'] ?? $_GET['facilities'] ?? '';
                    ?>
                    <option value="">Semua Fasilitas</option>
                    <option value="wifi" <?= $facilitiesFilter === 'wifi' ? 'selected' : '' ?>>WiFi</option>
                    <option value="ac" <?= $facilitiesFilter === 'ac' ? 'selected' : '' ?>>AC</option>
                    <option value="kamar-mandi-dalam" <?= $facilitiesFilter === 'kamar-mandi-dalam' ? 'selected' : '' ?>>K. Mandi Dalam</option>
                </select>
            </div>
        </div>

        <!-- Additional Filters -->
        <div class="flex items-center">
            <input type="checkbox" 
                   name="available_only" 
                   id="available_only"
                   <?= !empty($filters['available_only']) ? 'checked' : '' ?>
                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
            <label for="available_only" class="ml-2 text-sm text-gray-700">
                Hanya tampilkan kost dengan kamar tersedia
            </label>
        </div>

        <!-- Action Buttons -->
        <div class="flex space-x-3">
            <button type="submit" 
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-search mr-2"></i>
                Cari Kost
            </button>
            <a href="<?= url('/tenant/search') ?>" 
               class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                <i class="fas fa-redo mr-2"></i>
                Reset Filter
            </a>
        </div>
    </form>
</div>

<!-- Results Section -->
<div class="mb-4 flex justify-between items-center">
    <p class="text-gray-600">
        Ditemukan <span class="font-bold text-gray-800"><?= $totalResults ?></span> kost
    </p>
</div>

<!-- Kost List -->
<?php if (empty($kostList)): ?>
    <div class="bg-white rounded-lg shadow-md p-12 text-center">
        <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-700 mb-2">Tidak ada kost ditemukan</h3>
        <p class="text-gray-500">Coba ubah filter pencarian Anda</p>
    </div>
<?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($kostList as $kost): ?>
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
            <!-- Kost Image -->
            <div class="relative h-48 bg-gray-200">
                <?php if ($kost['primary_photo']): ?>
                    <img src="<?= url('/' . $kost['primary_photo']) ?>" 
                         alt="<?= e($kost['name']) ?>"
                         class="w-full h-full object-cover">
                <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center bg-gray-300">
                        <i class="fas fa-building text-6xl text-gray-400"></i>
                    </div>
                <?php endif; ?>
                
                <!-- Gender Badge -->
                <?php
                $genderColors = [
                    'putra' => 'bg-blue-600',
                    'putri' => 'bg-pink-600',
                    'campur' => 'bg-purple-600'
                ];
                $genderColor = $genderColors[$kost['gender_type']] ?? 'bg-gray-600';
                ?>
                <span class="absolute top-2 right-2 px-3 py-1 <?= $genderColor ?> text-white text-xs font-semibold rounded-full">
                    <?= ucfirst($kost['gender_type']) ?>
                </span>
            </div>

            <!-- Kost Info -->
            <div class="p-4">
                <h3 class="text-lg font-bold text-gray-800 mb-2 truncate"><?= e($kost['name']) ?></h3>
                
                <div class="flex items-center text-sm text-gray-600 mb-2">
                    <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>
                    <span class="truncate"><?= e($kost['location'] ?? 'Lokasi tidak tersedia') ?></span>
                </div>

                <!-- Rating -->
                <?php if (!empty($kost['total_reviews']) && $kost['total_reviews'] > 0): ?>
                    <div class="flex items-center mb-2">
                        <div class="flex items-center">
                            <?php 
                            $avgRating = round((float) ($kost['average_rating'] ?? 0), 1);
                            for ($i = 1; $i <= 5; $i++): 
                            ?>
                                <i class="<?= $i <= floor($avgRating) ? 'fas' : ($i - 0.5 <= $avgRating ? 'fas fa-star-half-alt' : 'far') ?> fa-star text-yellow-400 text-xs"></i>
                            <?php endfor; ?>
                        </div>
                        <span class="ml-2 text-sm text-gray-600">
                            <?= $avgRating ?> (<?= $kost['total_reviews'] ?> review)
                        </span>
                    </div>
                <?php endif; ?>

                <div class="flex items-center justify-between mb-3">
                    <div>
                        <?php if (!empty($kost['min_price']) && $kost['min_price'] > 0): ?>
                            <p class="text-sm text-gray-500">Mulai dari</p>
                            <p class="text-xl font-bold text-blue-600">
                                Rp <?= number_format($kost['min_price'], 0, ',', '.') ?>
                            </p>
                            <p class="text-xs text-gray-500">/bulan</p>
                        <?php else: ?>
                            <p class="text-sm text-gray-500">Harga</p>
                            <p class="text-lg font-semibold text-gray-400">
                                Belum Tersedia
                            </p>
                        <?php endif; ?>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-door-open text-green-500 mr-1"></i>
                            <?= $kost['available_kamar'] ?? 0 ?> Tersedia
                        </p>
                        <p class="text-xs text-gray-500">
                            dari <?= $kost['total_kamar'] ?? 0 ?> kamar
                        </p>
                    </div>
                </div>

                <a href="<?= url('/tenant/search/' . $kost['id']) ?>" 
                   class="block w-full text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Lihat Detail
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
