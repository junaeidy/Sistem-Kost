<?php

$user = auth();
$currentPath = $_SERVER['REQUEST_URI'];
?>

<nav class="bg-gradient-to-b from-gray-800 to-gray-900 text-white w-64 min-h-screen p-6">
    <!-- Profile Section -->
    <div class="mb-8 pb-6 border-b border-gray-700">
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center">
                <i class="fas fa-user-tie text-xl"></i>
            </div>
            <div>
                <p class="font-semibold"><?= e($user['email'] ?? 'Owner') ?></p>
                <p class="text-xs text-gray-400">Owner Panel</p>
            </div>
        </div>
    </div>

    <!-- Menu Items -->
    <ul class="space-y-2">
        <li>
            <a href="<?= url('/owner/dashboard') ?>" 
               class="flex items-center space-x-3 px-4 py-3 rounded-lg transition <?= strpos($currentPath, '/owner/dashboard') !== false ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700' ?>">
                <i class="fas fa-th-large w-5"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="<?= url('/owner/kost') ?>" 
               class="flex items-center space-x-3 px-4 py-3 rounded-lg transition <?= strpos($currentPath, '/owner/kost') !== false ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700' ?>">
                <i class="fas fa-building w-5"></i>
                <span>Kelola Kost</span>
            </a>
        </li>
        <li>
            <a href="<?= url('/owner/bookings') ?>" 
               class="flex items-center space-x-3 px-4 py-3 rounded-lg transition <?= strpos($currentPath, '/owner/bookings') !== false ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700' ?>">
                <i class="fas fa-calendar-check w-5"></i>
                <span>Booking</span>
            </a>
        </li>
        <li>
            <a href="<?= url('/owner/profile') ?>" 
               class="flex items-center space-x-3 px-4 py-3 rounded-lg transition <?= strpos($currentPath, '/owner/profile') !== false ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700' ?>">
                <i class="fas fa-user-circle w-5"></i>
                <span>Profil Saya</span>
            </a>
        </li>
    </ul>

    <!-- Logout -->
    <div class="mt-8 pt-6 border-t border-gray-700">
        <form action="<?= url('/logout') ?>" method="POST">
            <?= csrf_field() ?>
            <button type="submit" 
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-red-600 hover:text-white transition w-full">
                <i class="fas fa-sign-out-alt w-5"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</nav>
