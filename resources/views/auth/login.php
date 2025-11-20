<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?= config('app.name') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-500 to-purple-600 min-h-screen flex items-center justify-center">
    
    <div class="container mx-auto px-4">
        <div class="max-w-md mx-auto">
            
            <!-- Logo/Brand -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-white mb-2">
                    <i class="fas fa-home"></i> <?= config('app.name') ?>
                </h1>
                <p class="text-blue-100">Silakan login untuk melanjutkan</p>
            </div>
            
            <!-- Login Card -->
            <div class="bg-white rounded-lg shadow-2xl p-8">
                
                <!-- Flash Messages -->
                <?php if ($flash = get_flash()): ?>
                    <div class="mb-6 p-4 rounded-lg <?= $flash['type'] === 'success' ? 'bg-green-100 text-green-800' : ($flash['type'] === 'error' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') ?>">
                        <?= e($flash['message']) ?>
                    </div>
                <?php endif; ?>
                
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Login</h2>
                
                <form action="<?= url('/login') ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <!-- Email -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">
                            <i class="fas fa-envelope mr-2"></i>Email
                        </label>
                        <input type="email" name="email" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="email@example.com"
                               value="<?= old('email') ?>">
                    </div>
                    
                    <!-- Password -->
                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-2">
                            <i class="fas fa-lock mr-2"></i>Password
                        </label>
                        <input type="password" name="password" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="••••••••">
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-200">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </button>
                </form>
                
                <!-- Divider -->
                <div class="my-6 border-t border-gray-300"></div>
                
                <!-- Register Links -->
                <div class="text-center space-y-3">
                    <p class="text-gray-600">Belum punya akun?</p>
                    <div class="flex flex-col space-y-2">
                        <a href="<?= url('/register') ?>" 
                           class="block bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-600 transition duration-200">
                            <i class="fas fa-user-plus mr-2"></i>Daftar Sebagai Penyewa
                        </a>
                        <a href="<?= url('/register-owner') ?>" 
                           class="block bg-purple-500 text-white py-2 px-4 rounded-lg hover:bg-purple-600 transition duration-200">
                            <i class="fas fa-building mr-2"></i>Daftar Sebagai Pemilik Kost
                        </a>
                    </div>
                </div>
                
                <!-- Back to Home -->
                <div class="mt-6 text-center">
                    <a href="<?= url('/') ?>" class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Beranda
                    </a>
                </div>
                
            </div>
            
            <!-- Test Accounts Info -->
            <?php if (config('app.debug')): ?>
                <div class="mt-6 bg-white rounded-lg shadow p-4 text-sm">
                    <p class="font-semibold text-gray-700 mb-2">Test Accounts:</p>
                    <ul class="space-y-1 text-gray-600">
                        <li><strong>Admin:</strong> admin@kost.com / admin123</li>
                        <li><strong>Owner:</strong> owner1@gmail.com / password123</li>
                        <li><strong>Tenant:</strong> tenant1@gmail.com / password123</li>
                    </ul>
                </div>
            <?php endif; ?>
            
        </div>
    </div>
    
</body>
</html>
