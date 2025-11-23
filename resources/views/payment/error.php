<!-- Payment Error Page -->
<div class="min-h-screen bg-gray-100 py-8">
    <div class="container mx-auto px-4 max-w-2xl">
        
        <!-- Error Card -->
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <!-- Error Icon -->
            <div class="mb-6">
                <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto">
                    <i class="fas fa-exclamation-triangle text-red-500 text-5xl"></i>
                </div>
            </div>

            <!-- Error Message -->
            <h1 class="text-3xl font-bold text-gray-800 mb-3">
                Terjadi Kesalahan
            </h1>
            <p class="text-gray-600 mb-8">
                Maaf, terjadi kesalahan sistem saat memproses pembayaran Anda. Silakan coba lagi nanti.
            </p>

            <!-- Error Info -->
            <div class="bg-red-50 rounded-lg p-6 mb-6 text-left">
                <h3 class="font-bold text-red-800 mb-3">
                    <i class="fas fa-info-circle mr-2"></i> Informasi Error
                </h3>
                <p class="text-sm text-red-700">
                    Terjadi kesalahan teknis pada sistem pembayaran. Tim teknis kami telah diberitahu 
                    dan sedang menangani masalah ini. Kami mohon maaf atas ketidaknyamanan ini.
                </p>
            </div>

            <!-- What to Do -->
            <div class="bg-blue-50 rounded-lg p-6 mb-6 text-left">
                <h3 class="font-bold text-blue-800 mb-3">
                    <i class="fas fa-question-circle mr-2"></i> Apa yang Harus Dilakukan?
                </h3>
                <ul class="space-y-2 text-sm text-blue-700">
                    <li class="flex items-start">
                        <i class="fas fa-check mr-2 mt-1"></i>
                        <span>Tunggu beberapa saat dan coba lagi</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check mr-2 mt-1"></i>
                        <span>Refresh halaman browser Anda</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check mr-2 mt-1"></i>
                        <span>Pastikan koneksi internet Anda stabil</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check mr-2 mt-1"></i>
                        <span>Jika masalah berlanjut, hubungi customer service</span>
                    </li>
                </ul>
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

            <!-- Contact Support -->
            <div class="mt-8 p-6 bg-gray-50 rounded-lg">
                <h3 class="font-bold text-gray-800 mb-3">
                    <i class="fas fa-headset mr-2"></i> Butuh Bantuan?
                </h3>
                <p class="text-sm text-gray-600 mb-4">
                    Tim customer service kami siap membantu Anda 24/7
                </p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center text-sm">
                    <a href="tel:+6281234567890" 
                       class="text-blue-600 hover:text-blue-700">
                        <i class="fas fa-phone mr-2"></i> 0812-3456-7890
                    </a>
                    <a href="mailto:support@sistemkost.com" 
                       class="text-blue-600 hover:text-blue-700">
                        <i class="fas fa-envelope mr-2"></i> support@sistemkost.com
                    </a>
                    <a href="#" 
                       class="text-blue-600 hover:text-blue-700">
                        <i class="fab fa-whatsapp mr-2"></i> WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
