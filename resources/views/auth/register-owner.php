<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Sebagai Pemilik Kost - <?= config('app.name') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-purple-500 to-indigo-600 min-h-screen flex items-center justify-center py-8">
    
    <div class="container mx-auto px-4">
        <div class="max-w-md mx-auto">
            
            <!-- Logo/Brand -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-white mb-2">
                    <i class="fas fa-home"></i> <?= config('app.name') ?>
                </h1>
                <p class="text-purple-100">Daftar sebagai Pemilik Kost</p>
            </div>
            
            <!-- Register Card -->
            <div class="bg-white rounded-lg shadow-2xl p-8">
                
                <!-- Flash Messages -->
                <?php if ($flash = get_flash()): ?>
                    <div class="mb-6 p-4 rounded-lg <?= $flash['type'] === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                        <?= $flash['message'] ?>
                    </div>
                <?php endif; ?>
                
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Registrasi Pemilik Kost</h2>
                
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                    <p class="text-sm text-yellow-800">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Perhatian:</strong> Akun Anda akan diverifikasi oleh admin sebelum dapat digunakan.
                    </p>
                </div>
                
                <form action="<?= url('/register-owner') ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    
                    <!-- Name -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">
                            <i class="fas fa-user mr-2"></i>Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                               placeholder="Nama lengkap Anda"
                               value="<?= old('name') ?>">
                    </div>
                    
                    <!-- Email -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">
                            <i class="fas fa-envelope mr-2"></i>Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                               placeholder="email@example.com"
                               value="<?= old('email') ?>">
                    </div>
                    
                    <!-- Phone -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">
                            <i class="fas fa-phone mr-2"></i>Nomor HP <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="phone" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                               placeholder="08xxxxxxxxxx"
                               value="<?= old('phone') ?>">
                    </div>
                    
                    <!-- Address -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">
                            <i class="fas fa-map-marker-alt mr-2"></i>Alamat
                        </label>
                        <textarea name="address" rows="2"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                                  placeholder="Alamat lengkap Anda"><?= old('address') ?></textarea>
                    </div>
                    
                    <!-- KTP Photo -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">
                            <i class="fas fa-id-card mr-2"></i>Foto KTP (Opsional)
                        </label>
                        <input type="file" name="ktp_photo" accept="image/jpeg,image/jpg,image/png"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <p class="text-sm text-gray-500 mt-1">Format: JPG, PNG. Maksimal 2MB.</p>
                    </div>
                    
                    <!-- Password -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">
                            <i class="fas fa-lock mr-2"></i>Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                               placeholder="Minimal 6 karakter">
                        <p class="text-sm text-gray-500 mt-1">Minimal 6 karakter</p>
                    </div>
                    
                    <!-- Confirm Password -->
                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-2">
                            <i class="fas fa-lock mr-2"></i>Konfirmasi Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password_confirm" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                               placeholder="Ulangi password">
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full bg-purple-600 text-white py-3 rounded-lg font-semibold hover:bg-purple-700 transition duration-200">
                        <i class="fas fa-building mr-2"></i>Daftar Sebagai Pemilik
                    </button>
                </form>
                
                <!-- Divider -->
                <div class="my-6 border-t border-gray-300"></div>
                
                <!-- Login Link -->
                <div class="text-center">
                    <p class="text-gray-600 mb-3">Sudah punya akun?</p>
                    <a href="<?= url('/login') ?>" 
                       class="block bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-200">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </a>
                </div>
                
                <!-- Back to Home -->
                <div class="mt-6 text-center">
                    <a href="<?= url('/') ?>" class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Beranda
                    </a>
                </div>
                
            </div>
            
        </div>
    </div>
    
</body>
</html>
