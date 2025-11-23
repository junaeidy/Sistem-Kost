<!-- Payment Success Page -->
<div class="min-h-screen bg-gray-100 py-8">
    <div class="container mx-auto px-4 max-w-2xl">
        
        <!-- Success Card -->
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <!-- Success Icon -->
            <div class="mb-6">
                <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto">
                    <i class="fas fa-check-circle text-green-500 text-5xl"></i>
                </div>
            </div>

            <!-- Success Message -->
            <h1 class="text-3xl font-bold text-gray-800 mb-3">
                Pembayaran Berhasil!
            </h1>
            <p class="text-gray-600 mb-8">
                Terima kasih, pembayaran Anda telah berhasil diproses.
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
                        <span class="text-gray-600">Periode</span>
                        <span class="font-medium">
                            <?= date('d M Y', strtotime($payment['start_date'])) ?> - 
                            <?= date('d M Y', strtotime($payment['end_date'])) ?>
                        </span>
                    </div>
                    
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-gray-600">Metode Pembayaran</span>
                        <span class="font-medium">
                            <?= formatPaymentMethod($payment['payment_type'] ?? null) ?>
                        </span>
                    </div>
                    
                    <div class="flex justify-between pt-2">
                        <span class="font-bold text-lg">Total Dibayar</span>
                        <span class="font-bold text-lg text-green-600">
                            Rp <?= number_format($payment['amount'], 0, ',', '.') ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Next Steps -->
            <div class="bg-blue-50 rounded-lg p-6 mb-6 text-left">
                <h3 class="font-bold text-blue-800 mb-3">
                    <i class="fas fa-info-circle mr-2"></i> Langkah Selanjutnya
                </h3>
                <ul class="space-y-2 text-sm text-blue-700">
                    <li class="flex items-start">
                        <i class="fas fa-check mr-2 mt-1"></i>
                        <span>Pembayaran Anda sedang diproses oleh pemilik kost</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check mr-2 mt-1"></i>
                        <span>Anda akan menerima notifikasi setelah booking dikonfirmasi</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check mr-2 mt-1"></i>
                        <span>Silakan hubungi pemilik untuk konfirmasi check-in</span>
                    </li>
                </ul>
            </div>

            <!-- Contact Owner -->
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 text-left">
                <p class="text-sm text-yellow-700">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <strong>Penting:</strong> Hubungi pemilik kost untuk mengatur jadwal check-in Anda.
                </p>
                <div class="mt-2 text-sm">
                    <p class="text-gray-700">
                        <strong><?= htmlspecialchars($payment['owner_name'] ?? 'Pemilik Kost') ?>:</strong>
                        <a href="tel:<?= htmlspecialchars($payment['owner_phone'] ?? '') ?>" 
                           class="text-blue-600 hover:underline ml-2">
                            <i class="fas fa-phone mr-1"></i>
                            <?= htmlspecialchars($payment['owner_phone'] ?? '-') ?>
                        </a>
                    </p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="<?= url('/tenant/bookings') ?>" 
                   class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-200">
                    <i class="fas fa-list mr-2"></i> Lihat Booking Saya
                </a>
                <a href="<?= url('/') ?>" 
                   class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-300 transition duration-200">
                    <i class="fas fa-home mr-2"></i> Kembali ke Beranda
                </a>
            </div>

            <!-- Print Receipt -->
            <div class="mt-6">
                <button onclick="window.print()" 
                        class="text-blue-600 hover:text-blue-700 text-sm">
                    <i class="fas fa-print mr-1"></i> Cetak Bukti Pembayaran
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .container * {
            visibility: visible;
        }
        .container {
            position: absolute;
            left: 0;
            top: 0;
        }
        button, a {
            display: none !important;
        }
    }
</style>
