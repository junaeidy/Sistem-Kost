<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menunggu Verifikasi - <?= config('app.name') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-yellow-400 to-orange-500 min-h-screen flex items-center justify-center">
    
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto">
            
            <div class="bg-white rounded-lg shadow-2xl p-8 text-center">
                
                <!-- Icon -->
                <div class="mb-6">
                    <div class="w-24 h-24 bg-yellow-100 rounded-full flex items-center justify-center mx-auto">
                        <i class="fas fa-clock text-5xl text-yellow-600"></i>
                    </div>
                </div>
                
                <!-- Title -->
                <h1 class="text-3xl font-bold text-gray-800 mb-4">
                    🎉 Pendaftaran Berhasil!
                </h1>
                
                <p class="text-xl text-gray-600 mb-6">
                    Selamat datang di Sistem Kost! Akun Anda sedang dalam proses verifikasi.
                </p>
                
                <!-- Message -->
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 mb-6 text-left rounded-lg">
                    <div class="flex items-start mb-4">
                        <i class="fas fa-clock text-2xl text-yellow-600 mr-3 mt-1"></i>
                        <div>
                            <p class="text-gray-800 font-semibold text-lg mb-2">
                                Status: Menunggu Verifikasi
                            </p>
                            <p class="text-gray-700">
                                Tim kami sedang memeriksa data yang Anda kirimkan untuk memastikan keamanan dan kualitas layanan.
                            </p>
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-4 border-t border-yellow-200">
                        <p class="text-gray-700 font-semibold mb-3">
                            📋 Proses Verifikasi:
                        </p>
                        <div class="space-y-3">
                            <div class="flex items-start">
                                <div class="w-8 h-8 bg-yellow-500 text-white rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                    <span class="font-bold">1</span>
                                </div>
                                <div>
                                    <p class="text-gray-800 font-medium">Pemeriksaan Data</p>
                                    <p class="text-sm text-gray-600">Admin akan memverifikasi identitas dan dokumen Anda</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="w-8 h-8 bg-yellow-500 text-white rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                    <span class="font-bold">2</span>
                                </div>
                                <div>
                                    <p class="text-gray-800 font-medium">Notifikasi Email</p>
                                    <p class="text-sm text-gray-600">Anda akan menerima email pemberitahuan hasil verifikasi</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="w-8 h-8 bg-yellow-500 text-white rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                    <span class="font-bold">3</span>
                                </div>
                                <div>
                                    <p class="text-gray-800 font-medium">Aktivasi Akun</p>
                                    <p class="text-sm text-gray-600">Setelah disetujui, Anda dapat login dan mengelola kost</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-4 border-t border-yellow-200">
                        <p class="text-sm text-gray-600">
                            ⏱️ <strong>Estimasi waktu:</strong> 1-3 hari kerja (Senin-Jumat, 09:00-17:00)
                        </p>
                    </div>
                </div>
                
                <!-- Contact Info -->
                <div class="bg-blue-50 border-l-4 border-blue-400 p-6 mb-6 text-left">
                    <p class="text-gray-700 mb-2">
                        <i class="fas fa-headset mr-2 text-blue-600"></i>
                        <strong>Butuh bantuan?</strong>
                    </p>
                    <p class="text-gray-600">
                        Hubungi kami di: <a href="mailto:support@sistemkost.com" class="text-blue-600 hover:underline">support@sistemkost.com</a>
                    </p>
                </div>
                
                <!-- Actions -->
                <div class="space-y-3">
                    <a href="<?= url('/') ?>" 
                       class="block w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition duration-200">
                        <i class="fas fa-home mr-2"></i>Kembali ke Beranda
                    </a>
                    
                    <form action="<?= url('/logout') ?>" method="POST">
                        <?= csrf_field() ?>
                        <button type="submit" 
                                class="w-full bg-gray-500 text-white py-3 rounded-lg hover:bg-gray-600 transition duration-200">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </button>
                    </form>
                </div>
                
            </div>
            
        </div>
    </div>
    
</body>
</html>
