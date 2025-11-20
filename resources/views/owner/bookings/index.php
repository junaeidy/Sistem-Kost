<?php 
$pageTitle = $pageTitle ?? 'Kelola Booking';
$bookings = $bookings ?? [];
$statusCounts = $statusCounts ?? [];
$currentStatus = $currentStatus ?? 'all';
$currentSearch = $currentSearch ?? '';
?>

<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Kelola Booking</h2>
        <p class="text-gray-600 mt-1">Kelola permintaan booking dari penyewa</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm text-gray-600">Total Booking</div>
            <div class="text-2xl font-bold text-gray-800"><?= $statusCounts['all'] ?></div>
        </div>
        <div class="bg-blue-50 rounded-lg shadow p-4">
            <div class="text-sm text-blue-600">Menunggu Pembayaran</div>
            <div class="text-2xl font-bold text-blue-800"><?= $statusCounts['waiting_payment'] ?></div>
        </div>
        <div class="bg-yellow-50 rounded-lg shadow p-4">
            <div class="text-sm text-yellow-600">Sudah Dibayar</div>
            <div class="text-2xl font-bold text-yellow-800"><?= $statusCounts['paid'] ?></div>
        </div>
        <div class="bg-green-50 rounded-lg shadow p-4">
            <div class="text-sm text-green-600">Diterima</div>
            <div class="text-2xl font-bold text-green-800"><?= $statusCounts['accepted'] ?></div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form method="GET" action="<?= url('/owner/bookings') ?>" class="flex flex-col md:flex-row gap-4">
            <!-- Status Filter -->
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Filter Status</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="all" <?= $currentStatus === 'all' ? 'selected' : '' ?>>Semua Status</option>
                    <option value="waiting_payment" <?= $currentStatus === 'waiting_payment' ? 'selected' : '' ?>>Menunggu Pembayaran</option>
                    <option value="paid" <?= $currentStatus === 'paid' ? 'selected' : '' ?>>Sudah Dibayar</option>
                    <option value="accepted" <?= $currentStatus === 'accepted' ? 'selected' : '' ?>>Diterima</option>
                    <option value="rejected" <?= $currentStatus === 'rejected' ? 'selected' : '' ?>>Ditolak</option>
                    <option value="active_rent" <?= $currentStatus === 'active_rent' ? 'selected' : '' ?>>Aktif Sewa</option>
                    <option value="completed" <?= $currentStatus === 'completed' ? 'selected' : '' ?>>Selesai</option>
                    <option value="cancelled" <?= $currentStatus === 'cancelled' ? 'selected' : '' ?>>Dibatalkan</option>
                </select>
            </div>

            <!-- Search -->
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                <input type="text" 
                       name="search" 
                       value="<?= e($currentSearch) ?>"
                       placeholder="Nama penyewa, kost, atau kamar..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <!-- Buttons -->
            <div class="flex items-end gap-2">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="<?= url('/owner/bookings') ?>" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Bookings Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <?php if (empty($bookings)): ?>
            <div class="p-8 text-center text-gray-500">
                <i class="fas fa-inbox text-4xl mb-3"></i>
                <p>Belum ada booking yang masuk.</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penyewa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kost & Kamar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($bookings as $booking): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?= e($booking['booking_id']) ?></div>
                                    <div class="text-xs text-gray-500"><?= date('d M Y', strtotime($booking['created_at'])) ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900"><?= e($booking['tenant_name']) ?></div>
                                    <div class="text-xs text-gray-500"><?= e($booking['tenant_phone']) ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900"><?= e($booking['kost_name']) ?></div>
                                    <div class="text-xs text-gray-500">Kamar: <?= e($booking['kamar_name']) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?= date('d M Y', strtotime($booking['start_date'])) ?></div>
                                    <div class="text-xs text-gray-500"><?= $booking['duration_months'] ?> bulan</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">Rp <?= number_format($booking['total_price'], 0, ',', '.') ?></div>
                                    <?php if ($booking['payment_status'] === 'paid'): ?>
                                        <div class="text-xs text-green-600"><i class="fas fa-check-circle"></i> Lunas</div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php
                                    $statusColors = [
                                        'waiting_payment' => 'bg-yellow-100 text-yellow-800',
                                        'paid' => 'bg-blue-100 text-blue-800',
                                        'accepted' => 'bg-green-100 text-green-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                        'active_rent' => 'bg-purple-100 text-purple-800',
                                        'completed' => 'bg-gray-100 text-gray-800',
                                        'cancelled' => 'bg-gray-100 text-gray-600'
                                    ];
                                    $statusLabels = [
                                        'waiting_payment' => 'Menunggu Bayar',
                                        'paid' => 'Sudah Dibayar',
                                        'accepted' => 'Diterima',
                                        'rejected' => 'Ditolak',
                                        'active_rent' => 'Aktif Sewa',
                                        'completed' => 'Selesai',
                                        'cancelled' => 'Dibatalkan'
                                    ];
                                    $colorClass = $statusColors[$booking['status']] ?? 'bg-gray-100 text-gray-800';
                                    $statusLabel = $statusLabels[$booking['status']] ?? $booking['status'];
                                    ?>
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full <?= $colorClass ?>">
                                        <?= $statusLabel ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="<?= url('/owner/bookings/' . $booking['id']) ?>" 
                                       class="text-blue-600 hover:text-blue-800 font-medium">
                                        <i class="fas fa-eye mr-1"></i>Detail
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
