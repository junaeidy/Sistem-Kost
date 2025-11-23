<?php 
$pageTitle = $pageTitle ?? 'Dashboard';
$stats = $stats ?? [];
$recentBookings = $recentBookings ?? [];
$upcomingPayments = $upcomingPayments ?? [];
$tenant = $tenant ?? [];
?>

<!-- Welcome Section -->
<div class="bg-gradient-to-r from-green-600 to-teal-600 rounded-lg shadow-lg p-4 sm:p-6 mb-6 sm:mb-8 text-white">
    <h2 class="text-xl sm:text-2xl font-bold mb-2">Selamat Datang, <?= e($tenant['name'] ?? 'Tenant') ?>!</h2>
    <p class="text-sm sm:text-base text-green-100">Temukan kost impian Anda dan kelola booking dengan mudah</p>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 mb-6 sm:mb-8">
    
    <!-- Total Bookings -->
    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs sm:text-sm">Total Booking</p>
                <h3 class="text-2xl sm:text-3xl font-bold text-gray-800"><?= $stats['total_bookings'] ?? 0 ?></h3>
            </div>
            <div class="w-12 h-12 sm:w-14 sm:h-14 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-calendar-alt text-xl sm:text-2xl text-blue-600"></i>
            </div>
        </div>
    </div>
    
    <!-- Active Rentals -->
    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs sm:text-sm">Sewa Aktif</p>
                <h3 class="text-2xl sm:text-3xl font-bold text-gray-800"><?= $stats['active_rentals'] ?? 0 ?></h3>
            </div>
            <div class="w-12 h-12 sm:w-14 sm:h-14 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-home text-xl sm:text-2xl text-green-600"></i>
            </div>
        </div>
    </div>
    
    <!-- Waiting Payment -->
    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs sm:text-sm">Menunggu Bayar</p>
                <h3 class="text-2xl sm:text-3xl font-bold text-gray-800"><?= $stats['waiting_payment'] ?? 0 ?></h3>
            </div>
            <div class="w-12 h-12 sm:w-14 sm:h-14 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-clock text-xl sm:text-2xl text-yellow-600"></i>
            </div>
        </div>
    </div>
    
    <!-- Completed -->
    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs sm:text-sm">Selesai</p>
                <h3 class="text-2xl sm:text-3xl font-bold text-gray-800"><?= $stats['completed'] ?? 0 ?></h3>
            </div>
            <div class="w-12 h-12 sm:w-14 sm:h-14 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-check-circle text-xl sm:text-2xl text-purple-600"></i>
            </div>
        </div>
    </div>
    
</div>

<!-- Active Booking Section -->
<?php if ($stats['active_booking']): ?>
<div class="bg-white rounded-lg shadow-md p-4 sm:p-6 mb-6 sm:mb-8">
    <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-4">
        <i class="fas fa-home text-green-600 mr-2"></i>
        Sewa Aktif Saat Ini
    </h3>
    
    <div class="border-l-4 border-green-500 pl-4">
        <h4 class="font-semibold text-base sm:text-lg text-gray-800"><?= e($stats['active_booking']['kost_name']) ?></h4>
        <p class="text-sm sm:text-base text-gray-600">Kamar: <?= e($stats['active_booking']['kamar_name']) ?></p>
        <div class="mt-2 flex flex-col sm:flex-row sm:items-center sm:space-x-4 text-xs sm:text-sm text-gray-500 gap-1 sm:gap-0">
            <span><i class="fas fa-calendar mr-1"></i><?= date('d M Y', strtotime($stats['active_booking']['start_date'])) ?></span>
            <span class="hidden sm:inline">-</span>
            <span><i class="fas fa-calendar-check mr-1"></i><?= date('d M Y', strtotime($stats['active_booking']['end_date'])) ?></span>
        </div>
        <div class="mt-3">
            <a href="<?= url('/tenant/bookings/' . $stats['active_booking']['id']) ?>" 
               class="text-sm sm:text-base text-blue-600 hover:text-blue-700 font-medium">
                Lihat Detail <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Upcoming Payments -->
