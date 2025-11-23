<?php 
$pageTitle = $pageTitle ?? 'Detail Kost';
$kost = $kost ?? [];
$similarKost = $similarKost ?? [];
$isAuthenticated = isset($_SESSION['user_id']);
?>

<div class="min-h-screen bg-gray-50">
    
    <div class="container mx-auto px-4 py-8">
        
        <!-- Back Button -->
        <div class="mb-4">
            <a href="<?= url('/search') ?>" class="text-blue-600 hover:text-blue-700">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Pencarian
            </a>
        </div>

        <!-- Kost Images Gallery -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <?php if (!empty($kost['photos'])): ?>
                <div class="grid grid-cols-4 gap-2 p-4">
                    <?php foreach ($kost['photos'] as $index => $photo): ?>
                        <?php if ($index === 0): ?>
                            <!-- Main Photo -->
                            <div class="col-span-4 md:col-span-2 row-span-2">
                                <img src="<?= url('/' . $photo['photo_url']) ?>" 
                                     alt="<?= e($kost['name']) ?>"
                                     class="w-full h-96 object-cover rounded-lg">
                            </div>
                        <?php elseif ($index < 5): ?>
                            <div class="col-span-2 md:col-span-1">
                                <img src="<?= url('/' . $photo['photo_url']) ?>" 
                                     alt="<?= e($kost['name']) ?>"
                                     class="w-full h-48 object-cover rounded-lg">
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="h-96 bg-gray-200 flex items-center justify-center">
                    <i class="fas fa-building text-9xl text-gray-400"></i>
                </div>
            <?php endif; ?>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Kost Information -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-800 mb-2"><?= e($kost['name']) ?></h1>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>
                                <span><?= e($kost['address']) ?></span>
                            </div>
                            <?php if ($kost['location']): ?>
                                <p class="text-sm text-gray-500 mt-1 ml-6"><?= e($kost['location']) ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <?php
                        $genderColors = [
                            'putra' => 'bg-blue-600',
                            'putri' => 'bg-pink-600',
                            'campur' => 'bg-purple-600'
                        ];
                        $genderColor = $genderColors[$kost['gender_type']] ?? 'bg-gray-600';
                        ?>
                        <span class="px-4 py-2 <?= $genderColor ?> text-white text-sm font-semibold rounded-full">
                            <?= ucfirst($kost['gender_type']) ?>
                        </span>
                    </div>

                    <!-- Description -->
                    <?php if ($kost['description']): ?>
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">Deskripsi</h3>
                            <div class="text-gray-600 prose max-w-none">
                                <?= $kost['description'] ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Facilities -->
                    <?php if (!empty($kost['facilities_array'])): ?>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">Fasilitas Umum</h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                <?php foreach ($kost['facilities_array'] as $facility): ?>
                                    <div class="flex items-center text-gray-700">
                                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                        <span><?= e($facility) ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Available Rooms -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-door-open text-blue-600 mr-2"></i>
                        Kamar Tersedia (<?= $kost['available_kamar'] ?? 0 ?> dari <?= $kost['total_kamar'] ?? 0 ?>)
                    </h3>

                    <?php if (empty($kost['kamar'])): ?>
                        <p class="text-gray-500 text-center py-8">Belum ada kamar tersedia</p>
                    <?php else: ?>
                        <div class="space-y-4">
                            <?php foreach ($kost['kamar'] as $kamar): ?>
                                <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-500 transition">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-lg text-gray-800"><?= e($kamar['name']) ?></h4>
                                            
                                            <?php if ($kamar['description']): ?>
                                                <p class="text-sm text-gray-600 mt-1"><?= e($kamar['description']) ?></p>
                                            <?php endif; ?>

                                            <!-- Room Facilities -->
                                            <?php if (!empty($kamar['facilities_array'])): ?>
                                                <div class="mt-2 flex flex-wrap gap-2">
                                                    <?php foreach ($kamar['facilities_array'] as $facility): ?>
                                                        <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">
                                                            <i class="fas fa-check text-green-500 mr-1"></i>
                                                            <?= e($facility) ?>
                                                        </span>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>

                                            <div class="mt-3 flex items-center">
                                                <span class="text-2xl font-bold text-blue-600">
                                                    Rp <?= number_format($kamar['price'], 0, ',', '.') ?>
                                                </span>
                                                <span class="text-sm text-gray-500 ml-2">/bulan</span>
                                            </div>
                                        </div>

                                        <div class="ml-4">
                                            <?php if ($kamar['status'] === 'available'): ?>
                                                <?php if ($isAuthenticated): ?>
                                                    <a href="<?= url('/tenant/kamar/' . $kamar['id'] . '/book') ?>" 
                                                       class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition whitespace-nowrap">
                                                        Booking
                                                    </a>
                                                <?php else: ?>
                                                    <a href="<?= url('/login?redirect=/kost/' . $kost['id']) ?>" 
                                                       class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition whitespace-nowrap">
                                                        Login untuk Booking
                                                    </a>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="px-6 py-2 bg-gray-300 text-gray-600 rounded-lg cursor-not-allowed">
                                                    Tidak Tersedia
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                
                <!-- Owner Contact -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="fas fa-user-tie text-gray-600 mr-2"></i>
                        Kontak Pemilik
                    </h3>
                    
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-500">Nama</p>
                            <p class="font-semibold text-gray-800"><?= e($kost['owner_name']) ?></p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500">No. Telepon</p>
                            <a href="tel:<?= e($kost['owner_phone']) ?>" 
                               class="font-semibold text-blue-600 hover:text-blue-700">
                                <?= e($kost['owner_phone']) ?>
                            </a>
                        </div>

                        <a href="https://wa.me/62<?= ltrim($kost['owner_phone'], '0') ?>" 
                           target="_blank"
                           class="block w-full mt-4 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-center transition">
                            <i class="fab fa-whatsapp mr-2"></i>
                            Chat WhatsApp
                        </a>
                    </div>
                </div>

                <!-- Price Range -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="fas fa-money-bill-wave text-green-600 mr-2"></i>
                        Rentang Harga
                    </h3>
                    
                    <?php if (!empty($kost['kamar'])): ?>
                        <?php
                        $prices = array_column($kost['kamar'], 'price');
                        $minPrice = min($prices);
                        $maxPrice = max($prices);
                        ?>
                        <div class="text-center">
                            <p class="text-sm text-gray-500 mb-2">Mulai dari</p>
                            <p class="text-3xl font-bold text-blue-600">
                                Rp <?= number_format($minPrice, 0, ',', '.') ?>
                            </p>
                            <?php if ($minPrice !== $maxPrice): ?>
                                <p class="text-sm text-gray-500 mt-2">
                                    hingga Rp <?= number_format($maxPrice, 0, ',', '.') ?>
                                </p>
                            <?php endif; ?>
                            <p class="text-xs text-gray-500 mt-1">/bulan</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Map View Component -->
                <?php include __DIR__ . '/../components/map-view.php'; ?>

            </div>
        </div>

        <!-- Similar Kost -->
        <?php if (!empty($similarKost)): ?>
        <div class="mt-8">
            <h3 class="text-2xl font-bold text-gray-800 mb-4">Kost Serupa</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php foreach ($similarKost as $similar): ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                    <div class="relative h-40 bg-gray-200">
                        <?php if ($similar['primary_photo']): ?>
                            <img src="<?= url('/' . $similar['primary_photo']) ?>" 
                                 alt="<?= e($similar['name']) ?>"
                                 class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center bg-gray-300">
                                <i class="fas fa-building text-4xl text-gray-400"></i>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="p-4">
                        <h4 class="font-bold text-gray-800 mb-2 truncate"><?= e($similar['name']) ?></h4>
                        <p class="text-sm text-gray-600 mb-2 truncate">
                            <i class="fas fa-map-marker-alt text-red-500 mr-1"></i>
                            <?= e($similar['location'] ?? '') ?>
                        </p>
                        <p class="text-lg font-bold text-blue-600 mb-3">
                            Rp <?= number_format($similar['min_price'] ?? 0, 0, ',', '.') ?>
                        </p>
                        <a href="<?= url('/kost/' . $similar['id']) ?>" 
                           class="block w-full text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                            Lihat Detail
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
    </div>
    
</div>
