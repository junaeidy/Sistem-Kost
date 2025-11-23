<?php 
$pageTitle = $pageTitle ?? 'Cari Kost';
$kostList = $kostList ?? [];
$filters = $filters ?? [];
$totalResults = $totalResults ?? 0;
$isSearching = $isSearching ?? false;
?>

<div class="min-h-screen bg-gray-50">
    
    <!-- Search Header -->
    <section class="bg-gradient-to-br from-blue-600 to-purple-700 text-white py-12">
        <div class="container mx-auto px-4">
            <h1 class="text-4xl font-bold mb-2">Cari Kost di Medan</h1>
            <p class="text-blue-100">Temukan kost yang sesuai dengan kebutuhan Anda</p>
        </div>
    </section>

    <div class="container mx-auto px-4 py-8">
        
        <!-- Search & Filter Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <form method="GET" action="<?= url('/search') ?>" class="space-y-4">
                
                <!-- Search by Keyword and Location -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-search mr-1"></i> Kata Kunci
                        </label>
                        <input type="text" 
                               name="q" 
                               value="<?= e($filters['q'] ?? '') ?>"
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
                            <option value="putra" <?= ($filters['gender'] ?? '') === 'putra' ? 'selected' : '' ?>>Putra</option>
                            <option value="putri" <?= ($filters['gender'] ?? '') === 'putri' ? 'selected' : '' ?>>Putri</option>
                            <option value="campur" <?= ($filters['gender'] ?? '') === 'campur' ? 'selected' : '' ?>>Campur</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt mr-1"></i> Lokasi
                        </label>
                        <input type="text" 
                               name="location" 
                               value="<?= e($filters['location'] ?? '') ?>"
                               placeholder="Cari berdasarkan lokasi..."
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Price Range and Facilities -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-money-bill-wave mr-1"></i> Range Harga
                        </label>
                        <select name="price" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <?php 
                            $priceFilter = $filters['price'] ?? '';
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
                            $facilitiesFilter = $filters['facilities'] ?? '';
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
                    <a href="<?= url('/search') ?>" 
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
                    <div class="p-5">
                        <h3 class="text-lg font-bold text-gray-800 mb-2 truncate">
                            <?= e($kost['name']) ?>
                        </h3>
                        
                        <p class="text-sm text-gray-600 mb-3 flex items-start">
                            <i class="fas fa-map-marker-alt text-red-500 mr-2 mt-1"></i>
                            <span class="line-clamp-2"><?= e($kost['location']) ?></span>
                        </p>
                        
                        <!-- Price Range -->
                        <div class="mb-4">
                            <?php if (!empty($kost['min_price']) && $kost['min_price'] > 0): ?>
                                <p class="text-sm text-gray-500">Mulai dari</p>
                                <p class="text-2xl font-bold text-blue-600">
                                    Rp <?= number_format($kost['min_price'], 0, ',', '.') ?>
                                    <span class="text-sm text-gray-500 font-normal">/bulan</span>
                                </p>
                            <?php else: ?>
                                <p class="text-sm text-gray-500">Harga</p>
                                <p class="text-lg font-semibold text-gray-400">
                                    Belum Tersedia
                                </p>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Stats -->
                        <div class="flex items-center justify-between text-sm text-gray-600 mb-4 pb-4 border-b">
                            <?php 
                            $facilitiesCount = 0;
                            if (!empty($kost['facilities'])) {
                                $facilitiesArray = json_decode($kost['facilities'], true);
                                $facilitiesCount = is_array($facilitiesArray) ? count($facilitiesArray) : 0;
                            }
                            ?>
                            <div class="flex items-center">
                                <i class="fas fa-star text-yellow-500 mr-1"></i>
                                <span><?= $facilitiesCount ?> Fasilitas</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-1"></i>
                                <span><?= $kost['available_rooms'] ?? 0 ?> Tersedia</span>
                            </div>
                        </div>
                        
                        <!-- Action Button -->
                        <a href="<?= url('/kost/' . $kost['id']) ?>" 
                           class="block w-full text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-eye mr-2"></i>
                            Lihat Detail
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
    </div>
    
</div>
