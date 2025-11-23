<div class="min-h-screen">
    
    <!-- Hero Section -->
    <section class="hero-section bg-gradient-to-br from-blue-600 via-purple-600 to-purple-700 text-white py-24 relative overflow-hidden">
        <!-- Animated Background Shapes -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-20 left-10 w-72 h-72 bg-white rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-20 right-10 w-96 h-96 bg-white rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
        </div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-5xl md:text-6xl font-bold mb-6 animate-fade-in">
                    Temukan Kost Impian Anda di Medan
                </h1>
                <p class="text-xl md:text-2xl mb-10 text-blue-100 animate-fade-in" style="animation-delay: 0.2s;">
                    Platform terpercaya untuk mencari dan mengelola kost dengan mudah dan aman
                </p>
                
                <!-- Enhanced Search Bar -->
                <div class="bg-white rounded-2xl shadow-2xl p-6 animate-fade-in" style="animation-delay: 0.4s;">
                    <form action="<?= url('/search') ?>" method="GET" class="space-y-4">
                        <!-- Main Search Input -->
                        <div class="flex flex-col md:flex-row gap-3">
                            <div class="flex-1">
                                <input type="text" name="q" placeholder="Cari kost di Medan..."
                                       class="w-full px-5 py-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-800 text-lg">
                            </div>
                            <button type="submit" class="bg-blue-600 text-white px-10 py-4 rounded-lg hover:bg-blue-700 font-semibold text-lg transition transform hover:scale-105">
                                <i class="fas fa-search mr-2"></i>Cari Kost
                            </button>
                        </div>
                        
                        <!-- Quick Filters -->
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 text-gray-700">
                            <select name="gender" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                                <option value="">Semua Tipe</option>
                                <option value="putra">Putra</option>
                                <option value="putri">Putri</option>
                                <option value="campur">Campur</option>
                            </select>
                            
                            <select name="price" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                                <option value="">Harga</option>
                                <option value="0-500000">&lt; 500rb</option>
                                <option value="500000-1000000">500rb - 1jt</option>
                                <option value="1000000-2000000">1jt - 2jt</option>
                                <option value="2000000-9999999">&gt; 2jt</option>
                            </select>
                            
                            <select name="facilities" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                                <option value="">Fasilitas</option>
                                <option value="wifi">WiFi</option>
                                <option value="ac">AC</option>
                                <option value="kamar-mandi-dalam">K. Mandi Dalam</option>
                            </select>
                        </div>
                    </form>
                </div>

                <!-- Quick Stats -->
                <div class="grid grid-cols-3 gap-6 mt-12 max-w-2xl mx-auto">
                    <div class="text-center animate-fade-in" style="animation-delay: 0.6s;">
                        <div class="text-4xl font-bold">100+</div>
                        <div class="text-blue-200 text-sm mt-1">Kost Tersedia</div>
                    </div>
                    <div class="text-center animate-fade-in" style="animation-delay: 0.7s;">
                        <div class="text-4xl font-bold">500+</div>
                        <div class="text-blue-200 text-sm mt-1">Penyewa Puas</div>
                    </div>
                    <div class="text-center animate-fade-in" style="animation-delay: 0.8s;">
                        <div class="text-4xl font-bold">50+</div>
                        <div class="text-blue-200 text-sm mt-1">Owner Terdaftar</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Category Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-4">
                Kategori Kost Populer
            </h2>
            <p class="text-center text-gray-600 mb-12">Pilih kategori yang sesuai dengan kebutuhan Anda</p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto">
                <!-- Kost Putra -->
                <a href="<?= url('/search?gender=putra') ?>" class="group">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-8 text-white text-center transform transition hover:scale-105 hover:shadow-2xl">
                        <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-white/30 transition">
                            <i class="fas fa-male text-4xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-2">Kost Putra</h3>
                        <p class="text-blue-100">Kost khusus laki-laki</p>
                        <div class="mt-4 text-sm">
                            <i class="fas fa-arrow-right"></i> Lihat Semua
                        </div>
                    </div>
                </a>
                
                <!-- Kost Putri -->
                <a href="<?= url('/search?gender=putri') ?>" class="group">
                    <div class="bg-gradient-to-br from-pink-500 to-pink-600 rounded-xl shadow-lg p-8 text-white text-center transform transition hover:scale-105 hover:shadow-2xl">
                        <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-white/30 transition">
                            <i class="fas fa-female text-4xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-2">Kost Putri</h3>
                        <p class="text-pink-100">Kost khusus perempuan</p>
                        <div class="mt-4 text-sm">
                            <i class="fas fa-arrow-right"></i> Lihat Semua
                        </div>
                    </div>
                </a>
                
                <!-- Kost Campur -->
                <a href="<?= url('/search?gender=campur') ?>" class="group">
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-8 text-white text-center transform transition hover:scale-105 hover:shadow-2xl">
                        <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-white/30 transition">
                            <i class="fas fa-users text-4xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-2">Kost Campur</h3>
                        <p class="text-purple-100">Kost putra & putri</p>
                        <div class="mt-4 text-sm">
                            <i class="fas fa-arrow-right"></i> Lihat Semua
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </section>
    
    <!-- Features Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-4">
                Kenapa Memilih Kami?
            </h2>
            <p class="text-center text-gray-600 mb-12">Platform kost terpercaya dengan berbagai keunggulan</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 max-w-6xl mx-auto">
                <!-- Feature 1 -->
                <div class="bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-2xl transition transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-search text-3xl text-blue-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold mb-3 text-gray-800">Mudah Dicari</h3>
                    <p class="text-gray-600 text-sm">Filter kost berdasarkan harga, lokasi, dan fasilitas yang Anda inginkan</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-2xl transition transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-credit-card text-3xl text-green-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold mb-3 text-gray-800">Pembayaran Aman</h3>
                    <p class="text-gray-600 text-sm">Transaksi digital yang aman melalui Midtrans dengan berbagai metode pembayaran</p>
                </div>
                
                <!-- Feature 3 -->
                <div class="bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-2xl transition transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shield-alt text-3xl text-purple-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold mb-3 text-gray-800">Terpercaya</h3>
                    <p class="text-gray-600 text-sm">Semua pemilik kost terverifikasi oleh admin untuk keamanan Anda</p>
                </div>
                
                <!-- Feature 4 -->
                <div class="bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-2xl transition transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-headset text-3xl text-orange-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold mb-3 text-gray-800">Support 24/7</h3>
                    <p class="text-gray-600 text-sm">Tim support kami siap membantu Anda kapan saja Anda membutuhkan</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Featured Kost -->
    <?php if (!empty($featuredKost)): ?>
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Kost Terbaru & Populer</h2>
                <p class="text-gray-600">Temukan kost terbaru dan terbaik di Medan dengan harga terjangkau</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <?php foreach ($featuredKost as $kost): ?>
                <div class="kost-card bg-white rounded-xl shadow-md overflow-hidden hover:shadow-2xl transition-all transform hover:-translate-y-2">
                    <!-- Kost Image -->
                    <div class="relative h-52 bg-gray-200 overflow-hidden">
                        <?php if ($kost['primary_photo']): ?>
                            <img src="<?= url('/' . $kost['primary_photo']) ?>" 
                                 alt="<?= e($kost['name']) ?>"
                                 class="w-full h-full object-cover transition-transform duration-300 hover:scale-110"
                                 loading="lazy">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-300 to-gray-400">
                                <i class="fas fa-building text-6xl text-gray-500"></i>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Gender Badge -->
                        <?php
                        $genderColors = [
                            'putra' => 'bg-blue-600',
                            'putri' => 'bg-pink-600',
                            'campur' => 'bg-purple-600'
                        ];
                        $genderColor = $genderColors[$kost['gender_type']] ?? 'bg-gray-600';
                        ?>
                        <span class="absolute top-3 right-3 px-3 py-1 <?= $genderColor ?> text-white text-xs font-semibold rounded-full shadow-lg">
                            <i class="fas fa-<?= $kost['gender_type'] == 'putra' ? 'male' : ($kost['gender_type'] == 'putri' ? 'female' : 'users') ?> mr-1"></i>
                            <?= ucfirst($kost['gender_type']) ?>
                        </span>
                    </div>
                    
                    <!-- Kost Info -->
                    <div class="p-5">
                        <h3 class="text-xl font-bold text-gray-800 mb-2 truncate hover:text-blue-600 transition">
                            <?= e($kost['name']) ?>
                        </h3>
                        
                        <p class="text-sm text-gray-600 mb-4 flex items-start">
                            <i class="fas fa-map-marker-alt text-red-500 mr-2 mt-1 flex-shrink-0"></i>
                            <span class="line-clamp-2"><?= e($kost['location']) ?></span>
                        </p>
                        
                        <!-- Price Range -->
                        <div class="mb-4 bg-blue-50 p-3 rounded-lg">
                            <?php if (!empty($kost['min_price']) && $kost['min_price'] > 0): ?>
                                <p class="text-xs text-gray-500 mb-1">Mulai dari</p>
                                <p class="text-2xl font-bold text-blue-600">
                                    Rp <?= number_format($kost['min_price'], 0, ',', '.') ?>
                                    <span class="text-sm text-gray-500 font-normal">/bulan</span>
                                </p>
                            <?php else: ?>
                                <p class="text-xs text-gray-500 mb-1">Harga</p>
                                <p class="text-lg font-semibold text-gray-400">
                                    Belum Tersedia
                                </p>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Stats -->
                        <div class="flex items-center justify-between text-sm text-gray-600 mb-4 pb-4 border-b border-gray-200">
                            <?php 
                            $facilitiesCount = 0;
                            if (!empty($kost['facilities'])) {
                                $facilitiesArray = json_decode($kost['facilities'], true);
                                $facilitiesCount = is_array($facilitiesArray) ? count($facilitiesArray) : 0;
                            }
                            ?>
                            <div class="flex items-center">
                                <i class="fas fa-star text-yellow-500 mr-1"></i>
                                <span class="font-medium"><?= $facilitiesCount ?></span>
                                <span class="ml-1 text-xs">Fasilitas</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-door-open text-green-500 mr-1"></i>
                                <span class="font-medium"><?= $kost['available_rooms'] ?? 0 ?></span>
                                <span class="ml-1 text-xs">Tersedia</span>
                            </div>
                        </div>
                        
                        <!-- Action Button -->
                        <a href="<?= url('/kost/' . $kost['id']) ?>" 
                           class="block w-full text-center px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition transform hover:scale-105 font-semibold shadow-md">
                            <i class="fas fa-eye mr-2"></i>
                            Lihat Detail
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="text-center">
                <a href="<?= url('/search') ?>" 
                   class="inline-block bg-gradient-to-r from-blue-600 to-purple-600 text-white px-10 py-4 rounded-xl hover:from-blue-700 hover:to-purple-700 font-semibold shadow-lg transform hover:scale-105 transition">
                    Lihat Semua Kost
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </section>
    <?php endif; ?>
    
    <!-- Testimonials Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Apa Kata Mereka?</h2>
                <p class="text-gray-600">Testimoni dari penyewa dan pemilik kost yang puas</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <!-- Testimonial 1 -->
                <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100 hover:shadow-2xl transition">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold mr-3">
                            AR
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800">Ahmad Rizki</h4>
                            <p class="text-sm text-gray-500">Penyewa Kost</p>
                        </div>
                    </div>
                    <div class="flex mb-3">
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                    </div>
                    <p class="text-gray-600 italic">
                        "Sangat membantu dalam mencari kost yang sesuai budget. Proses pembayaran juga mudah dan aman!"
                    </p>
                </div>
                
                <!-- Testimonial 2 -->
                <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100 hover:shadow-2xl transition">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-pink-600 rounded-full flex items-center justify-center text-white font-bold mr-3">
                            SP
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800">Siti Permata</h4>
                            <p class="text-sm text-gray-500">Penyewa Kost</p>
                        </div>
                    </div>
                    <div class="flex mb-3">
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                    </div>
                    <p class="text-gray-600 italic">
                        "Platform yang sangat user-friendly. Saya bisa filter kost sesuai kebutuhan dengan mudah. Recommended!"
                    </p>
                </div>
                
                <!-- Testimonial 3 -->
                <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100 hover:shadow-2xl transition">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-purple-600 rounded-full flex items-center justify-center text-white font-bold mr-3">
                            BW
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800">Budi Wijaya</h4>
                            <p class="text-sm text-gray-500">Pemilik Kost</p>
                        </div>
                    </div>
                    <div class="flex mb-3">
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                    </div>
                    <p class="text-gray-600 italic">
                        "Memudahkan saya mengelola kost. Dashboard yang lengkap dan sistem pembayaran yang terintegrasi!"
                    </p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="py-16 bg-gradient-to-br from-gray-100 to-gray-200">
        <div class="container mx-auto px-4">
            <div class="max-w-5xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    <!-- For Tenants -->
                    <div class="bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition transform hover:-translate-y-2">
                        <div class="text-center">
                            <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-user text-5xl text-blue-600"></i>
                            </div>
                            <h3 class="text-2xl font-bold mb-3 text-gray-800">Untuk Penyewa</h3>
                            <p class="text-gray-600 mb-6">Cari dan sewa kost impian Anda dengan mudah. Ratusan pilihan kost menanti!</p>
                            <ul class="text-left text-sm text-gray-600 mb-6 space-y-2">
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    Pencarian kost yang mudah
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    Filter sesuai kebutuhan
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    Pembayaran online aman
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    Kost terverifikasi
                                </li>
                            </ul>
                            <a href="<?= url('/register') ?>" 
                               class="inline-block w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white px-8 py-4 rounded-xl hover:from-blue-700 hover:to-blue-800 font-semibold shadow-lg transform hover:scale-105 transition">
                                <i class="fas fa-user-plus mr-2"></i>
                                Daftar Sebagai Penyewa
                            </a>
                        </div>
                    </div>
                    
                    <!-- For Owners -->
                    <div class="bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition transform hover:-translate-y-2">
                        <div class="text-center">
                            <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-building text-5xl text-purple-600"></i>
                            </div>
                            <h3 class="text-2xl font-bold mb-3 text-gray-800">Untuk Pemilik Kost</h3>
                            <p class="text-gray-600 mb-6">Kelola kost Anda dan jangkau lebih banyak penyewa potensial di seluruh Medan</p>
                            <ul class="text-left text-sm text-gray-600 mb-6 space-y-2">
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    Dashboard lengkap & mudah
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    Kelola kamar & booking
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    Sistem pembayaran otomatis
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    Jangkauan luas
                                </li>
                            </ul>
                            <a href="<?= url('/register-owner') ?>" 
                               class="inline-block w-full bg-gradient-to-r from-purple-600 to-purple-700 text-white px-8 py-4 rounded-xl hover:from-purple-700 hover:to-purple-800 font-semibold shadow-lg transform hover:scale-105 transition">
                                <i class="fas fa-building mr-2"></i>
                                Daftar Sebagai Pemilik
                            </a>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </section>
    
    <!-- How It Works Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Cara Kerjanya</h2>
                <p class="text-gray-600">Hanya 3 langkah mudah untuk menemukan kost impian Anda</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                <!-- Step 1 -->
                <div class="text-center">
                    <div class="relative mb-6">
                        <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto shadow-xl">
                            <span class="text-4xl font-bold text-white">1</span>
                        </div>
                        <div class="absolute -top-2 -right-2 w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center">
                            <i class="fas fa-search text-white text-sm"></i>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Cari Kost</h3>
                    <p class="text-gray-600">Gunakan filter untuk menemukan kost yang sesuai dengan kebutuhan dan budget Anda</p>
                </div>
                
                <!-- Step 2 -->
                <div class="text-center">
                    <div class="relative mb-6">
                        <div class="w-24 h-24 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center mx-auto shadow-xl">
                            <span class="text-4xl font-bold text-white">2</span>
                        </div>
                        <div class="absolute -top-2 -right-2 w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center">
                            <i class="fas fa-calendar-check text-white text-sm"></i>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Booking Kamar</h3>
                    <p class="text-gray-600">Pilih kamar yang tersedia dan lakukan booking dengan mengisi form sederhana</p>
                </div>
                
                <!-- Step 3 -->
                <div class="text-center">
                    <div class="relative mb-6">
                        <div class="w-24 h-24 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center mx-auto shadow-xl">
                            <span class="text-4xl font-bold text-white">3</span>
                        </div>
                        <div class="absolute -top-2 -right-2 w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center">
                            <i class="fas fa-credit-card text-white text-sm"></i>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Bayar & Pindah</h3>
                    <p class="text-gray-600">Lakukan pembayaran online yang aman, dan Anda siap untuk pindah ke kost baru!</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Stats Section -->
    <section class="py-20 bg-gradient-to-r from-blue-600 via-purple-600 to-purple-700 text-white relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-full h-full" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        </div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold mb-3">Dipercaya Ribuan Pengguna</h2>
                <p class="text-blue-100 text-lg">Platform kost terbesar dan terpercaya di Medan</p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 max-w-5xl mx-auto">
                <div class="text-center transform hover:scale-110 transition">
                    <div class="text-5xl md:text-6xl font-bold mb-2 animate-pulse">100+</div>
                    <div class="text-blue-100 text-sm md:text-base">Kost Terdaftar</div>
                </div>
                <div class="text-center transform hover:scale-110 transition" style="animation-delay: 0.1s;">
                    <div class="text-5xl md:text-6xl font-bold mb-2 animate-pulse">500+</div>
                    <div class="text-blue-100 text-sm md:text-base">Penyewa Aktif</div>
                </div>
                <div class="text-center transform hover:scale-110 transition" style="animation-delay: 0.2s;">
                    <div class="text-5xl md:text-6xl font-bold mb-2 animate-pulse">50+</div>
                    <div class="text-blue-100 text-sm md:text-base">Pemilik Kost</div>
                </div>
                <div class="text-center transform hover:scale-110 transition" style="animation-delay: 0.3s;">
                    <div class="text-5xl md:text-6xl font-bold mb-2 animate-pulse">98%</div>
                    <div class="text-blue-100 text-sm md:text-base">Kepuasan User</div>
                </div>
            </div>
        </div>
    </section>
    
</div>
