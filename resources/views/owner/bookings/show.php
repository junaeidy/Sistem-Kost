<?php 
$pageTitle = $pageTitle ?? 'Detail Booking';
$booking = $booking ?? [];

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
    'waiting_payment' => 'Menunggu Pembayaran',
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

<div class="max-w-6xl mx-auto px-4 sm:px-6">
    <!-- Back Button -->
    <div class="mb-4 sm:mb-6">
        <a href="<?= url('/owner/bookings') ?>" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar Booking
        </a>
    </div>

    <!-- Header Card -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg shadow-lg p-4 sm:p-6 mb-4 sm:mb-6 text-white">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
            <div class="flex-1">
                <h2 class="text-xl sm:text-2xl font-bold mb-2">Detail Booking</h2>
                <div class="flex items-center gap-3 mb-2">
                    <p class="text-lg sm:text-xl font-semibold bg-white/20 px-3 py-1 rounded"><?= e($booking['booking_id']) ?></p>
                    <span class="px-3 py-1 inline-flex text-xs sm:text-sm font-semibold rounded-full <?= $colorClass ?>">
                        <?= $statusLabel ?>
                    </span>
                </div>
                <p class="text-blue-100 text-xs sm:text-sm">
                    <i class="fas fa-clock mr-1"></i>Dibuat pada <?= date('d F Y, H:i', strtotime($booking['created_at'])) ?>
                </p>
            </div>
        </div>

        <!-- Action Buttons -->
        <?php if ($booking['status'] === 'paid'): ?>
            <div class="mt-4 p-4 bg-white/10 backdrop-blur-sm rounded-lg border border-white/20">
                <div class="flex items-start gap-3 mb-3">
                    <i class="fas fa-info-circle text-yellow-300 text-xl"></i>
                    <p class="text-sm sm:text-base text-white font-medium flex-1">
                        Booking ini sudah dibayar dan menunggu konfirmasi Anda
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row gap-2">
                    <button onclick="confirmAccept(<?= $booking['id'] ?>)" 
                            class="flex-1 sm:flex-none px-4 sm:px-6 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition shadow-lg font-medium">
                        <i class="fas fa-check mr-2"></i>Terima Booking
                    </button>
                    <button onclick="showRejectModal(<?= $booking['id'] ?>)" 
                            class="flex-1 sm:flex-none px-4 sm:px-6 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition shadow-lg font-medium">
                        <i class="fas fa-times mr-2"></i>Tolak Booking
                    </button>
                </div>
            </div>
        <?php elseif ($booking['status'] === 'waiting_payment'): ?>
            <div class="mt-4 p-4 bg-yellow-500/20 backdrop-blur-sm rounded-lg border border-yellow-400/30">
                <p class="text-sm text-yellow-100 flex items-center">
                    <i class="fas fa-clock mr-2"></i> Booking ini masih menunggu pembayaran dari penyewa.
                </p>
            </div>
        <?php elseif ($booking['status'] === 'accepted'): ?>
            <div class="mt-4 p-4 bg-green-500/20 backdrop-blur-sm rounded-lg border border-green-400/30">
                <p class="text-sm text-green-100 flex items-center">
                    <i class="fas fa-check-circle mr-2"></i> Booking ini sudah diterima. Kamar dalam status terisi.
                </p>
            </div>
        <?php elseif ($booking['status'] === 'rejected'): ?>
            <div class="mt-4 p-4 bg-red-500/20 backdrop-blur-sm rounded-lg border border-red-400/30">
                <p class="text-sm text-red-100 flex items-center">
                    <i class="fas fa-times-circle mr-2"></i> Booking ini telah ditolak.
                </p>
            </div>
        <?php endif; ?>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
        <!-- Booking Information -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-4 sm:px-6 py-4">
                <h3 class="text-base sm:text-lg font-bold text-white flex items-center">
                    <i class="fas fa-file-alt mr-2"></i>
                    Informasi Booking
                </h3>
            </div>
            <div class="p-4 sm:p-6">
                <div class="space-y-4">
                    <div class="pb-4 border-b border-gray-200">
                        <label class="text-xs sm:text-sm text-gray-500 block mb-1">Kost</label>
                        <p class="font-semibold text-gray-900 text-sm sm:text-base"><?= e($booking['kost_name']) ?></p>
                    </div>

                    <div class="pb-4 border-b border-gray-200">
                        <label class="text-xs sm:text-sm text-gray-500 block mb-1">Kamar</label>
                        <p class="font-semibold text-gray-900 text-sm sm:text-base"><?= e($booking['kamar_name']) ?></p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 pb-4 border-b border-gray-200">
                        <div>
                            <label class="text-xs sm:text-sm text-gray-500 block mb-1">Tanggal Mulai</label>
                            <p class="font-semibold text-gray-900 text-sm"><?= date('d M Y', strtotime($booking['start_date'])) ?></p>
                        </div>
                        <div>
                            <label class="text-xs sm:text-sm text-gray-500 block mb-1">Tanggal Selesai</label>
                            <p class="font-semibold text-gray-900 text-sm"><?= date('d M Y', strtotime($booking['end_date'])) ?></p>
                        </div>
                    </div>

                    <div class="pb-4 border-b border-gray-200">
                        <label class="text-xs sm:text-sm text-gray-500 block mb-1">Durasi Sewa</label>
                        <p class="font-semibold text-gray-900 text-sm sm:text-base">
                            <i class="fas fa-calendar-alt text-blue-600 mr-1"></i><?= $booking['duration_months'] ?> Bulan
                        </p>
                    </div>

                    <div class="bg-blue-50 rounded-lg p-4">
                        <label class="text-xs sm:text-sm text-gray-600 block mb-2">Total Pembayaran</label>
                        <p class="text-2xl sm:text-3xl font-bold text-blue-600">Rp <?= number_format($booking['total_price'], 0, ',', '.') ?></p>
                        <p class="text-xs text-gray-500 mt-1">
                            Rp <?= number_format($booking['kamar_price'], 0, ',', '.') ?> × <?= $booking['duration_months'] ?> bulan
                        </p>
                    </div>

                    <?php if (!empty($booking['notes'])): ?>
                        <div class="pt-4">
                            <label class="text-xs sm:text-sm text-gray-500 block mb-2">
                                <i class="fas fa-sticky-note mr-1"></i>Catatan
                            </label>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <p class="text-sm text-gray-900 whitespace-pre-line"><?= e($booking['notes']) ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Tenant Information -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-4 sm:px-6 py-4">
                <h3 class="text-base sm:text-lg font-bold text-white flex items-center">
                    <i class="fas fa-user mr-2"></i>
                    Informasi Penyewa
                </h3>
            </div>
            <div class="p-4 sm:p-6">
                <div class="space-y-4">
                    <div class="flex items-center gap-4 pb-4 border-b border-gray-200">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-user text-white text-2xl"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-gray-900 text-base sm:text-lg truncate"><?= e($booking['tenant_name']) ?></p>
                            <p class="text-xs sm:text-sm text-gray-500">Penyewa</p>
                        </div>
                    </div>

                    <div class="pb-4 border-b border-gray-200">
                        <label class="text-xs sm:text-sm text-gray-500 block mb-1">
                            <i class="fas fa-envelope mr-1"></i>Email
                        </label>
                        <a href="mailto:<?= e($booking['tenant_email']) ?>" class="font-medium text-blue-600 hover:text-blue-800 text-sm">
                            <?= e($booking['tenant_email']) ?>
                        </a>
                    </div>

                    <div class="pb-4 border-b border-gray-200">
                        <label class="text-xs sm:text-sm text-gray-500 block mb-1">
                            <i class="fas fa-phone mr-1"></i>No. Telepon
                        </label>
                        <a href="tel:<?= e($booking['tenant_phone']) ?>" class="font-medium text-blue-600 hover:text-blue-800 text-sm">
                            <?= e($booking['tenant_phone']) ?>
                        </a>
                    </div>

                    <?php if (!empty($booking['tenant_address'])): ?>
                        <div class="pb-4 border-b border-gray-200">
                            <label class="text-xs sm:text-sm text-gray-500 block mb-1">
                                <i class="fas fa-map-marker-alt mr-1"></i>Alamat
                            </label>
                            <p class="text-sm text-gray-900"><?= e($booking['tenant_address']) ?></p>
                        </div>
                    <?php endif; ?>

                    <div class="pt-2">
                        <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $booking['tenant_phone']) ?>" 
                           target="_blank"
                           class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition shadow-lg font-medium">
                            <i class="fab fa-whatsapp text-xl mr-2"></i>
                            Hubungi via WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Information -->
    <?php if (!empty($booking['payment_status'])): ?>
        <div class="bg-white rounded-lg shadow-md overflow-hidden mt-4 sm:mt-6">
            <div class="bg-gradient-to-r from-green-500 to-green-600 px-4 sm:px-6 py-4">
                <h3 class="text-base sm:text-lg font-bold text-white flex items-center">
                    <i class="fas fa-credit-card mr-2"></i>
                    Informasi Pembayaran
                </h3>
            </div>
            <div class="p-4 sm:p-6">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <label class="text-xs sm:text-sm text-gray-500 block mb-2">Status Pembayaran</label>
                        <p class="font-semibold text-sm sm:text-base">
                            <?php if ($booking['payment_status'] === 'paid'): ?>
                                <span class="inline-flex items-center text-green-600">
                                    <i class="fas fa-check-circle mr-1"></i> Lunas
                                </span>
                            <?php elseif ($booking['payment_status'] === 'pending'): ?>
                                <span class="inline-flex items-center text-yellow-600">
                                    <i class="fas fa-clock mr-1"></i> Pending
                                </span>
                            <?php else: ?>
                                <span class="text-gray-600"><?= ucfirst($booking['payment_status']) ?></span>
                            <?php endif; ?>
                        </p>
                    </div>

                    <div class="p-4 bg-gray-50 rounded-lg">
                        <label class="text-xs sm:text-sm text-gray-500 block mb-2">Metode Pembayaran</label>
                        <p class="font-semibold text-sm sm:text-base text-gray-900">
                            <?= $booking['payment_type'] ? ucwords(str_replace('_', ' ', $booking['payment_type'])) : '-' ?>
                        </p>
                    </div>

                    <div class="p-4 bg-gray-50 rounded-lg">
                        <label class="text-xs sm:text-sm text-gray-500 block mb-2">Tanggal Dibayar</label>
                        <p class="font-semibold text-sm sm:text-base text-gray-900">
                            <?= $booking['paid_at'] ? date('d M Y, H:i', strtotime($booking['paid_at'])) : '-' ?>
                        </p>
                    </div>

                    <?php if (!empty($booking['midtrans_order_id'])): ?>
                        <div class="sm:col-span-3 p-4 bg-blue-50 rounded-lg">
                            <label class="text-xs sm:text-sm text-gray-500 block mb-2">Order ID</label>
                            <p class="font-mono text-xs sm:text-sm text-gray-900 break-all"><?= e($booking['midtrans_order_id']) ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Kamar Information -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mt-4 sm:mt-6">
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-4 sm:px-6 py-4">
            <h3 class="text-base sm:text-lg font-bold text-white flex items-center">
                <i class="fas fa-door-open mr-2"></i>
                Informasi Kamar
            </h3>
        </div>
        <div class="p-4 sm:p-6">
            <div class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 bg-orange-50 rounded-lg">
                        <label class="text-xs sm:text-sm text-gray-500 block mb-2">
                            <i class="fas fa-money-bill-wave mr-1"></i>Harga per Bulan
                        </label>
                        <p class="font-bold text-lg sm:text-xl text-orange-600">
                            Rp <?= number_format($booking['kamar_price'], 0, ',', '.') ?>
                        </p>
                    </div>
                    <div class="p-4 bg-blue-50 rounded-lg">
                        <label class="text-xs sm:text-sm text-gray-500 block mb-2">
                            <i class="fas fa-map-marker-alt mr-1"></i>Lokasi
                        </label>
                        <p class="font-medium text-sm text-gray-900"><?= e($booking['kost_address']) ?></p>
                    </div>
                </div>

                <?php if (!empty($booking['kamar_facilities'])): ?>
                    <div class="pt-4 border-t border-gray-200">
                        <label class="text-xs sm:text-sm text-gray-500 block mb-3">
                            <i class="fas fa-star mr-1"></i>Fasilitas Kamar
                        </label>
                        <div class="flex flex-wrap gap-2">
                            <?php 
                            $facilities = json_decode($booking['kamar_facilities'], true) ?: [];
                            foreach ($facilities as $facility): 
                            ?>
                                <span class="px-3 py-1.5 bg-blue-50 text-blue-700 rounded-full text-xs sm:text-sm font-medium">
                                    <i class="fas fa-check-circle mr-1"></i><?= e($facility) ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
    <div class="relative w-full max-w-md">
        <div class="bg-white rounded-lg shadow-2xl">
            <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4 rounded-t-lg">
                <h3 class="text-lg sm:text-xl font-bold text-white flex items-center">
                    <i class="fas fa-times-circle mr-2"></i>
                    Tolak Booking
                </h3>
            </div>
            <form id="rejectForm" method="POST" class="p-6">
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Alasan Penolakan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="reason" 
                              rows="5" 
                              required
                              placeholder="Jelaskan alasan penolakan booking ini..."
                              class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent resize-none"></textarea>
                    <p class="text-xs text-gray-500 mt-2">
                        <i class="fas fa-info-circle mr-1"></i>Alasan ini akan dikirim ke penyewa
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="button" 
                            onclick="closeRejectModal()"
                            class="flex-1 px-4 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>Batal
                    </button>
                    <button type="submit"
                            class="flex-1 px-4 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg hover:from-red-700 hover:to-red-800 transition font-medium shadow-lg">
                        <i class="fas fa-times mr-2"></i>Tolak Booking
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function confirmAccept(bookingId) {
    if (confirm('Apakah Anda yakin ingin menerima booking ini? Kamar akan berubah status menjadi terisi.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= url('/owner/bookings/') ?>' + bookingId + '/accept';
        document.body.appendChild(form);
        form.submit();
    }
}

function showRejectModal(bookingId) {
    const modal = document.getElementById('rejectModal');
    const form = document.getElementById('rejectForm');
    form.action = '<?= url('/owner/bookings/') ?>' + bookingId + '/reject';
    modal.classList.remove('hidden');
}

function closeRejectModal() {
    const modal = document.getElementById('rejectModal');
    modal.classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('rejectModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeRejectModal();
    }
});
</script>
