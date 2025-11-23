<?php 
$pageTitle = $pageTitle ?? 'Kelola Kost';
$kosts = $kosts ?? [];
$currentStatus = $currentStatus ?? 'all';
?>

<!-- Filter -->
<div class="bg-white rounded-lg shadow-md p-4 md:p-6 mb-4 md:mb-6">
    <div class="flex flex-col sm:flex-row sm:flex-wrap sm:items-center gap-2 md:gap-3">
        <span class="text-gray-700 font-medium text-sm md:text-base">Filter Status:</span>
        <a href="<?= url('/admin/kost?status=all') ?>" 
           class="px-3 md:px-4 py-2 rounded-lg text-sm md:text-base text-center <?= $currentStatus === 'all' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
            Semua<?= isset($total) && $currentStatus === 'all' ? ' (' . $total . ')' : '' ?>
        </a>
        <a href="<?= url('/admin/kost?status=active') ?>" 
           class="px-3 md:px-4 py-2 rounded-lg text-sm md:text-base text-center <?= $currentStatus === 'active' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
            Active
        </a>
        <a href="<?= url('/admin/kost?status=inactive') ?>" 
           class="px-3 md:px-4 py-2 rounded-lg text-sm md:text-base text-center <?= $currentStatus === 'inactive' ? 'bg-gray-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
            Inactive
        </a>
    </div>
</div>

<!-- Kost Table -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <?php if (empty($kosts)): ?>
        <div class="p-8 md:p-12 text-center">
            <i class="fas fa-building text-5xl md:text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-base md:text-lg">Tidak ada kost ditemukan</p>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kost</th>
                        <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden lg:table-cell">Owner</th>
                        <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Lokasi</th>
                        <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                        <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Tipe</th>
                        <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Status</th>
                        <th class="px-4 md:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($kosts as $kost): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 md:px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 md:w-12 md:h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-2 md:mr-3 flex-shrink-0">
                                        <i class="fas fa-building text-purple-600 text-sm md:text-base"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-medium text-gray-800 text-sm md:text-base truncate"><?= e($kost['name']) ?></p>
                                        <p class="text-xs md:text-sm text-gray-500">
                                            <?= date('d M Y', strtotime($kost['created_at'])) ?>
                                        </p>
                                        <div class="flex gap-1 mt-1 sm:hidden">
                                            <span class="px-2 py-0.5 text-xs rounded-full
                                                <?= $kost['gender_type'] === 'putra' ? 'bg-blue-100 text-blue-800' : 
                                                    ($kost['gender_type'] === 'putri' ? 'bg-pink-100 text-pink-800' : 'bg-purple-100 text-purple-800') ?>">
                                                <?= $kost['gender_type'] === 'putra' ? 'Putra' : ($kost['gender_type'] === 'putri' ? 'Putri' : 'Campur') ?>
                                            </span>
                                            <span class="px-2 py-0.5 text-xs font-medium rounded-full
                                                <?= $kost['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' ?>">
                                                <?= ucfirst($kost['status']) ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 md:px-6 py-4 hidden lg:table-cell">
                                <p class="font-medium text-gray-800 text-sm truncate"><?= e($kost['owner_name'] ?? '-') ?></p>
                                <p class="text-xs text-gray-500 truncate"><?= e($kost['owner_email'] ?? '-') ?></p>
                            </td>
                            <td class="px-4 md:px-6 py-4 hidden md:table-cell">
                                <p class="text-sm text-gray-700 truncate">
                                    <i class="fas fa-map-marker-alt text-red-500 mr-1"></i>
                                    <?= e($kost['location']) ?>
                                </p>
                            </td>
                            <td class="px-4 md:px-6 py-4">
                                <p class="font-semibold text-blue-600 text-sm md:text-base whitespace-nowrap">
                                    Rp <?= number_format($kost['price'], 0, ',', '.') ?>
                                </p>
                                <p class="text-xs text-gray-500">/bln</p>
                            </td>
                            <td class="px-4 md:px-6 py-4 hidden sm:table-cell">
                                <span class="px-2 py-1 text-xs rounded-full whitespace-nowrap
                                    <?= $kost['gender_type'] === 'putra' ? 'bg-blue-100 text-blue-800' : 
                                        ($kost['gender_type'] === 'putri' ? 'bg-pink-100 text-pink-800' : 'bg-purple-100 text-purple-800') ?>">
                                    <?= $kost['gender_type'] === 'putra' ? 'Putra' : ($kost['gender_type'] === 'putri' ? 'Putri' : 'Campur') ?>
                                </span>
                            </td>
                            <td class="px-4 md:px-6 py-4 hidden sm:table-cell">
                                <span class="px-2 md:px-3 py-1 text-xs font-medium rounded-full whitespace-nowrap
                                    <?= $kost['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' ?>">
                                    <?= ucfirst($kost['status']) ?>
                                </span>
                            </td>
                            <td class="px-4 md:px-6 py-4 text-center">
                                <a href="<?= url('/admin/kost/' . $kost['id']) ?>" 
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
    $baseUrl = url('/admin/kost');
    include __DIR__ . '/../../components/pagination.php';
    ?>
<?php endif; ?>
