<?php 
$pageTitle = $pageTitle ?? 'Detail Booking';
$booking = $booking ?? [];
$payment = $payment ?? null;
?>

<!-- Back Button -->
<div class="mb-4">
    <a href="<?= url('/tenant/bookings') ?>" class="text-blue-600 hover:text-blue-700">
        <i class="fas fa-arrow-left mr-2"></i>
        Kembali ke Daftar Booking
    </a>
</div>

<!-- Status Alert -->
<?php
$statusConfig = [
    'waiting_payment' => [
        'bg' => 'bg-yellow-50',
        'border' => 'border-yellow-200',
        'text' => 'text-yellow-800',
        'icon' => 'fas fa-clock',
        'title' => 'Menunggu Pembayaran',
        'message' => 'Silakan lakukan pembayaran untuk melanjutkan booking Anda.'
    ],
    'paid' => [
        'bg' => 'bg-blue-50',
        'border' => 'border-blue-200',
        'text' => 'text-blue-800',
        'icon' => 'fas fa-check-circle',
        'title' => 'Pembayaran Berhasil',
        'message' => 'Pembayaran Anda telah diterima. Menunggu konfirmasi dari pemilik kost.'
    ],
    'accepted' => [
        'bg' => 'bg-green-50',
        'border' => 'border-green-200',
        'text' => 'text-green-800',
        'icon' => 'fas fa-check-double',
        'title' => 'Booking Diterima',
        'message' => 'Booking Anda telah diterima oleh pemilik kost. Hubungi pemilik untuk detail lebih lanjut.'
    ],
    'active_rent' => [
        'bg' => 'bg-green-50',
        'border' => 'border-green-200',
        'text' => 'text-green-800',
        'icon' => 'fas fa-home',
        'title' => 'Sewa Aktif',
        'message' => 'Anda sedang menyewa kamar ini.'
    ],
    'completed' => [
        'bg' => 'bg-gray-50',
        'border' => 'border-gray-200',
        'text' => 'text-gray-800',
        'icon' => 'fas fa-flag-checkered',
        'title' => 'Sewa Selesai',
        'message' => 'Periode sewa Anda telah selesai.'
    ],
    'rejected' => [
        'bg' => 'bg-red-50',
        'border' => 'border-red-200',
        'text' => 'text-red-800',
        'icon' => 'fas fa-times-circle',
        'title' => 'Booking Ditolak',
        'message' => 'Maaf, booking Anda ditolak oleh pemilik kost.'
    ],
    'cancelled' => [
        'bg' => 'bg-red-50',
        'border' => 'border-red-200',
        'text' => 'text-red-800',
        'icon' => 'fas fa-ban',
        'title' => 'Booking Dibatalkan',
        'message' => 'Booking ini telah dibatalkan.'
    ]
];

$config = $statusConfig[$booking['status']] ?? $statusConfig['waiting_payment'];
?>

