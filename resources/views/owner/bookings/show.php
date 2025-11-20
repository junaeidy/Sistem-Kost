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

<div class="max-w-5xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="<?= url('/owner/bookings') ?>" class="inline-flex items-center text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar Booking
        </a>
    </div>

    <!-- Header Card -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Detail Booking</h2>
                <p class="text-lg font-semibold text-blue-600 mt-1"><?= e($booking['booking_id']) ?></p>
                <p class="text-gray-600 text-sm mt-1">Dibuat pada <?= date('d F Y, H:i', strtotime($booking['created_at'])) ?></p>
            </div>
            <div>
                <span class="px-4 py-2 inline-flex text-sm font-semibold rounded-full <?= $colorClass ?>">
                    <?= $statusLabel ?>
                </span>
            </div>
        </div>

        <!-- Action Buttons -->
        <?php if ($booking['status'] === 'paid'): ?>
            <div class="flex gap-3 mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                <div class="flex-1">
                    <p class="text-sm text-blue-800 font-medium mb-2">
                        <i class="fas fa-info-circle"></i> Booking ini sudah dibayar dan menunggu konfirmasi Anda
                    </p>
                </div>
                <div class="flex gap-2">
                    <button onclick="confirmAccept(<?= $booking['id'] ?>)" 
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-check mr-2"></i>Terima Booking
                    </button>
                    <button onclick="showRejectModal(<?= $booking['id'] ?>)" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        <i class="fas fa-times mr-2"></i>Tolak Booking
                    </button>
                </div>
            </div>
        <?php elseif ($booking['status'] === 'waiting_payment'): ?>
            <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                <p class="text-sm text-yellow-800">
                    <i class="fas fa-clock"></i> Booking ini masih menunggu pembayaran dari penyewa.
                </p>
            </div>
        <?php elseif ($booking['status'] === 'accepted'): ?>
            <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                <p class="text-sm text-green-800">
                    <i class="fas fa-check-circle"></i> Booking ini sudah diterima. Kamar dalam status terisi.
                </p>
            </div>
        <?php elseif ($booking['status'] === 'rejected'): ?>
            <div class="p-4 bg-red-50 rounded-lg border border-red-200">
                <p class="text-sm text-red-800">
                    <i class="fas fa-times-circle"></i> Booking ini telah ditolak.
                </p>
            </div>
        <?php endif; ?>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Booking Information -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-file-alt text-blue-600 mr-2"></i>
                Informasi Booking
            </h3>

            <div class="space-y-3">
                <div>
                    <label class="text-sm text-gray-600">Kost</label>
                    <p class="font-medium text-gray-900"><?= e($booking['kost_name']) ?></p>
                </div>

                <div>
                    <label class="text-sm text-gray-600">Kamar</label>
                    <p class="font-medium text-gray-900"><?= e($booking['kamar_name']) ?></p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-sm text-gray-600">Tanggal Mulai</label>
                        <p class="font-medium text-gray-900"><?= date('d M Y', strtotime($booking['start_date'])) ?></p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Tanggal Selesai</label>
                        <p class="font-medium text-gray-900"><?= date('d M Y', strtotime($booking['end_date'])) ?></p>
                    </div>
                </div>

                <div>
                    <label class="text-sm text-gray-600">Durasi Sewa</label>
                    <p class="font-medium text-gray-900"><?= $booking['duration_months'] ?> Bulan</p>
                </div>

                <div class="pt-3 border-t border-gray-200">
                    <label class="text-sm text-gray-600">Total Pembayaran</label>
                    <p class="text-2xl font-bold text-blue-600">Rp <?= number_format($booking['total_price'], 0, ',', '.') ?></p>
                </div>

                <?php if (!empty($booking['notes'])): ?>
                    <div class="pt-3 border-t border-gray-200">
                        <label class="text-sm text-gray-600">Catatan</label>
                        <p class="text-gray-900 whitespace-pre-line"><?= e($booking['notes']) ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Tenant Information -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-user text-blue-600 mr-2"></i>
                Informasi Penyewa
            </h3>

            <div class="space-y-3">
                <div>
                    <label class="text-sm text-gray-600">Nama Lengkap</label>
                    <p class="font-medium text-gray-900"><?= e($booking['tenant_name']) ?></p>
                </div>

                <div>
                    <label class="text-sm text-gray-600">Email</label>
                    <p class="font-medium text-gray-900"><?= e($booking['tenant_email']) ?></p>
                </div>

                <div>
                    <label class="text-sm text-gray-600">No. Telepon</label>
                    <p class="font-medium text-gray-900"><?= e($booking['tenant_phone']) ?></p>
                </div>

                <?php if (!empty($booking['tenant_address'])): ?>
                    <div>
                        <label class="text-sm text-gray-600">Alamat</label>
                        <p class="text-gray-900"><?= e($booking['tenant_address']) ?></p>
                    </div>
                <?php endif; ?>

                <div class="pt-3 border-t border-gray-200">
                    <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $booking['tenant_phone']) ?>" 
                       target="_blank"
                       class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        <i class="fab fa-whatsapp mr-2"></i>
                        Hubungi via WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Information -->
    <?php if (!empty($booking['payment_status'])): ?>
        <div class="bg-white rounded-lg shadow-md p-6 mt-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-credit-card text-blue-600 mr-2"></i>
                Informasi Pembayaran
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="text-sm text-gray-600">Status Pembayaran</label>
                    <p class="font-medium text-gray-900">
                        <?php if ($booking['payment_status'] === 'paid'): ?>
                            <span class="text-green-600"><i class="fas fa-check-circle"></i> Lunas</span>
                        <?php elseif ($booking['payment_status'] === 'pending'): ?>
                            <span class="text-yellow-600"><i class="fas fa-clock"></i> Pending</span>
                        <?php else: ?>
                            <span class="text-gray-600"><?= ucfirst($booking['payment_status']) ?></span>
                        <?php endif; ?>
                    </p>
                </div>

                <div>
                    <label class="text-sm text-gray-600">Metode Pembayaran</label>
                    <p class="font-medium text-gray-900"><?= $booking['payment_type'] ? ucwords(str_replace('_', ' ', $booking['payment_type'])) : '-' ?></p>
                </div>

                <div>
                    <label class="text-sm text-gray-600">Tanggal Dibayar</label>
                    <p class="font-medium text-gray-900">
                        <?= $booking['paid_at'] ? date('d M Y, H:i', strtotime($booking['paid_at'])) : '-' ?>
                    </p>
                </div>

                <?php if (!empty($booking['midtrans_order_id'])): ?>
                    <div class="col-span-full">
                        <label class="text-sm text-gray-600">Order ID</label>
                        <p class="font-mono text-sm text-gray-900"><?= e($booking['midtrans_order_id']) ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Kamar Information -->
    <div class="bg-white rounded-lg shadow-md p-6 mt-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-door-open text-blue-600 mr-2"></i>
            Informasi Kamar
        </h3>

        <div class="space-y-3">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-gray-600">Harga per Bulan</label>
                    <p class="font-medium text-gray-900">Rp <?= number_format($booking['kamar_price'], 0, ',', '.') ?></p>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Lokasi</label>
                    <p class="font-medium text-gray-900"><?= e($booking['kost_address']) ?></p>
                </div>
            </div>

            <?php if (!empty($booking['kamar_facilities'])): ?>
                <div>
                    <label class="text-sm text-gray-600 block mb-2">Fasilitas Kamar</label>
                    <div class="flex flex-wrap gap-2">
                        <?php 
                        $facilities = json_decode($booking['kamar_facilities'], true) ?: [];
                        foreach ($facilities as $facility): 
                        ?>
                            <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-sm">
                                <i class="fas fa-check mr-1"></i><?= e($facility) ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Tolak Booking</h3>
            <form id="rejectForm" method="POST">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Alasan Penolakan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="reason" 
                              rows="4" 
                              required
                              placeholder="Jelaskan alasan penolakan..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" 
                            onclick="closeRejectModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
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
