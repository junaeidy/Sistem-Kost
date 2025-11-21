<?php 
$pageTitle = $pageTitle ?? 'Dashboard';
$stats = $stats ?? [];
$recentBookings = $recentBookings ?? [];
$upcomingPayments = $upcomingPayments ?? [];
$tenant = $tenant ?? [];
?>

<!-- Welcome Section -->
<div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg shadow-lg p-6 mb-8 text-white">
    <h2 class="text-2xl font-bold mb-2">Selamat Datang, <?= e($tenant['name'] ?? 'Tenant') ?>!</h2>
    <p class="text-blue-100">Temukan kost impian Anda dan kelola booking dengan mudah</p>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    <!-- Total Bookings -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Booking</p>
                <h3 class="text-3xl font-bold text-gray-800"><?= $stats['total_bookings'] ?? 0 ?></h3>
            </div>
            <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-calendar-alt text-2xl text-blue-600"></i>
            </div>
        </div>
    </div>
    
    <!-- Active Rentals -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Sewa Aktif</p>
                <h3 class="text-3xl font-bold text-gray-800"><?= $stats['active_rentals'] ?? 0 ?></h3>
            </div>
            <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-home text-2xl text-green-600"></i>
            </div>
        </div>
    </div>
    
    <!-- Waiting Payment -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Menunggu Bayar</p>
                <h3 class="text-3xl font-bold text-gray-800"><?= $stats['waiting_payment'] ?? 0 ?></h3>
            </div>
            <div class="w-14 h-14 bg-yellow-100 rounded-full flex items-center justify-center">
                <i class="fas fa-clock text-2xl text-yellow-600"></i>
            </div>
        </div>
    </div>
    
    <!-- Completed -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Selesai</p>
                <h3 class="text-3xl font-bold text-gray-800"><?= $stats['completed'] ?? 0 ?></h3>
            </div>
            <div class="w-14 h-14 bg-purple-100 rounded-full flex items-center justify-center">
                <i class="fas fa-check-circle text-2xl text-purple-600"></i>
            </div>
        </div>
    </div>
    
</div>

<!-- Active Booking Section -->
<?php if ($stats['active_booking']): ?>
<div class="bg-white rounded-lg shadow-md p-6 mb-8">
    <h3 class="text-xl font-bold text-gray-800 mb-4">
        <i class="fas fa-home text-green-600 mr-2"></i>
        Sewa Aktif Saat Ini
    </h3>
    
    <div class="border-l-4 border-green-500 pl-4">
        <h4 class="font-semibold text-lg text-gray-800"><?= e($stats['active_booking']['kost_name']) ?></h4>
        <p class="text-gray-600">Kamar: <?= e($stats['active_booking']['kamar_name']) ?></p>
        <div class="mt-2 flex items-center space-x-4 text-sm text-gray-500">
            <span><i class="fas fa-calendar mr-1"></i><?= date('d M Y', strtotime($stats['active_booking']['start_date'])) ?></span>
            <span>-</span>
            <span><i class="fas fa-calendar-check mr-1"></i><?= date('d M Y', strtotime($stats['active_booking']['end_date'])) ?></span>
        </div>
        <div class="mt-3">
            <a href="<?= url('/tenant/bookings/' . $stats['active_booking']['id']) ?>" 
               class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                Lihat Detail <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Upcoming Payments -->
<?php if (!empty($upcomingPayments)): ?>
<div class="bg-white rounded-lg shadow-md p-6 mb-8">
    <h3 class="text-xl font-bold text-gray-800 mb-4">
        <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
        Menunggu Pembayaran
    </h3>
    
    <div class="space-y-4">
        <?php foreach ($upcomingPayments as $payment): ?>
        <div class="border-l-4 border-yellow-500 pl-4 py-2">
            <div class="flex justify-between items-start">
                <div>
                    <h4 class="font-semibold text-gray-800"><?= e($payment['kost_name']) ?></h4>
                    <p class="text-sm text-gray-600">Kamar: <?= e($payment['kamar_name']) ?></p>
                    <p class="text-sm font-bold text-blue-600 mt-1">Rp <?= number_format($payment['total_price'], 0, ',', '.') ?></p>
                </div>
                <div class="flex space-x-2">
                    <a href="<?= url('/tenant/bookings/' . $payment['id']) ?>" 
                       class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
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
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-bold text-gray-800">
            <i class="fas fa-history text-gray-600 mr-2"></i>
            Riwayat Booking Terbaru
        </h3>
        <a href="<?= url('/tenant/bookings') ?>" 
           class="text-blue-600 hover:text-blue-700 text-sm font-medium">
            Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
    
    <?php if (empty($recentBookings)): ?>
        <div class="text-center py-12">
            <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">Belum ada booking</p>
            <a href="<?= url('/tenant/search') ?>" 
               class="mt-4 inline-block px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Cari Kost Sekarang
            </a>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kost</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kamar</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Periode</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($recentBookings as $booking): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-4">
                            <div>
                                <p class="font-semibold text-gray-800"><?= e($booking['kost_name']) ?></p>
                                <p class="text-xs text-gray-500"><?= e($booking['kost_location'] ?? '') ?></p>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-700"><?= e($booking['kamar_name']) ?></td>
                        <td class="px-4 py-4 text-sm text-gray-600">
                            <?= date('d/m/Y', strtotime($booking['start_date'])) ?><br>
                            <span class="text-xs text-gray-500"><?= $booking['duration_months'] ?> bulan</span>
                        </td>
                        <td class="px-4 py-4 text-sm font-semibold text-gray-800">
                            Rp <?= number_format($booking['total_price'], 0, ',', '.') ?>
                        </td>
                        <td class="px-4 py-4">
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
                        <td class="px-4 py-4">
                            <a href="<?= url('/tenant/bookings/' . $booking['id']) ?>" 
                               class="text-blue-600 hover:text-blue-700 text-sm">
                                Detail
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
<div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-md p-6 text-white">
        <h4 class="text-xl font-bold mb-2">Cari Kost Baru</h4>
        <p class="text-blue-100 mb-4">Temukan kost yang sesuai dengan kebutuhan Anda</p>
        <a href="<?= url('/tenant/search') ?>" 
           class="inline-block px-6 py-2 bg-white text-blue-600 rounded-lg hover:bg-blue-50 font-semibold">
            Mulai Pencarian
        </a>
    </div>
    
    <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg shadow-md p-6 text-white">
        <h4 class="text-xl font-bold mb-2">Lihat Booking Anda</h4>
        <p class="text-purple-100 mb-4">Kelola semua booking dan pembayaran Anda</p>
        <a href="<?= url('/tenant/bookings') ?>" 
           class="inline-block px-6 py-2 bg-white text-purple-600 rounded-lg hover:bg-purple-50 font-semibold">
            Lihat Booking
        </a>
    </div>
</div>