<!-- Payment Countdown Timer (if waiting for payment) -->
<?php if ($booking['status'] === 'waiting_payment' && !empty($payment['expires_at'])): ?>
<div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <i class="fas fa-hourglass-half text-red-600 text-2xl mr-3"></i>
            <div>
                <h3 class="font-semibold text-red-800 text-lg">Waktu Pembayaran Tersisa</h3>
                <p class="text-red-600 text-sm">Selesaikan pembayaran sebelum waktu habis</p>
            </div>
        </div>
        <div id="countdown-timer" class="text-center" data-expires="<?= htmlspecialchars($payment['expires_at']) ?>">
            <div class="text-3xl font-bold text-red-600">
                <span id="hours">00</span>:<span id="minutes">00</span>:<span id="seconds">00</span>
            </div>
            <p class="text-xs text-red-500 mt-1">Jam:Menit:Detik</p>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="<?= $config['bg'] ?> border <?= $config['border'] ?> rounded-lg p-4 mb-6">
    <div class="flex items-start">
        <i class="<?= $config['icon'] ?> <?= $config['text'] ?> text-2xl mr-3"></i>
        <div>
            <h3 class="font-semibold <?= $config['text'] ?> text-lg"><?= $config['title'] ?></h3>
            <p class="<?= $config['text'] ?> text-sm mt-1"><?= $config['message'] ?></p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Booking Information -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 to-teal-600 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-info-circle mr-3"></i>
                    Informasi Booking
                </h2>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Booking ID</p>
                        <p class="font-semibold text-green-600 font-mono text-lg">
                            <?= htmlspecialchars($booking['booking_id'] ?? '#' . str_pad($booking['id'], 6, '0', STR_PAD_LEFT)) ?>
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Tanggal Booking</p>
                        <p class="font-semibold text-gray-800">
                            <?= date('d M Y H:i', strtotime($booking['created_at'])) ?>
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Tanggal Mulai</p>
                        <p class="font-semibold text-gray-800">
                            <?= date('d M Y', strtotime($booking['start_date'])) ?>
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Tanggal Selesai</p>
                        <p class="font-semibold text-gray-800">
                            <?= date('d M Y', strtotime($booking['end_date'])) ?>
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Durasi</p>
                        <p class="font-semibold text-gray-800">
                            <?= $booking['duration_months'] ?> Bulan
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <span class="inline-block px-3 py-1 <?= $config['bg'] ?> <?= $config['text'] ?> text-sm font-semibold rounded-full">
                            <?= $config['title'] ?>
                        </span>
                    </div>
                </div>

                <?php if (!empty($booking['notes'])): ?>
                    <div class="mt-4 pt-4 border-t">
                        <p class="text-sm text-gray-500 mb-1">Catatan</p>
                        <p class="text-gray-700"><?= nl2br(e($booking['notes'])) ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Kost & Room Information -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 to-teal-600 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-building mr-3"></i>
                    Informasi Kost & Kamar
                </h2>
            </div>
            
            <div class="p-6">
                <div class="flex flex-col sm:flex-row items-start mb-4">
                    <?php if ($booking['kost_photo']): ?>
                        <img src="<?= url('/' . $booking['kost_photo']) ?>" 
                             alt="<?= e($booking['kost_name']) ?>"
                             class="w-full sm:w-24 h-48 sm:h-24 object-cover rounded-lg mb-3 sm:mb-0 sm:mr-4">
                    <?php endif; ?>
                    
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-800"><?= e($booking['kost_name']) ?></h3>
                        <p class="text-gray-600">Kamar: <?= e($booking['kamar_name']) ?></p>
                        <div class="text-sm text-gray-600 mt-2">
                            <i class="fas fa-map-marker-alt text-red-500 mr-1"></i>
                            <?= e($booking['kost_address']) ?>
                        </div>
                        <?php if ($booking['kost_location']): ?>
                            <p class="text-sm text-gray-500 mt-1"><?= e($booking['kost_location']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Room Facilities -->
                <?php if (!empty($booking['kamar_facilities_array'])): ?>
                    <div class="mt-4 pt-4 border-t">
                        <h4 class="font-semibold text-gray-800 mb-2">Fasilitas Kamar</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <?php foreach ($booking['kamar_facilities_array'] as $facility): ?>
                                <div class="flex items-center text-sm text-gray-700">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    <span><?= e($facility) ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Kost Facilities -->
                <?php if (!empty($booking['kost_facilities_array'])): ?>
                    <div class="mt-4 pt-4 border-t">
                        <h4 class="font-semibold text-gray-800 mb-2">Fasilitas Umum</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <?php foreach ($booking['kost_facilities_array'] as $facility): ?>
                                <div class="flex items-center text-sm text-gray-700">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    <span><?= e($facility) ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <a href="<?= url('/tenant/search/' . $booking['kost_id']) ?>" 
                   class="block mt-4 text-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition shadow-md hover:shadow-lg">
                    <i class="fas fa-external-link-alt mr-2"></i>
                    Lihat Detail Kost
                </a>
            </div>
        </div>

        <!-- Payment Information -->
        <?php if ($payment): ?>
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 to-teal-600 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-credit-card mr-3"></i>
                    Informasi Pembayaran
                </h2>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Order ID</p>
                        <p class="font-semibold text-gray-800"><?= e($payment['midtrans_order_id']) ?></p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Status Pembayaran</p>
                        <span class="inline-block px-3 py-1 <?= $payment['payment_status'] === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?> text-sm font-semibold rounded-full">
                            <?= ucfirst($payment['payment_status']) ?>
                        </span>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Jumlah</p>
                        <p class="text-xl font-bold text-green-600">
                            Rp <?= number_format($payment['amount'], 0, ',', '.') ?>
                        </p>
                    </div>

                    <?php if ($payment['payment_type']): ?>
                    <div>
                        <p class="text-sm text-gray-500">Metode Pembayaran</p>
                        <p class="font-semibold text-gray-800"><?= ucwords(str_replace('_', ' ', $payment['payment_type'])) ?></p>
                    </div>
                    <?php endif; ?>

                    <?php if ($payment['paid_at']): ?>
                    <div>
                        <p class="text-sm text-gray-500">Tanggal Bayar</p>
                        <p class="font-semibold text-gray-800">
                            <?= date('d M Y H:i', strtotime($payment['paid_at'])) ?>
                        </p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        
        <!-- Price Summary -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 to-teal-600 px-6 py-4">
                <h3 class="text-lg font-semibold text-white">Rincian Biaya</h3>
            </div>
            
            <div class="p-6">
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Harga per Bulan</span>
                        <span class="font-semibold">Rp <?= number_format($booking['kamar_price'], 0, ',', '.') ?></span>
                    </div>

                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Durasi</span>
                        <span class="font-semibold"><?= $booking['duration_months'] ?> Bulan</span>
                    </div>

                    <div class="border-t pt-2 mt-2">
                        <div class="flex justify-between">
                            <span class="font-semibold text-gray-800">Total Biaya</span>
                            <span class="text-xl font-bold text-green-600">
                                Rp <?= number_format($booking['total_price'], 0, ',', '.') ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Owner Contact -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 to-teal-600 px-6 py-4">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-user-tie mr-2"></i>
                    Kontak Pemilik
                </h3>
            </div>
            
            <div class="p-6">
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500">Nama</p>
                        <p class="font-semibold text-gray-800"><?= e($booking['owner_name']) ?></p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">No. Telepon</p>
                        <a href="tel:<?= e($booking['owner_phone']) ?>" 
                           class="font-semibold text-green-600 hover:text-green-700">
                            <?= e($booking['owner_phone']) ?>
                        </a>
                    </div>

                    <a href="https://wa.me/62<?= ltrim($booking['owner_phone'], '0') ?>" 
                       target="_blank"
                       class="block w-full mt-4 px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 text-center transition shadow-md hover:shadow-lg">
                        <i class="fab fa-whatsapp mr-2 text-xl"></i>
                        <span class="font-semibold">Chat WhatsApp</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 to-teal-600 px-6 py-4">
                <h3 class="text-lg font-semibold text-white">Aksi</h3>
            </div>
            
            <div class="p-6">
                <div class="space-y-3">
                    <?php if ($booking['status'] === 'waiting_payment'): ?>
                        <!-- Tombol Bayar Sekarang - Redirect ke Midtrans Payment -->
                        <a href="<?= url('/payment/create/' . $booking['id']) ?>" 
                           class="block w-full px-6 py-4 bg-green-600 text-white rounded-lg hover:bg-green-700 text-center transition font-semibold text-lg shadow-lg hover:shadow-xl">
                            <i class="fas fa-credit-card mr-2"></i>
                            Bayar Sekarang
                        </a>
                        
                        <p class="text-xs text-center text-gray-500">
                            <i class="fas fa-lock mr-1"></i>
                            Pembayaran aman dengan Midtrans
                        </p>
                        
                        <form method="POST" action="<?= url('/tenant/bookings/' . $booking['id'] . '/cancel') ?>" 
                              onsubmit="return confirm('Yakin ingin membatalkan booking ini?')">
                            <button type="submit" 
                                    class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition shadow-md hover:shadow-lg">
                                <i class="fas fa-times mr-2"></i>
                                Batalkan Booking
                            </button>
                        </form>
                        
                    <?php elseif ($booking['status'] === 'paid'): ?>
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                            <i class="fas fa-check-circle text-green-600 text-3xl mb-2"></i>
                            <p class="text-green-800 font-semibold text-lg">Pembayaran Berhasil</p>
                            <p class="text-sm text-green-600 mt-1">Menunggu konfirmasi owner</p>
                        </div>
                        
                    <?php elseif ($booking['status'] === 'accepted'): ?>
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                            <i class="fas fa-check-double text-green-600 text-3xl mb-2"></i>
                            <p class="text-green-800 font-semibold text-lg">Booking Diterima</p>
                            <p class="text-sm text-green-600 mt-1">Hubungi owner untuk check-in</p>
                        </div>
                    <?php endif; ?>

                    <a href="<?= url('/tenant/bookings') ?>" 
                       class="block w-full px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-center transition shadow-md hover:shadow-lg">
                        <i class="fas fa-list mr-2"></i>
                        Lihat Semua Booking
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Countdown Timer Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const timerElement = document.getElementById('countdown-timer');
    
    if (!timerElement) return;
    
    const expiresAt = timerElement.dataset.expires;
    if (!expiresAt) return;
    
    const expiryTime = new Date(expiresAt).getTime();
    
    const hoursEl = document.getElementById('hours');
    const minutesEl = document.getElementById('minutes');
    const secondsEl = document.getElementById('seconds');
    
    function updateCountdown() {
        const now = new Date().getTime();
        const distance = expiryTime - now;
        
        if (distance < 0) {
            // Expired
            clearInterval(countdownInterval);
            timerElement.innerHTML = '<div class="text-2xl font-bold text-red-600">WAKTU HABIS</div><p class="text-xs text-red-500 mt-1">Pembayaran expired</p>';
            
            // Reload page after 3 seconds to show expired status
            setTimeout(() => {
                window.location.reload();
            }, 3000);
            return;
        }
        
        // Calculate time units
        const hours = Math.floor(distance / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
        // Update display
        hoursEl.textContent = String(hours).padStart(2, '0');
        minutesEl.textContent = String(minutes).padStart(2, '0');
        secondsEl.textContent = String(seconds).padStart(2, '0');
        
        // Change color when less than 1 hour remaining
        if (hours === 0) {
            hoursEl.classList.add('text-red-700');
            minutesEl.classList.add('text-red-700');
            secondsEl.classList.add('text-red-700');
            
            // Blink effect when less than 5 minutes
            if (minutes < 5) {
                timerElement.parentElement.classList.add('animate-pulse');
            }
        }
    }
    
    // Update immediately
    updateCountdown();
    
    // Update every second
    const countdownInterval = setInterval(updateCountdown, 1000);
});
</script>
