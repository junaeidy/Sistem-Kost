<?php 
$pageTitle = $pageTitle ?? 'Kelola Owner';
$owners = $owners ?? [];
$currentStatus = $currentStatus ?? 'all';
?>

<!-- Filter -->
<div class="bg-white rounded-lg shadow-md p-4 md:p-6 mb-4 md:mb-6">
    <div class="flex flex-col sm:flex-row sm:flex-wrap sm:items-center gap-2 md:gap-3">
        <span class="text-gray-700 font-medium text-sm md:text-base">Filter Status:</span>
        <a href="<?= url('/admin/owners?status=all') ?>" 
           class="px-3 md:px-4 py-2 rounded-lg text-sm md:text-base text-center <?= $currentStatus === 'all' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
            Semua<?= isset($total) && $currentStatus === 'all' ? ' (' . $total . ')' : '' ?>
        </a>
        <a href="<?= url('/admin/owners?status=pending') ?>" 
           class="px-3 md:px-4 py-2 rounded-lg text-sm md:text-base text-center <?= $currentStatus === 'pending' ? 'bg-yellow-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
            Pending
        </a>
        <a href="<?= url('/admin/owners?status=active') ?>" 
           class="px-3 md:px-4 py-2 rounded-lg text-sm md:text-base text-center <?= $currentStatus === 'active' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
            Active
        </a>
        <a href="<?= url('/admin/owners?status=rejected') ?>" 
           class="px-3 md:px-4 py-2 rounded-lg text-sm md:text-base text-center <?= $currentStatus === 'rejected' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
            Rejected
        </a>
        <a href="<?= url('/admin/owners?status=suspended') ?>" 
           class="px-3 md:px-4 py-2 rounded-lg text-sm md:text-base text-center <?= $currentStatus === 'suspended' ? 'bg-gray-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
            Suspended
        </a>
    </div>
</div>

<!-- Owners Table -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <?php if (empty($owners)): ?>
        <div class="p-8 md:p-12 text-center">
            <i class="fas fa-users text-5xl md:text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-base md:text-lg">Tidak ada owner ditemukan</p>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Owner</th>
                        <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Kontak</th>
                        <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden lg:table-cell">Alamat</th>
                        <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Bergabung</th>
                        <th class="px-4 md:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($owners as $owner): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 md:px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 md:w-10 md:h-10 bg-blue-100 rounded-full flex items-center justify-center mr-2 md:mr-3 flex-shrink-0">
                                        <i class="fas fa-user text-blue-600 text-xs md:text-sm"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-medium text-gray-800 text-sm md:text-base truncate"><?= e($owner['name']) ?></p>
                                        <p class="text-xs md:text-sm text-gray-500 truncate"><?= e($owner['email']) ?></p>
                                        <p class="text-xs text-gray-500 md:hidden"><?= e($owner['phone']) ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 md:px-6 py-4 hidden md:table-cell">
                                <p class="text-sm text-gray-700"><?= e($owner['phone']) ?></p>
                            </td>
                            <td class="px-4 md:px-6 py-4 hidden lg:table-cell">
                                <p class="text-sm text-gray-700"><?= e(substr($owner['address'] ?? '-', 0, 40)) ?><?= strlen($owner['address'] ?? '') > 40 ? '...' : '' ?></p>
                            </td>
                            <td class="px-4 md:px-6 py-4">
                                <?php $status = $owner['user_status'] ?? $owner['status'] ?? 'pending'; ?>
                                <span class="px-2 md:px-3 py-1 text-xs font-medium rounded-full whitespace-nowrap
                                    <?= $status === 'active' ? 'bg-green-100 text-green-800' : 
                                        ($status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                        ($status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) ?>">
                                    <?= ucfirst($status) ?>
                                </span>
                            </td>
                            <td class="px-4 md:px-6 py-4 hidden sm:table-cell">
                                <p class="text-sm text-gray-700 whitespace-nowrap"><?= date('d M Y', strtotime($owner['user_created_at'] ?? $owner['created_at'])) ?></p>
                            </td>
                            <td class="px-4 md:px-6 py-4 text-center">
                                <a href="<?= url('/admin/owners/' . $owner['id']) ?>" 
                                   class="inline-flex items-center px-2 md:px-3 py-1 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-xs md:text-sm whitespace-nowrap">
                                    <i class="fas fa-eye mr-0 md:mr-1"></i> <span class="hidden md:inline">Detail</span>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Pagination -->
<?php if (isset($totalPages) && $totalPages > 1): ?>
    <?php 
    $baseUrl = url('/admin/owners');
    include __DIR__ . '/../../components/pagination.php';
    ?>
<?php endif; ?>
