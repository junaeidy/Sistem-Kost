<?php 
$pageTitle = $pageTitle ?? 'Daftar Booking';
$bookings = $bookings ?? [];
$currentStatus = $currentStatus ?? null;
?>

<!-- Page Header -->
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Booking Saya</h1>
    <p class="text-gray-600 mt-2">Kelola semua booking kost Anda</p>
</div>

<!-- Filter Tabs -->
<div class="bg-white rounded-lg shadow-md p-4 mb-6">
    <div class="flex flex-wrap gap-2">
        <a href="<?= url('/tenant/bookings') ?>" 
           class="px-4 py-2 rounded-lg transition <?= !$currentStatus ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
            Semua
        </a>
        <a href="<?= url('/tenant/bookings?status=waiting_payment') ?>" 
           class="px-4 py-2 rounded-lg transition <?= $currentStatus === 'waiting_payment' ? 'bg-yellow-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
            Menunggu Bayar
        </a>
        <a href="<?= url('/tenant/bookings?status=paid') ?>" 
           class="px-4 py-2 rounded-lg transition <?= $currentStatus === 'paid' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
            Sudah Bayar
        </a>
        <a href="<?= url('/tenant/bookings?status=accepted') ?>" 
           class="px-4 py-2 rounded-lg transition <?= $currentStatus === 'accepted' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
            Diterima
        </a>
        <a href="<?= url('/tenant/bookings?status=active_rent') ?>" 
           class="px-4 py-2 rounded-lg transition <?= $currentStatus === 'active_rent' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
            Aktif
        </a>
        <a href="<?= url('/tenant/bookings?status=completed') ?>" 
           class="px-4 py-2 rounded-lg transition <?= $currentStatus === 'completed' ? 'bg-gray-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
            Selesai
        </a>
    </div>
</div>

<!-- Bookings List -->
<?php if (empty($bookings)): ?>
    <div class="bg-white rounded-lg shadow-md p-12 text-center">
        <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum ada booking</h3>
        <p class="text-gray-500 mb-6">Mulai cari kost dan buat booking pertama Anda</p>
        <a href="<?= url('/tenant/search') ?>" 
           class="inline-block px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 shadow-lg transition">
            <i class="fas fa-search mr-2"></i>
            Cari Kost Sekarang
        </a>
    </div>
<?php else: ?>
    <div class="space-y-4">
        <?php foreach ($bookings as $booking): ?>
        <div class="bg-white rounded-lg shadow-md p-4 md:p-6 hover:shadow-lg transition">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                
                <!-- Booking Info -->
                <div class="flex-1 mb-4 lg:mb-0">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between mb-3">
                        <div class="flex-1 mb-2 sm:mb-0">
                            <h3 class="text-lg md:text-xl font-bold text-gray-800"><?= e($booking['kost_name']) ?></h3>
                            <p class="text-gray-600 text-sm md:text-base">Kamar: <?= e($booking['kamar_name']) ?></p>
                            <?php if ($booking['kost_location']): ?>
                                <p class="text-xs md:text-sm text-gray-500 mt-1">
                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                    <?= e($booking['kost_location']) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                        
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
                        <span class="inline-block px-3 py-1 text-xs md:text-sm font-semibold rounded-full <?= $colorClass ?> whitespace-nowrap">
                            <?= $label ?>
                        </span>
                    </div>

                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
                        <div>
                            <p class="text-xs text-gray-500">Tanggal Mulai</p>
                            <p class="font-semibold text-gray-700 text-sm md:text-base">
                                <?= date('d M Y', strtotime($booking['start_date'])) ?>
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Tanggal Selesai</p>
                            <p class="font-semibold text-gray-700 text-sm md:text-base">
                                <?= date('d M Y', strtotime($booking['end_date'])) ?>
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Durasi</p>
                            <p class="font-semibold text-gray-700 text-sm md:text-base">
                                <?= $booking['duration_months'] ?> Bulan
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Total Biaya</p>
                            <p class="font-semibold text-green-600 text-sm md:text-base">
                                Rp <?= number_format($booking['total_price'], 0, ',', '.') ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-row sm:flex-col gap-2 lg:ml-6 mt-4 lg:mt-0">
                    <a href="<?= url('/tenant/bookings/' . $booking['id']) ?>" 
                       class="flex-1 sm:flex-none px-4 md:px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-center transition shadow-md hover:shadow-lg text-sm md:text-base">
                        <i class="fas fa-eye mr-2"></i>
                        Detail
                    </a>
                    
                    <?php if ($booking['status'] === 'waiting_payment'): ?>
                        <form method="POST" action="<?= url('/tenant/bookings/' . $booking['id'] . '/cancel') ?>" 
                              onsubmit="return confirm('Yakin ingin membatalkan booking ini?')"
                              class="flex-1 sm:flex-none">
                            <button type="submit" 
                                    class="w-full px-4 md:px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition shadow-md hover:shadow-lg text-sm md:text-base">
                                <i class="fas fa-times mr-2"></i>
                                <span class="hidden sm:inline">Batalkan</span>
                                <span class="sm:hidden">Batal</span>
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php if (isset($total_pages) && $total_pages > 1): ?>
        <div class="mt-6">
            <?php include __DIR__ . '/../components/pagination.php'; ?>
        </div>
    <?php endif; ?>
<?php endif; ?>
