<!-- Payment Checkout Page -->
<div class="min-h-screen bg-gray-100 py-8">
    <div class="container mx-auto px-4 max-w-4xl">
        
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-credit-card mr-2"></i> Pembayaran
            </h1>
            <p class="text-gray-600 mt-2">Selesaikan pembayaran untuk booking Anda</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Booking Details -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-info-circle mr-2"></i> Detail Booking
                    </h2>

                    <div class="space-y-4">
                        <!-- Kost Info -->
                        <div class="border-b pb-4">
                            <div class="flex items-start">
                                <?php if (isset($booking['kost_photo']) && $booking['kost_photo']): ?>
                                    <img src="<?= asset($booking['kost_photo']) ?>" 
                                         alt="<?= htmlspecialchars($booking['kost_name']) ?>"
                                         class="w-24 h-24 object-cover rounded-lg mr-4">
                                <?php else: ?>
                                    <div class="w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center mr-4">
                                        <i class="fas fa-home text-gray-400 text-3xl"></i>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <h3 class="font-bold text-lg text-gray-800">
                                        <?= htmlspecialchars($booking['kost_name']) ?>
                                    </h3>
                                    <p class="text-gray-600 text-sm">
                                        <?= htmlspecialchars($booking['kamar_name']) ?>
                                    </p>
                                    <p class="text-gray-500 text-sm mt-1">
                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        <?= htmlspecialchars($booking['kost_address']) ?>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Tenant Info -->
                        <div class="border-b pb-4">
                            <h4 class="font-semibold text-gray-700 mb-2">
                                <i class="fas fa-user mr-2"></i> Informasi Penyewa
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <p class="text-sm text-gray-600">Nama</p>
                                    <p class="font-medium"><?= htmlspecialchars($booking['tenant_name']) ?></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">No. Telepon</p>
                                    <p class="font-medium"><?= htmlspecialchars($booking['tenant_phone']) ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Booking Period -->
                        <div class="border-b pb-4">
                            <h4 class="font-semibold text-gray-700 mb-2">
                                <i class="fas fa-calendar-alt mr-2"></i> Periode Sewa
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <div>
                                    <p class="text-sm text-gray-600">Tanggal Mulai</p>
                                    <p class="font-medium">
                                        <?= date('d M Y', strtotime($booking['start_date'])) ?>
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Durasi</p>
                                    <p class="font-medium">
                                        <?= $booking['duration_months'] ?> Bulan
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Tanggal Selesai</p>
                                    <p class="font-medium">
                                        <?= date('d M Y', strtotime($booking['end_date'])) ?>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Owner Contact -->
                        <div>
                            <h4 class="font-semibold text-gray-700 mb-2">
                                <i class="fas fa-headset mr-2"></i> Kontak Pemilik
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <p class="text-sm text-gray-600">Nama Pemilik</p>
                                    <p class="font-medium"><?= htmlspecialchars($booking['owner_name']) ?></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">No. Telepon</p>
                                    <p class="font-medium"><?= htmlspecialchars($booking['owner_phone']) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-receipt mr-2"></i> Ringkasan Pembayaran
                    </h2>

                    <div class="space-y-3 mb-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Harga per Bulan</span>
                            <span class="font-medium">
                                Rp <?= number_format($booking['kamar_price'], 0, ',', '.') ?>
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Durasi</span>
                            <span class="font-medium">
                                <?= $booking['duration_months'] ?> Bulan
                            </span>
                        </div>
                        <div class="border-t pt-3 flex justify-between">
                            <span class="font-bold text-lg">Total Pembayaran</span>
                            <span class="font-bold text-lg text-blue-600">
                                Rp <?= number_format($payment['amount'], 0, ',', '.') ?>
                            </span>
                        </div>
                    </div>

                    <!-- Payment Button -->
                    <button id="pay-button" 
                            class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 transition duration-200">
                        <i class="fas fa-lock mr-2"></i> Bayar Sekarang
                    </button>

                    <!-- Payment Info -->
                    <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                        <p class="text-sm text-blue-800">
                            <i class="fas fa-shield-alt mr-2"></i>
                            Pembayaran aman menggunakan Midtrans
                        </p>
                    </div>

                    <!-- Supported Payments -->
                    <div class="mt-4">
                        <p class="text-xs text-gray-600 mb-2">Metode Pembayaran:</p>
                        <div class="grid grid-cols-3 gap-2">
                            <div class="bg-gray-100 p-2 rounded text-center">
                                <i class="fas fa-credit-card text-gray-600"></i>
                                <p class="text-xs mt-1">Kartu</p>
                            </div>
                            <div class="bg-gray-100 p-2 rounded text-center">
                                <i class="fas fa-university text-gray-600"></i>
                                <p class="text-xs mt-1">Bank</p>
                            </div>
                            <div class="bg-gray-100 p-2 rounded text-center">
                                <i class="fas fa-qrcode text-gray-600"></i>
                                <p class="text-xs mt-1">QRIS</p>
                            </div>
                        </div>
                    </div>

                    <!-- Order ID -->
                    <div class="mt-4 text-center">
                        <p class="text-xs text-gray-500">
                            Order ID: <span class="font-mono"><?= $payment['order_id'] ?></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Midtrans Snap.js -->
<script src="<?= $midtrans_snap_url ?>" 
        data-client-key="<?= $midtrans_client_key ?>"></script>

<script>
    // Pay button click handler
    document.getElementById('pay-button').addEventListener('click', function () {
        snap.pay('<?= $payment['snap_token'] ?>', {
            onSuccess: function(result) {
                console.log('Payment success:', result);
                window.location.href = '<?= url('/payment/success') ?>?order_id=' + result.order_id;
            },
            onPending: function(result) {
                console.log('Payment pending:', result);
                window.location.href = '<?= url('/payment/pending') ?>?order_id=' + result.order_id;
            },
            onError: function(result) {
                console.log('Payment error:', result);
                window.location.href = '<?= url('/payment/failed') ?>?order_id=' + result.order_id;
            },
            onClose: function() {
                console.log('Payment popup closed');
                alert('Anda menutup popup pembayaran. Silakan selesaikan pembayaran Anda.');
            }
        });
    });
</script>
