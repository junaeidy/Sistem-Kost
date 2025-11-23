<!-- Payment Pending Page -->
<div class="min-h-screen bg-gray-100 py-8">
    <div class="container mx-auto px-4 max-w-2xl">
        
        <!-- Pending Card -->
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <!-- Pending Icon -->
            <div class="mb-6">
                <div class="w-24 h-24 bg-yellow-100 rounded-full flex items-center justify-center mx-auto">
                    <i class="fas fa-clock text-yellow-500 text-5xl"></i>
                </div>
            </div>

            <!-- Pending Message -->
            <h1 class="text-3xl font-bold text-gray-800 mb-3">
                Pembayaran Pending
            </h1>
            <p class="text-gray-600 mb-8">
                Pembayaran Anda sedang diproses. Silakan selesaikan pembayaran sesuai instruksi yang diberikan.
            </p>

            <!-- Payment Details -->
            <div class="bg-gray-50 rounded-lg p-6 mb-6 text-left">
                <h2 class="font-bold text-gray-800 mb-4 text-center">Detail Pembayaran</h2>
                
                <div class="space-y-3">
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-gray-600">Order ID</span>
                        <span class="font-mono font-medium">
                            <?= htmlspecialchars($payment['midtrans_order_id']) ?>
                        </span>
                    </div>
                    
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-gray-600">Kost</span>
                        <span class="font-medium">
                            <?= htmlspecialchars($payment['kost_name']) ?>
                        </span>
                    </div>
                    
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-gray-600">Kamar</span>
                        <span class="font-medium">
                            <?= htmlspecialchars($payment['kamar_name']) ?>
                        </span>
                    </div>
                    
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-gray-600">Status</span>
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">
                            <i class="fas fa-clock mr-1"></i> Pending
                        </span>
                    </div>
                    
                    <div class="flex justify-between pt-2">
                        <span class="font-bold text-lg">Total Pembayaran</span>
                        <span class="font-bold text-lg text-yellow-600">
                            Rp <?= number_format($payment['amount'], 0, ',', '.') ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Instructions -->
            <div class="bg-yellow-50 rounded-lg p-6 mb-6 text-left">
                <h3 class="font-bold text-yellow-800 mb-3">
                    <i class="fas fa-exclamation-circle mr-2"></i> Instruksi Pembayaran
                </h3>
                <ul class="space-y-2 text-sm text-yellow-700">
                    <?php if ($payment['payment_type'] === 'bank_transfer'): ?>
                        <li class="flex items-start">
                            <i class="fas fa-check mr-2 mt-1"></i>
                            <span>Selesaikan transfer bank sesuai dengan instruksi yang diberikan</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check mr-2 mt-1"></i>
                            <span>Transfer ke nomor virtual account yang telah diberikan</span>
                        </li>
                    <?php elseif ($payment['payment_type'] === 'gopay'): ?>
                        <li class="flex items-start">
                            <i class="fas fa-check mr-2 mt-1"></i>
                            <span>Buka aplikasi GoPay Anda</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check mr-2 mt-1"></i>
                            <span>Selesaikan pembayaran sesuai notifikasi yang muncul</span>
                        </li>
                    <?php elseif ($payment['payment_type'] === 'qris'): ?>
                        <li class="flex items-start">
                            <i class="fas fa-check mr-2 mt-1"></i>
                            <span>Scan kode QR yang diberikan menggunakan aplikasi pembayaran</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check mr-2 mt-1"></i>
                            <span>Konfirmasi pembayaran di aplikasi Anda</span>
                        </li>
                    <?php else: ?>
                        <li class="flex items-start">
                            <i class="fas fa-check mr-2 mt-1"></i>
                            <span>Selesaikan pembayaran sesuai instruksi yang diberikan</span>
                        </li>
                    <?php endif; ?>
                    <li class="flex items-start">
                        <i class="fas fa-check mr-2 mt-1"></i>
                        <span>Pembayaran akan dikonfirmasi otomatis setelah berhasil</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check mr-2 mt-1"></i>
                        <span>Anda akan menerima notifikasi setelah pembayaran berhasil</span>
                    </li>
                </ul>
            </div>

            <!-- Warning -->
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 text-left">
                <p class="text-sm text-red-700">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <strong>Perhatian:</strong> Pembayaran akan kadaluarsa dalam 24 jam. 
                    Pastikan Anda menyelesaikan pembayaran sebelum waktu kadaluarsa.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <button onclick="checkPaymentStatus()" 
                        id="checkStatusBtn"
                        class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-200">
                    <i class="fas fa-sync mr-2"></i> Cek Status Pembayaran
                </button>
                <a href="<?= url('/tenant/bookings') ?>" 
                   class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-300 transition duration-200">
                    <i class="fas fa-list mr-2"></i> Lihat Booking Saya
                </a>
            </div>

            <!-- Help -->
            <div class="mt-6 text-sm text-gray-600">
                <p>Butuh bantuan? 
                    <a href="#" class="text-blue-600 hover:underline">Hubungi Customer Service</a>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    function checkPaymentStatus() {
        const btn = document.getElementById('checkStatusBtn');
        const originalText = btn.innerHTML;
        
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mengecek...';
        
        // Simulate checking status - in real implementation, call API
        setTimeout(() => {
            // Reload page to check updated status
            window.location.reload();
        }, 1500);
    }

    // Auto refresh every 30 seconds
    let autoRefreshInterval = setInterval(() => {
        fetch('<?= url('/payment/check-status/' . $payment['midtrans_order_id']) ?>')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success' && data.data.transaction_status === 'settlement') {
                    clearInterval(autoRefreshInterval);
                    window.location.href = '<?= url('/payment/success/' . $payment['midtrans_order_id']) ?>';
                }
            })
            .catch(error => console.error('Error checking status:', error));
    }, 30000);
</script>
