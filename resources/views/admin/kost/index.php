<?php 
$pageTitle = $pageTitle ?? 'Kelola Kost';
$kosts = $kosts ?? [];
$currentStatus = $currentStatus ?? 'all';
?>

<!-- Filter -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex flex-wrap items-center gap-3">
        <span class="text-gray-700 font-medium">Filter Status:</span>
        <a href="<?= url('/admin/kost?status=all') ?>" 
           class="px-4 py-2 rounded-lg <?= $currentStatus === 'all' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
            Semua (<?= count($kosts) ?>)
        </a>
        <a href="<?= url('/admin/kost?status=active') ?>" 
           class="px-4 py-2 rounded-lg <?= $currentStatus === 'active' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
            Active
        </a>
        <a href="<?= url('/admin/kost?status=inactive') ?>" 
           class="px-4 py-2 rounded-lg <?= $currentStatus === 'inactive' ? 'bg-gray-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
            Inactive
        </a>
    </div>
</div>

<!-- Kost Table -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <?php if (empty($kosts)): ?>
        <div class="p-12 text-center">
            <i class="fas fa-building text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-lg">Tidak ada kost ditemukan</p>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kost</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Owner</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lokasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($kosts as $kost): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-building text-purple-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800"><?= e($kost['name']) ?></p>
                                        <p class="text-sm text-gray-500">
                                            <?= date('d M Y', strtotime($kost['created_at'])) ?>
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-800"><?= e($kost['owner_name'] ?? '-') ?></p>
                                <p class="text-sm text-gray-500"><?= e($kost['owner_email'] ?? '-') ?></p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-700">
                                    <i class="fas fa-map-marker-alt text-red-500 mr-1"></i>
                                    <?= e($kost['location']) ?>
                                </p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-blue-600">
                                    Rp <?= number_format($kost['price'], 0, ',', '.') ?>
                                </p>
                                <p class="text-xs text-gray-500">per bulan</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded-full
                                    <?= $kost['gender_type'] === 'male' ? 'bg-blue-100 text-blue-800' : 
                                        ($kost['gender_type'] === 'female' ? 'bg-pink-100 text-pink-800' : 'bg-purple-100 text-purple-800') ?>">
                                    <?= $kost['gender_type'] === 'male' ? 'Putra' : ($kost['gender_type'] === 'female' ? 'Putri' : 'Campur') ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs font-medium rounded-full
                                    <?= $kost['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' ?>">
                                    <?= ucfirst($kost['status']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="<?= url('/admin/kost/' . $kost['id']) ?>" 
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
