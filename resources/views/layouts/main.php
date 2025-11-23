<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Sistem Kost' ?> - <?= config('app.name') ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    
    <?php if (isset($additionalCss)): ?>
        <?= $additionalCss ?>
    <?php endif; ?>
</head>
<body class="bg-gray-50">
    
    <!-- Navbar -->
    <nav class="bg-white shadow-md fixed top-0 left-0 right-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <a href="<?= url('/') ?>" class="text-2xl font-bold text-blue-600">
                    <i class="fas fa-home"></i> <?= config('app.name') ?>
                </a>
                
                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="<?= url('/') ?>" class="text-gray-700 hover:text-blue-600">Beranda</a>
                    <a href="<?= url('/search') ?>" class="text-gray-700 hover:text-blue-600">Cari Kost</a>
                    
                    <?php if (auth()): ?>
                        <?php if (is_admin()): ?>
                            <a href="<?= url('/admin/dashboard') ?>" class="text-gray-700 hover:text-blue-600">Dashboard</a>
                        <?php elseif (is_owner()): ?>
                            <a href="<?= url('/owner/dashboard') ?>" class="text-gray-700 hover:text-blue-600">Dashboard</a>
                        <?php elseif (is_tenant()): ?>
                            <a href="<?= url('/tenant/dashboard') ?>" class="text-gray-700 hover:text-blue-600">Dashboard</a>
                        <?php endif; ?>
                        
                        <div class="relative" id="accountDropdown">
                            <button onclick="toggleAccountMenu()" class="flex items-center text-gray-700 hover:text-blue-600">
                                <i class="fas fa-user-circle mr-2"></i>
                                Akun
                                <i class="fas fa-chevron-down ml-1 text-xs"></i>
                            </button>
                            <div id="accountMenu" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg hidden z-10 border border-gray-200">
                                <?php if (is_admin()): ?>
                                    <a href="<?= url('/admin/profile') ?>" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-t-md">
                                        <i class="fas fa-user mr-2"></i> Profil
                                    </a>
                                <?php elseif (is_owner()): ?>
                                    <a href="<?= url('/owner/profile') ?>" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-t-md">
                                        <i class="fas fa-user mr-2"></i> Profil
                                    </a>
                                <?php elseif (is_tenant()): ?>
                                    <a href="<?= url('/tenant/profile') ?>" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-t-md">
                                        <i class="fas fa-user mr-2"></i> Profil
                                    </a>
                                <?php endif; ?>
                                <form action="<?= url('/logout') ?>" method="POST">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-b-md">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="<?= url('/login') ?>" class="text-gray-700 hover:text-blue-600">Login</a>
                        <a href="<?= url('/register') ?>" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                            Daftar
                        </a>
                    <?php endif; ?>
                </div>
                
                <!-- Mobile Menu Button -->
                <button class="md:hidden text-gray-700" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
            
            <!-- Mobile Menu -->
            <div id="mobileMenu" class="hidden md:hidden pb-4">
                <a href="<?= url('/') ?>" class="block py-2 text-gray-700 hover:text-blue-600">Beranda</a>
                <a href="<?= url('/search') ?>" class="block py-2 text-gray-700 hover:text-blue-600">Cari Kost</a>
                
                <?php if (auth()): ?>
                    <?php if (is_admin()): ?>
                        <a href="<?= url('/admin/dashboard') ?>" class="block py-2 text-gray-700 hover:text-blue-600">Dashboard</a>
                    <?php elseif (is_owner()): ?>
                        <a href="<?= url('/owner/dashboard') ?>" class="block py-2 text-gray-700 hover:text-blue-600">Dashboard</a>
                    <?php elseif (is_tenant()): ?>
                        <a href="<?= url('/tenant/dashboard') ?>" class="block py-2 text-gray-700 hover:text-blue-600">Dashboard</a>
                    <?php endif; ?>
                    
                    <?php if (is_admin()): ?>
                        <a href="<?= url('/admin/profile') ?>" class="block py-2 text-gray-700 hover:text-blue-600">Profil</a>
                    <?php elseif (is_owner()): ?>
                        <a href="<?= url('/owner/profile') ?>" class="block py-2 text-gray-700 hover:text-blue-600">Profil</a>
                    <?php elseif (is_tenant()): ?>
                        <a href="<?= url('/tenant/profile') ?>" class="block py-2 text-gray-700 hover:text-blue-600">Profil</a>
                    <?php endif; ?>
                    <form action="<?= url('/logout') ?>" method="POST" class="mt-2">
                        <?= csrf_field() ?>
                        <button type="submit" class="text-left w-full py-2 text-gray-700 hover:text-blue-600">
                            Logout
                        </button>
                    </form>
                <?php else: ?>
                    <a href="<?= url('/login') ?>" class="block py-2 text-gray-700 hover:text-blue-600">Login</a>
                    <a href="<?= url('/register') ?>" class="block py-2 text-gray-700 hover:text-blue-600">Daftar</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    
    <!-- Flash Messages -->
    <?php if ($flash = get_flash()): ?>
        <div class="container mx-auto px-4 mt-4">
            <div class="alert alert-<?= $flash['type'] ?> p-4 rounded-lg <?= $flash['type'] === 'success' ? 'bg-green-100 text-green-800' : ($flash['type'] === 'error' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800') ?>">
                <div class="flex items-center justify-between">
                    <span><?= e($flash['message']) ?></span>
                    <button onclick="this.parentElement.parentElement.remove()" class="text-2xl">&times;</button>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Main Content -->
    <main class="mt-16">
        <?= $content ?? '' ?>
    </main>
    
    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <!-- Company Info -->
                <div class="col-span-1 md:col-span-2">
                    <h3 class="text-2xl font-bold text-white mb-4">
                        <i class="fas fa-home text-blue-500 mr-2"></i>
                        Sistem Kost
                    </h3>
                    <p class="text-gray-400 mb-4 leading-relaxed">
                        Platform terpercaya untuk mencari dan mengelola kost di Medan. 
                        Kami membantu Anda menemukan kost yang tepat dengan mudah, cepat, dan aman.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center hover:bg-blue-700 transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-blue-400 rounded-full flex items-center justify-center hover:bg-blue-500 transition">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-pink-600 rounded-full flex items-center justify-center hover:bg-pink-700 transition">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center hover:bg-green-700 transition">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h4 class="text-lg font-semibold text-white mb-4">Link Cepat</h4>
                    <ul class="space-y-2">
                        <li><a href="<?= url('/') ?>" class="hover:text-blue-500 transition flex items-center"><i class="fas fa-chevron-right text-xs mr-2"></i>Beranda</a></li>
                        <li><a href="<?= url('/search') ?>" class="hover:text-blue-500 transition flex items-center"><i class="fas fa-chevron-right text-xs mr-2"></i>Cari Kost</a></li>
                        <li><a href="<?= url('/register') ?>" class="hover:text-blue-500 transition flex items-center"><i class="fas fa-chevron-right text-xs mr-2"></i>Daftar Penyewa</a></li>
                        <li><a href="<?= url('/register-owner') ?>" class="hover:text-blue-500 transition flex items-center"><i class="fas fa-chevron-right text-xs mr-2"></i>Daftar Owner</a></li>
                        <li><a href="<?= url('/login') ?>" class="hover:text-blue-500 transition flex items-center"><i class="fas fa-chevron-right text-xs mr-2"></i>Login</a></li>
                    </ul>
                </div>
                
                <!-- Contact Info -->
                <div>
                    <h4 class="text-lg font-semibold text-white mb-4">Hubungi Kami</h4>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt text-blue-500 mr-3 mt-1"></i>
                            <span>Jl. Setia Budi No. 123<br>Medan, Sumatera Utara</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone text-blue-500 mr-3"></i>
                            <span>+62 812-3456-7890</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope text-blue-500 mr-3"></i>
                            <span>info@sistemkost.com</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-clock text-blue-500 mr-3"></i>
                            <span>24/7 Support</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Bottom Footer -->
            <div class="border-t border-gray-800 pt-8 text-center">
                <p class="text-gray-400">
                    &copy; <?= date('Y') ?> Sistem Kost. All rights reserved. Made with 
                    <i class="fas fa-heart text-red-500 mx-1"></i> in Medan
                </p>
                <div class="mt-3 space-x-4 text-sm">
                    <a href="<?= url('/privacy-policy') ?>" class="hover:text-blue-500 transition">Kebijakan Privasi</a>
                    <span class="text-gray-600">|</span>
                    <a href="<?= url('/terms-conditions') ?>" class="hover:text-blue-500 transition">Syarat & Ketentuan</a>
                    <span class="text-gray-600">|</span>
                    <a href="<?= url('/faq') ?>" class="hover:text-blue-500 transition">FAQ</a>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Custom JS -->
    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }
        
        // Account dropdown menu toggle
        function toggleAccountMenu() {
            const menu = document.getElementById('accountMenu');
            menu.classList.toggle('hidden');
        }
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('accountDropdown');
            const menu = document.getElementById('accountMenu');
            
            if (dropdown && menu && !dropdown.contains(event.target)) {
                menu.classList.add('hidden');
            }
        });
        
        // Auto-hide flash messages after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>
    
    <?php if (isset($additionalJs)): ?>
        <?= $additionalJs ?>
    <?php endif; ?>
</body>
</html>
