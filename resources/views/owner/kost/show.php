<?php 
$pageTitle = $pageTitle ?? 'Detail Kost';
$kost = $kost ?? [];
$photos = $photos ?? [];
$kamars = $kamars ?? [];
$facilities = !empty($kost['facilities']) ? json_decode($kost['facilities'], true) : [];
?>

<div class="mb-6 flex items-center justify-between">
    <div>
        <a href="<?= url('/owner/kost') ?>" class="text-blue-600 hover:text-blue-800 mb-2 inline-block">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Kost
        </a>
        <h2 class="text-2xl font-bold text-gray-800"><?= e($kost['name']) ?></h2>
        <p class="text-gray-600">
            <i class="fas fa-map-marker-alt mr-1 text-red-500"></i>
            <?= e($kost['location']) ?>
        </p>
    </div>
    <div class="flex space-x-2">
        
    </div>
</div>

<!-- Kost Info -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    
    <!-- Main Info -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Kost</h3>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <p class="text-sm text-gray-500">Harga per Bulan</p>
                    <p class="text-xl font-bold text-blue-600">
                        Rp <?= number_format($kost['price'], 0, ',', '.') ?>
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tipe Gender</p>
                    <p class="text-lg font-medium text-gray-800">
                        <i class="fas fa-<?= $kost['gender_type'] === 'putra' ? 'mars text-blue-500' : ($kost['gender_type'] === 'putri' ? 'venus text-pink-500' : 'venus-mars text-purple-500') ?> mr-1"></i>
                        <?= ucfirst($kost['gender_type']) ?>
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Status</p>
                    <span class="inline-block px-3 py-1 text-sm font-medium rounded-full
                        <?= $kost['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' ?>">
                        <?= ucfirst($kost['status']) ?>
                    </span>
                </div>
            </div>

            <div class="mb-4">
                <p class="text-sm text-gray-500 mb-1">Alamat Lengkap</p>
                <p class="text-gray-700"><?= e($kost['address']) ?></p>
            </div>

            <?php if (!empty($facilities)): ?>
            <div class="mb-4">
                <p class="text-sm text-gray-500 mb-2">Fasilitas</p>
                <div class="flex flex-wrap gap-2">
                    <?php foreach ($facilities as $facility): ?>
                        <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-sm">
                            <i class="fas fa-check mr-1"></i><?= e($facility) ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($kost['description'])): ?>
            <div>
                <p class="text-sm text-gray-500 mb-2">Deskripsi</p>
                <div class="prose max-w-none text-gray-700">
                    <?= $kost['description'] ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Photos -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Foto Kost</h3>
            <?php if (!empty($photos)): ?>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <?php foreach ($photos as $photo): ?>
                        <div class="relative group">
                            <?php if (!empty($photo['photo_url'])): ?>
                                <img src="<?= asset($photo['photo_url']) ?>" 
                                     alt="Kost Photo" 
                                     class="w-full h-40 object-cover rounded-lg cursor-pointer hover:opacity-75 transition"
                                     onclick="window.open(this.src, '_blank')">
                            <?php else: ?>
                                <img src="https://placehold.co/600x400?text=No+Image" 
                                     alt="No Photo" 
                                     class="w-full h-40 object-cover rounded-lg">
                            <?php endif; ?>
                            <?php if ($photo['is_primary']): ?>
                                <span class="absolute top-2 right-2 px-2 py-1 bg-yellow-500 text-white text-xs rounded-full">
                                    <i class="fas fa-star"></i> Primary
                                </span>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <img src="https://placehold.co/600x400?text=No+Image" 
                         alt="No Photos" 
                         class="w-full max-w-md h-40 object-cover rounded-lg mx-auto">
                    <p class="text-gray-500 text-sm mt-3">Belum ada foto kost</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Sidebar -->
    <div>
        <!-- Stats -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistik</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Total Kamar</span>
                    <span class="text-xl font-bold text-gray-800"><?= count($kamars) ?></span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Tersedia</span>
                    <span class="text-xl font-bold text-green-600">
                        <?= count(array_filter($kamars, fn($k) => $k['status'] === 'available')) ?>
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Terisi</span>
                    <span class="text-xl font-bold text-blue-600">
                        <?= count(array_filter($kamars, fn($k) => $k['status'] === 'occupied')) ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Actions</h3>
            <div class="space-y-3">
                <a href="<?= url('/owner/kost/' . $kost['id'] . '/kamar/create') ?>" 
                   class="block w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 text-center">
                    <i class="fas fa-plus mr-2"></i>Tambah Kamar
                </a>
                <a href="<?= url('/owner/kost/' . $kost['id'] . '/edit') ?>" 
                   class="block w-full bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 text-center">
                    <i class="fas fa-edit mr-2"></i>Edit Kost
                </a>
                <form action="<?= url('/owner/kost/' . $kost['id'] . '/delete') ?>" method="POST"
                      onsubmit="return confirm('Yakin ingin menghapus kost ini? Data tidak dapat dikembalikan.')">
                    <?= csrf_field() ?>
                    <button type="submit" 
                            class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                        <i class="fas fa-trash mr-2"></i>Hapus Kost
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Kamar List -->
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-800">Daftar Kamar</h3>
        <a href="<?= url('/owner/kost/' . $kost['id'] . '/kamar/create') ?>" 
           class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 text-sm">
            <i class="fas fa-plus mr-1"></i>Tambah Kamar
        </a>
    </div>

    <?php if (empty($kamars)): ?>
        <div class="text-center py-8">
            <i class="fas fa-door-open text-4xl text-gray-300 mb-3"></i>
            <p class="text-gray-500 mb-4">Belum ada kamar terdaftar</p>
            <a href="<?= url('/owner/kost/' . $kost['id'] . '/kamar/create') ?>" 
               class="inline-block bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                <i class="fas fa-plus mr-2"></i>Tambah Kamar Pertama
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach ($kamars as $kamar): 
                $kamarFacilities = !empty($kamar['facilities']) ? json_decode($kamar['facilities'], true) : [];
            ?>
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <h4 class="font-semibold text-gray-800">
                                <i class="fas fa-door-closed mr-1 text-gray-400"></i>
                                Kamar <?= e($kamar['name']) ?>
                            </h4>
                            <?php if (!empty($kamar['size'] ?? '')): ?>
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-ruler-combined mr-1"></i>
                                    <?= e($kamar['size']) ?> m²
                                </p>
                            <?php endif; ?>
                        </div>
                        <span class="px-2 py-1 text-xs rounded-full font-medium
                            <?= $kamar['status'] === 'available' ? 'bg-green-100 text-green-800' : 
                                ($kamar['status'] === 'occupied' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') ?>">
                            <?php 
                            $statusLabel = [
                                'available' => 'Tersedia',
                                'occupied' => 'Terisi',
                                'maintenance' => 'Maintenance'
                            ];
                            echo $statusLabel[$kamar['status']] ?? ucfirst($kamar['status']);
                            ?>
                        </span>
                    </div>
                    <p class="text-lg font-bold text-blue-600 mb-2">
                        Rp <?= number_format($kamar['price'] ?? 0, 0, ',', '.') ?><span class="text-sm font-normal text-gray-500">/bulan</span>
                    </p>
                    
                    <?php if (!empty($kamarFacilities)): ?>
                        <div class="mb-3">
                            <p class="text-xs text-gray-500 mb-1">Fasilitas:</p>
                            <div class="flex flex-wrap gap-1">
                                <?php foreach (array_slice($kamarFacilities, 0, 3) as $facility): ?>
                                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded">
                                        <?= e($facility) ?>
                                    </span>
                                <?php endforeach; ?>
                                <?php if (count($kamarFacilities) > 3): ?>
                                    <span class="text-xs text-gray-500">+<?= count($kamarFacilities) - 3 ?> lagi</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="flex space-x-2 mt-3">
                        <a href="<?= url('/owner/kamar/' . $kamar['id'] . '/edit') ?>" 
                           class="flex-1 bg-yellow-500 text-white px-3 py-1 rounded text-center text-sm hover:bg-yellow-600 transition">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </a>
                        <form action="<?= url('/owner/kamar/' . $kamar['id'] . '/delete') ?>" method="POST" class="flex-1"
                              onsubmit="return confirm('Yakin ingin menghapus kamar ini?\n\nKamar: <?= e($kamar['name']) ?>')">
                            <?= csrf_field() ?>
                            <button type="submit" class="w-full bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700 transition">
                                <i class="fas fa-trash mr-1"></i>Hapus
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
