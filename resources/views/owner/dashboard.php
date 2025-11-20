<?php 
$pageTitle = $pageTitle ?? 'Dashboard';
$stats = $stats ?? [];
$recentBookings = $recentBookings ?? [];
$kostList = $kostList ?? [];
$owner = $owner ?? [];
?>

<!-- Welcome Section -->
<div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg shadow-lg p-6 mb-8 text-white">
    <h2 class="text-2xl font-bold mb-2">Selamat Datang, <?= e($owner['name'] ?? 'Owner') ?>!</h2>
    <p class="text-blue-100">Kelola properti kost Anda dengan mudah dan efisien</p>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    
    <!-- Total Kost -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Kost</p>
                <h3 class="text-3xl font-bold text-gray-800"><?= $stats['total_kost'] ?? 0 ?></h3>
                <p class="text-xs text-gray-500 mt-1">
                    <?= $stats['total_kamar'] ?? 0 ?> kamar total
                </p>
            </div>
            <div class="w-14 h-14 bg-purple-100 rounded-full flex items-center justify-center">
                <i class="fas fa-building text-2xl text-purple-600"></i>
            </div>
        </div>
    </div>
    
    <!-- Available Kamar -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Kamar Tersedia</p>
                <h3 class="text-3xl font-bold text-gray-800"><?= $stats['available_kamar'] ?? 0 ?></h3>
                <p class="text-xs text-gray-500 mt-1">
                    dari <?= $stats['total_kamar'] ?? 0 ?> kamar
                </p>
            </div>
            <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-door-open text-2xl text-green-600"></i>
            </div>
        </div>
    </div>
    
    <!-- Active Rentals -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Penyewa Aktif</p>
                <h3 class="text-3xl font-bold text-gray-800"><?= $stats['active_rentals'] ?? 0 ?></h3>
                <p class="text-xs text-gray-500 mt-1">
                    <?= $stats['total_bookings'] ?? 0 ?> total booking
                </p>
            </div>
            <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-users text-2xl text-blue-600"></i>
            </div>
        </div>
    </div>
    
    <!-- Total Revenue -->
    <div class="bg-white rounded-lg shadow-md p-6 lg:col-span-3">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Pendapatan</p>
                <h3 class="text-3xl font-bold text-green-600">Rp <?= number_format($stats['total_revenue'] ?? 0, 0, ',', '.') ?></h3>
                <p class="text-xs text-gray-500 mt-1">Dari pembayaran yang berhasil</p>
            </div>
            <div class="w-14 h-14 bg-yellow-100 rounded-full flex items-center justify-center">
                <i class="fas fa-money-bill-wave text-2xl text-yellow-600"></i>
            </div>
        </div>
    </div>
    
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    
    <!-- Recent Bookings -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
                    Booking Terbaru
                </h3>
                <a href="<?= url('/owner/bookings') ?>" class="text-blue-600 hover:text-blue-800 text-sm">
                    Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
        <div class="p-6">
            <?php if (empty($recentBookings)): ?>
                <div class="text-center py-8">
                    <i class="fas fa-calendar-times text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500">Belum ada booking</p>
                </div>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($recentBookings as $booking): ?>
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <p class="font-medium text-gray-800"><?= e($booking['kost_name']) ?></p>
                                    <p class="text-sm text-gray-600"><?= e($booking['kamar_name']) ?></p>
                                </div>
                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                    <?= $booking['status'] === 'active_rent' ? 'bg-green-100 text-green-800' : 
                                        ($booking['status'] === 'paid' ? 'bg-blue-100 text-blue-800' : 
                                        ($booking['status'] === 'waiting_payment' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) ?>">
                                    <?= str_replace('_', ' ', ucfirst($booking['status'])) ?>
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <div class="text-gray-600">
                                    <i class="fas fa-user mr-1"></i>
                                    <?= e($booking['tenant_name']) ?>
                                </div>
                                <div class="text-gray-500">
                                    <?= date('d M Y', strtotime($booking['start_date'])) ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Kost List -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-home text-purple-600 mr-2"></i>
                    Properti Kost Saya
                </h3>
                <a href="<?= url('/owner/kost/create') ?>" class="bg-blue-600 text-white px-3 py-1 rounded-lg hover:bg-blue-700 text-sm">
                    <i class="fas fa-plus mr-1"></i> Tambah
                </a>
            </div>
        </div>
        <div class="p-6">
            <?php if (empty($kostList)): ?>
                <div class="text-center py-8">
                    <i class="fas fa-building text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500 mb-3">Belum ada kost terdaftar</p>
                    <a href="<?= url('/owner/kost/create') ?>" class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        <i class="fas fa-plus mr-1"></i> Tambah Kost Pertama
                    </a>
                </div>
            <?php else: ?>
                <div class="space-y-3">
                    <?php foreach ($kostList as $kost): ?>
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <p class="font-medium text-gray-800"><?= e($kost['name']) ?></p>
                                    <p class="text-sm text-gray-600"><?= e($kost['location']) ?></p>
                                </div>
                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                    <?= $kost['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' ?>">
                                    <?= ucfirst($kost['status']) ?>
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <div class="text-gray-600">
                                    <i class="fas fa-door-open mr-1"></i>
                                    <?= $kost['available_kamar'] ?>/<?= $kost['total_kamar'] ?> tersedia
                                </div>
                                <div class="space-x-2">
                                    <a href="<?= url('/owner/kost/' . $kost['id']) ?>" 
                                       class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?= url('/owner/kost/' . $kost['id'] . '/edit') ?>" 
                                       class="text-yellow-600 hover:text-yellow-800">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
</div>

<!-- Quick Actions -->
<div class="bg-white rounded-lg shadow-md p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">
        <i class="fas fa-bolt text-yellow-500 mr-2"></i>
        Quick Actions
    </h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="<?= url('/owner/kost/create') ?>" 
           class="flex flex-col items-center justify-center p-6 border-2 border-dashed border-gray-300 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition">
            <i class="fas fa-plus-circle text-3xl text-blue-600 mb-2"></i>
            <span class="text-sm font-medium text-gray-700">Tambah Kost</span>
        </a>
        <a href="<?= url('/owner/bookings') ?>" 
           class="flex flex-col items-center justify-center p-6 border-2 border-dashed border-gray-300 rounded-lg hover:border-green-500 hover:bg-green-50 transition">
            <i class="fas fa-calendar-check text-3xl text-green-600 mb-2"></i>
            <span class="text-sm font-medium text-gray-700">Lihat Booking</span>
        </a>
        <a href="<?= url('/owner/kost') ?>" 
           class="flex flex-col items-center justify-center p-6 border-2 border-dashed border-gray-300 rounded-lg hover:border-purple-500 hover:bg-purple-50 transition">
            <i class="fas fa-building text-3xl text-purple-600 mb-2"></i>
            <span class="text-sm font-medium text-gray-700">Kelola Kost</span>
        </a>
        <a href="<?= url('/owner/profile') ?>" 
           class="flex flex-col items-center justify-center p-6 border-2 border-dashed border-gray-300 rounded-lg hover:border-orange-500 hover:bg-orange-50 transition">
            <i class="fas fa-user-cog text-3xl text-orange-600 mb-2"></i>
            <span class="text-sm font-medium text-gray-700">Profil Saya</span>
        </a>
    </div>
</div>
