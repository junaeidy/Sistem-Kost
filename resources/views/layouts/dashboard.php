<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard' ?> - <?= config('app.name') ?></title>
    
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
<body class="bg-gray-100">
    
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-white flex-shrink-0">
            <div class="p-4">
                <h1 class="text-2xl font-bold">
                    <i class="fas fa-home mr-2"></i>
                    <?= config('app.name') ?>
                </h1>
                <p class="text-sm text-gray-400 mt-1">
                    <?php if (is_admin()): ?>
                        Admin Panel
                    <?php elseif (is_owner()): ?>
                        Owner Panel
                    <?php else: ?>
                        Tenant Panel
                    <?php endif; ?>
                </p>
            </div>
            
            <nav class="mt-6">
                <?= $sidebarMenu ?? '' ?>
            </nav>
            
            <div class="absolute bottom-0 w-64 p-4 bg-gray-900">
                <div class="flex items-center">
                    <i class="fas fa-user-circle text-3xl mr-3"></i>
                    <div>
                        <p class="font-semibold"><?= e($_SESSION['user_name'] ?? 'User') ?></p>
                        <p class="text-sm text-gray-400"><?= ucfirst(user_role()) ?></p>
                    </div>
                </div>
            </div>
        </aside>
        
        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- Top Navigation -->
            <header class="bg-white shadow-md">
                <div class="flex items-center justify-between px-6 py-4">
                    <button onclick="toggleSidebar()" class="text-gray-600 lg:hidden">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                    
                    <h2 class="text-xl font-semibold text-gray-800">
                        <?= $pageTitle ?? 'Dashboard' ?>
                    </h2>
                    
                    <div class="flex items-center space-x-4">
                        <a href="<?= url('/') ?>" class="text-gray-600 hover:text-blue-600" title="Lihat Website">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                        
                        <a href="<?= url('/profile') ?>" class="text-gray-600 hover:text-blue-600" title="Profil">
                            <i class="fas fa-user"></i>
                        </a>
                        
                        <form action="<?= url('/logout') ?>" method="POST" class="inline">
                            <?= csrf_field() ?>
                            <button type="submit" class="text-gray-600 hover:text-red-600" title="Logout">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </header>
            
            <!-- Flash Messages -->
            <?php if ($flash = get_flash()): ?>
                <div class="mx-6 mt-4">
                    <div class="alert alert-<?= $flash['type'] ?> p-4 rounded-lg <?= $flash['type'] === 'success' ? 'bg-green-100 text-green-800' : ($flash['type'] === 'error' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800') ?>">
                        <div class="flex items-center justify-between">
                            <span><?= e($flash['message']) ?></span>
                            <button onclick="this.parentElement.parentElement.remove()" class="text-2xl">&times;</button>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <?= $content ?? '' ?>
            </main>
            
        </div>
        
    </div>
    
    <!-- Custom JS -->
    <script>
        function toggleSidebar() {
            // Implement mobile sidebar toggle
            const sidebar = document.querySelector('aside');
            sidebar.classList.toggle('hidden');
        }
        
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
