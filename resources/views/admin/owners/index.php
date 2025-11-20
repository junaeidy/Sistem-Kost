<?php 
$pageTitle = $pageTitle ?? 'Kelola Owner';
$owners = $owners ?? [];
$currentStatus = $currentStatus ?? 'all';
?>

<!-- Filter -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex flex-wrap items-center gap-3">
        <span class="text-gray-700 font-medium">Filter Status:</span>
        <a href="<?= url('/admin/owners?status=all') ?>" 
           class="px-4 py-2 rounded-lg <?= $currentStatus === 'all' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
            Semua (<?= count($owners) ?>)
        </a>
        <a href="<?= url('/admin/owners?status=pending') ?>" 
           class="px-4 py-2 rounded-lg <?= $currentStatus === 'pending' ? 'bg-yellow-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
            Pending
        </a>
        <a href="<?= url('/admin/owners?status=active') ?>" 
           class="px-4 py-2 rounded-lg <?= $currentStatus === 'active' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
            Active
        </a>
        <a href="<?= url('/admin/owners?status=rejected') ?>" 
           class="px-4 py-2 rounded-lg <?= $currentStatus === 'rejected' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
            Rejected
        </a>
        <a href="<?= url('/admin/owners?status=suspended') ?>" 
           class="px-4 py-2 rounded-lg <?= $currentStatus === 'suspended' ? 'bg-gray-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
            Suspended
        </a>
    </div>
</div>

<!-- Owners Table -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <?php if (empty($owners)): ?>
        <div class="p-12 text-center">
            <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-lg">Tidak ada owner ditemukan</p>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Owner</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kontak</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alamat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bergabung</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($owners as $owner): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-user text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800"><?= e($owner['name']) ?></p>
                                        <p class="text-sm text-gray-500"><?= e($owner['email']) ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-700"><?= e($owner['phone']) ?></p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-700"><?= e(substr($owner['address'] ?? '-', 0, 40)) ?><?= strlen($owner['address'] ?? '') > 40 ? '...' : '' ?></p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs font-medium rounded-full
                                    <?= $owner['status'] === 'active' ? 'bg-green-100 text-green-800' : 
                                        ($owner['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                        ($owner['status'] === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) ?>">
                                    <?= ucfirst($owner['status']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-700"><?= date('d M Y', strtotime($owner['created_at'])) ?></p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="<?= url('/admin/owners/' . $owner['id']) ?>" 
                                   class="inline-flex items-center px-3 py-1 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                                    <i class="fas fa-eye mr-1"></i> Detail
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
