<?php
/**
 * Admin Sidebar Menu
 */
$currentPath = $_SERVER['REQUEST_URI'] ?? '';
$menuItems = [
    [
        'url' => '/admin/dashboard',
        'icon' => 'tachometer-alt',
        'label' => 'Dashboard',
        'active' => strpos($currentPath, '/admin/dashboard') !== false
    ],
    [
        'url' => '/admin/owners',
        'icon' => 'users-cog',
        'label' => 'Kelola Owner',
        'active' => strpos($currentPath, '/admin/owners') !== false
    ],
    [
        'url' => '/admin/kost',
        'icon' => 'building',
        'label' => 'Kelola Kost',
        'active' => strpos($currentPath, '/admin/kost') !== false
    ],
    [
        'url' => '/admin/transactions',
        'icon' => 'credit-card',
        'label' => 'Transaksi',
        'active' => strpos($currentPath, '/admin/transactions') !== false
    ],
    [
        'url' => '/admin/profile',
        'icon' => 'user-circle',
        'label' => 'Profil Saya',
        'active' => strpos($currentPath, '/admin/profile') !== false
    ],
];

// Set timezone to Indonesia
date_default_timezone_set('Asia/Jakarta');

// Get current date and time
$current_day = date('l'); // Day name
$current_date = date('d F Y'); // Date format
$current_time = date('H:i'); // Time format

// Translate day to Indonesian
$days_indonesian = [
    'Sunday' => 'Minggu',
    'Monday' => 'Senin',
    'Tuesday' => 'Selasa',
    'Wednesday' => 'Rabu',
    'Thursday' => 'Kamis',
    'Friday' => 'Jumat',
    'Saturday' => 'Sabtu'
];

$months_indonesian = [
    'January' => 'Januari',
    'February' => 'Februari',
    'March' => 'Maret',
    'April' => 'April',
    'May' => 'Mei',
    'June' => 'Juni',
    'July' => 'Juli',
    'August' => 'Agustus',
    'September' => 'September',
    'October' => 'Oktober',
    'November' => 'November',
    'December' => 'Desember'
];

$day_indonesian = $days_indonesian[$current_day];
foreach ($months_indonesian as $eng => $ind) {
    $current_date = str_replace($eng, $ind, $current_date);
}
?>

<!-- Date & Time Widget -->
<div class="px-4 py-4 border-b border-gray-700">
    <div class="bg-gray-900 rounded-lg p-4">
        <div class="text-center">
            <p class="text-gray-400 text-xs mb-1"><?= $day_indonesian ?></p>
            <p class="text-white font-semibold text-sm mb-2"><?= $current_date ?></p>
            <p class="text-blue-400 text-2xl font-bold" id="current-time"><?= $current_time ?></p>
        </div>
    </div>
</div>

<ul class="space-y-2 px-4 mt-4">
    <?php foreach ($menuItems as $item): ?>
        <li>
            <a href="<?= url($item['url']) ?>" 
               class="flex items-center px-4 py-3 rounded-lg transition <?= $item['active'] ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700' ?>">
                <i class="fas fa-<?= $item['icon'] ?> w-6"></i>
                <span class="ml-3"><?= $item['label'] ?></span>
            </a>
        </li>
    <?php endforeach; ?>
</ul>

<!-- Divider -->
<div class="border-t border-gray-700 my-4 mx-4"></div>

<!-- Additional Links -->
<ul class="space-y-2 px-4">
    <li>
        <a href="<?= url('/') ?>" 
           class="flex items-center px-4 py-3 rounded-lg transition text-gray-300 hover:bg-gray-700" 
           title="Lihat Website">
            <i class="fas fa-external-link-alt w-6"></i>
            <span class="ml-3">Lihat Website</span>
        </a>
    </li>
    <li>
        <form action="<?= url('/logout') ?>" method="POST">
            <?= csrf_field() ?>
            <button type="submit" 
                    class="w-full flex items-center px-4 py-3 rounded-lg transition text-gray-300 hover:bg-red-600 hover:text-white">
                <i class="fas fa-sign-out-alt w-6"></i>
                <span class="ml-3">Logout</span>
            </button>
        </form>
    </li>
</ul>

<script>
// Update time every second
function updateTime() {
    const now = new Date();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const timeElement = document.getElementById('current-time');
    if (timeElement) {
        timeElement.textContent = hours + ':' + minutes;
    }
}

// Update immediately and then every second
updateTime();
setInterval(updateTime, 1000);
</script>
