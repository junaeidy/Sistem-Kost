<?php

$user = auth();
$currentPath = $_SERVER['REQUEST_URI'];
?>

<!-- Sidebar with flex-col for proper scrolling -->
<div class="bg-gradient-to-b from-gray-800 to-gray-900 text-white h-screen w-64 fixed left-0 top-0 flex flex-col">
    <!-- Logo/Brand - Fixed Top -->
    <div class="p-6 border-b border-gray-700 flex-shrink-0">
        <h1 class="text-2xl font-bold">Sistem Kost</h1>
        <p class="text-sm text-blue-300 mt-1">Panel Pemilik</p>
    </div>

    <!-- Date & Time Widget - Fixed -->
    <div class="px-4 pt-4 pb-2 border-b border-gray-700 flex-shrink-0">
        <div class="bg-blue-800/50 rounded-lg p-3 text-center">
            <div class="text-sm text-blue-300" id="currentDay">Loading...</div>
            <div class="text-2xl font-bold" id="currentTime">--:--:--</div>
            <div class="text-xs text-blue-300" id="currentDate">Loading...</div>
        </div>
    </div>

    <!-- Navigation - Scrollable -->
    <nav class="p-4 flex-1 overflow-y-auto pb-24">
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

            <!-- Divider -->
            <li class="pt-2 border-t border-gray-700 mt-2">
                <a href="<?= url('/') ?>" 
                   target="_blank"
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-gray-700 transition">
                    <i class="fas fa-external-link-alt w-5"></i>
                    <span>Lihat Website</span>
                </a>
            </li>

            <!-- Logout in navigation -->
            <form action="<?= url('/logout') ?>" method="POST">
                <?= csrf_field() ?>
                <button type="submit" 
                        class="w-full flex items-center px-4 py-3 rounded-lg transition text-gray-300 hover:bg-red-600 hover:text-white">
                    <i class="fas fa-sign-out-alt w-6"></i>
                    <span class="ml-3">Logout</span>
                </button>
            </form>
        </ul>
    </nav>

    <!-- User Info - Fixed Bottom -->
    <div class="p-4 border-t border-gray-700 bg-gray-950 flex-shrink-0">
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center">
                <i class="fas fa-user-tie text-xl"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-semibold truncate"><?= e($user['email'] ?? 'Owner') ?></p>
                <p class="text-xs text-gray-400 truncate">Owner Panel</p>
            </div>
        </div>
    </div>
</div>

<!-- Date/Time Script -->
<script>
function updateDateTime() {
    const now = new Date();
    
    // Indonesian day and month names
    const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                   'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    
    // Get components
    const day = days[now.getDay()];
    const date = now.getDate();
    const month = months[now.getMonth()];
    const year = now.getFullYear();
    
    // Format time
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    
    // Update elements
    document.getElementById('currentDay').textContent = day;
    document.getElementById('currentTime').textContent = `${hours}:${minutes}:${seconds}`;
    document.getElementById('currentDate').textContent = `${date} ${month} ${year}`;
}

// Update immediately and then every second
updateDateTime();
setInterval(updateDateTime, 1000);
</script>
