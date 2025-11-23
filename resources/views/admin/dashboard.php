<?php 
$pageTitle = $pageTitle ?? 'Dashboard';
$stats = $stats ?? [];
$recentPendingOwners = $recentPendingOwners ?? [];
$recentUsers = $recentUsers ?? [];
?>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6 md:mb-8">
    
    <!-- Total Users -->
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs md:text-sm">Total Users</p>
                <h3 class="text-2xl md:text-3xl font-bold text-gray-800"><?= $stats['total_users'] ?? 0 ?></h3>
            </div>
            <div class="w-12 h-12 md:w-14 md:h-14 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-users text-xl md:text-2xl text-blue-600"></i>
            </div>
        </div>
    </div>
    
    <!-- Total Owners -->
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs md:text-sm">Total Owners</p>
                <h3 class="text-2xl md:text-3xl font-bold text-gray-800"><?= $stats['total_owners'] ?? 0 ?></h3>
                <p class="text-xs text-gray-500 mt-1">
                    <span class="text-yellow-600"><?= $stats['pending_owners'] ?? 0 ?></span> pending
                </p>
            </div>
            <div class="w-12 h-12 md:w-14 md:h-14 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-user-tie text-xl md:text-2xl text-green-600"></i>
            </div>
        </div>
    </div>
    
    <!-- Total Kost -->
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs md:text-sm">Total Kost</p>
                <h3 class="text-2xl md:text-3xl font-bold text-gray-800"><?= $stats['total_kost'] ?? 0 ?></h3>
                <p class="text-xs text-gray-500 mt-1">
                    <?= $stats['total_kamar'] ?? 0 ?> kamar total
                </p>
            </div>
            <div class="w-12 h-12 md:w-14 md:h-14 bg-purple-100 rounded-full flex items-center justify-center">
                <i class="fas fa-building text-xl md:text-2xl text-purple-600"></i>
            </div>
        </div>
    </div>
    
    <!-- Total Bookings -->
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs md:text-sm">Total Bookings</p>
                <h3 class="text-2xl md:text-3xl font-bold text-gray-800"><?= $stats['total_bookings'] ?? 0 ?></h3>
                <p class="text-xs text-gray-500 mt-1">
                    <?= $stats['available_kamar'] ?? 0 ?> kamar tersedia
                </p>
            </div>
            <div class="w-12 h-12 md:w-14 md:h-14 bg-orange-100 rounded-full flex items-center justify-center">
                <i class="fas fa-calendar-check text-xl md:text-2xl text-orange-600"></i>
            </div>
        </div>
    </div>
    
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-6 md:mb-8">
    
    <!-- Pending Owners -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <h3 class="text-base md:text-lg font-semibold text-gray-800">
                    <i class="fas fa-clock text-yellow-600 mr-2"></i>
                    Owner Menunggu Verifikasi
                </h3>
                <a href="<?= url('/admin/owners?status=pending') ?>" class="text-blue-600 hover:text-blue-800 text-sm">
                    Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
        <div class="p-4 md:p-6">
            <?php if (empty($recentPendingOwners)): ?>
                <div class="text-center py-8">
                    <i class="fas fa-check-circle text-4xl text-green-500 mb-3"></i>
                    <p class="text-gray-500 text-sm md:text-base">Tidak ada owner yang menunggu verifikasi</p>
                </div>
            <?php else: ?>
                <div class="space-y-3 md:space-y-4">
                    <?php foreach ($recentPendingOwners as $owner): ?>
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 p-3 md:p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div class="flex items-center">
                                <div class="w-10 h-10 md:w-12 md:h-12 bg-yellow-100 rounded-full flex items-center justify-center mr-3 md:mr-4 flex-shrink-0">
                                    <i class="fas fa-user text-yellow-600"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-medium text-gray-800 text-sm md:text-base truncate"><?= e($owner['name']) ?></p>
                                    <p class="text-xs md:text-sm text-gray-500 truncate"><?= e($owner['email']) ?></p>
                                    <p class="text-xs text-gray-400"><?= e($owner['phone']) ?></p>
                                </div>
                            </div>
                            <a href="<?= url('/admin/owners/' . $owner['id']) ?>" 
                               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm text-center whitespace-nowrap">
                                Verifikasi
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Recent Users -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <h3 class="text-base md:text-lg font-semibold text-gray-800">
                <i class="fas fa-user-plus text-blue-600 mr-2"></i>
                Pengguna Terbaru
            </h3>
        </div>
        <div class="p-4 md:p-6">
            <?php if (empty($recentUsers)): ?>
                <p class="text-gray-500 text-center py-8 text-sm md:text-base">Belum ada pengguna</p>
            <?php else: ?>
                <div class="space-y-3">
                    <?php foreach ($recentUsers as $user): ?>
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center min-w-0">
                                <div class="w-8 h-8 md:w-10 md:h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                    <i class="fas fa-user text-blue-600 text-xs md:text-sm"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="font-medium text-gray-800 text-xs md:text-sm truncate"><?= e($user['email']) ?></p>
                                    <div class="flex flex-wrap items-center gap-1 md:gap-2 mt-1">
                                        <span class="text-xs px-2 py-0.5 md:py-1 rounded-full whitespace-nowrap
                                            <?= $user['role'] === 'admin' ? 'bg-purple-100 text-purple-800' : 
                                                ($user['role'] === 'owner' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800') ?>">
                                            <?= ucfirst($user['role']) ?>
                                        </span>
                                        <span class="text-xs px-2 py-0.5 md:py-1 rounded-full whitespace-nowrap
                                            <?= $user['status'] === 'active' ? 'bg-green-100 text-green-800' : 
                                                ($user['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') ?>">
                                            <?= ucfirst($user['status']) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <span class="text-xs text-gray-400 sm:text-right">
                                <?= date('d/m/Y', strtotime($user['created_at'])) ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
</div>

<!-- Quick Actions -->
<div class="bg-white rounded-lg shadow-md p-4 md:p-6">
    <h3 class="text-base md:text-lg font-semibold text-gray-800 mb-4">
        <i class="fas fa-bolt text-yellow-500 mr-2"></i>
        Quick Actions
    </h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-4">
        <a href="<?= url('/admin/owners?status=pending') ?>" 
           class="flex items-center p-3 md:p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition">
            <i class="fas fa-user-check text-xl md:text-2xl text-yellow-600 mr-3 md:mr-4 flex-shrink-0"></i>
            <div class="min-w-0">
                <p class="font-medium text-gray-800 text-sm md:text-base">Verifikasi Owner</p>
                <p class="text-xs md:text-sm text-gray-600"><?= $stats['pending_owners'] ?? 0 ?> menunggu</p>
            </div>
        </a>
        
        <a href="<?= url('/admin/owners') ?>" 
           class="flex items-center p-3 md:p-4 bg-green-50 rounded-lg hover:bg-green-100 transition">
            <i class="fas fa-users-cog text-xl md:text-2xl text-green-600 mr-3 md:mr-4 flex-shrink-0"></i>
            <div class="min-w-0">
                <p class="font-medium text-gray-800 text-sm md:text-base">Kelola Owner</p>
                <p class="text-xs md:text-sm text-gray-600"><?= $stats['total_owners'] ?? 0 ?> owner</p>
            </div>
        </a>
        
        <a href="<?= url('/admin/kost') ?>" 
           class="flex items-center p-3 md:p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition sm:col-span-2 lg:col-span-1">
            <i class="fas fa-building text-xl md:text-2xl text-purple-600 mr-3 md:mr-4 flex-shrink-0"></i>
            <div class="min-w-0">
                <p class="font-medium text-gray-800 text-sm md:text-base">Kelola Kost</p>
                <p class="text-xs md:text-sm text-gray-600"><?= $stats['total_kost'] ?? 0 ?> kost</p>
            </div>
        </a>
    </div>
</div>
