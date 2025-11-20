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
];
?>

<ul class="space-y-2 px-4">
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
