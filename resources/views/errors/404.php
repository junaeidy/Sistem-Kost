<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan | Sistem Kost</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .float-animation {
            animation: float 3s ease-in-out infinite;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-purple-50">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-2xl w-full text-center">
            <!-- 404 Illustration -->
            <div class="mb-8 float-animation">
                <div class="text-9xl font-bold text-blue-600 mb-4">404</div>
                <div class="flex justify-center space-x-4 text-6xl">
                    <i class="fas fa-home text-blue-500"></i>
                    <i class="fas fa-question text-purple-500"></i>
                    <i class="fas fa-search text-pink-500"></i>
                </div>
            </div>
            
            <!-- Error Message -->
            <div class="bg-white rounded-2xl shadow-2xl p-8 mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-4">
                    Oops! Halaman Tidak Ditemukan
                </h1>
                <p class="text-gray-600 mb-6 text-lg">
                    Maaf, halaman yang Anda cari tidak dapat ditemukan. 
                    Mungkin halaman telah dipindahkan atau URL yang Anda masukkan salah.
                </p>
                
                <!-- Suggestions -->
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 text-left">
                    <h3 class="font-semibold text-gray-800 mb-2">
                        <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                        Saran untuk Anda:
                    </h3>
                    <ul class="text-gray-700 space-y-1 ml-6">
                        <li>• Periksa kembali URL yang Anda masukkan</li>
                        <li>• Kembali ke halaman sebelumnya</li>
                        <li>• Gunakan menu navigasi untuk menemukan halaman yang Anda cari</li>
                        <li>• Coba gunakan fitur pencarian</li>
                    </ul>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="<?= url('/') ?>" 
                       class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 font-semibold shadow-lg transform hover:scale-105 transition">
                        <i class="fas fa-home mr-2"></i>
                        Kembali ke Beranda
                    </a>
                    
                    <button onclick="history.back()" 
                            class="inline-flex items-center justify-center px-8 py-4 bg-white text-gray-700 border-2 border-gray-300 rounded-xl hover:bg-gray-50 font-semibold shadow-lg transform hover:scale-105 transition">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Halaman Sebelumnya
                    </button>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="<?= url('/') ?>" 
                   class="bg-white rounded-lg p-4 hover:shadow-lg transition transform hover:-translate-y-1">
                    <i class="fas fa-home text-3xl text-blue-600 mb-2"></i>
                    <div class="text-sm font-medium text-gray-700">Beranda</div>
                </a>
                
                <a href="<?= url('/search') ?>" 
                   class="bg-white rounded-lg p-4 hover:shadow-lg transition transform hover:-translate-y-1">
                    <i class="fas fa-search text-3xl text-purple-600 mb-2"></i>
                    <div class="text-sm font-medium text-gray-700">Cari Kost</div>
                </a>
                
                <a href="<?= url('/login') ?>" 
                   class="bg-white rounded-lg p-4 hover:shadow-lg transition transform hover:-translate-y-1">
                    <i class="fas fa-sign-in-alt text-3xl text-green-600 mb-2"></i>
                    <div class="text-sm font-medium text-gray-700">Login</div>
                </a>
                
                <a href="<?= url('/register') ?>" 
                   class="bg-white rounded-lg p-4 hover:shadow-lg transition transform hover:-translate-y-1">
                    <i class="fas fa-user-plus text-3xl text-pink-600 mb-2"></i>
                    <div class="text-sm font-medium text-gray-700">Daftar</div>
                </a>
            </div>
            
            <!-- Footer -->
            <div class="mt-8 text-gray-500 text-sm">
                <p>Butuh bantuan? <a href="mailto:info@sistemkost.com" class="text-blue-600 hover:underline">Hubungi kami</a></p>
            </div>
        </div>
    </div>
</body>
</html>
