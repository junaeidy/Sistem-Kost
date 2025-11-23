<?php 
$pageTitle = $pageTitle ?? 'Kelola Kost';
$kostList = $kostList ?? [];
$pagination = $pagination ?? null;
?>

<div class="mb-4 sm:mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
    <div>
        <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Kelola Kost</h2>
        <p class="text-sm text-gray-600">Manage all your properties</p>
    </div>
    <a href="<?= url('/owner/kost/create') ?>" 
       class="bg-blue-600 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg hover:bg-blue-700 transition text-center text-sm sm:text-base">
        <i class="fas fa-plus mr-2"></i>Tambah Kost
    </a>
</div>

<?php if (empty($kostList)): ?>
    <div class="bg-white rounded-lg shadow-md p-8 sm:p-12 text-center">
        <i class="fas fa-building text-4xl sm:text-6xl text-gray-300 mb-4"></i>
        <h3 class="text-lg sm:text-xl font-semibold text-gray-700 mb-2">Belum Ada Kost</h3>
        <p class="text-sm sm:text-base text-gray-500 mb-4 sm:mb-6">Mulai tambahkan properti kost Anda sekarang</p>
        <a href="<?= url('/owner/kost/create') ?>" 
           class="inline-block bg-blue-600 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg hover:bg-blue-700 transition text-sm sm:text-base">
            <i class="fas fa-plus mr-2"></i>Tambah Kost Pertama
        </a>
    </div>
<?php else: ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        <?php foreach ($kostList as $kost): ?>
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                <!-- Status Badge -->
                <div class="relative">
                    <div class="h-40 sm:h-48 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                        <i class="fas fa-building text-white text-5xl sm:text-6xl opacity-50"></i>
                    </div>
                    <span class="absolute top-2 sm:top-3 right-2 sm:right-3 px-2 sm:px-3 py-1 text-xs font-medium rounded-full
                        <?= $kost['status'] === 'active' ? 'bg-green-500 text-white' : 'bg-gray-500 text-white' ?>">
                        <?= ucfirst($kost['status']) ?>
                    </span>
                    <span class="absolute top-2 sm:top-3 left-2 sm:left-3 px-2 sm:px-3 py-1 text-xs font-medium rounded-full
                        <?= $kost['gender_type'] === 'putra' ? 'bg-blue-500 text-white' : 
                            ($kost['gender_type'] === 'putri' ? 'bg-pink-500 text-white' : 'bg-purple-500 text-white') ?>">
                        <i class="fas fa-<?= $kost['gender_type'] === 'putra' ? 'mars' : ($kost['gender_type'] === 'putri' ? 'venus' : 'venus-mars') ?> mr-1"></i>
                        <?= ucfirst($kost['gender_type']) ?>
                    </span>
                </div>

                <div class="p-4 sm:p-5">
                    <h3 class="text-base sm:text-lg font-bold text-gray-800 mb-2 truncate"><?= e($kost['name']) ?></h3>
                    <p class="text-xs sm:text-sm text-gray-600 mb-3 truncate">
                        <i class="fas fa-map-marker-alt mr-1 text-red-500"></i>
                        <?= e($kost['location']) ?>
                    </p>

                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-2 mb-4 pb-4 border-b border-gray-200">
                        <div class="text-center">
                            <p class="text-xs text-gray-500">Total</p>
                            <p class="text-base sm:text-lg font-bold text-gray-800"><?= $kost['total_kamar'] ?></p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-gray-500">Tersedia</p>
                            <p class="text-base sm:text-lg font-bold text-green-600"><?= $kost['available_kamar'] ?></p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-gray-500">Terisi</p>
                            <p class="text-base sm:text-lg font-bold text-blue-600"><?= $kost['occupied_kamar'] ?></p>
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="mb-4">
                        <p class="text-xs sm:text-sm text-gray-500">Harga mulai dari</p>
                        <p class="text-lg sm:text-xl font-bold text-blue-600">
                            Rp <?= number_format($kost['price'], 0, ',', '.') ?>
                            <span class="text-xs sm:text-sm text-gray-500 font-normal">/bulan</span>
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="flex space-x-2">
                        <a href="<?= url('/owner/kost/' . $kost['id']) ?>" 
                           class="flex-1 bg-blue-600 text-white px-3 sm:px-4 py-2 rounded-lg hover:bg-blue-700 text-center text-xs sm:text-sm">
                            <i class="fas fa-eye mr-1"></i><span class="hidden sm:inline">Detail</span>
                        </a>
                        <a href="<?= url('/owner/kost/' . $kost['id'] . '/edit') ?>" 
                           class="flex-1 bg-yellow-500 text-white px-3 sm:px-4 py-2 rounded-lg hover:bg-yellow-600 text-center text-xs sm:text-sm">
                            <i class="fas fa-edit mr-1"></i><span class="hidden sm:inline">Edit</span>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php if ($pagination && $pagination['total_pages'] > 1): ?>
        <?php include __DIR__ . '/../../components/pagination.php'; ?>
    <?php endif; ?>
<?php endif; ?>
