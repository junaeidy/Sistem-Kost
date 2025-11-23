<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Server Error | Sistem Kost</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-10px); }
            20%, 40%, 60%, 80% { transform: translateX(10px); }
        }
        .shake-animation {
            animation: shake 0.5s ease-in-out;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-red-50 to-orange-50">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-2xl w-full text-center">
            <!-- 500 Illustration -->
            <div class="mb-8">
                <div class="text-9xl font-bold text-red-600 mb-4">500</div>
                <div class="flex justify-center space-x-4 text-6xl shake-animation">
                    <i class="fas fa-server text-red-500"></i>
                    <i class="fas fa-exclamation-triangle text-orange-500"></i>
                    <i class="fas fa-tools text-yellow-500"></i>
                </div>
            </div>
            
            <!-- Error Message -->
            <div class="bg-white rounded-2xl shadow-2xl p-8 mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-4">
                    Oops! Terjadi Kesalahan Server
                </h1>
                <p class="text-gray-600 mb-6 text-lg">
                    Maaf, terjadi kesalahan pada server kami. 
                    Tim kami telah diberitahu dan sedang bekerja untuk memperbaikinya.
                </p>
                
                <!-- Suggestions -->
                <div class="bg-orange-50 border-l-4 border-orange-500 p-4 mb-6 text-left">
                    <h3 class="font-semibold text-gray-800 mb-2">
                        <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                        Apa yang bisa Anda lakukan:
                    </h3>
                    <ul class="text-gray-700 space-y-1 ml-6">
                        <li>• Muat ulang halaman ini dalam beberapa saat</li>
                        <li>• Kembali ke halaman beranda</li>
                        <li>• Hubungi tim support jika masalah berlanjut</li>
                        <li>• Coba akses halaman lain terlebih dahulu</li>
                    </ul>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button onclick="location.reload()" 
                            class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 font-semibold shadow-lg transform hover:scale-105 transition">
                        <i class="fas fa-sync-alt mr-2"></i>
                        Muat Ulang Halaman
                    </button>
                    
                    <a href="<?= url('/') ?>" 
                       class="inline-flex items-center justify-center px-8 py-4 bg-white text-gray-700 border-2 border-gray-300 rounded-xl hover:bg-gray-50 font-semibold shadow-lg transform hover:scale-105 transition">
                        <i class="fas fa-home mr-2"></i>
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
            
            <!-- Support Contact -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="font-semibold text-gray-800 mb-4">
                    <i class="fas fa-headset text-blue-600 mr-2"></i>
                    Butuh Bantuan?
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-left">
                    <div class="flex items-start">
                        <i class="fas fa-envelope text-blue-600 mr-3 mt-1"></i>
                        <div>
                            <div class="font-medium text-gray-700">Email</div>
                            <a href="mailto:support@sistemkost.com" class="text-blue-600 hover:underline">support@sistemkost.com</a>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-phone text-green-600 mr-3 mt-1"></i>
                        <div>
                            <div class="font-medium text-gray-700">Telepon</div>
                            <a href="tel:+6281234567890" class="text-blue-600 hover:underline">+62 812-3456-7890</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="mt-8 text-gray-500 text-sm">
                <p>Error ID: <?= uniqid('ERR-') ?> | Timestamp: <?= date('Y-m-d H:i:s') ?></p>
            </div>
        </div>
    </div>
</body>
</html>
