<div class="min-h-screen">
    
    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-blue-600 to-purple-700 text-white py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-5xl font-bold mb-6">
                    Temukan Kost Impian Anda di Medan
                </h1>
                <p class="text-xl mb-8 text-blue-100">
                    Platform terpercaya untuk mencari dan mengelola kost dengan mudah
                </p>
                
                <!-- Search Bar -->
                <div class="bg-white rounded-lg shadow-xl p-4">
                    <form action="<?= url('/search') ?>" method="GET" class="flex flex-col md:flex-row gap-3">
                        <input type="text" name="q" placeholder="Cari kost di Medan..."
                               class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-800">
                        <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 font-semibold">
                            <i class="fas fa-search mr-2"></i>Cari
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Features Section -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">
                Kenapa Memilih Kami?
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-search text-3xl text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Mudah Dicari</h3>
                    <p class="text-gray-600">Filter kost berdasarkan harga, lokasi, dan fasilitas yang Anda inginkan</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-credit-card text-3xl text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Pembayaran Aman</h3>
                    <p class="text-gray-600">Transaksi digital yang aman melalui Midtrans dengan berbagai metode pembayaran</p>
                </div>
                
                <!-- Feature 3 -->
                <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shield-alt text-3xl text-purple-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Terpercaya</h3>
                    <p class="text-gray-600">Semua pemilik kost terverifikasi oleh admin untuk keamanan Anda</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="bg-gray-100 py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    <!-- For Tenants -->
                    <div class="bg-white rounded-lg shadow-lg p-8">
                        <div class="text-center">
                            <i class="fas fa-user text-5xl text-blue-600 mb-4"></i>
                            <h3 class="text-2xl font-bold mb-4">Untuk Penyewa</h3>
                            <p class="text-gray-600 mb-6">Cari dan sewa kost impian Anda dengan mudah</p>
                            <a href="<?= url('/register') ?>" 
                               class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 font-semibold">
                                Daftar Sebagai Penyewa
                            </a>
                        </div>
                    </div>
                    
                    <!-- For Owners -->
                    <div class="bg-white rounded-lg shadow-lg p-8">
                        <div class="text-center">
                            <i class="fas fa-building text-5xl text-purple-600 mb-4"></i>
                            <h3 class="text-2xl font-bold mb-4">Untuk Pemilik Kost</h3>
                            <p class="text-gray-600 mb-6">Kelola kost Anda dan jangkau lebih banyak penyewa</p>
                            <a href="<?= url('/register-owner') ?>" 
                               class="inline-block bg-purple-600 text-white px-8 py-3 rounded-lg hover:bg-purple-700 font-semibold">
                                Daftar Sebagai Pemilik
                            </a>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </section>
    
    <!-- Stats Section -->
    <section class="py-16 bg-gradient-to-r from-blue-600 to-purple-600 text-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <div>
                    <div class="text-5xl font-bold mb-2">100+</div>
                    <div class="text-blue-100">Kost Terdaftar</div>
                </div>
                <div>
                    <div class="text-5xl font-bold mb-2">500+</div>
                    <div class="text-blue-100">Penyewa Aktif</div>
                </div>
                <div>
                    <div class="text-5xl font-bold mb-2">50+</div>
                    <div class="text-blue-100">Pemilik Kost</div>
                </div>
            </div>
        </div>
    </section>
    
</div>