<?php if (!empty($upcomingPayments)): ?>
<div class="bg-white rounded-lg shadow-md p-4 sm:p-6 mb-6 sm:mb-8">
    <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-4">
        <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
        Menunggu Pembayaran
    </h3>
    
    <div class="space-y-4">
        <?php foreach ($upcomingPayments as $payment): ?>
        <div class="border-l-4 border-yellow-500 pl-4 py-2">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3">
                <div class="flex-1">
                    <h4 class="font-semibold text-sm sm:text-base text-gray-800"><?= e($payment['kost_name']) ?></h4>
                    <p class="text-xs sm:text-sm text-gray-600">Kamar: <?= e($payment['kamar_name']) ?></p>
                    <p class="text-sm sm:text-base font-bold text-blue-600 mt-1">Rp <?= number_format($payment['total_price'], 0, ',', '.') ?></p>
                </div>
                <div>
                    <a href="<?= url('/tenant/bookings/' . $payment['id']) ?>" 
                       class="block px-4 py-2 bg-blue-600 text-white text-xs sm:text-sm rounded-lg hover:bg-blue-700 text-center">
                        Bayar Sekarang
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Recent Bookings -->
<div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 gap-2">
        <h3 class="text-lg sm:text-xl font-bold text-gray-800">
            <i class="fas fa-history text-gray-600 mr-2"></i>
            Riwayat Booking Terbaru
        </h3>
        <a href="<?= url('/tenant/bookings') ?>" 
           class="text-blue-600 hover:text-blue-700 text-xs sm:text-sm font-medium">
            Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
    
    <?php if (empty($recentBookings)): ?>
        <div class="text-center py-8 sm:py-12">
            <i class="fas fa-inbox text-4xl sm:text-6xl text-gray-300 mb-4"></i>
            <p class="text-sm sm:text-base text-gray-500">Belum ada booking</p>
            <a href="<?= url('/search') ?>" 
               class="mt-4 inline-block px-4 sm:px-6 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700" target="_blank">
                Cari Kost Sekarang
            </a>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 sm:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kost</th>
                        <th class="px-3 sm:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Kamar</th>
                        <th class="px-3 sm:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden lg:table-cell">Periode</th>
                        <th class="px-3 sm:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                        <th class="px-3 sm:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Status</th>
                        <th class="px-3 sm:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($recentBookings as $booking): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-3 sm:px-4 py-4">
                            <div>
                                <p class="font-semibold text-sm text-gray-800"><?= e($booking['kost_name']) ?></p>
                                <p class="text-xs text-gray-500"><?= e($booking['kost_location'] ?? '') ?></p>
                                <!-- Show kamar on mobile -->
                                <p class="text-xs text-gray-600 md:hidden mt-1">Kamar: <?= e($booking['kamar_name']) ?></p>
                            </div>
                        </td>
                        <td class="px-3 sm:px-4 py-4 text-xs sm:text-sm text-gray-700 hidden md:table-cell"><?= e($booking['kamar_name']) ?></td>
                        <td class="px-3 sm:px-4 py-4 text-xs sm:text-sm text-gray-600 hidden lg:table-cell">
                            <?= date('d/m/Y', strtotime($booking['start_date'])) ?><br>
                            <span class="text-xs text-gray-500"><?= $booking['duration_months'] ?> bulan</span>
                        </td>
                        <td class="px-3 sm:px-4 py-4 text-xs sm:text-sm font-semibold text-gray-800">
                            Rp <?= number_format($booking['total_price'], 0, ',', '.') ?>
                        </td>
                        <td class="px-3 sm:px-4 py-4 hidden sm:table-cell">
                            <?php
                            $statusColors = [
                                'waiting_payment' => 'bg-yellow-100 text-yellow-800',
                                'paid' => 'bg-blue-100 text-blue-800',
                                'accepted' => 'bg-green-100 text-green-800',
                                'rejected' => 'bg-red-100 text-red-800',
                                'active_rent' => 'bg-green-100 text-green-800',
                                'completed' => 'bg-gray-100 text-gray-800',
                                'cancelled' => 'bg-red-100 text-red-800'
                            ];
                            $statusLabels = [
                                'waiting_payment' => 'Menunggu Bayar',
                                'paid' => 'Sudah Bayar',
                                'accepted' => 'Diterima',
                                'rejected' => 'Ditolak',
                                'active_rent' => 'Aktif',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan'
                            ];
                            $colorClass = $statusColors[$booking['status']] ?? 'bg-gray-100 text-gray-800';
                            $label = $statusLabels[$booking['status']] ?? $booking['status'];
                            ?>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full <?= $colorClass ?>">
                                <?= $label ?>
                            </span>
                        </td>
                        <td class="px-3 sm:px-4 py-4">
                            <a href="<?= url('/tenant/bookings/' . $booking['id']) ?>" 
                               class="text-blue-600 hover:text-blue-700 text-xs sm:text-sm font-medium">
                                <i class="fas fa-eye sm:hidden"></i>
                                <span class="hidden sm:inline">Detail</span>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Quick Actions -->
<div class="mt-6 sm:mt-8 grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
    <div class="bg-gradient-to-r from-green-500 to-teal-600 rounded-lg shadow-md p-4 sm:p-6 text-white">
        <h4 class="text-lg sm:text-xl font-bold mb-2">Cari Kost Baru</h4>
        <p class="text-green-100 text-sm sm:text-base mb-4">Temukan kost yang sesuai dengan kebutuhan Anda</p>
        <a href="<?= url('/search') ?>" 
           class="inline-block px-4 sm:px-6 py-2 bg-white text-green-600 rounded-lg hover:bg-green-50 font-semibold text-sm sm:text-base" target="_blank">
            Mulai Pencarian
        </a>
    </div>
    
    <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg shadow-md p-4 sm:p-6 text-white">
        <h4 class="text-lg sm:text-xl font-bold mb-2">Lihat Booking Anda</h4>
        <p class="text-purple-100 text-sm sm:text-base mb-4">Kelola semua booking dan pembayaran Anda</p>
        <a href="<?= url('/tenant/bookings') ?>" 
           class="inline-block px-4 sm:px-6 py-2 bg-white text-purple-600 rounded-lg hover:bg-purple-50 font-semibold text-sm sm:text-base">
            Lihat Booking
        </a>
    </div>
</div>
