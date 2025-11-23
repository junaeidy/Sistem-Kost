<!-- Payment Failed Page -->
<div class="min-h-screen bg-gray-100 py-8">
    <div class="container mx-auto px-4 max-w-2xl">
        
        <!-- Failed Card -->
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <!-- Failed Icon -->
            <div class="mb-6">
                <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto">
                    <i class="fas fa-times-circle text-red-500 text-5xl"></i>
                </div>
            </div>

            <!-- Failed Message -->
            <h1 class="text-3xl font-bold text-gray-800 mb-3">
                Pembayaran Gagal
            </h1>
            <p class="text-gray-600 mb-8">
                Maaf, pembayaran Anda tidak dapat diproses. Silakan coba lagi atau gunakan metode pembayaran lain.
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
                        <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium">
                            <i class="fas fa-times mr-1"></i> Gagal
                        </span>
                    </div>
                    
                    <div class="flex justify-between pt-2">
                        <span class="font-bold text-lg">Total Pembayaran</span>
                        <span class="font-bold text-lg text-gray-600">
                            Rp <?= number_format($payment['amount'], 0, ',', '.') ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Possible Reasons -->
            <div class="bg-red-50 rounded-lg p-6 mb-6 text-left">
                <h3 class="font-bold text-red-800 mb-3">
                    <i class="fas fa-exclamation-circle mr-2"></i> Kemungkinan Penyebab
                </h3>
                <ul class="space-y-2 text-sm text-red-700">
                    <li class="flex items-start">
                        <i class="fas fa-times mr-2 mt-1"></i>
                        <span>Saldo atau limit kartu tidak mencukupi</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-times mr-2 mt-1"></i>
                        <span>Informasi kartu atau rekening tidak valid</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-times mr-2 mt-1"></i>
                        <span>Transaksi ditolak oleh bank atau penyedia pembayaran</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-times mr-2 mt-1"></i>
                        <span>Koneksi internet terputus saat proses pembayaran</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-times mr-2 mt-1"></i>
                        <span>Waktu pembayaran telah habis/kadaluarsa</span>
                    </li>
                </ul>
            </div>

            <!-- What to Do Next -->
            <div class="bg-blue-50 rounded-lg p-6 mb-6 text-left">
                <h3 class="font-bold text-blue-800 mb-3">
                    <i class="fas fa-info-circle mr-2"></i> Apa yang Harus Dilakukan?
                </h3>
                <ul class="space-y-2 text-sm text-blue-700">
                    <li class="flex items-start">
                        <i class="fas fa-check mr-2 mt-1"></i>
                        <span>Pastikan saldo atau limit kartu Anda mencukupi</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check mr-2 mt-1"></i>
                        <span>Periksa kembali informasi pembayaran yang Anda masukkan</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check mr-2 mt-1"></i>
                        <span>Coba gunakan metode pembayaran lain</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check mr-2 mt-1"></i>
                        <span>Hubungi bank atau penyedia pembayaran Anda jika masalah berlanjut</span>
                    </li>
                </ul>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="<?= url('/payment/create/' . $payment['booking_id']) ?>" 
                   class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-200">
                    <i class="fas fa-redo mr-2"></i> Coba Lagi
                </a>
                <a href="<?= url('/tenant/bookings') ?>" 
                   class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-300 transition duration-200">
                    <i class="fas fa-list mr-2"></i> Lihat Booking Saya
                </a>
            </div>

            <!-- Help -->
            <div class="mt-6 text-sm text-gray-600">
                <p>Butuh bantuan? 
                    <a href="#" class="text-blue-600 hover:underline">
                        <i class="fas fa-headset mr-1"></i> Hubungi Customer Service
                    </a>
                </p>
            </div>

            <!-- Alternative Payment Methods -->
            <div class="mt-8 p-4 border-t">
                <p class="text-sm text-gray-600 mb-3">Metode Pembayaran Lain:</p>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <div class="bg-gray-50 p-3 rounded-lg text-center hover:bg-gray-100 cursor-pointer">
                        <i class="fas fa-credit-card text-2xl text-gray-600 mb-2"></i>
                        <p class="text-xs">Kartu Kredit</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg text-center hover:bg-gray-100 cursor-pointer">
                        <i class="fas fa-university text-2xl text-gray-600 mb-2"></i>
                        <p class="text-xs">Transfer Bank</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg text-center hover:bg-gray-100 cursor-pointer">
                        <i class="fas fa-qrcode text-2xl text-gray-600 mb-2"></i>
                        <p class="text-xs">QRIS</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg text-center hover:bg-gray-100 cursor-pointer">
                        <i class="fas fa-mobile-alt text-2xl text-gray-600 mb-2"></i>
                        <p class="text-xs">E-Wallet</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
