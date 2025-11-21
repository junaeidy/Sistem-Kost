<?php
/**
 * Tenant Sidebar Menu
 */
$user = auth();
$currentPath = $_SERVER['REQUEST_URI'];
?>

<nav class="bg-gradient-to-b from-gray-800 to-gray-900 text-white w-64 min-h-screen p-6">
    <!-- Profile Section -->
    <div class="mb-8 pb-6 border-b border-gray-700">
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center">
                <i class="fas fa-user text-xl"></i>
            </div>
            <div>
                <p class="font-semibold"><?= e($user['email'] ?? 'Tenant') ?></p>
                <p class="text-xs text-gray-400">Tenant Panel</p>
            </div>
        </div>
    </div>

    <!-- Menu Items -->
    <ul class="space-y-2">
        <li>
            <a href="<?= url('/tenant/dashboard') ?>" 
               class="flex items-center space-x-3 px-4 py-3 rounded-lg transition <?= strpos($currentPath, '/tenant/dashboard') !== false ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700' ?>">
                <i class="fas fa-th-large w-5"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="<?= url('/tenant/search') ?>" 
               class="flex items-center space-x-3 px-4 py-3 rounded-lg transition <?= strpos($currentPath, '/tenant/search') !== false ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700' ?>">
                <i class="fas fa-search w-5"></i>
                <span>Cari Kost</span>
            </a>
        </li>
        <li>
            <a href="<?= url('/tenant/bookings') ?>" 
               class="flex items-center space-x-3 px-4 py-3 rounded-lg transition <?= strpos($currentPath, '/tenant/bookings') !== false ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700' ?>">
                <i class="fas fa-calendar-check w-5"></i>
                <span>Booking Saya</span>
            </a>
        </li>
        <li>
            <a href="<?= url('/tenant/profile') ?>" 
               class="flex items-center space-x-3 px-4 py-3 rounded-lg transition <?= strpos($currentPath, '/tenant/profile') !== false ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700' ?>">
                <i class="fas fa-user-circle w-5"></i>
                <span>Profil Saya</span>
            </a>
        </li>
        <li>
            <form method="POST" action="<?= url('/logout') ?>" class="w-full">
                <button type="submit" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition text-gray-300 hover:bg-gray-700 w-full text-left">
                    <i class="fas fa-sign-out-alt w-5"></i>
                    <span>Logout</span>
                </button>
            </form>
        </li>
    </ul>
</nav>
